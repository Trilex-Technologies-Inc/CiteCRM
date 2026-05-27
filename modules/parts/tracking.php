<?php
####################################################
# IN Cite CRM	Customer Relations Management			#
#  This program is distributed under the terms and	#
#  conditions of the GPL							#
#  Parts Tracking									#
####################################################

if (!xml2php("parts")) {
	$smarty->assign('error_msg', "Error in language file");
}

$invoice_id = isset($VAR['invoice_id']) ? $VAR['invoice_id'] : '';
$order_id = isset($VAR['order_id']) ? $VAR['order_id'] : '';

if ($invoice_id === '' || $order_id === '') {
	force_page('core', 'error&error_msg=Missing invoice_id or order_id&menu=1&type=warning');
	exit;
}

if (!ctype_digit((string)$invoice_id) || !ctype_digit((string)$order_id)) {
	force_page('core', 'error&error_msg=Invalid invoice_id or order_id&menu=1&type=warning');
	exit;
}

$q = "SELECT * FROM " . PRFX . "ORDERS
      WHERE ORDER_ID=" . $db->qstr((int)$order_id) . "
        AND INVOICE_ID=" . $db->qstr((int)$invoice_id) . "
      LIMIT 1";

if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
	exit;
}

if ($rs->EOF) {
	force_page('core', 'error&error_msg=Order not found&menu=1&type=warning');
	exit;
}

$order = $rs->fields;
$tracking_no = isset($order['TRACKING_NO']) ? (string)$order['TRACKING_NO'] : '';
$tracking_available = ($tracking_no !== '' && $tracking_no !== '0');

$smarty->assign('order', $order);
$smarty->assign('tracking_no', $tracking_no);
$smarty->assign('tracking_available', $tracking_available);
$smarty->display('parts' . SEP . 'tracking.tpl');

