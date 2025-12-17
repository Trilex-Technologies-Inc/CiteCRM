<?php
####################################################
# IN Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Payment Options												#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################

if(isset($VAR['submit'])) {

	/* update billing information */
	if($VAR['cc_billing'] == 1 ) {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=1 WHERE  BILLING_OPTION='cc_billing'";
		$rs = $db->execute($q);
		
		/* enter AN setup */
		$enc_passwd = encrypt ($VAR['AN_PASSWORD'], $strKey);

		$q = "UPDATE ".PRFX."SETUP SET 
				AN_LOGIN_ID	=". $db->qstr( $VAR['an_login'] 		).",
				AN_PASSWORD	=". $db->qstr( $enc_passwd				).",
				AN_TRANS_KEY	=". $db->qstr( $VAR['AN_TRANS_KEY']	);
		if(!$rs = $db->execute($q)) {
			echo $db->ErrorMsg();
		}
	} else {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=0 WHERE  BILLING_OPTION='cc_billing'";
		$rs = $db->execute($q);
	}

	if($VAR['check_billing'] == 1 ) {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=1 WHERE  BILLING_OPTION='check_billing'";
		$rs = $db->execute($q);
	} else {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=0 WHERE  BILLING_OPTION='check_billing'";
		$rs = $db->execute($q);
	}

	if($VAR['cash_billing'] == 1 ) {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=1 WHERE  BILLING_OPTION='cash_billing'";
		$rs = $db->execute($q);
	} else {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=0 WHERE  BILLING_OPTION='cash_billing'";
		$rs = $db->execute($q);
	}
	
	if($VAR['gift_billing'] == 1 ) {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=1 WHERE  BILLING_OPTION='gift_billing'";
		$rs = $db->execute($q);
	} else {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=0 WHERE  BILLING_OPTION='gift_billing'";
		$rs = $db->execute($q);
	}
	
	if($VAR['paypal_billing'] == 1 ) {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=1 WHERE  BILLING_OPTION='paypal_billing'";
		$rs = $db->execute($q);
		
		$q = "UPDATE ".PRFX."SETUP SET PP_ID=".$db->qstr($VAR['PP_ID']);
		$rs = $db->execute($q);
	} else {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=0 WHERE  BILLING_OPTION='paypal_billing'";
		$rs = $db->execute($q);
	}
	
	force_page('control', 'payment_options&msg=Billing Options Updated.');
	exit;	

} else {
	/* load billing options */
	$q = "SELECT * FROM ".PRFX."CONFIG_BILLING_OPTIONS";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	$arr = $rs->GetArray();

	/* load setup configuration for billing options */
	$q = "SELECT AN_LOGIN_ID,AN_TRANS_KEY,PP_ID FROM ".PRFX."SETUP";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	

	$opts = $rs->GetArray();
	
	$smarty->assign( 'opts', $opts );
	$smarty->assign( 'arr', $arr );
	$smarty->display('control'.SEP.'payment_options.tpl');
}
?>