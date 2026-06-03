<?php
####################################################
# IN Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Parts															#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################
if (!xml2php("parts")) {
	$smarty->assign('error_msg', "Error in language file");
}

function parts_table_has_column($db, $table, $column)
{
	$q = "SELECT COUNT(*) AS cnt
		  FROM information_schema.COLUMNS
		  WHERE TABLE_SCHEMA = DATABASE()
		    AND TABLE_NAME = " . $db->qstr($table) . "
		    AND COLUMN_NAME = " . $db->qstr($column);
	$rs = $db->Execute($q);
	return $rs && (int)$rs->fields['cnt'] > 0;
}

function parts_ensure_cart_shipping_columns($db)
{
	$table = PRFX . 'CART';
	$columns = array(
		'Length' => "ALTER TABLE `" . $table . "` ADD COLUMN `Length` varchar(20) NOT NULL default '' AFTER `Weight`",
		'Width' => "ALTER TABLE `" . $table . "` ADD COLUMN `Width` varchar(20) NOT NULL default '' AFTER `Length`",
		'Height' => "ALTER TABLE `" . $table . "` ADD COLUMN `Height` varchar(20) NOT NULL default '' AFTER `Width`",
	);

	foreach ($columns as $column => $sql) {
		if (!parts_table_has_column($db, $table, $column)) {
			if (!$db->Execute($sql)) {
				return false;
			}
		}
	}

	return true;
}

function parts_ensure_product_shipping_columns($db)
{
	$table = PRFX . 'TABLE_PRODUCT';
	$columns = array(
		'PRODUCT_WEIGHT' => "ALTER TABLE `" . $table . "` ADD COLUMN `PRODUCT_WEIGHT` decimal(10,2) NOT NULL default '0.00' AFTER `PRODUCT_PRICE`",
		'PRODUCT_LENGTH' => "ALTER TABLE `" . $table . "` ADD COLUMN `PRODUCT_LENGTH` decimal(10,2) NOT NULL default '0.00' AFTER `PRODUCT_WEIGHT`",
		'PRODUCT_WIDTH' => "ALTER TABLE `" . $table . "` ADD COLUMN `PRODUCT_WIDTH` decimal(10,2) NOT NULL default '0.00' AFTER `PRODUCT_LENGTH`",
		'PRODUCT_HEIGHT' => "ALTER TABLE `" . $table . "` ADD COLUMN `PRODUCT_HEIGHT` decimal(10,2) NOT NULL default '0.00' AFTER `PRODUCT_WIDTH`",
	);

	foreach ($columns as $column => $sql) {
		if (!parts_table_has_column($db, $table, $column)) {
			if (!$db->Execute($sql)) {
				return false;
			}
		}
	}

	return true;
}

if (!parts_ensure_cart_shipping_columns($db) || !parts_ensure_product_shipping_columns($db)) {
	force_page('core', 'error&error_msg=Database upgrade required: unable to add shipping columns.&menu=1&type=database');
	exit;
}

/* if we have work order assign it */
if (isset($VAR['wo_id'])) {
	$smarty->assign('wo_id', $VAR['wo_id']);
}

/* check to see if we have an open order for this WO */
$q = "SELECT count(*) as count  FROM " . PRFX . "ORDERS WHERE WO_ID=" . $db->qstr($VAR['wo_id']);
if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
	exit;
}
$count = $rs->fields['count'];
if ($count > 0) {
	force_page('workorder', 'view&wo_id=' . $VAR['wo_id'] . '&error&error_msg=A parts order already exists for this Work Order. &page_title=Work%20Order%20ID%20' . $VAR['wo_id']);
	exit;
}

##################################
# Load Configs							#
##################################

$has_shipping_provider_column = false;
$rs_cols = $db->Execute("SHOW COLUMNS FROM " . PRFX . "SETUP LIKE 'SHIPPING_PROVIDER'");
if ($rs_cols && !$rs_cols->EOF) {
	$has_shipping_provider_column = true;
}

