<?php
#####################################################
# Cite CRM  Customer Relations Management           #
# Copyright (C) 2003 - 2005 In-Site CRM             #
# www.citecrm.com  dev@onsitecrm.com                #
# This program is distributed under the terms and   #
# conditions of the GPL                             #
# new.php                                           #
# Version 0.0.1 Fri Sep 30 09:30:10 PDT 2005        #
#                                                   #
#####################################################
require_once("include.php");
if (!xml2php("billing")) {
	$smarty->assign('error_msg', "Error in language file");
}
// Grab customers Information
$wo_id       = $VAR['wo_id'];
$customer_id = $VAR['customer_id'];
$tech        = $_SESSION['login_id'];
$invoice_id	 = $VAR['invoice_id'];



/* Generic error control */
if ($wo_id == "" || $wo_id == "0") {
	force_page('core', 'error&error_msg=No Work Order ID&menu=1');
	exit;
}

/* check if we have a customer id and if so get details */
if ($customer_id == "" || $customer_id == "0") {
	force_page('core', 'error&error_msg=No Customer ID&menu=1');
	exit;
} else {
	$q = "SELECT * FROM " . PRFX . "TABLE_CUSTOMER WHERE CUSTOMER_ID=" . $db->qstr($customer_id);
	if (!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1');
		exit;
	}
	// Template expects each item to include CUSTOMER_ID, so use GetArray (not GetAssoc).
	$customer_details = $rs->GetArray();
	if (empty($customer_details)) {
		force_page('core', 'error&error_msg=No Customer details found.&menu=1');
		exit;
	}
	$smarty->assign('customer_details', $customer_details);
}


/* make sure we have an invoice id*/
if ($invoice_id == "" || $invoice_id == "0") {
	force_page('core', 'error&error_msg=No Invoice ID&menu=1');
	exit;
}

/* load invoice and ensure it is billable */
$q = "SELECT INVOICE_PAID, INVOICE_AMOUNT, INVOICE_DATE, INVOICE_DUE, INVOICE_ID, BALLANCE, WORKORDER_ID, CUSTOMER_ID
		FROM " . PRFX . "TABLE_INVOICE
		WHERE INVOICE_ID=" . $db->qstr($invoice_id) . "
		LIMIT 1";

if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1');
}

$row = $rs->FetchRow();
if (empty($row)) {
	force_page('core', 'error&error_msg=No invoice found for billing&menu=1');
	exit;
}

if ((string)$row['INVOICE_PAID'] === '1') {
	force_page('core', 'error&error_msg=Invoice is already paid&menu=1');
	exit;
}

// If URL params are inconsistent, trust the invoice record.
$wo_id = $row['WORKORDER_ID'];
$customer_id = $row['CUSTOMER_ID'];

// Reload customer details for the invoice's customer_id to keep hidden inputs consistent.
$q = "SELECT * FROM " . PRFX . "TABLE_CUSTOMER WHERE CUSTOMER_ID=" . $db->qstr($customer_id);
if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1');
	exit;
}
$customer_details = $rs->GetArray();
if (empty($customer_details)) {
	force_page('core', 'error&error_msg=No Customer details found.&menu=1');
	exit;
}
$smarty->assign('customer_details', $customer_details);

// Keep the original template contract (`{foreach item=item from=$invoice_details}`) intact.
$invoice_details = array(1 => $row);


if ($invoice_details[1]['INVOICE_AMOUNT'] <= 0) {
	force_page('core', 'error&error_msg=Invoice Does not have any amount to bill.&menu=1');
	exit;
}

if ($invoice_details[1]['BALLANCE'] > 0) {
	$transaction_column = transaction_invoice_column($db);
	$q = "SELECT * FROM " . PRFX . "TABLE_TRANSACTION WHERE " . $transaction_column . "=" . $db->qstr($invoice_details[1]['INVOICE_ID']);
	$rs = $db->execute($q);
	$trans = $rs->GetArray();
	$smarty->assign('trans', $trans);
}

$smarty->assign('invoice_details', $invoice_details);

/* get billing settings from db */
$q = "SELECT BILLING_OPTION, ACTIVE FROM " . PRFX . "CONFIG_BILLING_OPTIONS WHERE  ACTIVE='1'";
if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1');
	exit;
}
$billing_options = array();
while ($opt = $rs->FetchRow()) {
	if (isset($opt['BILLING_OPTION'])) {
		$billing_options[$opt['BILLING_OPTION']] = isset($opt['ACTIVE']) ? (string)$opt['ACTIVE'] : '0';
	}
}

if (empty($billing_options)) {
	force_page('core', 'error&error_msg=No Billing Methodes Available. Please select billing options in the configuration&menu=1');
	exit;
}

$smarty->assign('billing_options', $billing_options);

/* get Accepted Credit cards*/
if (isset($billing_options['cc_billing']) && $billing_options['cc_billing'] == '1') {

	$q = "SELECT CARD_TYPE, CARD_NAME FROM " . PRFX . "CONFIG_CC_CARDS WHERE ACTIVE='1'";
	if (!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1');
		exit;
	}

	$cc_cards = $rs->GetAssoc();

	if (empty($cc_cards)) {
		force_page('core', 'error&error_msg=Credit Card Billing is Set on but no cards are active. Please enable at least on credit card in the control panel&menu=1');
		exit;
	}

	$smarty->assign('cc_cards', $cc_cards);
}


$smarty->display('billing' . SEP . 'new.tpl');
