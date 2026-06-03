<?php
####################################################
# IN Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Parts Check Out file with Shipping Integration	#
#  Version 0.0.2	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################

// Helper function to get shipping provider settings
function get_shipping_provider_settings($db)
{
    $has_shipping_provider = false;
    $has_fedex_columns = false;
    $has_dhl_columns = false;
    $has_ups_sandbox = false;
    $has_fedex_sandbox = false;

    $rs_cols = $db->Execute("SHOW COLUMNS FROM " . PRFX . "SETUP LIKE 'SHIPPING_PROVIDER'");
    if ($rs_cols && !$rs_cols->EOF) {
        $has_shipping_provider = true;
    }

    $rs_cols = $db->Execute("SHOW COLUMNS FROM " . PRFX . "SETUP LIKE 'FEDEX_KEY'");
    if ($rs_cols && !$rs_cols->EOF) {
        $has_fedex_columns = true;
    }

    $rs_cols = $db->Execute("SHOW COLUMNS FROM " . PRFX . "SETUP LIKE 'DHL_KEY'");
    if ($rs_cols && !$rs_cols->EOF) {
        $has_dhl_columns = true;
    }

    $rs_cols = $db->Execute("SHOW COLUMNS FROM " . PRFX . "SETUP LIKE 'UPS_SANDBOX'");
    if ($rs_cols && !$rs_cols->EOF) {
        $has_ups_sandbox = true;
    }

    $rs_cols = $db->Execute("SHOW COLUMNS FROM " . PRFX . "SETUP LIKE 'FEDEX_SANDBOX'");
    if ($rs_cols && !$rs_cols->EOF) {
        $has_fedex_sandbox = true;
    }

    $cols = "PARTS_LO,PARTS_LOGIN,PARTS_PASSWORD,SERVICE_CODE,PARTS_MARKUP,INVOCIE_TAX,UPS_LOGIN,UPS_PASSWORD,UPS_ACCESS_KEY";
    if ($has_shipping_provider) {
        $cols .= ",SHIPPING_PROVIDER";
    }
    if ($has_ups_sandbox) {
        $cols .= ",UPS_SANDBOX";
    }
    if ($has_fedex_columns) {
        $cols .= ",FEDEX_KEY,FEDEX_PASSWORD,FEDEX_ACCOUNT,FEDEX_METER";
    }
    if ($has_fedex_sandbox) {
        $cols .= ",FEDEX_SANDBOX";
    }
    if ($has_dhl_columns) {
        $cols .= ",DHL_KEY,DHL_SECRET,DHL_ACCOUNT";
    }

    return array(
        'cols' => $cols,
        'has_shipping_provider' => $has_shipping_provider,
        'has_fedex_columns' => $has_fedex_columns,
        'has_dhl_columns' => $has_dhl_columns,
        'has_ups_sandbox' => $has_ups_sandbox,
        'has_fedex_sandbox' => $has_fedex_sandbox
    );
}

// Get shipping provider settings
$shipping_settings = get_shipping_provider_settings($db);
$q = "SELECT " . $shipping_settings['cols'] . " FROM " . PRFX . "SETUP ";
if (!$rs = $db->execute($q)) {
    force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
    exit;
}

$local = $rs->fields['PARTS_LO'];
$login = $rs->fields['PARTS_LOGIN'];
$passwd = $rs->fields['PARTS_PASSWORD'];
$service_code = $rs->fields['SERVICE_CODE'];
$tax = $rs->fields['INVOCIE_TAX'];
$tax = $tax * 0.01;
$mark_up = $rs->fields['PARTS_MARKUP'];
$mark_up = $mark_up * 0.01;
$ups_login = $rs->fields['UPS_LOGIN'];
$ups_password = $rs->fields['UPS_PASSWORD'];
$ups_access_key = $rs->fields['UPS_ACCESS_KEY'];

// Get shipping provider preference
$shipping_provider = $shipping_settings['has_shipping_provider'] ? strtolower(trim((string)$rs->fields['SHIPPING_PROVIDER'])) : 'ups';
if ($shipping_provider !== 'fedex' && $shipping_provider !== 'dhl') {
    $shipping_provider = 'ups';
}

