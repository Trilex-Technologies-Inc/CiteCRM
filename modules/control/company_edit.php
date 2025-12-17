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
	if($VAR['pdf_print'] == 1) {
		$html_print = 0;
		$pdf_print  = 1;
	} else {
		$html_print = 1;
		$pdf_print  = 0;
	}

$q = 'UPDATE '.PRFX.'SETUP SET ';

if($VAR['parts_password'] !='') {
	$q .= 'PARTS_PASSWORD		= '. $db->qstr( md5($VAR['parts_password'])).', ';	
}

if($VAR['ups_password'] != '') {
	$q .= 'UPS_PASSWORD		= '. $db->qstr( $VAR['ups_password']		) .', ';
}
		$q .= '
			HTML_PRINT 			= '. $db->qstr( $html_print          	) .',
			PDF_PRINT				= '. $db->qstr( $pdf_print           	) .',
			INVOCIE_TAX 			= '. $db->qstr( $VAR['inv_tax']      	) .',
			INV_THANK_YOU 		= '. $db->qstr( $VAR['inv_thank_you'] 	) .',
			WELCOME_NOTE			= '. $db->qstr( $VAR['welcome']      	).',
			PARTS_LO				= '. $db->qstr( $VAR['parts_lo']			).',
			SERVICE_CODE			= '. $db->qstr( $VAR['service_code']		).',
			PARTS_MARKUP			= '. $db->qstr( $VAR['parts_markup'] 	).',
			PARTS_LOGIN			= '. $db->qstr( $VAR['parts_login']		).',
			UPS_LOGIN 			= '. $db->qstr( $VAR['ups_login']			).',
			UPS_ACCESS_KEY		= '. $db->qstr( $VAR['ups_access_key']	);

	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

	print $q;


unset($q);


	/* update company information */
	$q = 'UPDATE '.PRFX.'TABLE_COMPANY SET
		  	COMPANY_NAME			= '. $db->qstr( $VAR['company_name']	) .',
		  	COMPANY_ADDRESS 	= '. $db->qstr( $VAR['address']		) .',
			COMPANY_CITY 		= '. $db->qstr( $VAR['city']			) .',
			COMPANY_STATE		= '. $db->qstr( $VAR['state']			) .',
			COMPANY_ZIP 			= '. $db->qstr( $VAR['zip']				) .',
			COMPANY_COUNTRY		= '. $db->qstr( $VAR['country']).',
			COMPNAY_PHONE		= '. $db->qstr( $VAR['phone']			) .',
			COMPNAY_MOBILE		= '. $db->qstr( $VAR['mobile_phone']	) .', 
			COMPANY_TOLL_FREE	= '. $db->qstr( $VAR['toll_free']		);
		

	
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