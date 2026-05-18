<?php
#####################################################
# Cite CRM  Customer Relations Management           #
# Copyright (C) 2003 - 2005 In-Site CRM             #
# www.citecrm.com  dev@onsitecrm.com                #
# This program is distributed under the terms and   #
# conditions of the GPL                             #
# check_gift.php                                    #
#                                                   #
#####################################################
require('include.php');

$gift_code = $VAR['gift_code'] ?? '';
$customer_id = $VAR['customer_id'] ?? '';
$invoice_id = $VAR['invoice_id'] ?? '';
$workorder_id = $VAR['workorder_id'] ?? '';

$gift_code = preg_replace('/\s+/', '', (string) $gift_code);
if ($gift_code === '') {
	force_page('billing', 'new&wo_id=' . $workorder_id . '&customer_id=' . $customer_id . '&invoice_id=' . $invoice_id . '&page_title=Billing&error_msg=Please enter a gift certificate code.');
	exit;
}
if (!preg_match('/^\d{13}$/', $gift_code)) {
	force_page('billing', 'new&wo_id=' . $workorder_id . '&customer_id=' . $customer_id . '&invoice_id=' . $invoice_id . '&page_title=Billing&error_msg=Gift certificate code must be 13 digits.');
	exit;
}

$q = "SELECT * FROM " . PRFX . "GIFT_CERT WHERE GIFT_CODE=" . $db->qstr($gift_code) . " LIMIT 1";
if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
	exit;
}

if (($rs->fields['GIFT_ID'] ?? '') === '') {
	force_page('billing', 'new&wo_id=' . $workorder_id . '&customer_id=' . $customer_id . '&invoice_id=' . $invoice_id . '&page_title=Billing&error_msg=Not a valid gift code.');
	exit;
}

$gift_active = (int) $rs->fields['ACTIVE'];
$gift_balance = (float) $rs->fields['AMOUNT'];
$gift_expire = (int) $rs->fields['EXPIRE'];

$status = ($gift_active === 1) ? 'Active' : 'Inactive';
$balance_fmt = number_format($gift_balance, 2, '.', '');
$expire_txt = ($gift_expire > 0) ? date('Y-m-d', $gift_expire) : 'No expiry';

force_page(
	'billing',
	'new&wo_id=' . $workorder_id
	. '&customer_id=' . $customer_id
	. '&invoice_id=' . $invoice_id
	. '&page_title=Billing'
	. '&msg=Gift certificate: ' . $status . ', Balance $' . $balance_fmt . ', Expires ' . $expire_txt
);
exit;

?>
