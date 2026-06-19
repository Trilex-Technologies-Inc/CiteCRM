<?php
/* Messaging - Email Logs viewer */

global $VAR, $db, $smarty;

$page = isset($VAR['p']) ? max(1, (int)$VAR['p']) : 1;
$per_page = 50;
$offset = ($page - 1) * $per_page;

$view_row = null;
$view_id = null;

if (isset($VAR['view']) && (int)$VAR['view'] > 0) {
    $view_id = (int)$VAR['view'];
} elseif (isset($_GET['view']) && (int)$_GET['view'] > 0) {
    $view_id = (int)$_GET['view'];
}

if ($view_id !== null) {
    $id = $view_id;
    $sql = "SELECT * FROM " . PRFX . "EMAIL_LOG WHERE LOG_ID = " . $db->qstr($id) . " LIMIT 1";
    $r = $db->Execute($sql);
    
    if ($r && !$r->EOF) {
        $view_row = $r->fields;
        
        if (isset($view_row['CREATED_AT']) && $view_row['CREATED_AT']) {
            $ca = $view_row['CREATED_AT'];
            if (is_numeric($ca)) {
                $ts = (int)$ca;
            } else {
                $ts = strtotime($ca);
                if ($ts === false || $ts === -1) {
                    $ts = null;
                }
            }
            $view_row['CREATED_AT_FMT'] = ($ts !== null) ? date('Y-m-d H:i:s', $ts) : $ca;
        } else {
            $view_row['CREATED_AT_FMT'] = '';
        }
        
        $view_row['SUBJECT_ESC'] = isset($view_row['SUBJECT']) ? htmlspecialchars($view_row['SUBJECT'], ENT_QUOTES, 'UTF-8') : '';
        $view_row['BODY_ESC'] = isset($view_row['BODY']) ? htmlspecialchars($view_row['BODY'], ENT_QUOTES, 'UTF-8') : '';
        $view_row['RAW_ESC'] = isset($view_row['RAW']) ? htmlspecialchars($view_row['RAW'], ENT_QUOTES, 'UTF-8') : '';
        
        $default_fields = array('FROM_EMAIL', 'TO_EMAIL', 'CC_EMAIL', 'BCC_EMAIL', 'SUBJECT', 'BODY', 'RAW', 'DIRECTION', 'LINKED_CUSTOMER_ID');
        foreach ($default_fields as $field) {
            if (!isset($view_row[$field])) {
                $view_row[$field] = '';
            }
        }
    }
}

$total_sql = "SELECT COUNT(1) FROM " . PRFX . "EMAIL_LOG";
$total_result = $db->GetOne($total_sql);
$total = ($total_result !== false && $total_result !== null) ? (int)$total_result : 0;

$rows = array();
$limit_sql = "SELECT * FROM " . PRFX . "EMAIL_LOG ORDER BY CREATED_AT DESC LIMIT " . intval($offset) . "," . intval($per_page);
$rs = $db->Execute($limit_sql);

if ($rs && !$rs->EOF) {
    $rows = $rs->GetArray();
    
    foreach ($rows as &$r) {
        if (isset($r['LOG_ID'])) {
            $r['LOG_ID'] = (int)$r['LOG_ID'];
        } else {
            $r['LOG_ID'] = 0;
        }
        
        if (isset($r['CREATED_AT']) && $r['CREATED_AT']) {
            $ca = $r['CREATED_AT'];
            if (is_numeric($ca)) {
                $ts = (int)$ca;
            } else {
                $ts = strtotime($ca);
                if ($ts === false || $ts === -1) {
                    $ts = null;
                }
            }
            $r['CREATED_AT_FMT'] = ($ts !== null) ? date('Y-m-d H:i:s', $ts) : $ca;
        } else {
            $r['CREATED_AT_FMT'] = '';
        }
        
        $r['SUBJECT_ESC'] = isset($r['SUBJECT']) ? htmlspecialchars($r['SUBJECT'], ENT_QUOTES, 'UTF-8') : '';
        $r['BODY_ESC'] = isset($r['BODY']) ? htmlspecialchars($r['BODY'], ENT_QUOTES, 'UTF-8') : '';
        
        $default_fields = array('FROM_EMAIL', 'TO_EMAIL', 'CC_EMAIL', 'BCC_EMAIL', 'SUBJECT', 'BODY', 'RAW', 'DIRECTION', 'LINKED_CUSTOMER_ID');
        foreach ($default_fields as $field) {
            if (!isset($r[$field])) {
                $r[$field] = '';
            }
        }
    }
    unset($r);
}

$total_pages = ($total > 0) ? ceil($total / $per_page) : 1;
$current_page = min($page, $total_pages);

$smarty->assign('rows', $rows);
$smarty->assign('total', $total);
$smarty->assign('page', $current_page);
$smarty->assign('per_page', $per_page);
$smarty->assign('view_row', $view_row);
$smarty->assign('total_pages', $total_pages);

$smarty->display('messaging' . SEP . 'logs.tpl');
?>