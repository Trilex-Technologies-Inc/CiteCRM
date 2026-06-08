<?php
####################################################
# IN Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Gift Certificate											#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################
require('include.php');

$requested_amount = isset($VAR['gift_amount']) ? $VAR['gift_amount'] : '';
$gift_code = isset($VAR['gift_code']) ? $VAR['gift_code'] : '';
$customer_id = isset($VAR['customer_id']) ? $VAR['customer_id'] : '';
$invoice_id = isset($VAR['invoice_id']) ? $VAR['invoice_id'] : '';
$workorder_id = isset($VAR['workorder_id']) ? $VAR['workorder_id'] : '';
$date = time();

$gift_code = preg_replace('/\\s+/', '', (string) $gift_code);
if ($gift_code === '') {
	force_page('billing', 'new&wo_id=' . $workorder_id . '&customer_id=' . $customer_id . '&invoice_id=' . $invoice_id . '&page_title=Billing&error_msg=Please enter a gift certificate code.');
	exit;
}
if (!preg_match('/^\\d{13}$/', $gift_code)) {
	force_page('billing', 'new&wo_id=' . $workorder_id . '&customer_id=' . $customer_id . '&invoice_id=' . $invoice_id . '&page_title=Billing&error_msg=Gift certificate code must be 13 digits.');
	exit;
}

/* load gift certificate */
$q = "SELECT * FROM " . PRFX . "GIFT_CERT WHERE GIFT_CODE=" . $db->qstr($gift_code) . " LIMIT 1";
if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
	exit;
}

if ((isset($rs->fields['GIFT_ID']) ? $rs->fields['GIFT_ID'] : '') === '') {
	force_page('billing', 'new&wo_id=' . $workorder_id . '&customer_id=' . $customer_id . '&invoice_id=' . $invoice_id . '&page_title=Billing&error_msg=Not a valid gift code.');
	exit;
}

$gift_expire = (int) $rs->fields['EXPIRE'];
$gift_balance = (float) $rs->fields['AMOUNT'];
$gift_active = (int) $rs->fields['ACTIVE'];
$gift_id = (int) $rs->fields['GIFT_ID'];

if ($gift_active !== 1) {
	force_page('billing', 'new&wo_id=' . $workorder_id . '&customer_id=' . $customer_id . '&invoice_id=' . $invoice_id . '&page_title=Billing&error_msg=This gift certificate is not active.');
	exit;
}

if ($gift_expire > 0 && $gift_expire < $date) {
	force_page('billing', 'new&wo_id=' . $workorder_id . '&customer_id=' . $customer_id . '&invoice_id=' . $invoice_id . '&page_title=Billing&error_msg=This gift certificate is expired.');
	exit;
}

if ($gift_balance <= 0) {
	force_page('billing', 'new&wo_id=' . $workorder_id . '&customer_id=' . $customer_id . '&invoice_id=' . $invoice_id . '&page_title=Billing&error_msg=This gift certificate has no remaining balance.');
	exit;
}

/* get invoice details */
$q = "SELECT * FROM " . PRFX . "TABLE_INVOICE WHERE INVOICE_ID=" . $db->qstr($invoice_id) . " LIMIT 1";
if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1');
	exit;
}
$invoice_details = $rs->FetchRow();

$invoice_amount = (float) (isset($invoice_details['INVOICE_AMOUNT']) ? $invoice_details['INVOICE_AMOUNT'] : 0);
$paid_amount = (float) (isset($invoice_details['PAID_AMOUNT']) ? $invoice_details['PAID_AMOUNT'] : 0);
$ballance_field = (float) (isset($invoice_details['BALLANCE']) ? $invoice_details['BALLANCE'] : 0);

$due = $ballance_field > 0 ? $ballance_field : max(0.0, $invoice_amount - $paid_amount);
if ($due <= 0) {
	force_page('billing', 'new&wo_id=' . $workorder_id . '&customer_id=' . $customer_id . '&invoice_id=' . $invoice_id . '&page_title=Billing&error_msg=This invoice is already paid.');
	exit;
}

