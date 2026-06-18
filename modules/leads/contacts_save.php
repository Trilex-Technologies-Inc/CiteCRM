<?php
/* Leads - save contact */
require_once 'modules' . SEP . 'leads' . SEP . 'include.php';

$id = isset($_POST['contact_id']) ? (int)$_POST['contact_id'] : 0;
$name = isset($_POST['contact_name']) ? trim($_POST['contact_name']) : '';
$email = isset($_POST['contact_email']) ? trim($_POST['contact_email']) : '';
$phone = isset($_POST['contact_phone']) ? trim($_POST['contact_phone']) : '';
$company = isset($_POST['company']) ? trim($_POST['company']) : '';

if ($name === '') force_page('core', 'error&error_msg=' . urlencode('Name required'));

if ($id > 0) {
    $db->Execute("UPDATE " . PRFX . "LEAD_CONTACTS SET CONTACT_NAME=" . $db->qstr($name) . ", CONTACT_EMAIL=" . $db->qstr($email) . ", CONTACT_PHONE=" . $db->qstr($phone) . ", COMPANY=" . $db->qstr($company) . " WHERE CONTACT_ID=" . $db->qstr($id));
} else {
    $db->Execute("INSERT INTO " . PRFX . "LEAD_CONTACTS (CONTACT_NAME,CONTACT_EMAIL,CONTACT_PHONE,COMPANY) VALUES (" . $db->qstr($name) . "," . $db->qstr($email) . "," . $db->qstr($phone) . "," . $db->qstr($company) . ")");
}

force_page('leads', 'contacts_list');

?>
