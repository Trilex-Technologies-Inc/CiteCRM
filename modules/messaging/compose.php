<?php
/* Messaging - compose email to a customer */

if (function_exists('xml2php')) {
    @xml2php("customer");
}

$to_email = '';
$to_name = '';
if (isset($_GET['customer_id']) && (int)$_GET['customer_id'] > 0) {
    $cid = (int)$_GET['customer_id'];
    $q = "SELECT CUSTOMER_EMAIL, CUSTOMER_DISPLAY_NAME FROM " . PRFX . "TABLE_CUSTOMER WHERE CUSTOMER_ID=" . $db->qstr($cid) . " LIMIT 1";
    if ($rs = $db->Execute($q)) {
        $to_email = isset($rs->fields['CUSTOMER_EMAIL']) ? $rs->fields['CUSTOMER_EMAIL'] : '';
        $to_name = isset($rs->fields['CUSTOMER_DISPLAY_NAME']) ? $rs->fields['CUSTOMER_DISPLAY_NAME'] : '';
    }
}

// support mass actions: subscribe list
if (isset($_GET['mass']) && $_GET['mass'] === 'subscribed') {
    $emails = array();
    $q = "SELECT CUSTOMER_EMAIL FROM " . PRFX . "TABLE_CUSTOMER WHERE CUSTOMER_IS_SUBSCRIBED=1 AND CUSTOMER_EMAIL<>''";
    if ($rs = $db->Execute($q)) {
        while ($row = $rs->FetchRow()) {
            if (!empty($row['CUSTOMER_EMAIL'])) $emails[] = $row['CUSTOMER_EMAIL'];
        }
    }
    if (!empty($emails)) {
        $to_email = implode(',', $emails);
        $to_name = '';
    }
}

// load available email templates from templates/messaging/email_templates
$templates_dir = 'templates' . SEP . 'messaging' . SEP . 'email_templates';
$templates = array();
$selected_template = '';
$template_body = '';
if (is_dir($templates_dir)) {
    $files = scandir($templates_dir);
    foreach ($files as $f) {
        if ($f === '.' || $f === '..') continue;
        if (is_file($templates_dir . SEP . $f)) {
            $templates[] = $f;
        }
    }
}

if (isset($_GET['template']) && $_GET['template'] !== '') {
    $selected_template = basename($_GET['template']);
    $path = $templates_dir . SEP . $selected_template;
    if (is_file($path)) {
        $template_body = file_get_contents($path);
    }
}

$smarty->assign('to_email', $to_email);
$smarty->assign('to_name', $to_name);
$smarty->assign('customer_id', isset($cid) ? (int)$cid : 0);
$smarty->assign('templates', $templates);
$smarty->assign('selected_template', $selected_template);
$smarty->assign('template_body', $template_body);

$smarty->display('messaging' . SEP . 'compose.tpl');
