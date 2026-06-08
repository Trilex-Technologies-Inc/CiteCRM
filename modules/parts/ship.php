<?php
####################################################
# IN Cite CRM Customer Relations Management
# Parts Ship Product
####################################################

if (!xml2php("parts")) {
	$smarty->assign('error_msg', "Error in language file");
}

function parts_ship_table_has_column($db, $table, $column) {
	$q = "SELECT COUNT(*) AS cnt
		  FROM information_schema.COLUMNS
		  WHERE TABLE_SCHEMA = DATABASE()
		    AND TABLE_NAME = ".$db->qstr($table)."
		    AND COLUMN_NAME = ".$db->qstr($column);
	$rs = $db->Execute($q);
	return $rs && (int)$rs->fields['cnt'] > 0;
}

function parts_ship_country($value, $fallback = 'US') {
	$value = strtoupper(substr(trim((string)$value), 0, 2));
	return $value !== '' ? $value : $fallback;
}

function parts_ship_phone($value) {
	$digits = preg_replace('/[^0-9]/', '', (string)$value);
	if (strlen($digits) < 10) {
		return '5555555555';
	}
	return substr($digits, 0, 15);
}

function parts_ship_text($value, $fallback, $max = 35) {
	$value = trim((string)$value);
	if ($value === '') {
		$value = $fallback;
	}
	return substr($value, 0, $max);
}

function parts_ship_save_tracking($db, $order, $tracking_no, $note_prefix) {
	$tracking_no = trim((string)$tracking_no);
	if ($tracking_no === '') {
		return 'Missing tracking number from carrier.';
	}

	$q = "UPDATE ".PRFX."ORDERS SET
			TRACKING_NO=".$db->qstr($tracking_no).",
			DATE_LAST=".$db->qstr(time())."
		  WHERE ORDER_ID=".$db->qstr((int)$order['ORDER_ID']);
	if (!$db->execute($q)) {
		return 'MySQL Error: '.$db->ErrorMsg();
	}

	if (isset($order['WO_ID']) && (int)$order['WO_ID'] > 0) {
		$memo = $note_prefix." Tracking: ".$tracking_no;
		$q = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
				WORK_ORDER_ID=".$db->qstr((int)$order['WO_ID']).",
				WORK_ORDER_STATUS_DATE=".$db->qstr(time()).",
				WORK_ORDER_STATUS_NOTES=".$db->qstr($memo).",
				WORK_ORDER_STATUS_ENTER_BY=".$db->qstr($_SESSION['login_id']);
		if (!$db->execute($q)) {
			return 'MySQL Error: '.$db->ErrorMsg();
		}
	}

	return '';
}

$order_id = isset($VAR['order_id']) ? $VAR['order_id'] : (isset($VAR['ORDER_ID']) ? $VAR['ORDER_ID'] : '');

if ($order_id === '' || !ctype_digit((string)$order_id)) {
	force_page('core', 'error&error_msg=Invalid order_id&menu=1&type=warning');
	exit;
}

$q = "SELECT * FROM ".PRFX."ORDERS WHERE ORDER_ID=".$db->qstr((int)$order_id)." LIMIT 1";
if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}

if ($rs->EOF) {
	force_page('core', 'error&error_msg=Order not found&menu=1&type=warning');
	exit;
}

$order = $rs->fields;
$error_msg = '';
$info_msg = '';
$has_shipping_provider_column = parts_ship_table_has_column($db, PRFX.'SETUP', 'SHIPPING_PROVIDER');
$has_ups_sandbox_column = parts_ship_table_has_column($db, PRFX.'SETUP', 'UPS_SANDBOX');
$has_customer_country = parts_ship_table_has_column($db, PRFX.'TABLE_CUSTOMER', 'CUSTOMER_COUNTRY');

$setup_cols = "PARTS_LOGIN,PARTS_PASSWORD,SERVICE_CODE,UPS_LOGIN,UPS_PASSWORD,UPS_ACCESS_KEY";
if ($has_shipping_provider_column) {
	$setup_cols .= ",SHIPPING_PROVIDER";
}
if ($has_ups_sandbox_column) {
	$setup_cols .= ",UPS_SANDBOX";
}

