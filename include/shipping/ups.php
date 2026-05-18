<?php

function citecrm_ups_get_oauth_token($client_id, $client_secret, $use_sandbox = false) {
	$client_id = trim((string)$client_id);
	$client_secret = (string)$client_secret;
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
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/x-www-form-urlencoded',
		'Authorization: Basic '.$auth,
	));

	$response = curl_exec($ch);
	$http_code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$curl_err = curl_error($ch);
	curl_close($ch);

	if ($response === false || $http_code < 200 || $http_code >= 300) {
		$err = $curl_err !== '' ? $curl_err : ('UPS OAuth failed (HTTP '.$http_code.')');
		return array(null, $err);
	}

	$data = json_decode($response, true);
	if (!is_array($data) || !isset($data['access_token'])) {
		return array(null, 'UPS OAuth returned an unexpected response');
	}

	return array((string)$data['access_token'], '');
}

function citecrm_ups_rate_rest($access_token, $rate_request, $use_sandbox = false) {
	$access_token = trim((string)$access_token);
	if ($access_token === '') {
		return array(null, 'Missing UPS access token');
	}

	$host = $use_sandbox ? 'https://wwwcie.ups.com' : 'https://onlinetools.ups.com';

	$candidates = array(
		$host.'/api/rating/v2403/rate',
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
			$last_error = 'UPS rate request failed (HTTP '.$http_code.')';
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

