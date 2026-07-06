<?php
/* Leads - list leads */
require_once 'modules' . SEP . 'leads' . SEP . 'include.php';

$q = "SELECT L.*, A.ACCOUNT_NAME, C.CONTACT_NAME FROM " . PRFX . "LEADS L
    LEFT JOIN " . PRFX . "LEAD_ACCOUNTS A ON A.ACCOUNT_ID = L.ACCOUNT_ID
    LEFT JOIN " . PRFX . "LEAD_CONTACTS C ON C.CONTACT_ID = L.CONTACT_ID
    ORDER BY L.CREATED_AT DESC";

$leads = array();
if ($rs = @$db->Execute($q)) {
    $leads = $rs->GetArray();
}

$smarty->assign('leads', $leads);
$smarty->assign('admin_links', array(
    array('label' => 'Form Builder', 'url' => 'index.php?page=leads:form_builder'),
    array('label' => 'Forms', 'url' => 'index.php?page=leads:forms_list'),
    array('label' => 'API Keys', 'url' => 'index.php?page=leads:keys_list'),
    array('label' => 'Import CSV', 'url' => 'index.php?page=leads:import_ui'),
    array('label' => 'Import Schedules', 'url' => 'index.php?page=leads:import_schedule')
));
$smarty->display('leads' . SEP . 'list.tpl');