// FedEx credentials
$fedex_key = $shipping_settings['has_fedex_columns'] ? (string)$rs->fields['FEDEX_KEY'] : '';
$fedex_password = $shipping_settings['has_fedex_columns'] ? (string)$rs->fields['FEDEX_PASSWORD'] : '';
$fedex_account = $shipping_settings['has_fedex_columns'] ? (string)$rs->fields['FEDEX_ACCOUNT'] : '';
$fedex_meter = $shipping_settings['has_fedex_columns'] ? (string)$rs->fields['FEDEX_METER'] : '';

// Sandbox flags
$ups_sandbox = $shipping_settings['has_ups_sandbox'] ? ((int)$rs->fields['UPS_SANDBOX'] === 1) : false;
$fedex_sandbox = $shipping_settings['has_fedex_sandbox'] ? ((int)$rs->fields['FEDEX_SANDBOX'] === 1) : false;

// DHL credentials
$dhl_key = $shipping_settings['has_dhl_columns'] ? (string)$rs->fields['DHL_KEY'] : '';
$dhl_secret = $shipping_settings['has_dhl_columns'] ? (string)$rs->fields['DHL_SECRET'] : '';
$dhl_account = $shipping_settings['has_dhl_columns'] ? (string)$rs->fields['DHL_ACCOUNT'] : '';

// Get origin address
$q = "SELECT COMPANY_ZIP, COMPANY_COUNTRY, COMPANY_CITY, COMPANY_STATE, COMPANY_ADDRESS FROM " . PRFX . "TABLE_COMPANY";
if (!$rs = $db->execute($q)) {
    force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
    exit;
}

$from_zip = $rs->fields['COMPANY_ZIP'];
$origin_country = strtoupper(substr(trim((string)$rs->fields['COMPANY_COUNTRY']), 0, 2));
if ($origin_country === '') {
    $origin_country = 'US';
}
$origin_city = trim((string)$rs->fields['COMPANY_CITY']);
if ($origin_city === '') {
    $origin_city = 'Unknown';
}
$origin_state = trim((string)$rs->fields['COMPANY_STATE']);
$origin_address = trim((string)$rs->fields['COMPANY_ADDRESS']);

$workorder_id = isset($VAR['wo_id']) ? $VAR['wo_id'] : '';

$q = "SELECT CUSTOMER_ID FROM " . PRFX . "TABLE_WORK_ORDER WHERE WORK_ORDER_ID=" . $db->qstr($workorder_id);
if (!$rs = $db->execute($q)) {
    force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
    exit;
}

$customer_id = $rs->fields['CUSTOMER_ID'];

// Get customer shipping address
$to_zip = '';
$to_country = 'US';
$to_city = 'Unknown';
$to_state = '';
$to_address = '';
$has_customer_country = false;
$rs_cols = $db->Execute("SHOW COLUMNS FROM " . PRFX . "TABLE_CUSTOMER LIKE 'CUSTOMER_COUNTRY'");
if ($rs_cols && !$rs_cols->EOF) {
    $has_customer_country = true;
}

if ($workorder_id !== '' && (int)$workorder_id > 0) {
    $customer_country_select = $has_customer_country ? ", c.CUSTOMER_COUNTRY" : "";
    $q = "SELECT c.CUSTOMER_ZIP, c.CUSTOMER_CITY, c.CUSTOMER_STATE, c.CUSTOMER_ADDRESS" . $customer_country_select . "
          FROM " . PRFX . "TABLE_WORK_ORDER w
          LEFT JOIN " . PRFX . "TABLE_CUSTOMER c ON w.CUSTOMER_ID = c.CUSTOMER_ID
          WHERE w.WORK_ORDER_ID=" . $db->qstr((int)$workorder_id) . "
          LIMIT 1";
    if (!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    }

    $to_zip = trim((string)$rs->fields['CUSTOMER_ZIP']);
    $to_city = trim((string)$rs->fields['CUSTOMER_CITY']);
    $to_state = trim((string)$rs->fields['CUSTOMER_STATE']);
    $to_address = trim((string)$rs->fields['CUSTOMER_ADDRESS']);
    if ($has_customer_country) {
        $to_country = strtoupper(substr(trim((string)$rs->fields['CUSTOMER_COUNTRY']), 0, 2));
        if ($to_country === '') {
            $to_country = $origin_country;
        }
    }
}

