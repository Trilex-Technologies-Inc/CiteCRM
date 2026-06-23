<?php
/**
 * Module Manager
 * - Scans modules/ directory for available modules
 * - Reads optional module.json manifest for metadata
 * - Allows install/enable/disable/uninstall and scaffold new module
 */

if (function_exists('xml2php')) @xml2php('control');

$modules_dir = 'modules';
$optional_module_dirs = array('leads', 'messaging', 'tasks');

// Ensure modules table exists
$create_sql = "CREATE TABLE IF NOT EXISTS " . PRFX . "MODULES (
    MODULE_ID INT AUTO_INCREMENT PRIMARY KEY,
    MODULE_NAME VARCHAR(255) NOT NULL,
    MODULE_DIR VARCHAR(255) NOT NULL,
    MODULE_VERSION VARCHAR(32) DEFAULT '',
    MODULE_AUTHOR VARCHAR(255) DEFAULT '',
    MODULE_DESC TEXT,
    INSTALLED TINYINT(1) DEFAULT 0,
    ENABLED TINYINT(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
@$db->Execute($create_sql);

// Simple helper: read manifest
function read_manifest($dir)
{
    $m = array('name' => $dir, 'version' => '', 'author' => '', 'description' => '', 'dir' => $dir);
    $path = 'modules' . SEP . $dir . SEP . 'module.json';
    if (is_file($path)) {
        $j = @file_get_contents($path);
        $data = @json_decode($j, true);
        if (is_array($data)) {
            $m['name'] = isset($data['name']) ? $data['name'] : $m['name'];
            $m['version'] = isset($data['version']) ? $data['version'] : '';
            $m['author'] = isset($data['author']) ? $data['author'] : '';
            $m['description'] = isset($data['description']) ? $data['description'] : '';
        }
    }
    return $m;
}

// SQL parser that supports DELIMITER changes and stored procedures
function parse_sql_statements($sql)
{
    $lines = preg_split('/\r?\n/', $sql);
    $statements = array();
    $buffer = '';
    $delimiter = ';';
    foreach ($lines as $raw) {
        $line = $raw;
        // skip BOM
        $line = preg_replace('/^\xEF\xBB\xBF/', '', $line);
        $trim = ltrim($line);
        // ignore SQL comments that start the line
        if (preg_match('/^(--|#)/', $trim)) {
            continue;
        }
        // handle DELIMITER directive
        if (preg_match('/^DELIMITER\s+(.*)$/i', $trim, $m)) {
            $delimiter = $m[1];
            continue;
        }
        $buffer .= $line . "\n";
        // check if buffer ends with the current delimiter on its own (delimiter may be multi-char)
        $pattern = '/' . preg_quote($delimiter, '/') . "\s*\$/";
        if ($delimiter !== '' && preg_match($pattern, rtrim($buffer))) {
            // remove the delimiter from the end
            $stmt = preg_replace($pattern, '', rtrim($buffer));
            $stmt = trim($stmt);
            if ($stmt !== '') $statements[] = $stmt;
            $buffer = '';
        }
    }
    // leftover
    $left = trim($buffer);
    if ($left !== '') {
        $statements[] = $left;
    }
    return $statements;
}

function run_module_php_script($path, &$exec_log, $db)
{
    if (!is_file($path)) {
        return true;
    }

    $exec_log[] = "Running PHP script: $path";
    ob_start();
    try {
        include $path;
        $out = ob_get_clean();
        if (trim($out) !== '') {
            $exec_log[] = "PHP script output: " . substr(trim($out), 0, 1000);
        }
        return true;
    } catch (Throwable $e) {
        ob_end_clean();
        $exec_log[] = 'PHP script failed: ' . $e->getMessage();
        return false;
    }
}

// handle actions
$msg = '';
$msg_type = 'success';
// execution log for SQL/install/uninstall scripts
$exec_log = array();
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'scaffold') {
        $moddir = preg_replace('/[^a-z0-9_\-]/i', '', $_POST['module_dir']);
        if ($moddir === '') {
            $msg = 'Invalid module directory name';
        } else {
            $base = 'modules' . SEP . $moddir;
            if (!is_dir($base)) {
                @mkdir($base, 0755, true);
                @mkdir($base . SEP . 'templates', 0755, true);
                // create basic files
                $index = "<?php\n// $moddir module main page\n\n" . "echo 'Hello from module $moddir';\n";
                file_put_contents($base . SEP . 'main.php', $index);
                $include = "<?php\n// $moddir include functions\n\n";
                file_put_contents($base . SEP . 'include.php', $include);
                $manifest = array('name' => $_POST['module_name'] ?: $moddir, 'version' => '0.1', 'author' => $_POST['module_author'] ?: '', 'description' => $_POST['module_desc'] ?: '');
                file_put_contents($base . SEP . 'module.json', json_encode($manifest, JSON_PRETTY_PRINT));
                $msg = 'Module scaffolded: ' . $moddir;
            } else {
                $msg = 'Module directory already exists';
            }
        }
    } elseif (isset($_POST['module_dir'])) {
        $mdir = preg_replace('/[^a-z0-9_\-]/i', '', $_POST['module_dir']);
        if ($mdir === '' || !is_dir($modules_dir . SEP . $mdir)) {
            $msg = 'Invalid module directory.';
            $msg_type = 'danger';
        } elseif (!in_array($mdir, $optional_module_dirs, true)) {
            $msg = 'System modules are always installed and cannot be installed, disabled, or uninstalled.';
            $msg_type = 'info';
        } elseif ($action === 'register') {
            $m = read_manifest($mdir);
            $q = "SELECT COUNT(*) AS c FROM " . PRFX . "MODULES WHERE MODULE_DIR=" . $db->qstr($mdir);
            $r = $db->Execute($q);
            if ($r && $r->fields['c'] == 0) {
                $ins = "INSERT INTO " . PRFX . "MODULES (MODULE_NAME,MODULE_DIR,MODULE_VERSION,MODULE_AUTHOR,MODULE_DESC,INSTALLED,ENABLED) VALUES (" . $db->qstr($m['name']) . "," . $db->qstr($m['dir']) . "," . $db->qstr($m['version']) . "," . $db->qstr($m['author']) . "," . $db->qstr($m['description']) . ",0,0)";
                $db->Execute($ins);
                $msg = 'Module registered: ' . $mdir . '. Click Install to create its tables and enable it.';
            } else {
                $msg = 'Module already registered';
            }
        } elseif ($action === 'install') {
            $m = read_manifest($mdir);
            $q = "SELECT COUNT(*) AS c FROM " . PRFX . "MODULES WHERE MODULE_DIR=" . $db->qstr($mdir);
            $r = $db->Execute($q);
            if ($r && $r->fields['c'] == 0) {
                // look for module-provided SQL files
                $sql_files = array();
                $candidate1 = 'modules' . SEP . $mdir . SEP . 'install.sql';
                $candidate2 = 'modules' . SEP . $mdir . SEP . 'sql' . SEP . 'install.sql';
                if (is_file($candidate1)) $sql_files[] = $candidate1;
                if (is_file($candidate2)) $sql_files[] = $candidate2;
                // also include any .sql files under modules/<dir>/sql/
                $sql_dir = 'modules' . SEP . $mdir . SEP . 'sql';
                if (is_dir($sql_dir)) {
                    foreach (scandir($sql_dir) as $f) {
                        if (preg_match('/\.sql$/i', $f)) {
                            $p = $sql_dir . SEP . $f;
                            if (!in_array($p, $sql_files)) $sql_files[] = $p;
                        }
                    }
                }

                $sql_error = '';
                    if (!empty($sql_files)) {
                        // run SQL statements inside a transaction and collect logs
                        $db->StartTrans();
                        $failed = false;
                        foreach ($sql_files as $sf) {
                            $exec_log[] = "Running SQL file: $sf";
                            $content = file_get_contents($sf);
                            if ($content === false) {
                                $exec_log[] = "Failed to read SQL file: $sf";
                                $db->FailTrans();
                                $failed = true;
                                break;
                            }
                            $stmts = parse_sql_statements($content);
                            foreach ($stmts as $stmt) {
                                $exec_log[] = "-- SQL: " . substr($stmt, 0, 200);
                                if (!$db->Execute($stmt)) {
                                    $err = $db->ErrorMsg();
                                    $exec_log[] = "ERROR: " . $err;
                                    $db->FailTrans();
                                    $failed = true;
                                    break 2;
                                }
                            }
                        }
                        $ok = $db->CompleteTrans();
                        if (!$ok && !$failed) {
                            $exec_log[] = 'Unknown SQL execution error';
                            $failed = true;
                        }
                        if ($failed) {
                            $sql_error = implode("\n", $exec_log);
                        }
                    }

                    if (empty($sql_error)) {
                        // Run a module-specific PHP installer after any SQL files.
                        $install_php = 'modules' . SEP . $mdir . SEP . 'install.php';
                        if (!run_module_php_script($install_php, $exec_log, $db)) {
                            $sql_error = implode("\n", $exec_log);
                        }
                    }

                    if ($sql_error !== '') {
                        $msg = 'Module SQL/install failed. See log below.';
                        $msg_type = 'danger';
                    } else {
                        $ins = "INSERT INTO " . PRFX . "MODULES (MODULE_NAME,MODULE_DIR,MODULE_VERSION,MODULE_AUTHOR,MODULE_DESC,INSTALLED,ENABLED) VALUES (" . $db->qstr($m['name']) . "," . $db->qstr($m['dir']) . "," . $db->qstr($m['version']) . "," . $db->qstr($m['author']) . "," . $db->qstr($m['description']) . ",1,1)";
                        $db->Execute($ins);
                        $msg = 'Module installed and enabled: ' . $mdir . ($sql_files ? ' (SQL executed)' : '');
                    }
            } else {
                $m = read_manifest($mdir);
                $install_php = 'modules' . SEP . $mdir . SEP . 'install.php';
                if (!run_module_php_script($install_php, $exec_log, $db)) {
                    $msg = 'Module install failed. See log below.';
                    $msg_type = 'danger';
                } else {
                    $upd = "UPDATE " . PRFX . "MODULES SET
                            MODULE_NAME=" . $db->qstr($m['name']) . ",
                            MODULE_VERSION=" . $db->qstr($m['version']) . ",
                            MODULE_AUTHOR=" . $db->qstr($m['author']) . ",
                            MODULE_DESC=" . $db->qstr($m['description']) . ",
                            INSTALLED=1,
                            ENABLED=1
                            WHERE MODULE_DIR=" . $db->qstr($mdir);
                    $db->Execute($upd);
                    $msg = 'Module installed and enabled: ' . $mdir;
                }
            }
        } elseif ($action === 'uninstall') {
                // Destructive action: require an explicit confirmation value.
                if (empty($_POST['confirm_uninstall']) || $_POST['confirm_uninstall'] !== 'yes') {
                    $msg = 'Uninstall was not confirmed.';
                    $msg_type = 'warning';
                } else {
                    // look for uninstall.sql files
                    $un_sql_files = array();
                    $c1 = 'modules' . SEP . $mdir . SEP . 'uninstall.sql';
                    $c2 = 'modules' . SEP . $mdir . SEP . 'sql' . SEP . 'uninstall.sql';
                    if (is_file($c1)) $un_sql_files[] = $c1;
                    if (is_file($c2)) $un_sql_files[] = $c2;
                    $sql_dir2 = 'modules' . SEP . $mdir . SEP . 'sql';
                    if (is_dir($sql_dir2)) {
                        foreach (scandir($sql_dir2) as $f) {
                            if (preg_match('/uninstall\.sql$/i', $f)) {
                                $p = $sql_dir2 . SEP . $f;
                                if (!in_array($p, $un_sql_files)) $un_sql_files[] = $p;
                            }
                        }
                    }
                    $failed = false;
                    if (!empty($un_sql_files)) {
                        $db->StartTrans();
                        foreach ($un_sql_files as $sf) {
                            $exec_log[] = "Running uninstall SQL file: $sf";
                            $content = file_get_contents($sf);
                            if ($content === false) {
                                $exec_log[] = "Failed to read uninstall SQL file: $sf";
                                $db->FailTrans();
                                $failed = true;
                                break;
                            }
                            $stmts = parse_sql_statements($content);
                            foreach ($stmts as $stmt) {
                                $exec_log[] = "-- SQL: " . substr($stmt, 0, 200);
                                if (!$db->Execute($stmt)) {
                                    $err = $db->ErrorMsg();
                                    $exec_log[] = "ERROR: " . $err;
                                    $db->FailTrans();
                                    $failed = true;
                                    break 2;
                                }
                            }
                        }
                        $ok = $db->CompleteTrans();
                        if (!$ok && !$failed) {
                            $exec_log[] = 'Unknown uninstall SQL execution error';
                            $failed = true;
                        }
                    }

                    if (!$failed) {
                        $uninstall_php = 'modules' . SEP . $mdir . SEP . 'uninstall.php';
                        if (!run_module_php_script($uninstall_php, $exec_log, $db)) {
                            $failed = true;
                        }
                    }

                    if ($failed) {
                        $msg = 'Module uninstall failed; see log below.';
                        $msg_type = 'danger';
                    } else {
                        $db->Execute("DELETE FROM " . PRFX . "MODULES WHERE MODULE_DIR=" . $db->qstr($mdir));
                        $msg = 'Module uninstalled and its data tables removed: ' . $mdir . '. Source files were kept for reinstalling.';
                    }
                }
        } elseif ($action === 'enable' || $action === 'disable') {
            $val = ($action === 'enable') ? 1 : 0;
            $db->Execute("UPDATE " . PRFX . "MODULES SET ENABLED=" . $db->qstr($val) . " WHERE MODULE_DIR=" . $db->qstr($mdir));
            $msg = ($val ? 'Enabled ' : 'Disabled ') . $mdir;
        }
    }
    // register_all: add any modules with module.json that are not yet registered
    elseif ($action === 'register_all') {
        $added = 0;
        foreach (scandir($modules_dir) as $d) {
            if ($d === '.' || $d === '..') continue;
            if (!in_array($d, $optional_module_dirs, true)) continue;
            $path = $modules_dir . SEP . $d;
            if (!is_dir($path)) continue;
            $m = read_manifest($d);
            $q = "SELECT COUNT(*) AS c FROM " . PRFX . "MODULES WHERE MODULE_DIR=" . $db->qstr($d);
            $r = $db->Execute($q);
            if ($r && $r->fields['c'] == 0) {
                $ins = "INSERT INTO " . PRFX . "MODULES (MODULE_NAME,MODULE_DIR,MODULE_VERSION,MODULE_AUTHOR,MODULE_DESC,INSTALLED,ENABLED) VALUES (" . $db->qstr($m['name']) . "," . $db->qstr($m['dir']) . "," . $db->qstr($m['version']) . "," . $db->qstr($m['author']) . "," . $db->qstr($m['description']) . ",0,0)";
                $db->Execute($ins);
                $added++;
            }
        }
        $msg = 'Register All completed. Modules added: ' . $added;
    }
}

