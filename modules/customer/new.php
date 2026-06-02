<?php
#######################################################
# 			Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM					#
#  www.citecrm.com  dev@onsitecrm.com						#
#  This program is distributed under the terms and 		#
#  conditions of the GPL											#
#  New Customer 													#
#  Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005			#
#																		#
#######################################################
require_once("include.php");
if (!xml2php("customer")) {
	$smarty->assign('error_msg', "Error in language file");
}

$q = "SELECT * FROM " . PRFX . "COUNTRY";
if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
	exit;
}
$country = $rs->GetArray();
$smarty->assign('country', $country);

$selected_country = '';
if (isset($VAR['country']) && trim((string)$VAR['country']) !== '') {
	$selected_country = strtoupper(trim((string)$VAR['country']));
} else if (isset($company_country) && trim((string)$company_country) !== '') {
	$selected_country = strtoupper(trim((string)$company_country));
}
$smarty->assign('selected_country', $selected_country);

$smarty->assign('has_brand_new_column', customer_has_brand_new_column($db));
if (isset($VAR['submit'])) {

	if (!check_customer_ex($db, $VAR['displayName'])) {
		$smarty->assign('VAR', $VAR);
		$smarty->assign('error_msg', 'The customer Display Name, ' . $VAR["displayName"] . ',  already exists! Please use a differnt name.');
		$smarty->display('customer' . SEP . 'new.tpl');
	} else {
		if (!$customer_id = insert_new_customer($db, $VAR)) {
			$smarty->assign('error_msg', 'Falied to insert customer');
			$smarty->display('core' . SEP . 'error.tpl');
		} else {
			force_page('customer', 'customer_details&customer_id=' . $customer_id . '&msg=Added New Customer ' . $VAR["displayName"] . ' &page_title=' . $VAR["displayName"]);
			exit;
		}
	}
} else {

	$smarty->display('customer' . SEP . 'new.tpl');
}
