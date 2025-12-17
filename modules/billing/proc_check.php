<?php
#####################################################
# Cite CRM  Customer Relations Management           #
# Copyright (C) 2003 - 2005 In-Site CRM             #
# www.citecrm.com  dev@onsitecrm.com                #
# This program is distributed under the terms and   #
# conditions of the GPL                             #
# proc_check.php                           	        #
# Version 0.0.1 Fri Sep 30 09:30:10 PDT 2005        #
#                                                   #
#####################################################
require('include.php');

/* get vars */
$check_amount	= $_POST['check_amount'];
$check_memo		= $_POST['check_memo'];
$check_recieved	= $_POST['check_recieved'];
$customer_id	= $_POST['customer_id'];
$invoice_id		= $_POST['invoice_id'];
$workorder_id	= $_POST['workorder_id'];


/* validation */
if(empty($check_amount)) {
	force_page("billing", "new&error_msg=Please Fill in the check amount.&wo_id=$workorder_id&customer_id=$customer_id&invoice_id=$invoice_id&page_title=Billing");
}

if(empty($check_recieved)) {
	force_page("billing", "new&error_msg=Please Fill in the Document Number.&wo_id=$workorder_id&customer_id=$customer_id&invoice_id=$invoice_id&page_title=Billing");
}

/* get invoice details */
$q = "SELECT * FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_ID=".$db->qstr($invoice_id);
if(!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
	exit;
}

$invocie_details = $rs->FetchRow();

/* check if this is a partial payment */
if($invocie_details['INVOICE_AMOUNT'] > $check_amount){
		if($invocie_details['BALLANCE'] > 0 ) {
			$balance = $invocie_details['BALLANCE'] - $check_amount;
		} else {
			$balance = $invocie_details['INVOICE_AMOUNT'] - $check_amount; 
		}	
		$paid_amount = $check_amount + $invocie_details['PAID_AMOUNT'];

		if($balance == 0 ) {
			$flag  = 1;
		} else {
			$flag = 0;
		}

	/* insert Transaction */
	$memo = "Partial Check Payment Made of $$check_amount Balance due:  $$balance, Check Number: $check_recieved  Check Memo: $check_memo";

	$q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
		  DATE 			= ".$db->qstr(time()).",
		  TYPE 			= '2',
		  INVOCIE_ID 	= ".$db->qstr($invoice_id).",
		  WORKORDER_ID 	= ".$db->qstr($workorder_id).",
		  CUSTOMER_ID 	= ".$db->qstr($customer_id).",
		  MEMO 			= ".$db->qstr($memo).",
		  AMOUNT			= ".$db->qstr($check_amount);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	
	/* update the invoice */	
	 if($balance == 0 ) {
			$q = "UPDATE ".PRFX."TABLE_INVOICE SET 
		  	PAID_DATE  	= ".$db->qstr(time()).",
		  	INVOICE_PAID	= ".$db->qstr($flag).",
		  	PAID_AMOUNT 	= ".$db->qstr($paid_amount).",
		  	BALLANCE 		= ".$db->qstr($balance).",
			INVOICE_PAID	='1' WHERE INVOICE_ID = ".$db->qstr($invoice_id);
	} else {
		$q = "UPDATE ".PRFX."TABLE_INVOICE SET 
		  	PAID_DATE  	= ".$db->qstr(time()).",
		  	INVOICE_PAID	= ".$db->qstr($flag).",
		  	PAID_AMOUNT 	= ".$db->qstr($paid_amount).",
		  	BALLANCE 		= ".$db->qstr($balance)." WHERE INVOICE_ID = ".$db->qstr($invoice_id);
	}
		  
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	
	/* update work order */
	$q = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
		  WORK_ORDER_ID						= ".$db->qstr($workorder_id).",
		  WORK_ORDER_STATUS_DATE 			= ".$db->qstr(time()).",
		  WORK_ORDER_STATUS_NOTES 		= ".$db->qstr($memo).",
		  WORK_ORDER_STATUS_ENTER_BY		= ".$db->qstr($employee);
	
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}

	if($balance == 0 ) {
	$q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
			WORK_ORDER_STATUS			= '6',
			WORK_ORDER_CURENT_STATUS 	= '8'
			WHERE WORK_ORDER_ID 		=	".$db->qstr($workorder_id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
	}

	force_page('invoice', "view&invoice_id=$invoice_id&customer_id=$customer_id");

} else {

	/* full payment made */
	if($invocie_details['INVOICE_AMOUNT'] < $check_amount) {
		force_page('billing', 'new&wo_id='.$workorder_id.'&customer_id='.$customer_id.'	&invoice_id='.$invoice_id.'&error_msg=You can not bill more than the amout of the invoice.');
			exit;
		} 
	if($invocie_details['INVOICE_AMOUNT'] == $check_amount){	
		/* insert Transaction */
		$memo = "Full Check Payment Made of $$check_amount, Check Number: $check_recieved  Check Memo: $check_memo";
	
		$q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
			DATE 			= ".$db->qstr(time()).",
			TYPE 			= '2',
			INVOCIE_ID 	= ".$db->qstr($invoice_id).",
			WORKORDER_ID 	= ".$db->qstr($workorder_id).",
			CUSTOMER_ID 	= ".$db->qstr($customer_id).",
			MEMO 			= ".$db->qstr($memo).",
			AMOUNT			= ".$db->qstr($check_amount);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		
		/* update the invoice */	
		$q = "UPDATE ".PRFX."TABLE_INVOICE SET
			PAID_DATE  			= ".$db->qstr(time()).", 
			PAID_AMOUNT 			= ".$db->qstr($check_amount).",
			INVOICE_PAID			= '1',
			BALLANCE 				= ".$db->qstr(0.00)."
			WHERE INVOICE_ID 	= ".$db->qstr($invoice_id);
			
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		
		/* update work order */
		$q = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
			WORK_ORDER_ID					= ".$db->qstr($workorder_id).",
			WORK_ORDER_STATUS_DATE 		= ".$db->qstr(time()).",
			WORK_ORDER_STATUS_NOTES 		= ".$db->qstr($memo).",
			WORK_ORDER_STATUS_ENTER_BY	= ".$db->qstr($_SESSION['login_id']);
		
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		
		$q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
			WORK_ORDER_STATUS			= '6',
			WORK_ORDER_CURENT_STATUS 	= '8'
			WHERE WORK_ORDER_ID 		=	".$db->qstr($workorder_id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
			
		force_page('invoice', "view&invoice_id=$invoice_id&customer_id=$customer_id");
			
	}
}
?>