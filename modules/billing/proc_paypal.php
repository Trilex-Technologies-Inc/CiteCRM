<?php
####################################################
# IN Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  pay pal payment											#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################
if(!xml2php("billing")) {
	$smarty->assign('error_msg',"Error in language file");
}
/* get company Info */
$q = "SELECT COMPANY_NAME, COMPANY_COUNTRY FROM ".PRFX."TABLE_COMPANY";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
$company			= $rs->fields['COMPANY_NAME'];
$country			= $rs->fields['COMPANY_COUNTRY'];
$curency_code	= 'USD';

/* get pay pal login */
$q = "SELECT PP_ID FROM ".PRFX."SETUP";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
$pay_pal_email	= $rs->fields['PP_ID'];

/* get invoice totals */
$amount		= $VAR['paypal_amount'];
$invoice_id	= $VAR['invoice_id'];

$content = "cmd=_xclick&business=".$pay_pal_email."&item_name=".$company." Computer Service&item_number=".$invoice_id."&amount=".$amount."&no_note=1&currency_code=".$curency_code."&lc=".$country."&bn=PP-BuyNowBF";

$ch = curl_init("https://www.paypal.com/cgi-bin/webscr"); // URL of gateway for cURL to post to
	curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
	curl_setopt($ch, CURLOPT_POSTFIELDS, $content); // use HTTP POST to send form data
	$resp = curl_exec($ch); //execute post and get results
	curl_close ($ch);

 		$fp = fopen('tmp.html','w') or die("can't open file.txt: $php_errormsg");
    fwrite($fp, $resp);
    fclose($fp);
$smarty->assign('invoice_id', $invoice_id);
$smarty->assign('wo_id', $VAR['workorder_id']);
$smarty->display('billing'.SEP.'proc_paypal.tpl');

?>