$has_fedex_columns = false;
$rs_cols = $db->Execute("SHOW COLUMNS FROM " . PRFX . "SETUP LIKE 'FEDEX_KEY'");
if ($rs_cols && !$rs_cols->EOF) {
	$has_fedex_columns = true;
}

$has_dhl_columns = false;
$rs_cols = $db->Execute("SHOW COLUMNS FROM " . PRFX . "SETUP LIKE 'DHL_KEY'");
if ($rs_cols && !$rs_cols->EOF) {
	$has_dhl_columns = true;
}

$has_ups_sandbox_column = false;
$rs_cols = $db->Execute("SHOW COLUMNS FROM " . PRFX . "SETUP LIKE 'UPS_SANDBOX'");
if ($rs_cols && !$rs_cols->EOF) {
	$has_ups_sandbox_column = true;
}

$has_fedex_sandbox_column = false;
$rs_cols = $db->Execute("SHOW COLUMNS FROM " . PRFX . "SETUP LIKE 'FEDEX_SANDBOX'");
if ($rs_cols && !$rs_cols->EOF) {
	$has_fedex_sandbox_column = true;
}

$cols = "PARTS_LO,PARTS_LOGIN,PARTS_PASSWORD,SERVICE_CODE,PARTS_MARKUP,UPS_LOGIN,UPS_PASSWORD,UPS_ACCESS_KEY";
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
if ($has_dhl_columns) {
	$cols .= ",DHL_KEY,DHL_SECRET,DHL_ACCOUNT";
}

$q = "SELECT " . $cols . " FROM " . PRFX . "SETUP ";
if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
	exit;
}
$local 			= $rs->fields['PARTS_LO'];
$login				= $rs->fields['PARTS_LOGIN'];
$passwd			= $rs->fields['PARTS_PASSWORD'];
$service_code	= $rs->fields['SERVICE_CODE'];
$mark_up			= $rs->fields['PARTS_MARKUP'];
$mark_up 			= $mark_up * .01;
$ups_login 		= $rs->fields['UPS_LOGIN'];
$ups_password	= $rs->fields['UPS_PASSWORD'];
$ups_access_key	= $rs->fields['UPS_ACCESS_KEY'];
$shipping_provider = $has_shipping_provider_column ? strtolower(trim((string)$rs->fields['SHIPPING_PROVIDER'])) : 'ups';
if ($shipping_provider !== 'fedex' && $shipping_provider !== 'dhl') {
	$shipping_provider = 'ups';
}
$fedex_key = $has_fedex_columns ? (string)$rs->fields['FEDEX_KEY'] : '';
$fedex_password = $has_fedex_columns ? (string)$rs->fields['FEDEX_PASSWORD'] : '';
$fedex_account = $has_fedex_columns ? (string)$rs->fields['FEDEX_ACCOUNT'] : '';
$ups_sandbox = $has_ups_sandbox_column ? ((int)$rs->fields['UPS_SANDBOX'] === 1) : false;
$fedex_sandbox = $has_fedex_sandbox_column ? ((int)$rs->fields['FEDEX_SANDBOX'] === 1) : false;
$dhl_key = $has_dhl_columns ? (string)$rs->fields['DHL_KEY'] : '';
$dhl_secret = $has_dhl_columns ? (string)$rs->fields['DHL_SECRET'] : '';
$dhl_account = $has_dhl_columns ? (string)$rs->fields['DHL_ACCOUNT'] : '';

$q = "SELECT COMPANY_ZIP FROM " . PRFX . "TABLE_COMPANY";
if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
	exit;
}
$from_zip = $rs->fields['COMPANY_ZIP'];

