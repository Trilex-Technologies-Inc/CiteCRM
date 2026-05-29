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
$tracking_provider = 'ups';
$tracking_error = '';
$tracking_result = array(
	'status' => '',
	'status_detail' => '',
	'estimated_delivery' => '',
	'latest_event' => '',
	'latest_event_time' => '',
	'latest_location' => '',
	'events' => array(),
);

if ($tracking_available) {
	$has_shipping_provider_column = false;
	$rs_cols = $db->Execute("SHOW COLUMNS FROM ".PRFX."SETUP LIKE 'SHIPPING_PROVIDER'");
	if ($rs_cols && !$rs_cols->EOF) {
		$has_shipping_provider_column = true;
	}

	$has_fedex_columns = false;
	$rs_cols = $db->Execute("SHOW COLUMNS FROM ".PRFX."SETUP LIKE 'FEDEX_KEY'");
	if ($rs_cols && !$rs_cols->EOF) {
		$has_fedex_columns = true;
	}

	$has_ups_sandbox_column = false;
	$rs_cols = $db->Execute("SHOW COLUMNS FROM ".PRFX."SETUP LIKE 'UPS_SANDBOX'");
	if ($rs_cols && !$rs_cols->EOF) {
		$has_ups_sandbox_column = true;
	}

	$has_fedex_sandbox_column = false;
	$rs_cols = $db->Execute("SHOW COLUMNS FROM ".PRFX."SETUP LIKE 'FEDEX_SANDBOX'");
	if ($rs_cols && !$rs_cols->EOF) {
		$has_fedex_sandbox_column = true;
	}

	$cols = "UPS_LOGIN,UPS_PASSWORD,UPS_ACCESS_KEY";
	if ($has_shipping_provider_column) {
		$cols .= ",SHIPPING_PROVIDER";
	}
	if ($has_ups_sandbox_column) {
		$cols .= ",UPS_SANDBOX";
	}
	if ($has_fedex_columns) {
		$cols .= ",FEDEX_KEY,FEDEX_PASSWORD,FEDEX_ACCOUNT,FEDEX_METER";
	}
	if ($has_fedex_sandbox_column) {
		$cols .= ",FEDEX_SANDBOX";
	}

	$q = "SELECT ".$cols." FROM ".PRFX."SETUP LIMIT 1";
	if (!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
		exit;
	}

	$setup = $rs->fields;
	$tracking_provider = $has_shipping_provider_column ? strtolower(trim((string)$setup['SHIPPING_PROVIDER'])) : 'ups';
	$ups_sandbox = $has_ups_sandbox_column ? ((int)$setup['UPS_SANDBOX'] === 1) : false;
	$fedex_sandbox = $has_fedex_sandbox_column ? ((int)$setup['FEDEX_SANDBOX'] === 1) : false;
	if ($tracking_provider !== 'fedex' && $tracking_provider !== 'ups') {
		$tracking_error = 'Tracking is currently implemented for FedEx and UPS. Please select FedEx or UPS in Shipping Management.';
	}

	if ($tracking_error === '' && $tracking_provider === 'fedex') {
		if (!$has_fedex_columns) {
			$tracking_error = 'FedEx credential columns are missing. Please run the shipping database upgrade.';
		} else {
			require_once('include/shipping/fedex.php');

			list($token, $token_err) = citecrm_fedex_get_oauth_token($setup['FEDEX_KEY'], $setup['FEDEX_PASSWORD'], $fedex_sandbox);
			if ($token === null) {
				$tracking_error = $token_err;
			} else {
				list($tracking_data, $tracking_err) = citecrm_fedex_track($token, $tracking_no, $fedex_sandbox);
				if ($tracking_data === null) {
					$tracking_error = $tracking_err;
				} else {
					$tracking_result = citecrm_fedex_normalize_tracking($tracking_data);
				}
			}
		}
	} else if ($tracking_error === '' && $tracking_provider === 'ups') {
		require_once('include/shipping/ups.php');

		list($token, $token_err) = citecrm_ups_get_oauth_token($setup['UPS_ACCESS_KEY'], $setup['UPS_PASSWORD'], $ups_sandbox, $setup['UPS_LOGIN']);
		if ($token === null) {
			$tracking_error = $token_err;
		} else {
			list($tracking_data, $tracking_err) = citecrm_ups_track($token, $tracking_no, $ups_sandbox);
			if ($tracking_data === null) {
				$tracking_error = $tracking_err;
			} else {
				$tracking_result = citecrm_ups_normalize_tracking($tracking_data);
			}
		}
	}
}

$smarty->assign('order', $order);
$smarty->assign('tracking_no', $tracking_no);
$smarty->assign('tracking_available', $tracking_available);
$smarty->assign('tracking_provider', strtoupper($tracking_provider));
$smarty->assign('tracking_error', $tracking_error);
$smarty->assign('tracking_result', $tracking_result);
$smarty->display('parts' . SEP . 'tracking.tpl');