if ($to_zip === '') {
    $to_zip = $from_zip;
}
if ($to_city === '') {
    $to_city = 'Unknown';
}

/*
 * Local-only checkout: build the order from the local CART table
 */
$cart_where = '';
$wo_id = $workorder_id;
if ($workorder_id !== '' && (int)$workorder_id > 0) {
    $cart_where = " WHERE WO_ID=" . $db->qstr((int)$workorder_id);
} else if (isset($VAR['wo_id']) && $VAR['wo_id'] === '0') {
    $cart_where = " WHERE WO_ID=" . $db->qstr('0');
}

$q = "SELECT SKU, AMOUNT, DESCRIPTION, VENDOR, PRICE, SUB_TOTAL, Weight, Length, Width, Height
      FROM " . PRFX . "CART" . $cart_where;
if (!$rs = $db->execute($q)) {
    force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
    exit;
}

$cart_rows = $rs->GetArray();
if (!is_array($cart_rows) || count($cart_rows) === 0) {
    if ($cart_where !== '') {
        $q = "SELECT SKU, AMOUNT, DESCRIPTION, VENDOR, PRICE, SUB_TOTAL, Weight, Length, Width, Height FROM " . PRFX . "CART";
        if ($rs2 = $db->execute($q)) {
            $cart_rows = $rs2->GetArray();
        }
    }
}
if (!is_array($cart_rows) || count($cart_rows) === 0) {
    force_page('parts', 'main&error_msg=You have no parts in your Cart. Please select the parts you wish to order and click add.&wo_id=' . $workorder_id . '&page_title=Order%20Parts');
    exit;
}

$details = array();
$cart_total = 0.00;
$shipping = 0.00;
$weight = 0.00;
$total_items = 0;
$length = 10; // Default package dimensions
$width = 10;
$height = 10;

foreach ($cart_rows as $row) {
    $qty = (int)$row['AMOUNT'];
    $price_each = (float)$row['PRICE'];
    $line_sub_total = (float)$row['SUB_TOTAL'];

    if ($line_sub_total <= 0 && $qty > 0) {
        $line_sub_total = $qty * $price_each;
    }

    $cart_total += $line_sub_total;
    $total_items += $qty;
    $item_weight = ((float)$row['Weight']) * $qty;
    $weight += $item_weight;

    // Calculate max package dimensions
    if (isset($row['Length']) && (float)$row['Length'] > 0) {
        $length = max($length, (float)$row['Length']);
    }
    if (isset($row['Width']) && (float)$row['Width'] > 0) {
        $width = max($width, (float)$row['Width']);
    }
    if (isset($row['Height']) && (float)$row['Height'] > 0) {
        $height = max($height, (float)$row['Height']);
    }

    $details[] = array(
        'SKU' => $row['SKU'],
        'COUNT' => $qty,
        'PRICE' => number_format($price_each, 2, '.', ''),
        'SUB_TOTAL' => number_format($line_sub_total, 2, '.', ''),
        'VENDOR' => $row['VENDOR'],
        'DESCRIPTION' => $row['DESCRIPTION'],
    );
}

// Calculate shipping rates based on selected carrier
$shipping_charges = 0.00;
$shipping_error = '';
$ResponseStatusCode = 1;

if ($weight <= 0) {
    $weight = 1; // Minimum weight for rate calculation
}

