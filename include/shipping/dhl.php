<?php

function citecrm_dhl_rate($api_key, $api_secret, $account_number, $rate_params, $use_sandbox = false) {
	$api_key = trim((string)$api_key);
	$api_secret = (string)$api_secret;
	$account_number = trim((string)$account_number);

	if ($api_key === '' || trim($api_secret) === '') {
		return array(null, 'Missing DHL API Key/Secret');
	}
	if ($account_number === '') {
		return array(null, 'Missing DHL account number');
	}
	if (!is_array($rate_params)) {
		return array(null, 'Invalid DHL rate params');
	}

	$host = 'https://express.api.dhl.com/mydhlapi';
	if ($use_sandbox) {
		$host .= '/test';
	}

	// GET /rates uses query parameters
	$rate_params['accountNumber'] = $account_number;

	$query = http_build_query($rate_params);
	$url = $host.'/rates'.($query !== '' ? ('?'.$query) : '');

	$auth = base64_encode($api_key.':'.$api_secret);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_HTTPGET, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Accept: application/json',
		'Authorization: Basic '.$auth,
	));

	$response = curl_exec($ch);
	$http_code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$curl_err = curl_error($ch);
	curl_close($ch);

	if ($response === false || $http_code < 200 || $http_code >= 300) {
		$err = $curl_err !== '' ? $curl_err : ('DHL rate request failed (HTTP '.$http_code.')');
		return array(null, $err);
	}

	$data = json_decode($response, true);
	if (!is_array($data)) {
		return array(null, 'DHL rate returned invalid JSON');
	}

	return array($data, '');
}

/**
 * Attempts to find a "best" (cheapest) rate amount in DHL /rates response.
 * Returns array(price, currency, product_code, product_name)
 */
function citecrm_dhl_extract_best_rate($rate_data) {
	if (!is_array($rate_data) || !isset($rate_data['products']) || !is_array($rate_data['products'])) {
		return array(null, null, null, null);
	}

	$best_price = null;
	$best_currency = null;
	$best_code = null;
	$best_name = null;

	foreach ($rate_data['products'] as $product) {
		if (!is_array($product)) {
			continue;
		}

		$price = null;
		$currency = null;

		// Typical shape: totalPrice[0]['price'], totalPrice[0]['currency']
		if (isset($product['totalPrice']) && is_array($product['totalPrice']) && isset($product['totalPrice'][0]) && is_array($product['totalPrice'][0])) {
			$tp0 = $product['totalPrice'][0];
			if (isset($tp0['price'])) {
				$price = $tp0['price'];
			}
			if (isset($tp0['currency'])) {
				$currency = $tp0['currency'];
			}
		}

		// Some responses may have a simple totalPrice field
		if ($price === null && isset($product['price'])) {
			$price = $product['price'];
		}

		if ($price === null) {
			continue;
		}

		$price_num = (float)$price;
		if ($price_num <= 0) {
			continue;
		}

		if ($best_price === null || $price_num < $best_price) {
			$best_price = $price_num;
			$best_currency = $currency !== null ? (string)$currency : null;
			$best_code = isset($product['productCode']) ? (string)$product['productCode'] : null;
			$best_name = isset($product['productName']) ? (string)$product['productName'] : null;
		}
	}

	return array($best_price, $best_currency, $best_code, $best_name);
}

