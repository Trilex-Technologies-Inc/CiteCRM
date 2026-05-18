<?php

function citecrm_fedex_get_oauth_token($client_id, $client_secret, $use_sandbox = false) {
	$client_id = trim((string)$client_id);
	$client_secret = (string)$client_secret;
	if ($client_id === '' || trim($client_secret) === '') {
		return array(null, 'Missing FedEx Client ID/Secret');
	}

	$host = $use_sandbox ? 'https://apis-sandbox.fedex.com' : 'https://apis.fedex.com';
	$url = $host.'/oauth/token';

	$body = http_build_query(array(
		'grant_type' => 'client_credentials',
		'client_id' => $client_id,
		'client_secret' => $client_secret,
	));

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/x-www-form-urlencoded',
	));

	$response = curl_exec($ch);
	$http_code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$curl_err = curl_error($ch);
	curl_close($ch);

	if ($response === false || $http_code < 200 || $http_code >= 300) {
		$err = $curl_err !== '' ? $curl_err : ('FedEx OAuth failed (HTTP '.$http_code.')');
		return array(null, $err);
	}

	$data = json_decode($response, true);
	if (!is_array($data) || !isset($data['access_token'])) {
		return array(null, 'FedEx OAuth returned an unexpected response');
	}

	return array((string)$data['access_token'], '');
}

function citecrm_fedex_rate($access_token, $rate_request, $use_sandbox = false) {
	$access_token = trim((string)$access_token);
	if ($access_token === '') {
		return array(null, 'Missing FedEx access token');
	}

	$host = $use_sandbox ? 'https://apis-sandbox.fedex.com' : 'https://apis.fedex.com';
	$url = $host.'/rate/v1/rates/quotes';

	$payload = json_encode($rate_request);
	if ($payload === false) {
		return array(null, 'Unable to encode FedEx rate request');
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
	));

	$response = curl_exec($ch);
	$http_code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$curl_err = curl_error($ch);
	curl_close($ch);

	if ($response === false || $http_code < 200 || $http_code >= 300) {
		$err = $curl_err !== '' ? $curl_err : ('FedEx rate request failed (HTTP '.$http_code.')');
		return array(null, $err);
	}

	$data = json_decode($response, true);
	if (!is_array($data)) {
		return array(null, 'FedEx rate returned invalid JSON');
	}

	return array($data, '');
}