// Get shipping rates from selected carrier
if ($shipping_provider === 'fedex') {
    require_once('include/shipping/fedex.php');

    $rate_value = null;

    // Get OAuth token for FedEx REST API
    list($token, $token_err) = citecrm_fedex_get_oauth_token($fedex_key, $fedex_password, $fedex_sandbox);

    if ($token !== null) {
        $rate_request = array(
            'accountNumber' => array('value' => trim((string)$fedex_account)),
            'requestedShipment' => array(
                'shipper' => array(
                    'address' => array(
                        'postalCode' => (string)$from_zip,
                        'countryCode' => (string)$origin_country
                    )
                ),
                'recipient' => array(
                    'address' => array(
                        'postalCode' => (string)$to_zip,
                        'countryCode' => (string)$to_country
                    )
                ),
                'pickupType' => 'DROPOFF_AT_FEDEX_LOCATION',
                'rateRequestType' => array('ACCOUNT'),
                'requestedPackageLineItems' => array(
                    array(
                        'weight' => array(
                            'units' => 'LB',
                            'value' => (float)$weight
                        ),
                        'dimensions' => array(
                            'length' => (int)$length,
                            'width' => (int)$width,
                            'height' => (int)$height,
                            'units' => 'IN'
                        )
                    )
                )
            )
        );

        list($rate_data, $rate_err) = citecrm_fedex_rate($token, $rate_request, $fedex_sandbox);

        if (is_array($rate_data)) {
            $details_arr = null;
            if (isset($rate_data['output']) && isset($rate_data['output']['rateReplyDetails'])) {
                $details_arr = $rate_data['output']['rateReplyDetails'];
            }
            if (is_array($details_arr) && isset($details_arr[0]) && is_array($details_arr[0])) {
                $first = $details_arr[0];
                if (isset($first['ratedShipmentDetails'][0]['totalNetChargeWithDutiesAndTaxes']['amount'])) {
                    $rate_value = $first['ratedShipmentDetails'][0]['totalNetChargeWithDutiesAndTaxes']['amount'];
                } else if (isset($first['ratedShipmentDetails'][0]['totalNetCharge']['amount'])) {
                    $rate_value = $first['ratedShipmentDetails'][0]['totalNetCharge']['amount'];
                } else if (isset($first['ratedShipmentDetails'][0]['shipmentRateDetail']['totalNetCharge']['amount'])) {
                    $rate_value = $first['ratedShipmentDetails'][0]['shipmentRateDetail']['totalNetCharge']['amount'];
                }
            }
        }

        if ($rate_value !== null) {
            $shipping_charges = (float)$rate_value;
        } else {
            $shipping_error = $rate_err !== '' ? $rate_err : 'Unable to retrieve FedEx rate';
            $ResponseStatusCode = 0;
        }
    } else {
        $shipping_error = $token_err;
        $ResponseStatusCode = 0;
    }
} else if ($shipping_provider === 'dhl') {
    require_once('include/shipping/dhl.php');

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
        'weight' => (float)$weight,
        'length' => (float)$length,
        'width' => (float)$width,
        'height' => (float)$height,
        'unitOfMeasurement' => 'imperial',
        'isCustomsDeclarable' => $is_customs_declarable ? 'true' : 'false'
    );

    list($rate_data, $rate_err) = citecrm_dhl_rate($dhl_key, $dhl_secret, $dhl_account, $rate_params, false);

    if (is_array($rate_data)) {
        list($best_price, $best_currency, $best_code, $best_name) = citecrm_dhl_extract_best_rate($rate_data);
        if ($best_price !== null) {
            $rate_value = $best_price;
        } else {
            $shipping_error = 'Unable to retrieve DHL rate (no products returned)';
        }
    } else {
        $shipping_error = $rate_err;
    }

    if ($rate_value !== null) {
        $shipping_charges = (float)$rate_value;
    } else {
        $shipping_charges = 0.00;
        $ResponseStatusCode = 0;
    }
} else {
    // UPS - Default carrier
    require_once('include/shipping/ups.php');

    $rate_value = null;
    $rate_err = '';

    $rate_request = array(
        'RateRequest' => array(
            'Request' => array(
                'TransactionReference' => array('CustomerContext' => 'CiteCRM'),
                'RequestOption' => 'Rate'
            ),
            'Shipment' => array(
                'Shipper' => array(
                    'Address' => array(
                        'PostalCode' => (string)$from_zip,
                        'CountryCode' => (string)$origin_country
                    )
                ),
                'ShipTo' => array(
                    'Address' => array(
                        'PostalCode' => (string)$to_zip,
                        'CountryCode' => (string)$to_country
                    )
                ),
                'ShipFrom' => array(
                    'Address' => array(
                        'PostalCode' => (string)$from_zip,
                        'CountryCode' => (string)$origin_country
                    )
                ),
                'Service' => array('Code' => (string)$service_code),
                'Package' => array(
                    'PackagingType' => array('Code' => '02'),
                    'Dimensions' => array(
                        'UnitOfMeasurement' => array('Code' => 'IN'),
                        'Length' => (string)$length,
                        'Width' => (string)$width,
                        'Height' => (string)$height
                    ),
                    'PackageWeight' => array(
                        'UnitOfMeasurement' => array('Code' => 'LBS'),
                        'Weight' => (string)$weight
                    )
                )
            )
        )
    );

    list($token, $token_err) = citecrm_ups_get_oauth_token($ups_access_key, $ups_password, $ups_sandbox, $ups_login);

    if ($token !== null) {
        list($rate_data, $rate_err) = citecrm_ups_rate_rest($token, $rate_request, $ups_sandbox);

        if (is_array($rate_data)) {
            $shipment_resp = null;
            if (isset($rate_data['RateResponse']) && isset($rate_data['RateResponse']['RatedShipment'])) {
                $shipment_resp = $rate_data['RateResponse']['RatedShipment'];
                if (isset($shipment_resp[2])) {
                    $shipment_resp = $shipment_resp[2];
                }
            }

            if (is_array($shipment_resp)) {
                if (isset($shipment_resp['NegotiatedRateCharges']['TotalCharge']['MonetaryValue'])) {
                    $rate_value = $shipment_resp['NegotiatedRateCharges']['TotalCharge']['MonetaryValue'];
                } else if (isset($shipment_resp['TotalCharges']['MonetaryValue'])) {
                    $rate_value = $shipment_resp['TotalCharges']['MonetaryValue'];
                }
            }
        }
    } else {
        $rate_err = $token_err;
    }

    // Fallback to legacy XML API if REST fails and not in sandbox
    if ($rate_value === null && !$ups_sandbox) {
        list($legacy_rate, $legacy_err) = citecrm_ups_rate_xml_legacy(
            $ups_access_key,
            $ups_login,
            $ups_password,
            $from_zip,
            $to_zip,
            $service_code,
            $length,
            $width,
            $height,
            $weight
        );

        if (is_array($legacy_rate) && isset($legacy_rate['MonetaryValue'])) {
            $rate_value = $legacy_rate['MonetaryValue'];
        } else {
            $rate_err = $legacy_err !== '' ? $legacy_err : ($rate_err !== '' ? $rate_err : 'Unable to retrieve UPS rate');
        }
    }

    if ($rate_value !== null) {
        $shipping_charges = (float)$rate_value;
    } else {
        $shipping_charges = 0.00;
        $shipping_error = $rate_err;
        $ResponseStatusCode = 0;
    }
}

