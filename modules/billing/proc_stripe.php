<?php
require_once("include.php");

if (!xml2php("billing")) {
	$smarty->assign('error_msg', "Error in language file");
}

function citecrm_base_url()
{
	$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
	$scheme = $https ? 'https' : 'http';
	$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
	if ($host === '') {
		return '';
	}
	return $scheme . '://' . $host;
}

function stripe_api_request($method, $path, $params, $secret_key)
{
	$url = 'https://api.stripe.com' . $path;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Authorization: Bearer ' . $secret_key,
		'Content-Type: application/x-www-form-urlencoded',
	));
	if (strtoupper($method) !== 'GET') {
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params, '', '&'));
	}
	$resp = curl_exec($ch);
	$http_code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$curl_err = curl_error($ch);
	curl_close($ch);

	if ($resp === false || $http_code < 200 || $http_code >= 300) {
		return array(
			'ok' => false,
			'http_code' => $http_code,
			'error' => $curl_err ?: 'Stripe API error',
			'raw' => (string)$resp,
		);
	}

	$data = json_decode((string)$resp, true);
	if (!is_array($data)) {
		return array('ok' => false, 'http_code' => $http_code, 'error' => 'Invalid Stripe JSON', 'raw' => (string)$resp);
	}

	return array('ok' => true, 'http_code' => $http_code, 'data' => $data);
}

/* get stripe config */
$q = "SELECT STRIPE_SECRET_KEY, STRIPE_TEST_MODE FROM " . PRFX . "SETUP";
$rs = $db->Execute($q);
if (!$rs) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
	exit;
}
$stripe_secret_key = trim((string)decrypt(isset($rs->fields['STRIPE_SECRET_KEY']) ? $rs->fields['STRIPE_SECRET_KEY'] : '', $strKey));
$stripe_test_mode = isset($rs->fields['STRIPE_TEST_MODE']) ? (int)$rs->fields['STRIPE_TEST_MODE'] : 1;

if ($stripe_secret_key === '') {
	force_page('core', 'error&error_msg=Stripe is not configured. Please set your Stripe keys in Control Panel -> Payment Options.&menu=1');
	exit;
}

$amount = isset($VAR['stripe_amount']) ? (string)$VAR['stripe_amount'] : '';
$invoice_id = isset($VAR['invoice_id']) ? (int)$VAR['invoice_id'] : 0;
$workorder_id = isset($VAR['workorder_id']) ? (int)$VAR['workorder_id'] : 0;
$customer_id = isset($VAR['customer_id']) ? (int)$VAR['customer_id'] : 0;

if ($invoice_id <= 0 || $workorder_id <= 0 || $customer_id <= 0) {
	force_page('core', 'error&error_msg=Missing invoice/workorder/customer information for Stripe.&menu=1');
	exit;
}

$amount_num = (float)$amount;
if ($amount_num <= 0) {
	force_page('core', 'error&error_msg=Invalid Stripe amount.&menu=1');
	exit;
}
$amount_cents = (int)round($amount_num * 100);
if ($amount_cents < 50) {
	force_page('core', 'error&error_msg=Stripe amount must be at least $0.50.&menu=1');
	exit;
}

/* get company info */
$q = "SELECT COMPANY_NAME FROM " . PRFX . "TABLE_COMPANY";
$rs = $db->Execute($q);
$company_name = ($rs && trim((string)$rs->fields['COMPANY_NAME']) !== '') ? (string)$rs->fields['COMPANY_NAME'] : 'CiteCRM';

$base = citecrm_base_url();
if ($base === '') {
	force_page('core', 'error&error_msg=Cannot determine base URL for Stripe redirects (missing HTTP_HOST).&menu=1');
	exit;
}

$success_url = $base . '/index.php?page=billing:stripe_complete'
	. '&session_id={CHECKOUT_SESSION_ID}'
	. '&invoice_id=' . urlencode((string)$invoice_id)
	. '&wo_id=' . urlencode((string)$workorder_id);
$cancel_url = $base . '/index.php?page=billing:new'
	. '&wo_id=' . urlencode((string)$workorder_id)
	. '&customer_id=' . urlencode((string)$customer_id)
	. '&invoice_id=' . urlencode((string)$invoice_id)
	. '&page_title=Billing'
	. '&error_msg=' . urlencode('Stripe payment canceled.');

$params = array(
	'mode' => 'payment',
	'success_url' => $success_url,
	'cancel_url' => $cancel_url,
	'client_reference_id' => (string)$invoice_id,
	'metadata[invoice_id]' => (string)$invoice_id,
	'metadata[workorder_id]' => (string)$workorder_id,
	'metadata[customer_id]' => (string)$customer_id,
	'line_items[0][price_data][currency]' => 'usd',
	'line_items[0][price_data][product_data][name]' => $company_name . ' Invoice #' . $invoice_id,
	'line_items[0][price_data][unit_amount]' => (string)$amount_cents,
	'line_items[0][quantity]' => '1',
);

$result = stripe_api_request('POST', '/v1/checkout/sessions', $params, $stripe_secret_key);
if (empty($result['ok'])) {
	$http_code = isset($result['http_code']) ? (int)$result['http_code'] : 0;
	$err = isset($result['error']) ? (string)$result['error'] : 'Unknown';
	$msg = 'Stripe error (HTTP ' . $http_code . '): ' . $err;
	force_page('core', 'error&error_msg=' . urlencode($msg) . '&menu=1');
	exit;
}

$session = $result['data'];
$redirect_url = isset($session['url']) ? (string)$session['url'] : '';
if ($redirect_url === '') {
	force_page('core', 'error&error_msg=Stripe did not return a redirect URL.&menu=1');
	exit;
}

header('Location: ' . $redirect_url);
exit;
?>
