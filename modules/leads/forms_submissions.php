<?php
if (!defined('PRFX')) exit;

$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
if (!$form_id) {
    force_page('leads', 'forms_list');
    exit;
}

$form = $db->GetRow("SELECT * FROM " . PRFX . "LEAD_FORMS WHERE FORM_ID = ?", array($form_id));
if (!$form) {
    force_page('leads', 'forms_list');
    exit;
}

$rows = $db->GetArray("SELECT SUBMISSION_ID,FORM_ID,SUBMITTED_AT,DATA,SOURCE_IP FROM " . PRFX . "LEAD_FORM_SUBMISSIONS WHERE FORM_ID = ? ORDER BY SUBMISSION_ID DESC", array($form_id));

$smarty->assign('form', $form);
$smarty->assign('submissions', $rows);
$smarty->display('leads/forms_submissions.tpl');
