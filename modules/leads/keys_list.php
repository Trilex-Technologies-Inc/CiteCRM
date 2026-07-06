<?php
if (!defined('PRFX')) exit;
// List API keys
$keys = $db->GetArray("SELECT k.*, f.FORM_NAME FROM " . PRFX . "LEAD_FORM_KEYS k LEFT JOIN " . PRFX . "LEAD_FORMS f ON f.FORM_ID = k.FORM_ID ORDER BY k.CREATED_AT DESC");
$smarty->assign('keys',$keys);
$smarty->display('leads/keys_list.tpl');

?>
