<?php
require_once("include.php");

if (!xml2php("billing")) {
	$smarty->assign('error_msg', "Error in language file");
}

function stripe_api_get($path, $secret_key)
{
	$url = 'https://api.stripe.com' . $path;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $secret_key));
	$resp = curl_exec($ch);
	$http_code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$curl_err = curl_error($ch);
	curl_close($ch);

	if ($resp === false || $http_code < 200 || $http_code >= 300) {
		return array('ok' => false, 'http_code' => $http_code, 'error' => $curl_err ?: 'Stripe API error', 'raw' => (string)$resp);
	}

	$data = json_decode((string)$resp, true);
	if (!is_array($data)) {
		return array('ok' => false, 'http_code' => $http_code, 'error' => 'Invalid Stripe JSON', 'raw' => (string)$resp);
	}

	return array('ok' => true, 'http_code' => $http_code, 'data' => $data);
}

$session_id = isset($VAR['session_id']) ? trim((string)$VAR['session_id']) : '';
$invoice_id = isset($VAR['invoice_id']) ? (int)$VAR['invoice_id'] : 0;
$workorder_id = isset($VAR['wo_id']) ? (int)$VAR['wo_id'] : 0;

if ($session_id === '' || $invoice_id <= 0 || $workorder_id <= 0) {
	force_page('core', 'error&error_msg=Missing Stripe completion parameters.&menu=1');
	exit;
}

/* get stripe config */
$q = "SELECT STRIPE_SECRET_KEY FROM " . PRFX . "SETUP";
$rs = $db->Execute($q);
if (!$rs) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
	exit;
}
$stripe_secret_key = trim((string)decrypt(isset($rs->fields['STRIPE_SECRET_KEY']) ? $rs->fields['STRIPE_SECRET_KEY'] : '', $strKey));
if ($stripe_secret_key === '') {
	force_page('core', 'error&error_msg=Stripe is not configured.&menu=1');
	exit;
}

$result = stripe_api_get('/v1/checkout/sessions/' . urlencode($session_id), $stripe_secret_key);
if (empty($result['ok'])) {
	$http_code = isset($result['http_code']) ? (int)$result['http_code'] : 0;
	$err = isset($result['error']) ? (string)$result['error'] : 'Unknown';
	$msg = 'Stripe verify error (HTTP ' . $http_code . '): ' . $err;
	force_page('core', 'error&error_msg=' . urlencode($msg) . '&menu=1');
	exit;
}

$session = $result['data'];
$payment_status = isset($session['payment_status']) ? (string)$session['payment_status'] : '';
$amount_total = isset($session['amount_total']) ? (int)$session['amount_total'] : 0;
$payment_intent = isset($session['payment_intent']) ? (string)$session['payment_intent'] : '';

if ($payment_status !== 'paid') {
	$memo = "Stripe payment not completed (status: " . $payment_status . ", session: " . $session_id . ")";
	$q = "INSERT INTO " . PRFX . "TABLE_WORK_ORDER_STATUS SET
		WORK_ORDER_ID=" . $db->qstr($workorder_id) . ",
		WORK_ORDER_STATUS_DATE=" . $db->qstr(time()) . ",
		WORK_ORDER_STATUS_NOTES=" . $db->qstr($memo) . ",
		WORK_ORDER_STATUS_ENTER_BY=" . $db->qstr($_SESSION['login_id']);
	$db->Execute($q);

	$customer_id_from_session = 0;
	if (isset($session['metadata']) && is_array($session['metadata']) && isset($session['metadata']['customer_id'])) {
		$customer_id_from_session = (int)$session['metadata']['customer_id'];
	}
	force_page(
		'billing',
		'new&wo_id=' . urlencode((string)$workorder_id)
			. '&customer_id=' . urlencode((string)$customer_id_from_session)
			. '&invoice_id=' . urlencode((string)$invoice_id)
			. '&error_msg=' . urlencode('Stripe payment not completed.')
	);
	exit;
}

/* load invoice */
$q = "SELECT * FROM " . PRFX . "TABLE_INVOICE WHERE INVOICE_ID=" . $db->qstr($invoice_id);
$rs = $db->Execute($q);
if (!$rs) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1');
	exit;
}
$invoice = $rs->FetchRow();
if (empty($invoice)) {
	force_page('core', 'error&error_msg=Invoice not found.&menu=1');
	exit;
}

$customer_id = (int)$invoice['CUSTOMER_ID'];
$invoice_amount = (float)$invoice['INVOICE_AMOUNT'];
$prev_paid = (float)$invoice['PAID_AMOUNT'];
$prev_balance = (float)$invoice['BALLANCE'];
$amount_paid = $amount_total > 0 ? ((float)$amount_total / 100.0) : 0.0;
if ($amount_paid <= 0) {
	$amount_paid = $invoice_amount;
}

/* compute new balance */
$current_due = ($prev_balance > 0) ? $prev_balance : max(0.0, ($invoice_amount - $prev_paid));
$new_balance = $current_due - $amount_paid;
if ($new_balance < 0) {
	$new_balance = 0.0;
}
$new_paid = $prev_paid + $amount_paid;
$is_paid = ($new_balance <= 0.00001) ? 1 : 0;

$memo = "Stripe payment received: $" . number_format($amount_paid, 2, '.', '') . " (Session: " . $session_id . ")";
if ($payment_intent !== '') {
	$memo .= " PI: " . $payment_intent;
}

/* insert transaction (TYPE 6 = Stripe) */
$q = "INSERT INTO " . PRFX . "TABLE_TRANSACTION SET
	DATE=" . $db->qstr(time()) . ",
	TYPE='6',
	INVOCIE_ID=" . $db->qstr($invoice_id) . ",
	WORKORDER_ID=" . $db->qstr($workorder_id) . ",
	CUSTOMER_ID=" . $db->qstr($customer_id) . ",
	MEMO=" . $db->qstr($memo) . ",
	AMOUNT=" . $db->qstr($amount_paid);
$db->Execute($q);

/* update invoice */
$q = "UPDATE " . PRFX . "TABLE_INVOICE SET
	PAID_DATE=" . $db->qstr(time()) . ",
	INVOICE_PAID=" . $db->qstr($is_paid) . ",
	PAID_AMOUNT=" . $db->qstr($new_paid) . ",
	BALLANCE=" . $db->qstr($new_balance) . "
	WHERE INVOICE_ID=" . $db->qstr($invoice_id);
$db->Execute($q);

/* update work order status */
$q = "INSERT INTO " . PRFX . "TABLE_WORK_ORDER_STATUS SET
	WORK_ORDER_ID=" . $db->qstr($workorder_id) . ",
	WORK_ORDER_STATUS_DATE=" . $db->qstr(time()) . ",
	WORK_ORDER_STATUS_NOTES=" . $db->qstr($memo) . ",
	WORK_ORDER_STATUS_ENTER_BY=" . $db->qstr($_SESSION['login_id']);
$db->Execute($q);

/* if fully paid, close */
if ($is_paid === 1) {
	$q = "UPDATE " . PRFX . "TABLE_WORK_ORDER SET
	WORK_ORDER_STATUS='6',
	WORK_ORDER_CURENT_STATUS='8'
	WHERE WORK_ORDER_ID=" . $db->qstr($workorder_id);
	$db->Execute($q);
}

force_page('invoice', 'view&invoice_id=' . urlencode((string)$invoice_id) . '&customer_id=' . urlencode((string)$customer_id));
exit;
?>