// Format shipping charges
$shipping = number_format($shipping_charges, 2, '.', '');
$cart_total = number_format($cart_total, 2, '.', '');
$weight = number_format($weight, 2, '.', '');
$total = number_format(((float)$cart_total + $shipping_charges), 2, '.', '');

// Local invoice id
$crm_invoice_id = (int)time() + (int)mt_rand(0, 999);
$tracking_no = '0';

/* Insert Order */
$q = "INSERT INTO " . PRFX . "ORDERS SET
    INVOICE_ID = " . $db->qstr($crm_invoice_id) . ",
    WO_ID = " . $db->qstr($wo_id) . ",
    DATE_CREATE = '" . time() . "',
    DATE_LAST = '" . time() . "',
    SUB_TOTAL = " . $db->qstr($cart_total) . ",
    SHIPPING = " . $db->qstr($shipping) . ",
    TOTAL = " . $db->qstr($total) . ",
    WEIGHT = " . $db->qstr($weight) . ",
    ITEMS = " . $db->qstr($total_items) . ",
    TRACKING_NO = " . $db->qstr($tracking_no) . ",
    STATUS = " . $db->qstr(1);

if (!$rs = $db->execute($q)) {
    force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
    exit;
}

$order_id = $db->insert_id();

/* Update Work Order status and record invoice created */
if ($wo_id != '') {
    $q = "SELECT count(*) as count FROM " . PRFX . "TABLE_INVOICE WHERE WORKORDER_ID=" . $db->qstr($wo_id);
    $rs = $db->Execute($q);
    $count = $rs->fields['count'];

    $tax_amount = number_format($total * $tax, 2, '.', ',');
    $total_with_tax = number_format(((float)$total + $tax_amount), 2, '.', ',');

    if ($count == 0) {
        $q = "INSERT INTO " . PRFX . "TABLE_INVOICE SET
            INVOICE_DATE = " . $db->qstr(time()) . ",
            CUSTOMER_ID = " . $db->qstr($customer_id) . ", 
            WORKORDER_ID = " . $db->qstr($wo_id) . ",
            EMPLOYEE_ID = " . $db->qstr($_SESSION['login_id']) . ", 
            INVOICE_PAID = '0', 
            INVOICE_AMOUNT = " . $db->qstr($total_with_tax) . ",
            SHIPPING = " . $db->qstr($shipping) . ",
            TAX = " . $db->qstr($tax_amount) . ",
            SUB_TOTAL = " . $db->qstr($cart_total);

        if (!$rs = $db->Execute($q)) {
            force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1');
            exit;
        }

        $invoice_id = $db->insert_id();

        $msg = "Invoice Created ID: " . $invoice_id;
        $sql = "INSERT INTO " . PRFX . "TABLE_WORK_ORDER_STATUS SET
            WORK_ORDER_ID = " . $db->qstr($wo_id) . ",
            WORK_ORDER_STATUS_DATE = " . $db->qstr(time()) . ",
            WORK_ORDER_STATUS_NOTES = " . $db->qstr($msg) . ",
            WORK_ORDER_STATUS_ENTER_BY = " . $db->qstr($_SESSION['login_id']);

        if (!$result = $db->Execute($sql)) {
            force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
            exit;
        }
    } else if ($count == 1) {
        $q = "SELECT INVOICE_ID,INVOICE_AMOUNT, SUB_TOTAL, TAX FROM " . PRFX . "TABLE_INVOICE WHERE WORKORDER_ID=" . $db->qstr($wo_id);
        $rs = $db->Execute($q);
        $invoice_id = $rs->fields['INVOICE_ID'];
        $invoice_total = $total_with_tax + $rs->fields['INVOICE_AMOUNT'];
        $invoice_sub_total = $cart_total + $rs->fields['SUB_TOTAL'];

        $q = "UPDATE " . PRFX . "TABLE_INVOICE SET
            INVOICE_AMOUNT = " . $db->qstr($invoice_total) . ",
            SUB_TOTAL = " . $db->qstr($invoice_sub_total) . ",
            SHIPPING = " . $db->qstr($shipping) . ",
            TAX = " . $db->qstr($tax_amount) . "
            WHERE INVOICE_ID = " . $db->qstr($invoice_id);
    }

    /* Add shipping error note if applicable */
    if ($shipping_error !== '') {
        $error_msg = "Shipping Rate Error: " . $shipping_error . " - Using $0.00 shipping";
        $sql = "INSERT INTO " . PRFX . "TABLE_WORK_ORDER_STATUS SET
            WORK_ORDER_ID = " . $db->qstr($wo_id) . ",
            WORK_ORDER_STATUS_DATE = " . $db->qstr(time()) . ",
            WORK_ORDER_STATUS_NOTES = " . $db->qstr($error_msg) . ",
            WORK_ORDER_STATUS_ENTER_BY = " . $db->qstr($_SESSION['login_id']);
        $db->Execute($sql);
    }

    /* update work order Status */
    $msg = "Parts Ordered. Cite CRM Order ID: " . $crm_invoice_id . " Tracking: Not shipped yet. Amount: $" . $cart_total . " Shipping: $" . $shipping . " Total: $" . number_format($cart_total + $shipping, 2, '.', ',') . " - Carrier: " . strtoupper($shipping_provider);

    $sql = "INSERT INTO " . PRFX . "TABLE_WORK_ORDER_STATUS SET
        WORK_ORDER_ID = " . $db->qstr($wo_id) . ",
        WORK_ORDER_STATUS_DATE = " . $db->qstr(time()) . ",
        WORK_ORDER_STATUS_NOTES = " . $db->qstr($msg) . ",
        WORK_ORDER_STATUS_ENTER_BY = " . $db->qstr($_SESSION['login_id']);

    if (!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    }

    /* mark work order waiting for parts */
    $sql = "UPDATE " . PRFX . "TABLE_WORK_ORDER SET
        WORK_ORDER_CURENT_STATUS = '3',
        LAST_ACTIVE = " . $db->qstr(time()) . "
        WHERE WORK_ORDER_ID = " . $db->qstr($wo_id);

    if (!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    }

    $msg = "Work Order Changed status to Waiting For Parts";
    $sql = "INSERT INTO " . PRFX . "TABLE_WORK_ORDER_STATUS SET
        WORK_ORDER_ID = " . $db->qstr($wo_id) . ",
        WORK_ORDER_STATUS_DATE = " . $db->qstr(time()) . ",
        WORK_ORDER_STATUS_NOTES = " . $db->qstr($msg) . ",
        WORK_ORDER_STATUS_ENTER_BY = " . $db->qstr($_SESSION['login_id']);

    if (!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    }
}

