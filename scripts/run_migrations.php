<?php
// Simple migration runner for this workspace.
// Usage: php scripts/run_migrations.php

chdir(__DIR__ . "/..");
require_once __DIR__ . "/../conf.php";

if (!isset($db)) {
    echo "Error: cannot find \$db connection from conf.php\n";
    exit(1);
}

$files = [
    'sql/create_email_tracking.sql',
    'sql/create_notifications.sql',
    'sql/upgrade_add_acl_sso_settings.sql'
];

$php_installs = [
    'modules/leads/install.php'
];

echo "Running migrations...\n";

foreach ($files as $f) {
    if (!is_file($f)) {
        echo "Skipping missing file: $f\n";
        continue;
    }
    echo "Applying $f...\n";
    $sql = file_get_contents($f);
    if ($sql === false) {
        echo "  Failed to read $f\n";
        continue;
    }

    // replace placeholder prefix token
    if (defined('PRFX')) {
        $sql = str_replace('PREFIX_', PRFX, $sql);
        $sql = str_replace('`PREFIX_', '`' . PRFX, $sql);
    }

    // split statements by semicolon (simple)
    $parts = preg_split('/;\s*\n/', $sql);
    $db->BeginTrans();
    $ok = true;
    foreach ($parts as $stmt) {
        $stmt = trim($stmt);
        if ($stmt === '' || strpos($stmt, '--') === 0) continue;
        try {
            $res = $db->Execute($stmt);
            if ($res === false) {
                echo "  ERROR: " . $db->ErrorMsg() . "\n";
                $ok = false;
                break;
            }
        } catch (Exception $ex) {
            echo "  EXCEPTION: " . $ex->getMessage() . "\n";
            $ok = false;
            break;
        }
    }
    if ($ok) {
        $db->CommitTrans();
        echo "  Applied OK.\n";
    } else {
        $db->RollbackTrans();
        echo "  Rolled back due to errors.\n";
    }
}

echo "Migrations complete. Review output for errors.\n";

// Run PHP install scripts
echo "\nRunning PHP install scripts...\n";
foreach ($php_installs as $f) {
    if (!is_file($f)) {
        echo "Skipping missing file: $f\n";
        continue;
    }
    echo "Running $f...\n";
    ob_start();
    try {
        include $f;
        $output = ob_get_clean();
        echo "  " . str_replace("\n", "\n  ", $output) . "\n";
    } catch (Exception $ex) {
        ob_end_clean();
        echo "  EXCEPTION: " . $ex->getMessage() . "\n";
    }
}

echo "All migrations and installs complete. Review output for errors.\n";
