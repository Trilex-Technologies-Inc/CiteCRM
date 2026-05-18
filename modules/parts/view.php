<?php
####################################################
# IN Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Orders View													#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################
$order_id = isset($VAR['ORDER_ID']) ? $VAR['ORDER_ID'] : '';
if (!xml2php("parts")) {
	$smarty->assign('error_msg', "Error in language file");
}

$q = "SELECT * FROM " . PRFX . "ORDERS WHERE ORDER_ID=" . $db->qstr($order_id);
if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
	exit;
}
$arr = $rs->GetArray();

if (count($arr) > 0) {
	// Order line-items are stored in ORDERS_DETAILS at checkout time.
	// The previous join to TABLE_INVOICE_PARTS incorrectly matched ORDER_ID=INVOICE_ID,
	// which can produce an empty result set even when items exist.
	// Keep template compatibility by aliasing DESCRIPTION/VENDOR to the expected keys.
	$q = "SELECT " . PRFX . "ORDERS_DETAILS.*,
	             " . PRFX . "ORDERS_DETAILS.DESCRIPTION AS INVOICE_PARTS_DESCRIPTION,
	             " . PRFX . "ORDERS_DETAILS.VENDOR AS INVOICE_PARTS_MANUF
	      FROM " . PRFX . "ORDERS_DETAILS
	      WHERE ORDER_ID = " . $db->qstr($arr[0]['ORDER_ID']) . "
	      ORDER BY DETAILS_ID ASC";
} else {
	force_page('core', 'error&error_msg=Order not found&menu=1&type=warning');
	exit;
}
$rs = $db->execute($q);

$details = $rs->GetArray();

$smarty->assign('order_details', $details);
$smarty->assign('order', $arr);
$smarty->assign('invoice_details', array('TAX' => '0.00'));
$smarty->display('parts' . SEP . 'view.tpl');