/* insert order details */
$i = 0;
foreach ($details as $val) {
    $q = "INSERT INTO " . PRFX . "ORDERS_DETAILS (ORDER_ID,SKU,DESCRIPTION,VENDOR,COUNT,PRICE,SUB_TOTAL)
    VALUES (" . $db->qstr($order_id) . "," . $db->qstr($details[$i]['SKU']) . "," . $db->qstr($details[$i]['DESCRIPTION']) . "," . $db->qstr($details[$i]['VENDOR']) . "," . $db->qstr($details[$i]['COUNT']) . "," . $db->qstr($details[$i]['PRICE']) . "," . $db->qstr($details[$i]['SUB_TOTAL']) . ")";

    if (!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    }

    if ($wo_id != '') {
        $q = "INSERT INTO " . PRFX . "TABLE_INVOICE_PARTS SET
            INVOICE_ID = " . $db->qstr($invoice_id) . ",
            INVOICE_PARTS_MANUF = " . $db->qstr($details[$i]['VENDOR']) . ", 
            INVOCIE_PARTS_MFID = " . $db->qstr($details[$i]['SKU']) . ",
            INVOICE_PARTS_DESCRIPTION = " . $db->qstr($details[$i]['DESCRIPTION']) . ",
            INVOICE_PARTS_AMOUNT = " . $db->qstr($details[$i]['PRICE']) . ",
            INVOICE_PARTS_SUBTOTA = " . $db->qstr($details[$i]['SUB_TOTAL']) . ", 
            INVOICE_PARTS_COUNT = " . $db->qstr($details[$i]['COUNT']);

        if (!$rs = $db->execute($q)) {
            force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
            exit;
        }
    }
    $i++;
}

