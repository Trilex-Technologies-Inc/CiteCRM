<?php
/* Leads - contacts list */
require_once 'modules' . SEP . 'leads' . SEP . 'include.php';

$q = "SELECT * FROM " . PRFX . "LEAD_CONTACTS ORDER BY CONTACT_NAME";
$contacts = array();
if ($rs = @$db->Execute($q)) $contacts = $rs->GetArray();

$smarty->assign('contacts', $contacts);
$smarty->display('leads' . SEP . 'contacts_list.tpl');

?>
