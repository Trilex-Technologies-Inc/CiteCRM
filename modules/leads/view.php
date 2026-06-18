<?php
/* Leads - view lead details */
require_once 'modules' . SEP . 'leads' . SEP . 'include.php';

$lead = null;
$lead_id = isset($_GET['lead_id']) ? (int)$_GET['lead_id'] : 0;
if ($lead_id > 0) {
    $lead = leads_get_lead($lead_id);
}

$smarty->assign('lead', $lead);
$smarty->display('leads' . SEP . 'view.tpl');
