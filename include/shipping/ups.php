<?php

function citecrm_ups_get_oauth_token($client_id, $client_secret, $use_sandbox = true, $merchant_id = '') {
	$client_id = trim((string)$client_id);
	$client_secret = (string)$client_secret;
	$merchant_id = trim((string)$merchant_id);
	if ($client_id === '' || trim($client_secret) === '') {
		return array(null, 'Missing UPS Client ID/Secret');
	}

	$host = $use_sandbox ? 'https://wwwcie.ups.com' : 'https://onlinetools.ups.com';
	$url = $host.'/security/v1/oauth/token';

	$auth = base64_encode($client_id.':'.$client_secret);
	$body = 'grant_type=client_credentials';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$headers = array(
		'Content-Type: application/x-www-form-urlencoded',
		'Authorization: Basic '.$auth,
	);
	if (citecrm_ups_is_merchant_id($merchant_id)) {
		$headers[] = 'x-merchant-id: '.$merchant_id;
	}
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$response = curl_exec($ch);
	$http_code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$curl_err = curl_error($ch);
	curl_close($ch);

	if ($response === false || $http_code < 200 || $http_code >= 300) {
		$err = $curl_err !== '' ? $curl_err : citecrm_ups_error_from_response($response, 'UPS OAuth failed (HTTP '.$http_code.')');
		return array(null, $err);
	}

	$data = json_decode($response, true);
	if (!is_array($data) || !isset($data['access_token'])) {
		return array(null, 'UPS OAuth returned an unexpected response');
	}

	return array((string)$data['access_token'], '');
}

function citecrm_ups_rate_rest($access_token, $rate_request, $use_sandbox = true) {
	$access_token = trim((string)$access_token);
	if ($access_token === '') {
		echo "Missing UPS access token";
		return array(null, 'Missing UPS access token');
	}

	$host = $use_sandbox ? 'https://wwwcie.ups.com' : 'https://onlinetools.ups.com';

	$candidates = array(
		$host.'/api/rating/v2409/Rate',
		$host.'/api/rating/v2409/Shop',
		$host.'/api/rating/v2403/Rate',
		$host.'/api/rating/v1/Rate',
		$host.'/api/rating/v1/Shop',
	);

	$payload = json_encode($rate_request);
	if ($payload === false) {
		return array(null, 'Unable to encode UPS rate request');
	}

	$last_error = '';
	foreach ($candidates as $url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Bearer '.$access_token,
			'transId: citecrm-'.time(),
			'transactionSrc: CiteCRM',
		));

		$response = curl_exec($ch);
		$http_code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$curl_err = curl_error($ch);
		curl_close($ch);

		if ($response === false) {
			$last_error = $curl_err !== '' ? $curl_err : 'UPS rate request failed';
			continue;
		}
		if ($http_code < 200 || $http_code >= 300) {
			$last_error = citecrm_ups_error_from_response($response, 'UPS rate request failed (HTTP '.$http_code.')');
			continue;
		}

		$data = json_decode($response, true);
		if (!is_array($data)) {
			$last_error = 'UPS rate returned invalid JSON';
			continue;
		}

		return array($data, '');
	}

	return array(null, $last_error !== '' ? $last_error : 'UPS rate request failed');
}

