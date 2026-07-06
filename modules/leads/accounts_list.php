<?php
/* Leads - accounts list */
require_once 'modules' . SEP . 'leads' . SEP . 'include.php';

$q = "SELECT * FROM " . PRFX . "LEAD_ACCOUNTS ORDER BY ACCOUNT_NAME";
$accounts = array();
if ($rs = @$db->Execute($q)) $accounts = $rs->GetArray();

$smarty->assign('accounts', $accounts);
$smarty->display('leads' . SEP . 'accounts_list.tpl');

?>