// Email customer order confirmation
$customer_email = '';
$customer_name = '';
if ($customer_id !== '' && (int)$customer_id > 0) {
    $q = "SELECT CUSTOMER_EMAIL, CUSTOMER_DISPLAY_NAME, CUSTOMER_FIRST_NAME, CUSTOMER_LAST_NAME
          FROM " . PRFX . "TABLE_CUSTOMER
          WHERE CUSTOMER_ID=" . $db->qstr((int)$customer_id);
    $rs = $db->Execute($q);
    if ($rs) {
        $customer_email = (string)$rs->fields['CUSTOMER_EMAIL'];
        $customer_name = trim((string)$rs->fields['CUSTOMER_DISPLAY_NAME']);
        if ($customer_name === '') {
            $customer_name = trim((string)$rs->fields['CUSTOMER_FIRST_NAME'] . ' ' . (string)$rs->fields['CUSTOMER_LAST_NAME']);
        }
    }
}

$company_email = '';
$company_name = 'CiteCRM';
$q = "SELECT COMPANY_EMAIL, COMPANY_NAME FROM " . PRFX . "TABLE_COMPANY";
$rs = $db->Execute($q);
if ($rs) {
    $company_email = (string)$rs->fields['COMPANY_EMAIL'];
    if (trim((string)$rs->fields['COMPANY_NAME']) !== '') {
        $company_name = (string)$rs->fields['COMPANY_NAME'];
    }
}

