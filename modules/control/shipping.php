<?php
####################################################
# IN Cite CRM	Customer Relations Management			#
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Shipping Settings											#
#																	#
####################################################

$has_shipping_columns = false;
$rs_cols = $db->Execute("SHOW COLUMNS FROM ".PRFX."SETUP LIKE 'SHIPPING_PROVIDER'");
if ($rs_cols && !$rs_cols->EOF) {
	$has_shipping_columns = true;
}

if (isset($VAR['submit'])) {
	$q = 'UPDATE '.PRFX.'SETUP SET ';

	$updates = array();

	if (isset($VAR['ups_login'])) {
		$updates[] = 'UPS_LOGIN = '.$db->qstr(trim((string)$VAR['ups_login']));
	}
	if (isset($VAR['ups_access_key'])) {
		$updates[] = 'UPS_ACCESS_KEY = '.$db->qstr(trim((string)$VAR['ups_access_key']));
	}
	if (isset($VAR['ups_password']) && trim((string)$VAR['ups_password']) !== '') {
		$updates[] = 'UPS_PASSWORD = '.$db->qstr((string)$VAR['ups_password']);
	}

	if ($has_shipping_columns) {
		$provider = isset($VAR['shipping_provider']) ? strtolower(trim((string)$VAR['shipping_provider'])) : 'ups';
		if ($provider !== 'fedex') {
			$provider = 'ups';
		}
		$updates[] = 'SHIPPING_PROVIDER = '.$db->qstr($provider);

		if (isset($VAR['fedex_key'])) {
			$updates[] = 'FEDEX_KEY = '.$db->qstr(trim((string)$VAR['fedex_key']));
		}
		if (isset($VAR['fedex_account'])) {
			$updates[] = 'FEDEX_ACCOUNT = '.$db->qstr(trim((string)$VAR['fedex_account']));
		}
		if (isset($VAR['fedex_meter'])) {
			$updates[] = 'FEDEX_METER = '.$db->qstr(trim((string)$VAR['fedex_meter']));
		}
		if (isset($VAR['fedex_password']) && trim((string)$VAR['fedex_password']) !== '') {
			$updates[] = 'FEDEX_PASSWORD = '.$db->qstr((string)$VAR['fedex_password']);
		}
	}

	$q .= implode(",\n\t", $updates);

	if (!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

	force_page('control', 'shipping&msg=Shipping settings updated.');
	exit;
}

$q = 'SELECT * FROM '.PRFX.'SETUP';
if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$setup = $rs->GetArray();

$smarty->assign('setup', $setup);
$smarty->assign('has_shipping_columns', $has_shipping_columns);
$smarty->display('control'.SEP.'shipping.tpl');
?>

