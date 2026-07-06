<?php
/* Leads - export CSV */
require_once 'modules' . SEP . 'leads' . SEP . 'include.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="leads_export.csv"');
echo "Lead ID,Title,Status,Priority,Account,Contact\n";
$q = "SELECT L.LEAD_ID,L.LEAD_TITLE,L.LEAD_STATUS,L.LEAD_PRIORITY,A.ACCOUNT_NAME,C.CONTACT_NAME FROM " . PRFX . "LEADS L LEFT JOIN " . PRFX . "LEAD_ACCOUNTS A ON A.ACCOUNT_ID=L.ACCOUNT_ID LEFT JOIN " . PRFX . "LEAD_CONTACTS C ON C.CONTACT_ID=L.CONTACT_ID";
if ($rs = @$db->Execute($q)) {
    while (!$rs->EOF) {
        $r = $rs->fields;
        $line = array($r['LEAD_ID'],$r['LEAD_TITLE'],$r['LEAD_STATUS'],$r['LEAD_PRIORITY'],$r['ACCOUNT_NAME'],$r['CONTACT_NAME']);
        $escaped = array();
        foreach ($line as $v) $escaped[] = '"' . str_replace('"', '""', (string)$v) . '"';
        echo implode(',', $escaped) . "\n";
        $rs->MoveNext();
    }
}
exit;

?>