function citecrm_ups_rate_xml_legacy($ups_access_key, $ups_login, $ups_password, $from_zip, $to_zip, $service_code, $length, $width, $height, $weight_lbs) {
	$ups_access_key = trim((string)$ups_access_key);
	$ups_login = trim((string)$ups_login);
	$ups_password = (string)$ups_password;
	if ($ups_access_key === '' || $ups_login === '' || trim($ups_password) === '') {
		return array(null, 'You have not set up UPS information');
	}

	$y = "<?xml version=\"1.0\"?><AccessRequest xml:lang=\"en-US\"><AccessLicenseNumber>$ups_access_key</AccessLicenseNumber><UserId>$ups_login</UserId><Password>$ups_password</Password></AccessRequest><?xml version=\"1.0\"?><RatingServiceSelectionRequest xml:lang=\"en-US\"><Request><TransactionReference><CustomerContext>CiteCRM Rate Request</CustomerContext><XpciVersion>1.0</XpciVersion></TransactionReference><RequestAction>Rate</RequestAction><RequestOption>Rate</RequestOption></Request><PickupType><Code>01</Code></PickupType><Shipment><Shipper><Address><PostalCode>$from_zip</PostalCode><CountryCode>US</CountryCode></Address></Shipper><ShipTo><Address><PostalCode>$to_zip</PostalCode><CountryCode>US</CountryCode></Address></ShipTo><ShipFrom><Address><PostalCode>$from_zip</PostalCode><CountryCode>US</CountryCode></Address></ShipFrom><Service><Code>$service_code</Code></Service><Package><PackagingType><Code>02</Code></PackagingType><Dimensions><UnitOfMeasurement><Code>IN</Code></UnitOfMeasurement><Length>$length</Length><Width>$width</Width><Height>$height</Height></Dimensions><PackageWeight><UnitOfMeasurement><Code>LBS</Code></UnitOfMeasurement><Weight>$weight_lbs</Weight></PackageWeight></Package></Shipment></RatingServiceSelectionRequest>";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://www.ups.com/ups.app/xml/Rate");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $y);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($ch);
	$curl_err = curl_error($ch);
	curl_close($ch);

	if ($response === false) {
		return array(null, $curl_err !== '' ? $curl_err : 'UPS legacy rate request failed');
	}

	$rate = array();
	$ResponseStatusCode = 1;
	$ErrorDescription = '';
	$MonetaryValue = array('MonetaryValue' => '0.00');
	$GuaranteedDaysToDelivery = array('GuaranteedDaysToDelivery' => '');

	$parser = xml_parser_create();
	xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	$values = array();
	$tags = array();
	xml_parse_into_struct($parser, $response, $values, $tags);
	xml_parser_free($parser);

	foreach ($values as $xml) {
		if ($xml['tag'] == "ResponseStatusCode" && isset($xml['value']) && $xml['value'] != "") {
			$ResponseStatusCode = $xml['value'];
		}
		if ($xml['tag'] == "ErrorDescription" && isset($xml['value']) && $xml['value'] != "") {
			$ErrorDescription = $xml['value'];
		}
		if ($xml['tag'] == "MonetaryValue" && isset($xml['value']) && $xml['value'] != "") {
			$MonetaryValue = array('MonetaryValue' => $xml['value']);
		}
		if ($xml['tag'] == "GuaranteedDaysToDelivery" && isset($xml['value']) && $xml['value'] != "") {
			$GuaranteedDaysToDelivery = array('GuaranteedDaysToDelivery' => $xml['value']);
		}
		if ($xml['tag'] == "RatedShipment" && $xml['type'] == "close") {
			$rate[] = array_merge(array('ResponseStatusCode' => $ResponseStatusCode), $MonetaryValue, $GuaranteedDaysToDelivery);
		}
	}

	return array(isset($rate[0]) ? $rate[0] : null, $ErrorDescription);
}

function citecrm_ups_create_shipment($access_token, $shipment_request, $use_sandbox = true) {
	$access_token = trim((string)$access_token);
	if ($access_token === '') {
		return array(null, 'Missing UPS access token');
	}

	$host = $use_sandbox ? 'https://wwwcie.ups.com' : 'https://onlinetools.ups.com';
	$url = $host.'/api/shipments/v2409/ship';

	$payload = json_encode($shipment_request);
	if ($payload === false) {
		return array(null, 'Unable to encode UPS shipment request');
	}

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Bearer '.$access_token,
		'transId: citecrm-'.time(),
		'transactionSrc: CiteCRM',
	));

	$response = curl_exec($ch);
	$http_code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$curl_err = curl_error($ch);
	curl_close($ch);

	if ($response === false || $http_code < 200 || $http_code >= 300) {
		$err = $curl_err !== '' ? $curl_err : citecrm_ups_error_from_response($response, 'UPS shipment request failed (HTTP '.$http_code.')');
		return array(null, $err);
	}

	$data = json_decode($response, true);
	if (!is_array($data)) {
		return array(null, 'UPS shipment returned invalid JSON');
	}

	return array($data, '');
}

