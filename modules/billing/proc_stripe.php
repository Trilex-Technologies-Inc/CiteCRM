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

	// If app is installed in a subfolder (e.g. /z), include it.
	$script = isset($_SERVER['SCRIPT_NAME']) ? (string)$_SERVER['SCRIPT_NAME'] : '';
	$basePath = '';
	if ($script !== '') {
		$dir = str_replace('\\', '/', dirname($script));
		if ($dir !== '/' && $dir !== '.' && $dir !== '\\') {
			$basePath = rtrim($dir, '/');
		}
	}

	return $scheme . '://' . $host . $basePath;
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

function citecrm_safe_redirect($url)
{
	$url = (string)$url;

	if (!headers_sent()) {
		header('Location: ' . $url);
		exit;
	}

	$esc = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
	echo '<!doctype html><html><head><meta charset="utf-8">';
	echo '<meta http-equiv="refresh" content="0;url=' . $esc . '">';
	echo '<title>Redirecting...</title></head><body>';
	echo 'Redirecting... <a href="' . $esc . '">Continue</a>';
	echo '</body></html>';
	exit;
}

function citecrm_send_payment_email($db, $invoice_id, $workorder_id, $customer_id, $amount, $payment_url, $company_name)
{
	$email_sent = false;
	$customer_email = '';
	$customer_name = '';

	if ($customer_id > 0) {
		$q = "SELECT CUSTOMER_EMAIL, CUSTOMER_DISPLAY_NAME, CUSTOMER_FIRST_NAME, CUSTOMER_LAST_NAME
			  FROM " . PRFX . "TABLE_CUSTOMER
			  WHERE CUSTOMER_ID=" . $db->qstr($customer_id);
		$rs = $db->Execute($q);
		if ($rs) {
			$customer_email = trim((string)$rs->fields['CUSTOMER_EMAIL']);
			$customer_name = trim((string)$rs->fields['CUSTOMER_DISPLAY_NAME']);
			if ($customer_name === '') {
				$customer_name = trim((string)$rs->fields['CUSTOMER_FIRST_NAME'] . ' ' . (string)$rs->fields['CUSTOMER_LAST_NAME']);
			}
		}
	}

	$company_email = '';
	$q = "SELECT COMPANY_EMAIL, COMPANY_NAME FROM " . PRFX . "TABLE_COMPANY";
	$rs = $db->Execute($q);
	if ($rs) {
		$company_email = trim((string)$rs->fields['COMPANY_EMAIL']);
		if (trim((string)$rs->fields['COMPANY_NAME']) !== '') {
			$company_name = (string)$rs->fields['COMPANY_NAME'];
		}
	}

	$has_sendmail = false;
	$sendmail_path = (string)ini_get('sendmail_path');
	$sendmail_bin = trim(strtok($sendmail_path, " \t"));
	if ($sendmail_bin !== '' && @file_exists($sendmail_bin)) {
		$has_sendmail = true;
	}

	if ($customer_email !== '' && filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
		$subject = $company_name . ' - Stripe payment link (Invoice #' . $invoice_id . ')';

		$lines = array();
		if ($customer_name !== '') {
			$lines[] = 'Hello ' . $customer_name . ',';
			$lines[] = '';
		}
		$lines[] = 'Please use the link below to complete your payment via Stripe:';
		$lines[] = $payment_url;
		$lines[] = '';
		$lines[] = 'Invoice ID: ' . $invoice_id;
		if ($workorder_id > 0) {
			$lines[] = 'Work Order: ' . $workorder_id;
		}
		if ($amount !== '') {
			$lines[] = 'Amount: $' . $amount;
		}
		$lines[] = '';
		$lines[] = 'Thank you.';
		$message = implode("\r\n", $lines);

		$headers = array();
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-Type: text/plain; charset=UTF-8';
		if ($company_email !== '' && filter_var($company_email, FILTER_VALIDATE_EMAIL)) {
			$headers[] = 'From: ' . $company_name . ' <' . $company_email . '>';
			$headers[] = 'Reply-To: ' . $company_email;
		}

		if ($has_sendmail) {
			$email_sent = @mail($customer_email, $subject, $message, implode("\r\n", $headers));
		}

		if ($workorder_id > 0) {
			if ($email_sent) {
				$msg = "Stripe payment link emailed to " . $customer_email . " (Invoice ID: " . $invoice_id . ")";
			} else {
				$reason = $has_sendmail ? 'mail() failed' : ('sendmail missing (sendmail_path=' . $sendmail_path . ')');
				$msg = "Stripe payment link NOT emailed to " . $customer_email . " (Invoice ID: " . $invoice_id . ") - " . $reason;
				@error_log('[' . date('c') . '] ' . $msg . "\n", 3, 'log/mail.log');
			}
			$sql = "INSERT INTO " . PRFX . "TABLE_WORK_ORDER_STATUS SET
					WORK_ORDER_ID=" . $db->qstr($workorder_id) . ",
					WORK_ORDER_STATUS_DATE=" . $db->qstr(time()) . ",
					WORK_ORDER_STATUS_NOTES=" . $db->qstr($msg) . ",
					WORK_ORDER_STATUS_ENTER_BY=" . $db->qstr($_SESSION['login_id']);
			$db->Execute($sql);
		}
	}

	return array(
		'email_sent' => $email_sent ? 1 : 0,
		'email_to' => $customer_email,
	);
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
// Optional: email the Stripe payment link to the customer instead of redirecting immediately.
$email_link = !empty($VAR['stripe_email_link']) ? 1 : 0;

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
	. '&escape=1'
	. '&session_id={CHECKOUT_SESSION_ID}'
	. '&invoice_id=' . urlencode((string)$invoice_id)
	. '&wo_id=' . urlencode((string)$workorder_id);
if ($email_link === 1) {
	$cancel_url = $base . '/index.php?page=billing:stripe_cancel'
		. '&escape=1'
		. '&invoice_id=' . urlencode((string)$invoice_id)
		. '&wo_id=' . urlencode((string)$workorder_id);
} else {
	$cancel_url = $base . '/index.php?page=billing:new'
		. '&wo_id=' . urlencode((string)$workorder_id)
		. '&customer_id=' . urlencode((string)$customer_id)
		. '&invoice_id=' . urlencode((string)$invoice_id)
		. '&page_title=Billing'
		. '&error_msg=' . urlencode('Stripe payment canceled.');
}

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

/* Note: we email the Checkout Session URL (hosted Stripe payment page). */
if ($email_link === 1) {
	$result = citecrm_send_payment_email(
		$db,
		$invoice_id,
		$workorder_id,
		$customer_id,
		$amount,
		$redirect_url,
		$company_name
	);

	$smarty->assign('invoice_id', $invoice_id);
	$smarty->assign('wo_id', $workorder_id);
	$smarty->assign('stripe_url', $redirect_url);
	$smarty->assign('email_sent', (int)$result['email_sent']);
	$smarty->assign('email_to', (string)$result['email_to']);
	$smarty->display('billing' . SEP . 'proc_stripe.tpl');
	exit;
}

citecrm_safe_redirect($redirect_url);
	?>