$customer_email = trim($customer_email);
if ($customer_email !== '' && filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
    $subject = 'Parts Order Confirmation #' . $crm_invoice_id;

    $lines = array();
    $lines[] = $company_name . ' - Parts Order Confirmation';
    $lines[] = '';
    $lines[] = 'Carrier: ' . strtoupper($shipping_provider);
    $lines[] = '';
    if ($customer_name !== '') {
        $lines[] = 'Customer: ' . $customer_name;
    }
    $lines[] = 'Order ID: ' . $crm_invoice_id;
    $lines[] = 'Tracking: Not shipped yet';
    if ($wo_id !== '' && (int)$wo_id > 0) {
        $lines[] = 'Work Order: ' . $wo_id;
    }
    $lines[] = 'Date: ' . date('Y-m-d H:i:s');
    $lines[] = '';
    $lines[] = 'Shipping Address:';
    $lines[] = $to_address;
    $lines[] = $to_city . ', ' . $to_state . ' ' . $to_zip;
    $lines[] = $to_country;
    $lines[] = '';
    $lines[] = 'Items:';
    foreach ($details as $item) {
        $qty = isset($item['COUNT']) ? (int)$item['COUNT'] : 0;
        $sku = isset($item['SKU']) ? (string)$item['SKU'] : '';
        $desc = isset($item['DESCRIPTION']) ? (string)$item['DESCRIPTION'] : '';
        $line_total = isset($item['SUB_TOTAL']) ? (string)$item['SUB_TOTAL'] : '0.00';
        $lines[] = '- ' . $qty . ' x ' . $sku . ' ' . $desc . ' ($' . $line_total . ')';
    }
    $lines[] = '';
    $lines[] = 'Sub Total: $' . $cart_total;
    $lines[] = 'Shipping: $' . $shipping;
    $lines[] = 'Total: $' . $total;
    $lines[] = '';
    $lines[] = 'Thank you.';
    $message = implode("\r\n", $lines);

    $headers = array();
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: text/plain; charset=UTF-8';
    if ($company_email !== '' && filter_var(trim($company_email), FILTER_VALIDATE_EMAIL)) {
        $headers[] = 'From: ' . $company_name . ' <' . trim($company_email) . '>';
        $headers[] = 'Reply-To: ' . trim($company_email);
    }

    $sendmail_path = (string)ini_get('sendmail_path');
    $sendmail_bin = trim(strtok($sendmail_path, " \t"));
    $has_sendmail = ($sendmail_bin !== '' && @file_exists($sendmail_bin));

    $sent = false;
    if ($has_sendmail) {
        $sent = @mail($customer_email, $subject, $message, implode("\r\n", $headers));
    }
}

/* clear cart */
if ($workorder_id !== '' && (int)$workorder_id > 0) {
    $q = "DELETE FROM " . PRFX . "CART WHERE WO_ID=" . $db->qstr((int)$workorder_id);
} else {
    $q = "TRUNCATE TABLE " . PRFX . "CART";
}
if (!$rs = $db->execute($q)) {
    force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
    exit;
}

/* assign smarty and display page */
$q = "SELECT * FROM " . PRFX . "TABLE_COMPANY";
if (!$rs = $db->execute($q)) {
    force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
    exit;
}
$arr = $rs->GetArray();
$smarty->assign('customer', $arr);

if (!xml2php("parts")) {
    $smarty->assign('error_msg', "Error in language file");
}

/* get CRM ORDER details */
$q = "SELECT * FROM " . PRFX . "ORDERS WHERE INVOICE_ID=" . $db->qstr($crm_invoice_id);
if (!$rs = $db->execute($q)) {
    force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
    exit;
}
$invoice_details = array(
    'DB_ORDER_ID' => $rs->fields['ORDER_ID'],
    'ORDER_ID' => $rs->fields['INVOICE_ID'],
    'CART_TOTAL' => $rs->fields['SUB_TOTAL'],
    'SHIPPING' => $rs->fields['SHIPPING'],
    'TAX' => '0.00',
    'TOTAL' => $rs->fields['TOTAL'],
    'WEIGHT' => $rs->fields['WEIGHT'],
    'TOTAL_ITEMS' => $rs->fields['ITEMS'],
    'WORKORDER' => $rs->fields['WO_ID'],
    'DATE' => time(),
    'TRACKING_NO' => $rs->fields['TRACKING_NO'],
    'SHIPPING_PROVIDER' => strtoupper($shipping_provider),
    'SHIPPING_ERROR' => $shipping_error
);
$smarty->assign('invoice_details', $invoice_details);
$smarty->assign('details', $details);
$smarty->assign('shipping_error', $shipping_error);
$smarty->assign('shipping_provider', strtoupper($shipping_provider));

$smarty->display('parts' . SEP . 'results.tpl');
