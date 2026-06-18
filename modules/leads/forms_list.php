<?php
if (!defined('PRFX')) exit;
// List configured lead capture forms
$forms = $db->GetArray("SELECT * FROM " . PRFX . "LEAD_FORMS ORDER BY FORM_NAME");
$smarty->assign('forms', $forms);
$smarty->display('leads/forms_list.tpl');

?>
