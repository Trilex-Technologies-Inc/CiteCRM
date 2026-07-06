<?php
/* Leads - save handler */
require_once 'modules' . SEP . 'leads' . SEP . 'include.php';

$lead_id = isset($_POST['lead_id']) ? (int)$_POST['lead_id'] : 0;
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$desc = isset($_POST['description']) ? trim($_POST['description']) : '';
$status = isset($_POST['status']) ? trim($_POST['status']) : 'New';
$priority = isset($_POST['priority']) ? trim($_POST['priority']) : 'Normal';

if ($title === '') {
    force_page('core', 'error&error_msg=' . urlencode('Title is required'));
    exit;
}

if ($lead_id > 0) {
    $q = "UPDATE " . PRFX . "LEADS SET LEAD_TITLE=" . $db->qstr($title) . ", LEAD_DESCRIPTION=" . $db->qstr($desc) . ", LEAD_STATUS=" . $db->qstr($status) . ", LEAD_PRIORITY=" . $db->qstr($priority) . ", UPDATED_AT=NOW() WHERE LEAD_ID=" . $db->qstr($lead_id);
    $db->Execute($q);
} else {
    $q = "INSERT INTO " . PRFX . "LEADS (LEAD_TITLE,LEAD_DESCRIPTION,LEAD_STATUS,LEAD_PRIORITY) VALUES (" . $db->qstr($title) . "," . $db->qstr($desc) . "," . $db->qstr($status) . "," . $db->qstr($priority) . ")";
    $db->Execute($q);
}

force_page('leads', 'list');

?>