$q = "SELECT ".$setup_cols." FROM ".PRFX."SETUP LIMIT 1";
if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$setup = $rs->fields;

$shipping_provider = $has_shipping_provider_column ? strtolower(trim((string)$setup['SHIPPING_PROVIDER'])) : 'ups';
if ($shipping_provider !== 'fedex' && $shipping_provider !== 'dhl') {
	$shipping_provider = 'ups';
}
$ups_sandbox = $has_ups_sandbox_column ? ((int)$setup['UPS_SANDBOX'] === 1) : false;

$q = "SELECT * FROM ".PRFX."TABLE_COMPANY LIMIT 1";
if (!$company_rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$company = $company_rs->fields;

$customer = array();
if (isset($order['WO_ID']) && (int)$order['WO_ID'] > 0) {
	$customer_country_select = $has_customer_country ? ", c.CUSTOMER_COUNTRY" : "";
	$q = "SELECT c.CUSTOMER_DISPLAY_NAME, c.CUSTOMER_FIRST_NAME, c.CUSTOMER_LAST_NAME,
				 c.CUSTOMER_ADDRESS, c.CUSTOMER_CITY, c.CUSTOMER_STATE, c.CUSTOMER_ZIP,
				 c.CUSTOMER_PHONE, c.CUSTOMER_EMAIL".$customer_country_select."
		  FROM ".PRFX."TABLE_WORK_ORDER w
		  LEFT JOIN ".PRFX."TABLE_CUSTOMER c ON w.CUSTOMER_ID = c.CUSTOMER_ID
		  WHERE w.WORK_ORDER_ID=".$db->qstr((int)$order['WO_ID'])."
		  LIMIT 1";
	if (!$customer_rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	$customer = $customer_rs->fields;
}

if (!is_array($customer) || count($customer) === 0) {
	$customer = array(
		'CUSTOMER_DISPLAY_NAME' => 'Customer',
		'CUSTOMER_FIRST_NAME' => '',
		'CUSTOMER_LAST_NAME' => '',
		'CUSTOMER_ADDRESS' => '',
		'CUSTOMER_CITY' => '',
		'CUSTOMER_STATE' => '',
		'CUSTOMER_ZIP' => '',
		'CUSTOMER_PHONE' => '',
		'CUSTOMER_EMAIL' => '',
		'CUSTOMER_COUNTRY' => '',
	);
}

$company_country = parts_ship_country(isset($company['COMPANY_COUNTRY']) ? $company['COMPANY_COUNTRY'] : 'US');
$customer_country = $has_customer_country ? parts_ship_country(isset($customer['CUSTOMER_COUNTRY']) ? $customer['CUSTOMER_COUNTRY'] : '', $company_country) : $company_country;
$order_has_tracking = (isset($order['TRACKING_NO']) && trim((string)$order['TRACKING_NO']) !== '' && (string)$order['TRACKING_NO'] !== '0');
$auto_ship_available = ($shipping_provider === 'ups' && !$order_has_tracking);

if (isset($VAR['create_ups_shipment'])) {
	if ($order_has_tracking) {
		$error_msg = 'This order already has a tracking number. Use the manual field only if you need to replace it.';
	} else if ($shipping_provider !== 'ups') {
		$error_msg = 'Automatic shipment creation is currently available for UPS only.';
	} else if ($company_country !== 'US' || $customer_country !== 'US') {
		$error_msg = 'Automatic UPS shipment creation currently supports US domestic shipments only.';
	} else {
		require_once('include/shipping/ups.php');

		$account_number = trim((string)$setup['UPS_LOGIN']);
		$service_code = trim((string)$setup['SERVICE_CODE']);
		if ($service_code === '') {
			$service_code = '03';
		}

		if ($account_number === '') {
			$error_msg = 'Missing UPS account number. Set UPS Login to the UPS shipper/account number in Shipping Management.';
		} else if (trim((string)$company['COMPANY_ADDRESS']) === '' || trim((string)$company['COMPANY_CITY']) === '' || trim((string)$company['COMPANY_STATE']) === '' || trim((string)$company['COMPANY_ZIP']) === '') {
			$error_msg = 'Company shipping address is incomplete. Update Company Settings before creating a UPS shipment.';
		} else if (trim((string)$customer['CUSTOMER_ADDRESS']) === '' || trim((string)$customer['CUSTOMER_CITY']) === '' || trim((string)$customer['CUSTOMER_STATE']) === '' || trim((string)$customer['CUSTOMER_ZIP']) === '') {
			$error_msg = 'Customer shipping address is incomplete. Update the customer address before creating a UPS shipment.';
		} else {
			list($token, $token_err) = citecrm_ups_get_oauth_token($setup['UPS_ACCESS_KEY'], $setup['UPS_PASSWORD'], $ups_sandbox, $account_number);
			if ($token === null) {
				$error_msg = $token_err;
			} else {
			$company_phone = isset($company['COMPNAY_PHONE']) ? $company['COMPNAY_PHONE'] : (isset($company['COMPANY_PHONE']) ? $company['COMPANY_PHONE'] : '');
			$customer_name = trim((string)$customer['CUSTOMER_DISPLAY_NAME']);
			if ($customer_name === '') {
				$customer_name = trim((string)$customer['CUSTOMER_FIRST_NAME'].' '.(string)$customer['CUSTOMER_LAST_NAME']);
			}

			$weight = (float)$order['WEIGHT'];
			if ($weight <= 0) {
				$weight = 1;
			}

			$shipment_request = array(
				'ShipmentRequest' => array(
					'Request' => array(
						'RequestOption' => 'nonvalidate',
						'TransactionReference' => array('CustomerContext' => 'CiteCRM Order '.$order['ORDER_ID']),
					),
					'Shipment' => array(
						'Description' => 'CiteCRM parts order '.$order['ORDER_ID'],
						'Shipper' => array(
							'Name' => parts_ship_text(isset($company['COMPANY_NAME']) ? $company['COMPANY_NAME'] : '', 'CiteCRM'),
							'AttentionName' => parts_ship_text(isset($company['COMPANY_NAME']) ? $company['COMPANY_NAME'] : '', 'Shipping'),
							'Phone' => array('Number' => parts_ship_phone($company_phone)),
							'ShipperNumber' => $account_number,
							'Address' => array(
								'AddressLine' => array(parts_ship_text(isset($company['COMPANY_ADDRESS']) ? $company['COMPANY_ADDRESS'] : '', 'Shipping Address')),
								'City' => parts_ship_text(isset($company['COMPANY_CITY']) ? $company['COMPANY_CITY'] : '', 'City', 30),
								'StateProvinceCode' => parts_ship_text(isset($company['COMPANY_STATE']) ? $company['COMPANY_STATE'] : '', 'OR', 5),
								'PostalCode' => parts_ship_text(isset($company['COMPANY_ZIP']) ? $company['COMPANY_ZIP'] : '', '97526', 10),
								'CountryCode' => $company_country,
							),
						),
						'ShipTo' => array(
							'Name' => parts_ship_text($customer_name, 'Customer'),
							'AttentionName' => parts_ship_text($customer_name, 'Customer'),
							'Phone' => array('Number' => parts_ship_phone($customer['CUSTOMER_PHONE'])),
							'Address' => array(
								'AddressLine' => array(parts_ship_text($customer['CUSTOMER_ADDRESS'], 'Customer Address')),
								'City' => parts_ship_text($customer['CUSTOMER_CITY'], 'City', 30),
								'StateProvinceCode' => parts_ship_text($customer['CUSTOMER_STATE'], 'OR', 5),
								'PostalCode' => parts_ship_text($customer['CUSTOMER_ZIP'], '97526', 10),
								'CountryCode' => $customer_country,
							),
						),
						'ShipFrom' => array(
							'Name' => parts_ship_text(isset($company['COMPANY_NAME']) ? $company['COMPANY_NAME'] : '', 'CiteCRM'),
							'AttentionName' => parts_ship_text(isset($company['COMPANY_NAME']) ? $company['COMPANY_NAME'] : '', 'Shipping'),
							'Phone' => array('Number' => parts_ship_phone($company_phone)),
							'Address' => array(
								'AddressLine' => array(parts_ship_text(isset($company['COMPANY_ADDRESS']) ? $company['COMPANY_ADDRESS'] : '', 'Shipping Address')),
								'City' => parts_ship_text(isset($company['COMPANY_CITY']) ? $company['COMPANY_CITY'] : '', 'City', 30),
								'StateProvinceCode' => parts_ship_text(isset($company['COMPANY_STATE']) ? $company['COMPANY_STATE'] : '', 'OR', 5),
								'PostalCode' => parts_ship_text(isset($company['COMPANY_ZIP']) ? $company['COMPANY_ZIP'] : '', '97526', 10),
								'CountryCode' => $company_country,
							),
						),
						'PaymentInformation' => array(
							'ShipmentCharge' => array(
								'Type' => '01',
								'BillShipper' => array('AccountNumber' => $account_number),
							),
						),
						'Service' => array('Code' => $service_code),
						'Package' => array(
							'Description' => 'Parts',
							'Packaging' => array('Code' => '02', 'Description' => 'Customer Supplied Package'),
							'Dimensions' => array(
								'UnitOfMeasurement' => array('Code' => 'IN', 'Description' => 'Inches'),
								'Length' => '10',
								'Width' => '10',
								'Height' => '10',
							),
							'PackageWeight' => array(
								'UnitOfMeasurement' => array('Code' => 'LBS', 'Description' => 'Pounds'),
								'Weight' => number_format($weight, 1, '.', ''),
							),
						),
						'LabelSpecification' => array(
							'LabelImageFormat' => array('Code' => 'GIF', 'Description' => 'GIF'),
							'HTTPUserAgent' => 'Mozilla/4.5',
						),
					),
				),
			);

			list($shipment_data, $shipment_err) = citecrm_ups_create_shipment($token, $shipment_request, $ups_sandbox);
			if ($shipment_data === null) {
				$error_msg = $shipment_err;
			} else {
				$shipment_result = citecrm_ups_extract_shipment_result($shipment_data);
				$tracking_no = $shipment_result['tracking_number'];
				$save_err = parts_ship_save_tracking($db, $order, $tracking_no, 'UPS shipment created.');
				if ($save_err !== '') {
					$error_msg = $save_err;
				} else {
					force_page('parts', 'tracking&invoice_id='.$order['INVOICE_ID'].'&order_id='.$order['ORDER_ID'].'&page_title=Parts%20Tracking');
					exit;
				}
			}
		}
		}
	}
}

if (isset($VAR['submit'])) {
	$tracking_no = isset($VAR['tracking_no']) ? trim((string)$VAR['tracking_no']) : '';

	if ($tracking_no === '') {
		$error_msg = 'Tracking number is required.';
	} else if (!preg_match('/^[A-Za-z0-9][A-Za-z0-9 ._-]{2,79}$/', $tracking_no)) {
		$error_msg = 'Tracking number contains invalid characters.';
	} else {
		$save_err = parts_ship_save_tracking($db, $order, $tracking_no, 'Parts shipped.');
		if ($save_err !== '') {
			$error_msg = $save_err;
		} else {
			force_page('parts', 'tracking&invoice_id='.$order['INVOICE_ID'].'&order_id='.$order['ORDER_ID'].'&page_title=Parts%20Tracking');
			exit;
		}
	}
}

$smarty->assign('order', $order);
$smarty->assign('tracking_no', (isset($order['TRACKING_NO']) && $order['TRACKING_NO'] !== '0') ? $order['TRACKING_NO'] : '');
$smarty->assign('shipping_provider', strtoupper($shipping_provider));
$smarty->assign('auto_ship_available', $auto_ship_available);
$smarty->assign('ups_sandbox', $ups_sandbox);
$smarty->assign('error_msg', $error_msg);
$smarty->assign('info_msg', $info_msg);
$smarty->display('parts'.SEP.'ship.tpl');
?>
