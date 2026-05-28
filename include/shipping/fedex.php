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

function citecrm_fedex_track($access_token, $tracking_number, $use_sandbox = false) {
	$access_token = trim((string)$access_token);
	$tracking_number = trim((string)$tracking_number);
	if ($access_token === '') {
		return array(null, 'Missing FedEx access token');
	}
	if ($tracking_number === '') {
		return array(null, 'Missing FedEx tracking number');
	}

	$host = $use_sandbox ? 'https://apis-sandbox.fedex.com' : 'https://apis.fedex.com';
	$url = $host.'/track/v1/trackingnumbers';

	$payload = json_encode(array(
		'includeDetailedScans' => true,
		'trackingInfo' => array(
			array(
				'trackingNumberInfo' => array(
					'trackingNumber' => $tracking_number,
				),
			),
		),
	));
	if ($payload === false) {
		return array(null, 'Unable to encode FedEx tracking request');
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
		$err = $curl_err !== '' ? $curl_err : ('FedEx tracking request failed (HTTP '.$http_code.')');
		return array(null, $err);
	}

	$data = json_decode($response, true);
	if (!is_array($data)) {
		return array(null, 'FedEx tracking returned invalid JSON');
	}

	return array($data, '');
}

function citecrm_fedex_normalize_tracking($data) {
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

	$track = null;
	if (isset($data['output']['completeTrackResults'][0]['trackResults'][0])) {
		$track = $data['output']['completeTrackResults'][0]['trackResults'][0];
	}
	if (!is_array($track)) {
		return $result;
	}

	if (isset($track['latestStatusDetail']) && is_array($track['latestStatusDetail'])) {
		$detail = $track['latestStatusDetail'];
		if (isset($detail['statusByLocale'])) {
			$result['status'] = (string)$detail['statusByLocale'];
		} else if (isset($detail['description'])) {
			$result['status'] = (string)$detail['description'];
		} else if (isset($detail['code'])) {
			$result['status'] = (string)$detail['code'];
		}
		if (isset($detail['scanLocation'])) {
			$result['latest_location'] = citecrm_fedex_format_location($detail['scanLocation']);
		}
	}

	if (isset($track['dateAndTimes']) && is_array($track['dateAndTimes'])) {
		foreach ($track['dateAndTimes'] as $date_time) {
			if (!is_array($date_time) || !isset($date_time['dateTime'])) {
				continue;
			}
			$type = isset($date_time['type']) ? strtoupper((string)$date_time['type']) : '';
			if ($result['estimated_delivery'] === '' && strpos($type, 'ESTIMATED_DELIVERY') !== false) {
				$result['estimated_delivery'] = (string)$date_time['dateTime'];
			}
		}
	}

	$scan_events = isset($track['scanEvents']) && is_array($track['scanEvents']) ? $track['scanEvents'] : array();
	foreach ($scan_events as $event) {
		if (!is_array($event)) {
			continue;
		}

		$description = '';
		if (isset($event['eventDescription'])) {
			$description = (string)$event['eventDescription'];
		} else if (isset($event['derivedStatus'])) {
			$description = (string)$event['derivedStatus'];
		}

		$event_time = isset($event['date']) ? (string)$event['date'] : '';
		$location = isset($event['scanLocation']) ? citecrm_fedex_format_location($event['scanLocation']) : '';

		$result['events'][] = array(
			'time' => $event_time,
			'description' => $description,
			'location' => $location,
		);
	}

	if (isset($result['events'][0])) {
		$result['latest_event'] = $result['events'][0]['description'];
		$result['latest_event_time'] = $result['events'][0]['time'];
		if ($result['latest_location'] === '') {
			$result['latest_location'] = $result['events'][0]['location'];
		}
	}

	return $result;
}

function citecrm_fedex_format_location($location) {
	if (!is_array($location)) {
		return '';
	}

	$parts = array();
	foreach (array('city', 'stateOrProvinceCode', 'postalCode', 'countryCode') as $key) {
		if (isset($location[$key]) && trim((string)$location[$key]) !== '') {
			$parts[] = trim((string)$location[$key]);
		}
	}

	return implode(', ', $parts);
}
