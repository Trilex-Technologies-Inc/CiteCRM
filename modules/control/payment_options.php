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

	$gateway = isset($VAR['gateway']) ? strtolower(trim((string)$VAR['gateway'])) : '';
	if ($gateway !== 'cc_billing' && $gateway !== 'stripe_billing' && $gateway !== 'paypal_billing') {
		$gateway = '';
	}

	/* gateway: only one of cc/stripe/paypal */
	$db->execute("UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=0 WHERE BILLING_OPTION IN ('cc_billing','stripe_billing','paypal_billing')");
	if ($gateway !== '') {
		$db->execute("UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=1 WHERE BILLING_OPTION=".$db->qstr($gateway));
	}

	/* update billing information */
	if($gateway === 'cc_billing') {
		/* enter AN setup */
		$enc_passwd = encrypt ($VAR['AN_PASSWORD'], $strKey);

		$q = "UPDATE ".PRFX."SETUP SET 
				AN_LOGIN_ID	=". $db->qstr( $VAR['an_login'] 		).",
				AN_PASSWORD	=". $db->qstr( $enc_passwd				).",
				AN_TRANS_KEY	=". $db->qstr( $VAR['AN_TRANS_KEY']	);
		if(!$rs = $db->execute($q)) {
			echo $db->ErrorMsg();
		}
	}

	if(isset($VAR['check_billing']) && $VAR['check_billing'] == 1 ) {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=1 WHERE  BILLING_OPTION='check_billing'";
		$rs = $db->execute($q);
	} else {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=0 WHERE  BILLING_OPTION='check_billing'";
		$rs = $db->execute($q);
	}

	if(isset($VAR['cash_billing']) && $VAR['cash_billing'] == 1 ) {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=1 WHERE  BILLING_OPTION='cash_billing'";
		$rs = $db->execute($q);
	} else {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=0 WHERE  BILLING_OPTION='cash_billing'";
		$rs = $db->execute($q);
	}
	
	if(isset($VAR['gift_billing']) && $VAR['gift_billing'] == 1 ) {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=1 WHERE  BILLING_OPTION='gift_billing'";
		$rs = $db->execute($q);
	} else {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=0 WHERE  BILLING_OPTION='gift_billing'";
		$rs = $db->execute($q);
	}
	
	if($gateway === 'paypal_billing') {
		$pp_sandbox = (isset($VAR['PP_SANDBOX']) && $VAR['PP_SANDBOX'] == 1) ? 1 : 0;
		$q = "UPDATE ".PRFX."SETUP SET
				PP_ID=".$db->qstr($VAR['PP_ID']).",
				PP_SANDBOX=".$db->qstr($pp_sandbox);
		$rs = $db->execute($q);
	}

	if($gateway === 'stripe_billing') {
		$stripe_test_mode = (isset($VAR['STRIPE_TEST_MODE']) && $VAR['STRIPE_TEST_MODE'] == 1) ? 1 : 0;
		$stripe_secret = isset($VAR['STRIPE_SECRET_KEY']) ? trim((string)$VAR['STRIPE_SECRET_KEY']) : '';
		$stripe_publishable = isset($VAR['STRIPE_PUBLISHABLE_KEY']) ? trim((string)$VAR['STRIPE_PUBLISHABLE_KEY']) : '';
		$enc_secret = ($stripe_secret !== '') ? encrypt($stripe_secret, $strKey) : '';
		$enc_pub = ($stripe_publishable !== '') ? encrypt($stripe_publishable, $strKey) : '';

		$q = "UPDATE ".PRFX."SETUP SET
				STRIPE_SECRET_KEY=".$db->qstr($enc_secret).",
				STRIPE_PUBLISHABLE_KEY=".$db->qstr($enc_pub).",
				STRIPE_TEST_MODE=".$db->qstr($stripe_test_mode);
		$db->execute($q);
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

	$gateway = '';
	for ($i = 0; $i < count($arr); $i++) {
		if ((int)$arr[$i]['ACTIVE'] !== 1) {
			continue;
		}
		$opt = isset($arr[$i]['BILLING_OPTION']) ? strtolower(trim((string)$arr[$i]['BILLING_OPTION'])) : '';
		if ($opt === 'cc_billing' || $opt === 'stripe_billing' || $opt === 'paypal_billing') {
			$gateway = $opt;
			break;
		}
	}

	/* load setup configuration for billing options */
	$q = "SELECT AN_LOGIN_ID,AN_TRANS_KEY,PP_ID,PP_SANDBOX,STRIPE_PUBLISHABLE_KEY,STRIPE_SECRET_KEY,STRIPE_TEST_MODE FROM ".PRFX."SETUP";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	

	$opts = $rs->GetArray();
	if (!empty($opts) && isset($opts[0])) {
		$opts[0]['STRIPE_PUBLISHABLE_KEY'] = decrypt(isset($opts[0]['STRIPE_PUBLISHABLE_KEY']) ? $opts[0]['STRIPE_PUBLISHABLE_KEY'] : '', $strKey);
		$opts[0]['STRIPE_SECRET_KEY'] = decrypt(isset($opts[0]['STRIPE_SECRET_KEY']) ? $opts[0]['STRIPE_SECRET_KEY'] : '', $strKey);
	}
	
	$smarty->assign( 'opts', $opts );
	$smarty->assign( 'arr', $arr );
	$smarty->assign( 'gateway', $gateway );
	$smarty->display('control'.SEP.'payment_options.tpl');
}
?>