function citecrm_ups_extract_shipment_result($data) {
	$result = array(
		'shipment_id' => '',
		'tracking_number' => '',
		'label_format' => '',
		'label_image' => '',
	);

	if (!is_array($data) || !isset($data['ShipmentResponse']['ShipmentResults'])) {
		return $result;
	}

	$shipment = $data['ShipmentResponse']['ShipmentResults'];
	if (!is_array($shipment)) {
		return $result;
	}

	if (isset($shipment['ShipmentIdentificationNumber'])) {
		$result['shipment_id'] = (string)$shipment['ShipmentIdentificationNumber'];
	}

	$package = isset($shipment['PackageResults']) ? $shipment['PackageResults'] : null;
	if (is_array($package) && isset($package[0]) && is_array($package[0])) {
		$package = $package[0];
	}

	if (is_array($package)) {
		if (isset($package['TrackingNumber'])) {
			$result['tracking_number'] = (string)$package['TrackingNumber'];
		}
		if (isset($package['ShippingLabel']) && is_array($package['ShippingLabel'])) {
			$label = $package['ShippingLabel'];
			if (isset($label['ImageFormat']['Code'])) {
				$result['label_format'] = (string)$label['ImageFormat']['Code'];
			}
			if (isset($label['GraphicImage'])) {
				$result['label_image'] = (string)$label['GraphicImage'];
			}
		}
		if ($result['label_image'] === '' && isset($package['LabelImage']) && is_array($package['LabelImage'])) {
			$label = $package['LabelImage'];
			if (isset($label['LabelImageFormat']['Code'])) {
				$result['label_format'] = (string)$label['LabelImageFormat']['Code'];
			}
			if (isset($label['GraphicImage'])) {
				$result['label_image'] = (string)$label['GraphicImage'];
			}
		}
	}

	if ($result['tracking_number'] === '' && $result['shipment_id'] !== '') {
		$result['tracking_number'] = $result['shipment_id'];
	}

	return $result;
}

function citecrm_ups_track($access_token, $tracking_number, $use_sandbox = true) {
	$access_token = trim((string)$access_token);
	$tracking_number = trim((string)$tracking_number);
	if ($access_token === '') {
		return array(null, 'Missing UPS access token');
	}
	if ($tracking_number === '') {
		return array(null, 'Missing UPS tracking number');
	}

	$host = $use_sandbox ? 'https://wwwcie.ups.com' : 'https://onlinetools.ups.com';
	$url = $host.'/api/track/v1/details/'.rawurlencode($tracking_number).'?locale=en_US&returnSignature=false';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Bearer '.$access_token,
		'transId: citecrm-'.time(),
		'transactionSrc: CiteCRM',
	));

	$response = curl_exec($ch);
	$http_code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$curl_err = curl_error($ch);
	curl_close($ch);

	if ($response === false || $http_code < 200 || $http_code >= 300) {
		$err = $curl_err !== '' ? $curl_err : citecrm_ups_error_from_response($response, 'UPS tracking request failed (HTTP '.$http_code.')');
		return array(null, $err);
	}

	$data = json_decode($response, true);
	if (!is_array($data)) {
		return array(null, 'UPS tracking returned invalid JSON');
	}

	return array($data, '');
}

