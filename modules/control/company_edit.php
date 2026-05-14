<?php
####################################################
# IN Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
# Company Edit file											#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################

if(isset($VAR['submit'])) {


	/* get pdf printing option */
	if(isset($VAR['pdf_print']) && $VAR['pdf_print'] == 1) {
		$html_print = 0;
		$pdf_print  = 1;
	} else {
		$html_print = 1;
		$pdf_print  = 0;
	}

$q = 'UPDATE '.PRFX.'SETUP SET ';

/* Optional shipping columns (for upgraded DBs) */
$has_shipping_columns = false;
$rs_cols = $db->Execute("SHOW COLUMNS FROM ".PRFX."SETUP LIKE 'SHIPPING_PROVIDER'");
if($rs_cols && !$rs_cols->EOF) {
	$has_shipping_columns = true;
}

if(isset($VAR['parts_password']) && $VAR['parts_password'] !='') {
	$q .= 'PARTS_PASSWORD		= '. $db->qstr( md5($VAR['parts_password'])).', ';	
}

if(isset($VAR['ups_password']) && $VAR['ups_password'] != '') {
	$q .= 'UPS_PASSWORD		= '. $db->qstr( $VAR['ups_password']		) .', ';
}

if($has_shipping_columns && isset($VAR['fedex_password']) && $VAR['fedex_password'] != '') {
	$q .= 'FEDEX_PASSWORD		= '. $db->qstr( $VAR['fedex_password']	) .', ';
}
		$q .= '
			HTML_PRINT 			= '. $db->qstr( $html_print          	) .',
			PDF_PRINT				= '. $db->qstr( $pdf_print           	) .',
			INVOCIE_TAX 			= '. $db->qstr( isset($VAR['inv_tax']) && $VAR['inv_tax'] != '' ? $VAR['inv_tax'] : '0' ) .',
			INV_THANK_YOU 		= '. $db->qstr( isset($VAR['inv_thank_you']) && $VAR['inv_thank_you'] != '' ? $VAR['inv_thank_you'] : ' ' ) .',
			WELCOME_NOTE			= '. $db->qstr( isset($VAR['welcome']) && $VAR['welcome'] != '' ? $VAR['welcome'] : ' ' ) .',
			PARTS_LO				= '. $db->qstr( isset($VAR['parts_lo']) && $VAR['parts_lo'] != '' ? $VAR['parts_lo'] : '0' ) .',
			SERVICE_CODE			= '. $db->qstr( isset($VAR['service_code']) && $VAR['service_code'] != '' ? $VAR['service_code'] : ' ' ) .',
			PARTS_MARKUP			= '. $db->qstr( isset($VAR['parts_markup']) && $VAR['parts_markup'] != '' ? $VAR['parts_markup'] : '0' ) .',
			PARTS_LOGIN			= '. $db->qstr( isset($VAR['parts_login']) && $VAR['parts_login'] != '' ? $VAR['parts_login'] : ' ' );

	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}




unset($q);


	/* update company information */
	$q = 'UPDATE '.PRFX.'TABLE_COMPANY SET
		  	COMPANY_NAME			= '. $db->qstr( isset($VAR['company_name']) && $VAR['company_name'] != '' ? $VAR['company_name'] : ' ' ) .',
		  	COMPANY_ADDRESS 	= '. $db->qstr( isset($VAR['address']) && $VAR['address'] != '' ? $VAR['address'] : ' ' ) .',
			COMPANY_CITY 		= '. $db->qstr( isset($VAR['city']) && $VAR['city'] != '' ? $VAR['city'] : ' ' ) .',
			COMPANY_STATE		= '. $db->qstr( isset($VAR['state']) && $VAR['state'] != '' ? $VAR['state'] : ' ' ) .',
			COMPANY_ZIP 			= '. $db->qstr( isset($VAR['zip']) && $VAR['zip'] != '' ? $VAR['zip'] : ' ' ) .',
			COMPANY_COUNTRY		= '. $db->qstr( isset($VAR['country']) && $VAR['country'] != '' ? $VAR['country'] : ' ' ) .',
			COMPNAY_PHONE		= '. $db->qstr( isset($VAR['phone']) && $VAR['phone'] != '' ? $VAR['phone'] : ' ' ) .',
			COMPNAY_MOBILE		= '. $db->qstr( isset($VAR['mobile_phone']) && $VAR['mobile_phone'] != '' ? $VAR['mobile_phone'] : ' ' ) .', 
			COMPANY_TOLL_FREE	= '. $db->qstr( isset($VAR['toll_free']) && $VAR['toll_free'] != '' ? $VAR['toll_free'] : ' ' );
		

	
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		force_page('control', 'company_edit&msg=The Company information was updated');
		exit;
	}

} else {

	/* get current Company information */
	$q = 'SELECT * FROM '.PRFX.'TABLE_COMPANY';
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		} else {
			$arr = $rs->GetArray();
		}
	
	/* load setup Information */
	$q = 'SELECT * FROM '.PRFX.'SETUP';
	if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		} else {
			$setup = $rs->GetArray();
		}
	
	/* get country codes */
	$q = 'SELECT * FROM '.PRFX.'COUNTRY';
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	$country = $rs->GetArray();
	
	$smarty->assign('country', $country);
	$smarty->assign('setup', $setup);
	$smarty->assign('company', $arr);
	$smarty->display('control/company_edit.tpl');
}
?>
