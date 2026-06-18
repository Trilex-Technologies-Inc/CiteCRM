<?php
/**
 * Module Manager
 * - Scans modules/ directory for available modules
 * - Reads optional module.json manifest for metadata
 * - Allows install/enable/disable/uninstall and scaffold new module
 */

if (function_exists('xml2php')) @xml2php('control');

$modules_dir = 'modules';

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

// handle actions
$msg = '';
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
        $mdir = $_POST['module_dir'];
        if ($action === 'register') {
            $m = read_manifest($mdir);
            $q = "SELECT COUNT(*) AS c FROM " . PRFX . "MODULES WHERE MODULE_DIR=" . $db->qstr($mdir);
            $r = $db->Execute($q);
            if ($r && $r->fields['c'] == 0) {
                $ins = "INSERT INTO " . PRFX . "MODULES (MODULE_NAME,MODULE_DIR,MODULE_VERSION,MODULE_AUTHOR,MODULE_DESC,INSTALLED,ENABLED) VALUES (" . $db->qstr($m['name']) . "," . $db->qstr($m['dir']) . "," . $db->qstr($m['version']) . "," . $db->qstr($m['author']) . "," . $db->qstr($m['description']) . ",1,1)";
                $db->Execute($ins);
                $msg = 'Module registered and enabled: ' . $mdir;
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
                        // try to run module install.php if present; capture output and exceptions
                        $install_php = 'modules' . SEP . $mdir . SEP . 'install.php';
                        if (is_file($install_php)) {
                            $exec_log[] = "Running install.php: $install_php";
                            try {
                                ob_start();
                                include $install_php;
                                $out = ob_get_clean();
                                if (trim($out) !== '') $exec_log[] = "install.php output: " . substr(trim($out), 0, 1000);
                            } catch (Throwable $e) {
                                ob_end_clean();
                                $exec_log[] = 'install.php threw exception: ' . $e->getMessage();
                                $sql_error = implode("\n", $exec_log);
                            }
                        }
                    }

                    if ($sql_error !== '') {
                        $msg = 'Module SQL/install failed. See log below.';
                    } else {
                        $ins = "INSERT INTO " . PRFX . "MODULES (MODULE_NAME,MODULE_DIR,MODULE_VERSION,MODULE_AUTHOR,MODULE_DESC,INSTALLED,ENABLED) VALUES (" . $db->qstr($m['name']) . "," . $db->qstr($m['dir']) . "," . $db->qstr($m['version']) . "," . $db->qstr($m['author']) . "," . $db->qstr($m['description']) . ",1,1)";
                        $db->Execute($ins);
                        $msg = 'Module installed and enabled: ' . $mdir . ($sql_files ? ' (SQL executed)' : '');
                    }
            } else {
                $msg = 'Module already installed';
            }
        } elseif ($action === 'uninstall') {
                // require explicit confirmation to run uninstall SQL
                if (empty($_POST['confirm_uninstall']) || $_POST['confirm_uninstall'] !== 'yes') {
                    $msg = 'Please confirm uninstall by checking the confirmation box.';
                    $smarty->assign('confirm_uninstall_needed', 1);
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

                    if ($failed) {
                        $msg = 'Module uninstall failed; see log below.';
                    } else {
                        $db->Execute("DELETE FROM " . PRFX . "MODULES WHERE MODULE_DIR=" . $db->qstr($mdir));
                        $msg = 'Module record removed (files not deleted): ' . $mdir . ($un_sql_files ? ' (uninstall SQL executed)' : '');
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
            $path = $modules_dir . SEP . $d;
            if (!is_dir($path)) continue;
            $m = read_manifest($d);
            $q = "SELECT COUNT(*) AS c FROM " . PRFX . "MODULES WHERE MODULE_DIR=" . $db->qstr($d);
            $r = $db->Execute($q);
            if ($r && $r->fields['c'] == 0) {
                $ins = "INSERT INTO " . PRFX . "MODULES (MODULE_NAME,MODULE_DIR,MODULE_VERSION,MODULE_AUTHOR,MODULE_DESC,INSTALLED,ENABLED) VALUES (" . $db->qstr($m['name']) . "," . $db->qstr($m['dir']) . "," . $db->qstr($m['version']) . "," . $db->qstr($m['author']) . "," . $db->qstr($m['description']) . ",1,1)";
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
    $available[] = $m;
}

// Auto-register messaging module if present but not yet registered (bootstrap)
foreach ($available as $mod) {
    if ($mod['dir'] === 'messaging' && empty($mod['installed'])) {
        $m = read_manifest('messaging');
        $ins = "INSERT INTO " . PRFX . "MODULES (MODULE_NAME,MODULE_DIR,MODULE_VERSION,MODULE_AUTHOR,MODULE_DESC,INSTALLED,ENABLED) VALUES (" . $db->qstr($m['name']) . "," . $db->qstr($m['dir']) . "," . $db->qstr($m['version']) . "," . $db->qstr($m['author']) . "," . $db->qstr($m['description']) . ",1,1)";
        @$db->Execute($ins);
        $msg = 'Messaging module auto-registered and enabled.';
        // refresh available list flag
        foreach ($available as &$am) {
            if ($am['dir'] === 'messaging') { $am['installed'] = 1; $am['enabled'] = 1; }
        }
        break;
    }
}

$smarty->assign('modules', $available);
$smarty->assign('msg', $msg);
// pass execution log (if any) to template
$smarty->assign('exec_log', $exec_log);
if (isset($confirm_uninstall_needed) && $confirm_uninstall_needed) $smarty->assign('confirm_uninstall_needed', 1);
$smarty->display('control' . SEP . 'modules.tpl');


?>