/* assign service coed to smarty */
if ($service_code == "03") {
	$smarty->assign('service_code', 'UPS Ground');
} else if ($service_code == "02") {
	$smarty->assign('service_code', 'UPS 2nd Day Air');
} else if ($service_code == "01") {
	$smarty->assign('service_code', 'UPS Next Day Air');
} else if ($service_code == "07") {
	$smarty->assign('service_code', 'UPS Worldwide Express');
} else if ($service_code == "08") {
	$smarty->assign('service_code', 'UPS Worldwide Expedited');
} else if ($service_code == "11") {
	$smarty->assign('service_code', 'UPS Standard');
} else if ($service_code == "12") {
	$smarty->assign('service_code', 'UPS 3 Day Select');
} else if ($service_code == "13") {
	$smarty->assign('service_code', 'UPS Next Day Air Saver');
} else if ($service_code == "14") {
	$smarty->assign('service_code', 'UPS Next Day Air Early');
} else if ($service_code == "54") {
	$smarty->assign('service_code', 'UPS Worldwide Express Plus');
} else if ($service_code == "59") {
	$smarty->assign('service_code', 'UPS 2nd Day Air A.M.');
} else if ($service_code == "65") {
	$smarty->assign('service_code', 'UPS Express Saver');
}

/* assign smarty wharehoues location */
$warehouse_city = '';
if ($local == "AT") {
	$warehouse_city = 'Atlanta';
} else if ($local == "CH") {
	$warehouse_city = 'Chicago';
} else if ($local == "DA") {
	$warehouse_city = 'Dallas';
} else if ($local == "FR") {
	$warehouse_city = 'Fremont';
} else if ($local == "HO") {
	$warehouse_city = 'Houston';
} else if ($local == "KA") {
	$warehouse_city = 'Kansas';
} else if ($local == "LR") {
	$warehouse_city = 'Laredo';
} else if ($local == "LA") {
	$warehouse_city = 'Los Angeles';
} else if ($local == "MI") {
	$warehouse_city = 'Miami';
} else if ($local == "NJ") {
	$warehouse_city = 'New Jersey';
} else if ($local == "PO") {
	$warehouse_city = 'Portland';
} else if ($local == "TP") {
	$warehouse_city = 'Tampa';
}
$smarty->assign('location', $warehouse_city);



##################################
# Load Category							#
##################################

$supports_parent_id = false;
$chk = "SELECT COUNT(*) AS cnt
		FROM information_schema.COLUMNS
		WHERE TABLE_SCHEMA = DATABASE()
		  AND TABLE_NAME = " . $db->qstr(PRFX . "CAT") . "
		  AND COLUMN_NAME = 'PARENT_ID'";
$chk_rs = $db->Execute($chk);
if ($chk_rs && (int)$chk_rs->fields['cnt'] > 0) {
	$supports_parent_id = true;
}

if (!$supports_parent_id) {
	force_page('core', 'error&error_msg=Database upgrade required: CAT.PARENT_ID missing.&menu=1&type=validation');
	exit;
}

// Parent categories (top-level)
$q = "SELECT * FROM " . PRFX . "CAT WHERE (PARENT_ID = '' OR PARENT_ID IS NULL) ORDER BY DESCRIPTION";
if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
	exit;
}

$arr = $rs->GetArray();
$smarty->assign('CAT', $arr);

// Child categories, mapped to legacy SUB_CAT keys expected by templates
$q = "SELECT ID, DESCRIPTION, PARENT_ID
	  FROM " . PRFX . "CAT
	  WHERE PARENT_ID <> '' AND PARENT_ID IS NOT NULL
	  ORDER BY PARENT_ID, DESCRIPTION";
if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
	exit;
}
$arr = $rs->GetArray();
for ($i = 0; $i < count($arr); $i++) {
	$arr[$i]['CAT'] = $arr[$i]['PARENT_ID'];
	$arr[$i]['SUB_CATEGORY'] = $arr[$i]['ID'];
}
$smarty->assign('SUB_CAT', $arr);


##################################
# If Submit								#
##################################