$requested_amount = trim((string) $requested_amount);
$requested = ($requested_amount === '') ? $due : (float) $requested_amount;

if ($requested <= 0) {
	force_page('billing', 'new&wo_id=' . $workorder_id . '&customer_id=' . $customer_id . '&invoice_id=' . $invoice_id . '&page_title=Billing&error_msg=Invalid payment amount.');
	exit;
}

$apply_amount = min($requested, $gift_balance, $due);
$apply_amount = round($apply_amount, 2);
if ($apply_amount <= 0) {
	force_page('billing', 'new&wo_id=' . $workorder_id . '&customer_id=' . $customer_id . '&invoice_id=' . $invoice_id . '&page_title=Billing&error_msg=Nothing to apply from gift certificate.');
	exit;
}

$new_paid_total = round($paid_amount + $apply_amount, 2);
$new_due = round(max(0.0, $due - $apply_amount), 2);
$invoice_paid = ($new_due <= 0.00001) ? 1 : 0;

$apply_amount_fmt = number_format($apply_amount, 2, '.', '');
$new_due_fmt = number_format($new_due, 2, '.', '');

$memo = $invoice_paid
	? "Full Gift Certificate Payment Made of $$apply_amount_fmt, ID: $gift_code"
	: "Partial Gift Certificate Payment Made of $$apply_amount_fmt Balance due: $$new_due_fmt, ID: $gift_code";

/* insert Transaction */
$q = "INSERT INTO " . PRFX . "TABLE_TRANSACTION SET
	DATE=" . $db->qstr(time()) . ",
	TYPE='3',
	INVOICE_ID=" . $db->qstr($invoice_id) . ",
	WORKORDER_ID=" . $db->qstr($workorder_id) . ",
	CUSTOMER_ID=" . $db->qstr($customer_id) . ",
	MEMO=" . $db->qstr($memo) . ",
	AMOUNT=" . $db->qstr($apply_amount_fmt);
if (!$db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1');
	exit;
}

/* update invoice */
$q = "UPDATE " . PRFX . "TABLE_INVOICE SET
	PAID_DATE=" . $db->qstr(time()) . ",
	INVOICE_PAID=" . $db->qstr($invoice_paid) . ",
	PAID_AMOUNT=" . $db->qstr(number_format($new_paid_total, 2, '.', '')) . ",
	BALLANCE=" . $db->qstr(number_format($new_due, 2, '.', '')) . "
	WHERE INVOICE_ID=" . $db->qstr($invoice_id);
if (!$db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1');
	exit;
}

/* work order status note */
$q = "INSERT INTO " . PRFX . "TABLE_WORK_ORDER_STATUS SET
	WORK_ORDER_ID=" . $db->qstr($workorder_id) . ",
	WORK_ORDER_STATUS_DATE=" . $db->qstr(time()) . ",
	WORK_ORDER_STATUS_NOTES=" . $db->qstr($memo) . ",
	WORK_ORDER_STATUS_ENTER_BY=" . $db->qstr($_SESSION['login_id']);
if (!$db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1');
	exit;
}

if ($invoice_paid) {
	$q = "UPDATE " . PRFX . "TABLE_WORK_ORDER SET
		WORK_ORDER_STATUS='6',
		WORK_ORDER_CURENT_STATUS='8'
		WHERE WORK_ORDER_ID=" . $db->qstr($workorder_id);
	if (!$db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1');
		exit;
	}
}

/* update gift certificate remaining balance */
$remaining = round($gift_balance - $apply_amount, 2);
$active = ($remaining <= 0.00001) ? 0 : 1;
$q = "UPDATE " . PRFX . "GIFT_CERT SET
	AMOUNT=" . $db->qstr(number_format(max(0.0, $remaining), 2, '.', '')) . ",
	ACTIVE=" . $db->qstr($active) . ",
	DATE_REDEMED=" . $db->qstr(time()) . ",
	INVOICE_ID=" . $db->qstr($invoice_id) . "
	WHERE GIFT_ID=" . $db->qstr($gift_id);
if (!$db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
	exit;
}

force_page('invoice', "view&invoice_id=$invoice_id&customer_id=$customer_id");
exit;

?>