function citecrm_ups_error_from_response($response, $fallback) {
	$fallback = (string)$fallback;
	$data = json_decode((string)$response, true);
	if (!is_array($data)) {
		return $fallback;
	}

	$errors = array();
	if (isset($data['response']['errors']) && is_array($data['response']['errors'])) {
		$errors = $data['response']['errors'];
	} else if (isset($data['errors']) && is_array($data['errors'])) {
		$errors = $data['errors'];
	}

	foreach ($errors as $error) {
		if (!is_array($error)) {
			continue;
		}

		$parts = array();
		if (isset($error['code']) && trim((string)$error['code']) !== '') {
			$parts[] = trim((string)$error['code']);
		}
		if (isset($error['message']) && trim((string)$error['message']) !== '') {
			$parts[] = trim((string)$error['message']);
		}
		if (!empty($parts)) {
			return 'UPS error: '.implode(' - ', $parts);
		}
	}

	return $fallback;
}

function citecrm_ups_is_merchant_id($merchant_id) {
	$merchant_id = trim((string)$merchant_id);
	$length = strlen($merchant_id);

	return $length >= 6 && $length <= 10 && ctype_alnum($merchant_id);
}

function citecrm_ups_normalize_tracking($data) {
	$result = array(
		'status' => '',
		'status_detail' => '',
		'estimated_delivery' => '',
		'latest_event' => '',
		'latest_event_time' => '',
		'latest_location' => '',
		'events' => array(),
	);

	if (!is_array($data)) {
		return $result;
	}

	$package = null;
	if (isset($data['trackResponse']['shipment'][0]['package'][0])) {
		$package = $data['trackResponse']['shipment'][0]['package'][0];
	} else if (isset($data['trackResponse']['shipment']['package'][0])) {
		$package = $data['trackResponse']['shipment']['package'][0];
	}
	if (!is_array($package)) {
		return $result;
	}

	if (isset($package['currentStatus']) && is_array($package['currentStatus'])) {
		if (isset($package['currentStatus']['description'])) {
			$result['status'] = (string)$package['currentStatus']['description'];
		} else if (isset($package['currentStatus']['code'])) {
			$result['status'] = (string)$package['currentStatus']['code'];
		}
	}

	if (isset($package['deliveryDate']) && is_array($package['deliveryDate'])) {
		foreach ($package['deliveryDate'] as $delivery_date) {
			if (!is_array($delivery_date) || !isset($delivery_date['date'])) {
				continue;
			}
			$type = isset($delivery_date['type']) ? strtoupper((string)$delivery_date['type']) : '';
			if ($result['estimated_delivery'] === '' || $type === 'DEL') {
				$result['estimated_delivery'] = (string)$delivery_date['date'];
			}
		}
	}

	$activities = isset($package['activity']) && is_array($package['activity']) ? $package['activity'] : array();
	foreach ($activities as $activity) {
		if (!is_array($activity)) {
			continue;
		}

		$description = '';
		if (isset($activity['status']['description'])) {
			$description = (string)$activity['status']['description'];
		} else if (isset($activity['status']['type'])) {
			$description = (string)$activity['status']['type'];
		}

		$date = isset($activity['date']) ? (string)$activity['date'] : '';
		$time = isset($activity['time']) ? (string)$activity['time'] : '';
		$event_time = trim($date.' '.$time);
		$location = isset($activity['location']['address']) ? citecrm_ups_format_location($activity['location']['address']) : '';

		$result['events'][] = array(
			'time' => $event_time,
			'description' => $description,
			'location' => $location,
		);
	}

	if (isset($result['events'][0])) {
		$result['latest_event'] = $result['events'][0]['description'];
		$result['latest_event_time'] = $result['events'][0]['time'];
		$result['latest_location'] = $result['events'][0]['location'];
	}

	return $result;
}

function citecrm_ups_format_location($address) {
	if (!is_array($address)) {
		return '';
	}

	$parts = array();
	foreach (array('city', 'stateProvince', 'postalCode', 'countryCode') as $key) {
		if (isset($address[$key]) && trim((string)$address[$key]) !== '') {
			$parts[] = trim((string)$address[$key]);
		}
	}

	return implode(', ', $parts);
}
