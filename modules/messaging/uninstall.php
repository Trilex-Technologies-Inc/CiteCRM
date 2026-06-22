<?php
/* Messaging module uninstaller: removes all tables owned by this module. */
if (!defined('PRFX')) {
    exit;
}

$tables = array(
    'NOTIFICATIONS',
    'EMAIL_TRACKING',
    'EMAIL_LOG',
    'EMAIL_ACCOUNTS',
    'TABLE_CONTACT',
    'TABLE_BUSINESS',
);

$errors = array();
foreach ($tables as $table) {
    if (!$db->Execute("DROP TABLE IF EXISTS `" . PRFX . $table . "`")) {
        $errors[] = PRFX . $table . ': ' . $db->ErrorMsg();
    }
}

if ($errors) {
    throw new RuntimeException("Messaging uninstall errors:\n" . implode("\n", $errors));
}

echo 'Messaging module tables removed successfully.';
