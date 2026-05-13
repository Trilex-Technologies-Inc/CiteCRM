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
$q = "SELECT PP_ID, PP_SANDBOX FROM ".PRFX."SETUP";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
$pay_pal_email	= $rs->fields['PP_ID'];
$pp_sandbox     = isset($rs->fields['PP_SANDBOX']) ? (int)$rs->fields['PP_SANDBOX'] : 0;

/* get invoice totals */
$amount		= $VAR['paypal_amount'];
$invoice_id	= $VAR['invoice_id'];

$params = array(
	'cmd' => '_xclick',
	'business' => $pay_pal_email,
	'item_name' => $company.' Computer Service',
	'item_number' => $invoice_id,
	'amount' => $amount,
	'no_note' => '1',
	'currency_code' => $curency_code,
	'lc' => $country,
	'bn' => 'PP-BuyNowBF',
);
$content = http_build_query($params, '', '&');

$gateway = ($pp_sandbox === 1)
	? "https://www.sandbox.paypal.com/cgi-bin/webscr"
	: "https://www.paypal.com/cgi-bin/webscr";

$paypal_url = $gateway.'?'.$content;

$ch = curl_init($gateway); // URL of gateway for cURL to post to
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
$smarty->assign('paypal_url', $paypal_url);

/*
 * Email the PayPal link to the customer (if email exists).
 */
$email_sent = false;
$customer_email = '';
$customer_name = '';
$customer_id = isset($VAR['customer_id']) ? (int)$VAR['customer_id'] : 0;
if ($customer_id > 0) {
	$q = "SELECT CUSTOMER_EMAIL, CUSTOMER_DISPLAY_NAME, CUSTOMER_FIRST_NAME, CUSTOMER_LAST_NAME
		  FROM ".PRFX."TABLE_CUSTOMER
		  WHERE CUSTOMER_ID=".$db->qstr($customer_id);
	$rs = $db->Execute($q);
	if ($rs) {
		$customer_email = trim((string)$rs->fields['CUSTOMER_EMAIL']);
		$customer_name = trim((string)$rs->fields['CUSTOMER_DISPLAY_NAME']);
		if ($customer_name === '') {
			$customer_name = trim((string)$rs->fields['CUSTOMER_FIRST_NAME'].' '.(string)$rs->fields['CUSTOMER_LAST_NAME']);
		}
	}
}

$company_email = '';
$company_name = 'CiteCRM';
$q = "SELECT COMPANY_EMAIL, COMPANY_NAME FROM ".PRFX."TABLE_COMPANY";
$rs = $db->Execute($q);
if ($rs) {
	$company_email = trim((string)$rs->fields['COMPANY_EMAIL']);
	if (trim((string)$rs->fields['COMPANY_NAME']) !== '') {
		$company_name = (string)$rs->fields['COMPANY_NAME'];
	}
}

if ($customer_email !== '' && filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
	$subject = $company_name.' - PayPal payment link (Invoice #'.$invoice_id.')';

	$lines = array();
	if ($customer_name !== '') {
		$lines[] = 'Hello '.$customer_name.',';
		$lines[] = '';
	}
	$lines[] = 'Please use the link below to complete your payment via PayPal:';
	$lines[] = $paypal_url;
	$lines[] = '';
	$lines[] = 'Invoice ID: '.$invoice_id;
	if (!empty($VAR['workorder_id'])) {
		$lines[] = 'Work Order: '.$VAR['workorder_id'];
	}
	if ($amount !== '') {
		$lines[] = 'Amount: $'.$amount;
	}
	$lines[] = '';
	$lines[] = 'Thank you.';
	$message = implode("\r\n", $lines);

	$headers = array();
	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-Type: text/plain; charset=UTF-8';
	if ($company_email !== '' && filter_var($company_email, FILTER_VALIDATE_EMAIL)) {
		$headers[] = 'From: '.$company_name.' <'.$company_email.'>';
		$headers[] = 'Reply-To: '.$company_email;
	}

	$sendmail_path = (string)ini_get('sendmail_path');
	$sendmail_bin = trim(strtok($sendmail_path, " \t"));
	$has_sendmail = ($sendmail_bin !== '' && @file_exists($sendmail_bin));
	if ($has_sendmail) {
		$email_sent = @mail($customer_email, $subject, $message, implode("\r\n", $headers));
	}

	$workorder_id = isset($VAR['workorder_id']) ? (int)$VAR['workorder_id'] : 0;
	if ($workorder_id > 0) {
		if ($email_sent) {
			$msg = "PayPal payment link emailed to ".$customer_email." (Invoice ID: ".$invoice_id.")";
		} else {
			$reason = $has_sendmail ? 'mail() failed' : ('sendmail missing (sendmail_path='.$sendmail_path.')');
			$msg = "PayPal payment link NOT emailed to ".$customer_email." (Invoice ID: ".$invoice_id.") - ".$reason;
			@error_log('['.date('c').'] '.$msg."\n", 3, 'log/mail.log');
		}
		$sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
				WORK_ORDER_ID					=".$db->qstr($workorder_id).",
				WORK_ORDER_STATUS_DATE			=".$db->qstr(time()).",
				WORK_ORDER_STATUS_NOTES			=".$db->qstr($msg).",
				WORK_ORDER_STATUS_ENTER_BY 		=".$db->qstr($_SESSION['login_id']);
		$db->Execute($sql);
	}
}

$smarty->assign('email_sent', $email_sent ? 1 : 0);
$smarty->assign('email_to', $customer_email);
$smarty->display('billing'.SEP.'proc_paypal.tpl');

?>



