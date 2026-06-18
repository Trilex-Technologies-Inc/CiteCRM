<?php
/* Leads - import CSV (simple) */
require_once 'modules' . SEP . 'leads' . SEP . 'include.php';

if (!empty($_FILES['csv_file']['tmp_name'])) {
    $fh = fopen($_FILES['csv_file']['tmp_name'], 'r');
    // expect header row
    $header = fgetcsv($fh);
    while ($row = fgetcsv($fh)) {
        // map: Title,Status,Priority,Account,Contact
        $title = isset($row[0]) ? $row[0] : '';
        $status = isset($row[1]) ? $row[1] : '';
        $priority = isset($row[2]) ? $row[2] : '';
        $account = isset($row[3]) ? $row[3] : '';
        $contact = isset($row[4]) ? $row[4] : '';
        // naive insert (no account/contact linking)
        $db->Execute("INSERT INTO " . PRFX . "LEADS (LEAD_TITLE,LEAD_STATUS,LEAD_PRIORITY) VALUES (" . $db->qstr($title) . "," . $db->qstr($status) . "," . $db->qstr($priority) . ")");
    }
    fclose($fh);
}

force_page('leads', 'list');

?>
