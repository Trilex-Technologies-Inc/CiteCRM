<?php
if (!defined('PRFX')) exit;
$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
if ($form_id) {
    $form = $db->GetRow("SELECT * FROM " . PRFX . "LEAD_FORMS WHERE FORM_ID = ?", array($form_id));
    $fields = $db->GetArray("SELECT * FROM " . PRFX . "LEAD_FORM_FIELDS WHERE FORM_ID = ? ORDER BY POSITION", array($form_id));
} else {
    $form = array('FORM_ID'=>0,'FORM_NAME'=>'','FORM_SLUG'=>'','FORM_HTML'=>'','FORM_MAPPING'=>'');
    $fields = array();
}
$smarty->assign('form',$form);
$smarty->assign('fields',$fields);
$smarty->display('leads/form_builder.tpl');

?>
