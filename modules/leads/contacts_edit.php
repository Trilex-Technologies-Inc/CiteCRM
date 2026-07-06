<?php
/* Leads - contact edit/create */
require_once 'modules' . SEP . 'leads' . SEP . 'include.php';

$contact = null;
$contact_id = isset($_GET['contact_id']) ? (int)$_GET['contact_id'] : 0;
if ($contact_id > 0) {
    $r = @$db->Execute("SELECT * FROM " . PRFX . "LEAD_CONTACTS WHERE CONTACT_ID=" . $db->qstr($contact_id) . " LIMIT 1");
    if ($r && !$r->EOF) $contact = $r->fields;
}

$smarty->assign('contact', $contact);
$smarty->display('leads' . SEP . 'contacts_edit.tpl');

?>
