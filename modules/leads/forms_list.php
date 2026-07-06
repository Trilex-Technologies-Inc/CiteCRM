<?php
if (!defined('PRFX')) exit;
// List configured lead capture forms
$forms = $db->GetArray("SELECT * FROM " . PRFX . "LEAD_FORMS ORDER BY FORM_NAME");

// Fetch submission counts per form to display on the UI
$counts = $db->GetArray("SELECT FORM_ID, COUNT(*) AS cnt FROM " . PRFX . "LEAD_FORM_SUBMISSIONS GROUP BY FORM_ID");
$countMap = array();
if (is_array($counts)) {
    foreach ($counts as $c) {
        $countMap[$c['FORM_ID']] = (int)$c['cnt'];
    }
}

foreach ($forms as $i => $f) {
    $forms[$i]['sub_count'] = isset($countMap[$f['FORM_ID']]) ? $countMap[$f['FORM_ID']] : 0;
}
$smarty->assign('forms', $forms);
$smarty->display('leads/forms_list.tpl');