// scan modules directory
$dirs = array();
foreach (scandir($modules_dir) as $d) {
    if ($d === '.' || $d === '..') continue;
    if (is_dir($modules_dir . SEP . $d)) {
        $dirs[] = $d;
    }
}

$available = array();
foreach ($dirs as $d) {
    $m = read_manifest($d);
    $m['is_system'] = !in_array($d, $optional_module_dirs, true);

    if ($m['is_system']) {
        $m['installed'] = 1;
        $m['enabled'] = 1;
        $m['db_id'] = 0;
    } else {
        // check installed status
        $r = $db->Execute("SELECT * FROM " . PRFX . "MODULES WHERE MODULE_DIR=" . $db->qstr($d) . " LIMIT 1");
        if ($r && !$r->EOF) {
            $m['installed'] = (int)$r->fields['INSTALLED'];
            $m['enabled'] = (int)$r->fields['ENABLED'];
            $m['db_id'] = $r->fields['MODULE_ID'];
        } else {
            $m['installed'] = 0;
            $m['enabled'] = 0;
            $m['db_id'] = 0;
        }
    }
    $available[] = $m;
}

$smarty->assign('modules', $available);
$smarty->assign('msg', $msg);
$smarty->assign('msg_type', $msg_type);
// pass execution log (if any) to template
$smarty->assign('exec_log', $exec_log);
$smarty->display('control' . SEP . 'modules.tpl');


?>
