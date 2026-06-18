<?php
/* Leads - save account */
require_once 'modules' . SEP . 'leads' . SEP . 'include.php';

$id = isset($_POST['account_id']) ? (int)$_POST['account_id'] : 0;
$name = isset($_POST['account_name']) ? trim($_POST['account_name']) : '';
$phone = isset($_POST['account_phone']) ? trim($_POST['account_phone']) : '';
$website = isset($_POST['account_website']) ? trim($_POST['account_website']) : '';

if ($name === '') force_page('core', 'error&error_msg=' . urlencode('Account name required'));

if ($id > 0) {
    $db->Execute("UPDATE " . PRFX . "LEAD_ACCOUNTS SET ACCOUNT_NAME=" . $db->qstr($name) . ", ACCOUNT_PHONE=" . $db->qstr($phone) . ", ACCOUNT_WEBSITE=" . $db->qstr($website) . " WHERE ACCOUNT_ID=" . $db->qstr($id));
} else {
    $db->Execute("INSERT INTO " . PRFX . "LEAD_ACCOUNTS (ACCOUNT_NAME,ACCOUNT_PHONE,ACCOUNT_WEBSITE) VALUES (" . $db->qstr($name) . "," . $db->qstr($phone) . "," . $db->qstr($website) . ")");
}

force_page('leads', 'accounts_list');

?>
