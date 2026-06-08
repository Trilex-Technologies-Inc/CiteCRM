<?php
####################################################
# IN Cite CRM	Customer Relations Management			#
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Gift Certificates (Admin)								#
####################################################

function generate_unique_gift_code_13($db)
{
	$rand_int = function ($min, $max) {
		if (function_exists('random_int')) {
			return random_int($min, $max);
		}
		return mt_rand($min, $max);
	};

	for ($attempt = 0; $attempt < 25; $attempt++) {
		$gift_code = (string) $rand_int(1, 9);
		for ($i = 1; $i < 13; $i++) {
			$gift_code .= (string) $rand_int(0, 9);
		}

		$q = "SELECT GIFT_ID FROM " . PRFX . "GIFT_CERT WHERE GIFT_CODE=" . $db->qstr($gift_code) . " LIMIT 1";
		if (!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
			exit;
		}
		if ($rs->EOF) {
			return $gift_code;
		}
	}

	return false;
}

$action = isset($VAR['action']) ? strtolower(trim((string) $VAR['action'])) : '';

if (isset($VAR['submit']) && $action === 'create') {
	$amount = isset($VAR['amount']) ? (float) $VAR['amount'] : 0.0;
	$memo = isset($VAR['memo']) ? (string) $VAR['memo'] : '';
	$customer_id = isset($VAR['customer_id']) ? (int) $VAR['customer_id'] : 0;
	$expire = isset($VAR['expire']) ? trim((string) $VAR['expire']) : '';
	$expire_ts = $expire !== '' ? strtotime($expire) : 0;

	if ($amount <= 0) {
		force_page('control', 'gift_cert&page_title=Gift%20Certificates&error_msg=Please enter a valid amount.');
		exit;
	}
	if ($expire !== '' && $expire_ts <= 0) {
		force_page('control', 'gift_cert&page_title=Gift%20Certificates&error_msg=Please enter a valid expire date.');
		exit;
	}

	$gift_code = generate_unique_gift_code_13($db);
	if ($gift_code === false) {
		force_page('control', 'gift_cert&page_title=Gift%20Certificates&error_msg=Unable to generate a unique gift certificate code. Please try again.');
		exit;
	}

	$q = "INSERT INTO " . PRFX . "GIFT_CERT SET
		MEMO=" . $db->qstr($memo) . ",
		DATE_CREATE=" . $db->qstr(time()) . ",
		EXPIRE=" . $db->qstr((int) $expire_ts) . ",
		GIFT_CODE=" . $db->qstr($gift_code) . ",
		CUSTOMER_ID=" . $db->qstr($customer_id) . ",
		AMOUNT=" . $db->qstr(number_format($amount, 2, '.', '')) . ",
		ACTIVE=" . $db->qstr(1) . ",
		DATE_REDEMED=" . $db->qstr(0) . ",
		INVOICE_ID=" . $db->qstr(0);

	if (!$db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
		exit;
	}

	force_page('control', 'gift_cert&page_title=Gift%20Certificates&created_code=' . urlencode($gift_code) . '&msg=Gift certificate created.');
	exit;
}

if (isset($VAR['submit']) && $action === 'deactivate') {
	$gift_id = isset($VAR['gift_id']) ? (int) $VAR['gift_id'] : 0;
	if ($gift_id > 0) {
		$q = "UPDATE " . PRFX . "GIFT_CERT SET ACTIVE=0 WHERE GIFT_ID=" . $db->qstr($gift_id);
		if (!$db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
			exit;
		}
	}
	force_page('control', 'gift_cert&page_title=Gift%20Certificates&msg=Gift certificate deactivated.');
	exit;
}

/* list/search */
$search_code = isset($VAR['search_code']) ? preg_replace('/\s+/', '', (string) $VAR['search_code']) : '';
$search_customer_id = isset($VAR['search_customer_id']) ? (int) $VAR['search_customer_id'] : 0;
$search_active = isset($VAR['search_active']) ? trim((string) $VAR['search_active']) : '';
$created_code = isset($VAR['created_code']) ? preg_replace('/\s+/', '', (string) $VAR['created_code']) : '';

$where = array();
if ($search_code !== '') {
	$where[] = "GIFT_CODE=" . $db->qstr($search_code);
}
if ($search_customer_id > 0) {
	$where[] = "CUSTOMER_ID=" . $db->qstr($search_customer_id);
}
if ($search_active === '1' || $search_active === '0') {
	$where[] = "ACTIVE=" . $db->qstr((int) $search_active);
}

$q = "SELECT * FROM " . PRFX . "GIFT_CERT";
if (!empty($where)) {
	$q .= " WHERE " . implode(" AND ", $where);
}
$q .= " ORDER BY GIFT_ID DESC LIMIT 50";

if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
	exit;
}
$gifts = $rs->GetArray();

$smarty->assign('search_code', $search_code);
$smarty->assign('search_customer_id', $search_customer_id);
$smarty->assign('search_active', $search_active);
$smarty->assign('created_code', $created_code);
$smarty->assign('gifts', $gifts);
$smarty->display('control' . SEP . 'gift_cert.tpl');
?>
