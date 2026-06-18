<?php
if (!defined('PRFX')) exit;
$forms = $db->GetArray("SELECT FORM_ID,FORM_NAME FROM " . PRFX . "LEAD_FORMS ORDER BY FORM_NAME");
$smarty->assign('forms',$forms);
$smarty->display('leads/keys_create.tpl');

?>
