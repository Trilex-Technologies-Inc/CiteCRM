<?php
/* Leads - account edit/create */
require_once 'modules' . SEP . 'leads' . SEP . 'include.php';

$account = null;
$account_id = isset($_GET['account_id']) ? (int)$_GET['account_id'] : 0;
if ($account_id > 0) {
    $r = @$db->Execute("SELECT * FROM " . PRFX . "LEAD_ACCOUNTS WHERE ACCOUNT_ID=" . $db->qstr($account_id) . " LIMIT 1");
    if ($r && !$r->EOF) $account = $r->fields;
}

$smarty->assign('account', $account);
$smarty->display('leads' . SEP . 'accounts_edit.tpl');

?>
