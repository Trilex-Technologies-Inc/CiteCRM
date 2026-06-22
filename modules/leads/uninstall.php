<?php
/* Leads module uninstaller: removes only tables owned by this module. */
if (!defined('PRFX')) {
    exit;
}

$tables = array(
    'LEAD_BOARD_ITEMS',
    'LEAD_FORM_SUBMISSIONS',
    'LEAD_FORM_FIELDS',
    'LEAD_FORM_KEYS',
    'LEAD_IMPORT_SCHEDULES',
    'LEAD_IMPORT_PRESETS',
    'LEAD_FORMS',
    'LEAD_BOARDS',
    'LEADS',
    'LEAD_CONTACTS',
    'LEAD_ACCOUNTS',
);

$errors = array();
foreach ($tables as $table) {
    if (!$db->Execute("DROP TABLE IF EXISTS `" . PRFX . $table . "`")) {
        $errors[] = PRFX . $table . ': ' . $db->ErrorMsg();
    }
}

if ($errors) {
    throw new RuntimeException("Leads uninstall errors:\n" . implode("\n", $errors));
}

echo 'Leads module tables removed successfully.';
