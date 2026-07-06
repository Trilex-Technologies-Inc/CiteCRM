<?php
/* Tasks module uninstaller. */
if (!defined('PRFX')) {
    exit;
}

if (!$db->Execute("DROP TABLE IF EXISTS `" . PRFX . "TASKS`")) {
    throw new RuntimeException(
        'Could not remove the tasks table: ' . $db->ErrorMsg()
    );
}

echo 'Tasks table removed successfully.';
