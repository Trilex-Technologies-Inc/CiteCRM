<?php
/* Messaging - Email Logs viewer */

global $VAR, $db, $smarty;

$page = isset($VAR['p']) ? max(1, (int)$VAR['p']) : 1;
$per_page = 50;
$offset = ($page - 1) * $per_page;

// view single record
$view_row = null;
$view_id = null;
if (isset($VAR['view']) && (int)$VAR['view'] > 0) {
    $view_id = (int)$VAR['view'];
} elseif (isset($_GET['view']) && (int)$_GET['view'] > 0) {
    $view_id = (int)$_GET['view'];
}
if ($view_id !== null) {
    $id = $view_id;
    $r = $db->Execute("SELECT * FROM " . PRFX . "EMAIL_LOG WHERE LOG_ID=" . $db->qstr($id) . " LIMIT 1");
    if ($r && !$r->EOF) {
        $view_row = $r->fields;
        if (isset($view_row['CREATED_AT'])) {
            $ca = $view_row['CREATED_AT'];
            $ts = is_numeric($ca) ? (int)$ca : strtotime($ca);
            $view_row['CREATED_AT_FMT'] = $ts ? date('Y-m-d H:i:s', $ts) : $ca;
        }
    }
}

// list
$total = (int)$db->GetOne("SELECT COUNT(1) FROM " . PRFX . "EMAIL_LOG");
$rows = array();
$rs = $db->Execute("SELECT * FROM " . PRFX . "EMAIL_LOG ORDER BY CREATED_AT DESC LIMIT " . intval($offset) . "," . intval($per_page));
if ($rs && !$rs->EOF) {
    $rows = $rs->GetArray();
    foreach ($rows as &$r) {
        // Ensure LOG_ID is available (it should be from SELECT *)
        if (isset($r['LOG_ID'])) {
            $r['LOG_ID'] = (int)$r['LOG_ID'];
        }
        
        // Format created_at
        if (isset($r['CREATED_AT'])) {
            $ca = $r['CREATED_AT'];
            $ts = is_numeric($ca) ? (int)$ca : strtotime($ca);
            $r['CREATED_AT_FMT'] = $ts ? date('Y-m-d H:i:s', $ts) : $ca;
        }
        
        // Escape subject for safe HTML display
        $r['SUBJECT_ESC'] = htmlspecialchars($r['SUBJECT'] ?? '');
        
        // Ensure all email fields are set
        $r['FROM_EMAIL'] = $r['FROM_EMAIL'] ?? '';
        $r['TO_EMAIL'] = $r['TO_EMAIL'] ?? '';
        $r['CC_EMAIL'] = $r['CC_EMAIL'] ?? '';
        $r['BCC_EMAIL'] = $r['BCC_EMAIL'] ?? '';
        $r['SUBJECT'] = $r['SUBJECT'] ?? '';
        $r['BODY'] = $r['BODY'] ?? '';
        $r['RAW'] = $r['RAW'] ?? '';
        $r['DIRECTION'] = $r['DIRECTION'] ?? '';
        $r['LINKED_CUSTOMER_ID'] = $r['LINKED_CUSTOMER_ID'] ?? '';
    }
}

$smarty->assign('rows', $rows);
$smarty->assign('total', $total);
$smarty->assign('page', $page);
$smarty->assign('per_page', $per_page);
$smarty->assign('view_row', $view_row);

$smarty->display('messaging' . SEP . 'logs.tpl');