if (isset($VAR['submit'])) {

	if (!isset($VAR['check_out'])) {
		/* get parts */
		$x = "<CRM_PARTS_LIST>
				<LOGIN>$login</LOGIN>
				<PASSWORD>$passwd</PASSWORD>
				<SUB_CATEGORY>" . $VAR['CAT2'] . "</SUB_CATEGORY>
				<LOCATION>$local</LOCATION>
			</CRM_PARTS_LIST>";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, INCITCRM);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "page=parts:list&xml=" . $x . "&escape=1");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$content = curl_exec($ch); # This returns HTML
		curl_close($ch);



		/* return errors */
		if ($content == 98) {
			$smarty->assign('crm_msg', 'Account Login Failed. Please Enter corect login information in the Control Center Under Company Edit. If you do not have an account please click here to create one <a href="https://www.incitecrm.com/?page=sign_up:main&page_title=Sign%20Up" target="new">Create Account</a>. To order parts we must have an active credit card on file. <br><br> If you feel this is an error please verify your account information on In-Cite CRM by logging in here <a href="https://www.incitecrm.com/?page=account:account" target="new">In-cite CRM Login</a>');
		} else if ($content == 1) {
			$smarty->assign('crm_msg', 'Wharehouse Location Not Found. Please Select a Location in the Control Center');
		} else if ($content == 2) {
			$smarty->assign('crm_msg', 'Please Select A category');
		} else if ($content == 99) {
			$smarty->assign('crm_msg', 'Server Error');
		}

		/* parse Return */
		$parser = xml_parser_create();
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, $content, $values, $tags);
		xml_parser_free($parser);


		/* build array from returned xml */

		foreach ($values as $xml) {
			if ($xml['tag'] == "SKU" && $xml['value'] != "") {
				$sku = array('SKU' => $xml['value']);
			}

			if ($xml['tag'] == "PART_ID" && $xml['value'] != "") {
				$part_id = array('ITEMID' => $xml['value']);
			}

			if ($xml['tag'] == "VENDOR" && $xml['value'] != "") {
				$vendor = array('VENDOR' => $xml['value']);
			}

			if ($xml['tag'] == "DESCRIPTION" && $xml['value'] != "") {
				$description = array('DESCRIPTION' => $xml['value']);
			}

			if ($xml['tag'] == "PRICE" && $xml['value'] != "") {
				$price = array('PRICE' => $number = number_format(($xml['value'] * $mark_up) + $xml['value'], 2, '.', ''));
			}

			if ($xml['tag'] == "Weight" && $xml['value'] != "") {
				$weight = array('Weight' => $xml['value']);
			}

			if ($xml['tag'] == "PART" && $xml['type'] == "close") {
				$parts[] =  array_merge($sku, $part_id, $vendor, $description, $price, $weight);
			}
		}

		$parts    = isset($parts) ? $parts : array();

		$smarty->assign('from_zip', $from_zip);
		$smarty->assign('parts', $parts);
		$smarty->assign('CAT2', isset($VAR['CAT2']) ? $VAR['CAT2'] : null);

		$smarty->assign('CAT2', $VAR['CAT2']);

		// Local product catalog listing (inventory module)
		$cat2 = isset($VAR['CAT2']) ? trim((string)$VAR['CAT2']) : '';
		$inventory_products = array();
		if ($cat2 !== '') {
			$q = "SELECT p.PRODUCT_ID, p.PRODUCT_SKU, p.PRODUCT_NAME, p.PRODUCT_DESCRIPTION, p.PRODUCT_PRICE,
					p.PRODUCT_WEIGHT, p.PRODUCT_LENGTH, p.PRODUCT_WIDTH, p.PRODUCT_HEIGHT,
					COALESCE(m.MANUFACTURER_NAME,'') AS MANUFACTURER_NAME
			  FROM " . PRFX . "TABLE_PRODUCT p
			  LEFT JOIN " . PRFX . "TABLE_MANUFACTURER m ON (m.MANUFACTURER_ID = p.MANUFACTURER_ID)
			  WHERE p.PRODUCT_ACTIVE=1
			    AND p.CAT_ID=" . $db->qstr($cat2) . "
			  ORDER BY p.PRODUCT_NAME";
			if (!$rs = $db->execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
				exit;
			}
			$inventory_products = $rs->GetArray();
		}
		$smarty->assign('inventory_products', $inventory_products);
	}
	###############################
	# Add Part							#
	###############################
	/* if parts where added */
	if (isset($VAR['add_part'])) {
		if ($VAR['AMOUNT'] == '') {
			$VAR['AMOUNT'] = 1;
		}

		$sub = $VAR['AMOUNT'] * $VAR['PRICE'];
		$q = "INSERT INTO  " . PRFX . "CART SET
				SKU 			=" . $db->qstr($VAR['SKU']) . ",
				AMOUNT			=" . $db->qstr($VAR['AMOUNT']) . ",
				DESCRIPTION	=" . $db->qstr($VAR['DESCRIPTION']) . ",
				VENDOR 		=" . $db->qstr($VAR['VENDOR']) . ",
				ITEMID 		=" . $db->qstr($VAR['ITEMID']) . ",
				Weight 		=" . $db->qstr(isset($VAR['Weight']) ? $VAR['Weight'] : '') . ",
				Length 		=" . $db->qstr(isset($VAR['Length']) ? $VAR['Length'] : '') . ",
				Width 		=" . $db->qstr(isset($VAR['Width']) ? $VAR['Width'] : '') . ",
				Height 		=" . $db->qstr(isset($VAR['Height']) ? $VAR['Height'] : '') . ",
				PRICE 			=" . $db->qstr($VAR['PRICE']) . ",
				SUB_TOTAL		=" . $db->qstr($sub) . ",
				ZIP				=" . $db->qstr($VAR['from_zip']) . ",
				WO_ID			=" . $db->qstr($VAR['wo_id']) . ",
				LAST			=" . time();
		if (!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
			exit;
		}
	}

	##################################
	# Remove part From Cart				#
	##################################
	/* if parts where removed */
	if (isset($VAR['update_cart'])) {
		$current_wo_id = 0;
		if (isset($VAR['wo_id']) && (int)$VAR['wo_id'] > 0) {
			$current_wo_id = (int)$VAR['wo_id'];
		}
		if (isset($VAR['remove']) && is_array($VAR['remove'])) {
			foreach ($VAR['remove'] as $SKU) {
				$SKU = trim((string)$SKU);
				if ($SKU === '') {
					continue;
				}
				$q = "DELETE FROM " . PRFX . "CART WHERE SKU=" . $db->qstr($SKU);
				if ($current_wo_id > 0) {
					$q .= " AND WO_ID=" . $db->qstr($current_wo_id);
				}
				if (!$rs = $db->execute($q)) {
					force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
					exit;
				}
			}
		}
	}

	##################################
	# Check Out								#
	##################################
	/* if checkout selected */
	if (isset($VAR['check_out'])) {
		$cart_where = '';
		if (isset($VAR['wo_id']) && (int)$VAR['wo_id'] > 0) {
			$cart_where = " WHERE WO_ID=" . $db->qstr((int)$VAR['wo_id']);
		}
		$q = "SELECT * FROM " . PRFX . "CART" . $cart_where;
		if (!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
			exit;
		}
		$arr = $rs->GetArray();

		// Initialize totals before accumulating
		$sub_total         = 0;
		$cart_weight_total = 0;

		foreach ($arr as $key => $val) {
			$sub_total         = $sub_total + $val['SUB_TOTAL'];
			$amount            = $val['AMOUNT'] * $val['Weight'];
			$cart_weight_total = $cart_weight_total + $amount;
		}

		$q = "SELECT COMPANY_ZIP, COMPANY_COUNTRY, COMPANY_CITY FROM " . PRFX . "TABLE_COMPANY";
		if (!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
			exit;
		}

		$from_zip = trim((string)$rs->fields['COMPANY_ZIP']);
		$origin_country = strtoupper(substr(trim((string)$rs->fields['COMPANY_COUNTRY']), 0, 2));
		if ($origin_country === '') {
			$origin_country = 'US';
		}
		$origin_city = trim((string)$rs->fields['COMPANY_CITY']);
		if ($origin_city === '') {
			$origin_city = 'Unknown';
		}

		$to_zip = '';
		$to_country = 'US';
		$to_city = 'Unknown';
		$has_customer_country = false;
		$rs_cols = $db->Execute("SHOW COLUMNS FROM " . PRFX . "TABLE_CUSTOMER LIKE 'CUSTOMER_COUNTRY'");
		if ($rs_cols && !$rs_cols->EOF) {
			$has_customer_country = true;
		}
		if (isset($VAR['wo_id']) && (int)$VAR['wo_id'] > 0) {
			$customer_country_select = $has_customer_country ? ", c.CUSTOMER_COUNTRY" : "";
			$q = "SELECT c.CUSTOMER_ZIP, c.CUSTOMER_CITY" . $customer_country_select . "
					  FROM " . PRFX . "TABLE_WORK_ORDER w
					  LEFT JOIN " . PRFX . "TABLE_CUSTOMER c ON w.CUSTOMER_ID = c.CUSTOMER_ID
					  WHERE w.WORK_ORDER_ID=" . $db->qstr((int)$VAR['wo_id']) . " 
					  LIMIT 1";
			if (!$rs = $db->execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
				exit;
			}

			$to_zip = trim((string)$rs->fields['CUSTOMER_ZIP']);
			$to_city = trim((string)$rs->fields['CUSTOMER_CITY']);
			if ($has_customer_country) {
				$to_country = strtoupper(substr(trim((string)$rs->fields['CUSTOMER_COUNTRY']), 0, 2));
				if ($to_country === '') {
					$to_country = $origin_country;
				}
			}
		}
		if ($to_city === '') {
			$to_city = 'Unknown';
		}

		$length		= 10;
		$width		   = 10;
		$height		= 10;
		foreach ($arr as $key => $val) {
			if (isset($val['Length']) && (float)$val['Length'] > 0) {
				$length = max($length, (float)$val['Length']);
			}
			if (isset($val['Width']) && (float)$val['Width'] > 0) {
				$width = max($width, (float)$val['Width']);
			}
			if (isset($val['Height']) && (float)$val['Height'] > 0) {
				$height = max($height, (float)$val['Height']);
			}
		}
		if ($cart_weight_total <= 0) {
			$cart_weight_total = 1;
		}

		$ResponseStatusCode = 1;
		$ErrorDescription = '';
		$shipping_charges = '0.00';
		$total_charges = $sub_total;

		if ($shipping_provider === 'fedex') {
			require_once('include/shipping/fedex.php');

			$rate_err = '';
			$rate_value = null;

			list($token, $token_err) = citecrm_fedex_get_oauth_token($fedex_key, $fedex_password, $fedex_sandbox);
			if ($token === null) {
				$rate_err = $token_err;
			} else {
				$rate_request = array(
					'accountNumber' => array('value' => trim((string)$fedex_account)),
					'requestedShipment' => array(
						'shipper' => array('address' => array('postalCode' => (string)$from_zip, 'countryCode' => (string)$origin_country)),
						'recipient' => array('address' => array('postalCode' => (string)$to_zip, 'countryCode' => (string)$to_country)),
						'pickupType' => 'DROPOFF_AT_FEDEX_LOCATION',
						'rateRequestType' => array('ACCOUNT'),
						'requestedPackageLineItems' => array(
							array(
								'weight' => array('units' => 'LB', 'value' => (float)$cart_weight_total),
								'dimensions' => array(
									'length' => (int)$length,
									'width' => (int)$width,
									'height' => (int)$height,
									'units' => 'IN',
								),
							),
						),
					),
				);

				list($rate_data, $rate_err) = citecrm_fedex_rate($token, $rate_request, $fedex_sandbox);

				if (is_array($rate_data)) {
					$details = null;
					if (isset($rate_data['output']) && isset($rate_data['output']['rateReplyDetails'])) {
						$details = $rate_data['output']['rateReplyDetails'];
					}
					if (is_array($details) && isset($details[0]) && is_array($details[0])) {
						$first = $details[0];
						if (isset($first['ratedShipmentDetails'][0]['totalNetChargeWithDutiesAndTaxes']['amount'])) {
							$rate_value = $first['ratedShipmentDetails'][0]['totalNetChargeWithDutiesAndTaxes']['amount'];
						} else if (isset($first['ratedShipmentDetails'][0]['totalNetCharge']['amount'])) {
							$rate_value = $first['ratedShipmentDetails'][0]['totalNetCharge']['amount'];
						} else if (isset($first['ratedShipmentDetails'][0]['shipmentRateDetail']['totalNetCharge']['amount'])) {
							$rate_value = $first['ratedShipmentDetails'][0]['shipmentRateDetail']['totalNetCharge']['amount'];
						}
					}
				}
			}

			if ($rate_value !== null) {
				$shipping_charges = number_format((float)$rate_value, 2, '.', '');
				$total_charges = $sub_total + (float)$rate_value;
			} else {
				$ErrorDescription = $rate_err !== '' ? $rate_err : 'Unable to retrieve FedEx rate (using $0.00 shipping)';
			}
		} else if ($shipping_provider === 'dhl') {
			require_once('include/shipping/dhl.php');

			$rate_err = '';
			$rate_value = null;

			$planned_date = date('Y-m-d');
			$is_customs_declarable = ($origin_country !== $to_country);

			$rate_params = array(
				'requestEstimatedDeliveryDate' => 'true',
				'plannedShippingDate' => $planned_date,
				'originCountryCode' => $origin_country,
				'originCityName' => $origin_city,
				'originPostalCode' => (string)$from_zip,
				'destinationCountryCode' => (string)$to_country,
				'destinationCityName' => (string)$to_city,
				'destinationPostalCode' => (string)$to_zip,
				'weight' => (float)$cart_weight_total,
				'length' => (float)$length,
				'width' => (float)$width,
				'height' => (float)$height,
				'unitOfMeasurement' => 'imperial',
				'isCustomsDeclarable' => $is_customs_declarable ? 'true' : 'false',
			);

			list($rate_data, $rate_err) = citecrm_dhl_rate($dhl_key, $dhl_secret, $dhl_account, $rate_params, false);
			if (is_array($rate_data)) {
				list($best_price, $best_currency, $best_code, $best_name) = citecrm_dhl_extract_best_rate($rate_data);
				if ($best_price !== null) {
					$rate_value = $best_price;
				} else {
					$rate_err = 'Unable to retrieve DHL rate (no products returned)';
				}
			}

			if ($rate_value !== null) {
				$shipping_charges = number_format((float)$rate_value, 2, '.', '');
				$total_charges = $sub_total + (float)$rate_value;
			} else {
				$shipping_charges = '0.00';
				$total_charges = $sub_total;
				$ErrorDescription = $rate_err !== '' ? $rate_err : 'Unable to retrieve DHL rate (using $0.00 shipping)';
			}
		} else {
			require_once('include/shipping/ups.php');

			$rate_value = null;
			$rate_err = '';

			$rate_request = array(
				'RateRequest' => array(
					'Request' => array(
						'TransactionReference' => array('CustomerContext' => 'CiteCRM'),
						'RequestOption' => 'Rate',
					),
					'Shipment' => array(
						'Shipper' => array('Address' => array('PostalCode' => (string)$from_zip, 'CountryCode' => (string)$origin_country)),
						'ShipTo' => array('Address' => array('PostalCode' => (string)$to_zip, 'CountryCode' => (string)$to_country)),
						'ShipFrom' => array('Address' => array('PostalCode' => (string)$from_zip, 'CountryCode' => (string)$origin_country)),
						'Service' => array('Code' => (string)$service_code),
						'Package' => array(
							'PackagingType' => array('Code' => '02'),
							'Dimensions' => array(
								'UnitOfMeasurement' => array('Code' => 'IN'),
								'Length' => (string)$length,
								'Width' => (string)$width,
								'Height' => (string)$height,
							),
							'PackageWeight' => array(
								'UnitOfMeasurement' => array('Code' => 'LBS'),
								'Weight' => (string)$cart_weight_total,
							),
						),
					),
				),
			);

			list($token, $token_err) = citecrm_ups_get_oauth_token($ups_access_key, $ups_password, $ups_sandbox, $ups_login);
			if ($token !== null) {
				list($rate_data, $rate_err) = citecrm_ups_rate_rest($token, $rate_request, $ups_sandbox);

				if (is_array($rate_data)) {
					$shipment = null;
					if (isset($rate_data['RateResponse']) && isset($rate_data['RateResponse']['RatedShipment'])) {
						$shipment = $rate_data['RateResponse']['RatedShipment'];
						if (isset($shipment[2])) {
							$shipment = $shipment[2];
						}
					}

					if (is_array($shipment)) {
						if (isset($shipment['NegotiatedRateCharges']['TotalCharge']['MonetaryValue'])) {
							$rate_value = $shipment['NegotiatedRateCharges']['TotalCharge']['MonetaryValue'];
						} else if (isset($shipment['TotalCharges']['MonetaryValue'])) {
							$rate_value = $shipment['TotalCharges']['MonetaryValue'];
						}
					}
				}
			} else {
				$rate_err = $token_err;
			}

			if ($rate_value === null && !$ups_sandbox) {
				list($legacy_rate, $legacy_err) = citecrm_ups_rate_xml_legacy($ups_access_key, $ups_login, $ups_password, $from_zip, $to_zip, $service_code, $length, $width, $height, $cart_weight_total);
				if (is_array($legacy_rate) && isset($legacy_rate['MonetaryValue'])) {
					$rate_value = $legacy_rate['MonetaryValue'];
				} else {
					$rate_err = $legacy_err !== '' ? $legacy_err : ($rate_err !== '' ? $rate_err : 'Unable to retrieve UPS rate (using $0.00 shipping)');
				}
			} else if ($rate_value === null && $ups_sandbox && $rate_err === '') {
				$rate_err = 'Unable to retrieve UPS sandbox rate (using $0.00 shipping)';
			}

			if ($rate_value !== null) {
				$shipping_charges = number_format((float)$rate_value, 2, '.', '');
				$total_charges = $sub_total + (float)$rate_value;
			} else {
				$shipping_charges = '0.00';
				$total_charges = $sub_total;
				$ErrorDescription = $rate_err;
			}
		}

		/* get Cart Total */
		$smarty->assign('ResponseStatusCode', $ResponseStatusCode);
		$smarty->assign('ErrorDescription', $ErrorDescription);
		$smarty->assign('sub_total', number_format($sub_total, 2, '.', ''));
		$smarty->assign('shipping_charges', $shipping_charges);
		$smarty->assign('total_charges', $total_charges);
		$smarty->assign('cart_weight_total', $cart_weight_total);
		$smarty->assign('cart_contents', $arr);
	}
}



##################################
# Get Cart Contents					#
##################################
$cart_where = '';
if (isset($VAR['wo_id']) && (int)$VAR['wo_id'] > 0) {
	$cart_where = " WHERE WO_ID=" . $db->qstr((int)$VAR['wo_id']);
}
$q = "SELECT * FROM " . PRFX . "CART" . $cart_where;
if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
	exit;
}
$arr = $rs->GetArray();
//print_r($arr);

// Initialize cart subtotal before accumulating
$cart_sub_total = 0;
foreach ($arr as $key => $val) {
	$cart_sub_total = $cart_sub_total + $val['SUB_TOTAL'];
}

$smarty->assign('cart_total', number_format($cart_sub_total, 2, '.', ''));
$smarty->assign('cart_count', count($arr));
$smarty->assign('cart', $arr);

$smarty->display('parts' . SEP . 'main.tpl');
