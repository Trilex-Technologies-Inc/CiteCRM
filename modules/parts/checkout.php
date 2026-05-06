<?php
####################################################
# IN Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Parts Check Out file										#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################

$q = "SELECT PARTS_LO,PARTS_LOGIN,PARTS_PASSWORD,SERVICE_CODE,PARTS_MARKUP,INVOCIE_TAX   FROM ".PRFX."SETUP ";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

	$local 			= $rs->fields['PARTS_LO'];
 	$login				= $rs->fields['PARTS_LOGIN'];
	$passwd			= $rs->fields['PARTS_PASSWORD'];
	$service_code	= $rs->fields['SERVICE_CODE'];
	$tax 				= $rs->fields['INVOCIE_TAX'];
	$tax 				= $tax * 0.01;
	$mark_up			= $rs->fields['PARTS_MARKUP'];
	$mark_up 			= $mark_up * 0.01;

$q = "SELECT COMPANY_ZIP FROM ".PRFX."TABLE_COMPANY";
if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

$from_zip = $rs->fields['COMPANY_ZIP'];
$workorder_id = $VAR['wo_id'];

$q = "SELECT CUSTOMER_ID FROM ".PRFX."TABLE_WORK_ORDER  WHERE WORK_ORDER_ID=".$db->qstr($workorder_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

$customer_id = $rs->fields['CUSTOMER_ID'];



$q = "SELECT SKU,AMOUNT FROM ".PRFX."CART";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

	if($rs->fields['SKU'] == ''){
		   force_page('parts', 'main&error_msg=You  have no parts in your Cart. Please select the parts you whish to order and click add.&wo_id='.$VAR['wo_id'].'&page_title=Order%20Parts');
			exit;
	}

	/*
	 * Local-only checkout: build the order from the local CART table
	 * instead of calling external APIs.
	 */
	$cart_where = '';
	$wo_id = $workorder_id;
	if ($workorder_id !== '' && (int)$workorder_id > 0) {
		$cart_where = " WHERE WO_ID=".$db->qstr((int)$workorder_id);
	}

	$q = "SELECT SKU, AMOUNT, DESCRIPTION, VENDOR, PRICE, SUB_TOTAL, Weight
		  FROM ".PRFX."CART".$cart_where;
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

	$cart_rows = $rs->GetArray();
	if (!is_array($cart_rows) || count($cart_rows) === 0) {
		force_page('parts', 'main&error_msg=You  have no parts in your Cart. Please select the parts you whish to order and click add.&wo_id='.$VAR['wo_id'].'&page_title=Order%20Parts');
		exit;
	}

	$details = array();
	$cart_total = 0.00;
	$shipping = 0.00; // no external shipping quote; can be adjusted locally if desired
	$weight = 0.00;
	$total_items = 0;

	foreach($cart_rows as $row) {
		$qty = (int)$row['AMOUNT'];
		$price_each = (float)$row['PRICE'];
		$line_sub_total = (float)$row['SUB_TOTAL'];

		// If SUB_TOTAL isn't populated correctly, fall back to qty * price.
		if ($line_sub_total <= 0 && $qty > 0) {
			$line_sub_total = $qty * $price_each;
		}

		$cart_total += $line_sub_total;
		$total_items += $qty;
		$weight += ((float)$row['Weight']) * $qty;

		$details[] = array(
			'SKU' => $row['SKU'],
			'COUNT' => $qty,
			'PRICE' => number_format($price_each, 2, '.', ''),
			'SUB_TOTAL' => number_format($line_sub_total, 2, '.', ''),
			'VENDOR' => $row['VENDOR'],
			'DESCRIPTION' => $row['DESCRIPTION'],
		);
	}

	$cart_total = number_format($cart_total, 2, '.', '');
	$shipping = number_format($shipping, 2, '.', '');
	$weight = number_format($weight, 2, '.', '');
	$total = number_format(((float)$cart_total + (float)$shipping), 2, '.', '');

	// Local invoice id (int) used in ORDERS.INVOICE_ID and for messages.
	// Keep within signed 32-bit INT range.
	$crm_invoice_id = (int)time() + (int)mt_rand(0, 999);
	/* Insert Order */
	$q= "INSERT INTO ".PRFX."ORDERS SET
			INVOICE_ID	=".$db->qstr($crm_invoice_id								).",
			WO_ID 			=".$db->qstr($wo_id											).",
			DATE_CREATE	='".time()."',
			DATE_LAST		='".time()."',
			SUB_TOTAL		=".$db->qstr( number_format($cart_total, 2,'.', '')	).",
			SHIPPING 		=".$db->qstr( number_format($shipping, 2,'.', '')		).",
			TOTAL			=".$db->qstr( number_format($total, 2,'.', '')			).",
			WEIGHT			=".$db->qstr( number_format($weight, 2,'.', '')		).",
			ITEMS			=".$db->qstr( $total_items									).",
			TRACKING_NO	=".$db->qstr(0													).",
			STATUS			=".$db->qstr(1													);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	
	$order_id = $db->insert_id();


	/* Update Work Order status and record invoice created */
	if($wo_id != '') {

		/* create Invoice */

		$q = "SELECT  count(*) as count FROM ".PRFX."TABLE_INVOICE WHERE WORKORDER_ID=".$db->qstr($wo_id);
		$rs = $db->Execute($q);
		$count = $rs->fields['count'];
	
		

		if($count == 0) {
			$tax_amount = number_format($total * $tax, 2, '.', ',');
			$total = $total + $tax_amount;

			$q = "INSERT INTO ".PRFX."TABLE_INVOICE SET
				INVOICE_DATE 	=".$db->qstr(time()											).",
				CUSTOMER_ID		=".$db->qstr($customer_id									).", 
				WORKORDER_ID		=".$db->qstr($wo_id											).",
				EMPLOYEE_ID		=".$db->qstr($_SESSION['login_id']							).", 
				INVOICE_PAID	   ='0', 
				INVOICE_AMOUNT	=".$db->qstr( number_format($total, 2, '.', ',') 		).",
				SHIPPING			=".$db->qstr( number_format($shipping, 2, '.', ',')	).",
				TAX 				=".$db->qstr( number_format($tax_amount, 2, '.', ',')	).",
				SUB_TOTAL			=".$db->qstr( number_format($cart_total, 2, '.', ',')	);
				
			if(!$rs = $db->Execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
				exit;
			}
	
			$invoice_id = $db->insert_id();

			/* Update Work Order status and record invoice created */
			$msg = "Invoice Created ID: ".$invoice_id;
		
			$sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
				WORK_ORDER_ID					=".$db->qstr($wo_id).",
				WORK_ORDER_STATUS_DATE			=".$db->qstr(time()).",
				WORK_ORDER_STATUS_NOTES		=".$db->qstr($msg).",
				WORK_ORDER_STATUS_ENTER_BY 	=".$db->qstr($_SESSION['login_id']);	

			if(!$result = $db->Execute($sql)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
				exit;
			}

		} else if($count == 1) {
			/* get curent Invoice details */
			$q = "SELECT INVOICE_ID,INVOICE_AMOUNT, SUB_TOTAL, TAX FROM ".PRFX."TABLE_INVOICE WHERE WORKORDER_ID=".$db->qstr($wo_id);
			$rs = $db->Execute($q);
			$invoice_id	= $rs->fields['INVOICE_ID'];
			$tax_amount = number_format($total * $tax, 2, '.', ',');
			$total = $total + $tax_amount;
			$invoice_total = $total + $rs->fields['INVOICE_AMOUNT'];
			$invoice_sub_total = $total + $rs->fields['SUB_TOTAL'];

			$q = "UPDATE ".PRFX."TABLE_INVOICE SET
				INVOICE_AMOUNT		=".$db->qstr( number_format($invoice_total, 2, '.', ',')			).",
				SUB_TOTAL				=".$db->qstr( number_format($invoice_sub_total, 2, '.', ',')	).",
				SHIPPING				=".$db->qstr( number_format($shipping, 2, '.', ',')				).",
				TAX 					=".$db->qstr( number_format($tax_amount, 2, '.', ',')				)."
				WHERE INVOICE_ID 	=".$db->qstr($invoice_id);

		}

		/* update work order Status */
		$msg = "Parts Ordered. Cite CRM Orderd ID: ".$crm_invoice_id." Amount: $".number_format($cart_total, 2, '.', ',')." Shipping: $".number_format($shipping, 2, '.', ',')." Total: $".number_format($cart_total + $shipping, 2, '.', ',');
		
		$sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
				WORK_ORDER_ID					=".$db->qstr($wo_id).",
				WORK_ORDER_STATUS_DATE			=".$db->qstr(time()).",
				WORK_ORDER_STATUS_NOTES		=".$db->qstr($msg).",
				WORK_ORDER_STATUS_ENTER_BY 	=".$db->qstr($_SESSION['login_id']);	

		if(!$result = $db->Execute($sql)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		} 

		/* mark work order waiting for parts */
		$sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
			  WORK_ORDER_CURENT_STATUS	='3',
			  LAST_ACTIVE					=". $db->qstr(time())."
	  		  WHERE WORK_ORDER_ID			=". $db->qstr($wo_id);

		if(!$result = $db->Execute($sql)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

		$msg = "Work Order Changed status to Waiting For Parts";
		$sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
			  WORK_ORDER_ID					=". $db->qstr( $wo_id).",
			  WORK_ORDER_STATUS_DATE		=". $db->qstr( time()).",
			  WORK_ORDER_STATUS_NOTES		=". $db->qstr( $msg).",
			  WORK_ORDER_STATUS_ENTER_BY =". $db->qstr( $_SESSION['login_id']);
		
		if(!$result = $db->Execute($sql)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
		
	}

	/* insert order details */

	$i = 0;
	foreach($details as $val) {
		// DETAILS_ID is AUTO_INCREMENT; omit it to avoid strict-mode errors inserting ''.
		$q = "INSERT INTO ".PRFX."ORDERS_DETAILS (ORDER_ID,SKU,DESCRIPTION,VENDOR,COUNT,PRICE,SUB_TOTAL)
		VALUES (".$db->qstr($order_id).",".$db->qstr($details[$i]['SKU']).",".$db->qstr($details[$i]['DESCRIPTION']).",".$db->qstr($details[$i]['VENDOR']).",".$db->qstr($details[$i]['COUNT']).",".$db->qstr($details[$i]['PRICE']).",".$db->qstr($details[$i]['SUB_TOTAL']).")";
	
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;	
		}
	
		if($wo_id != '') {
			/* insert into Invoice Parts */
			$q = "INSERT INTO ".PRFX."TABLE_INVOICE_PARTS SET
			INVOICE_ID 						=".$db->qstr($invoice_id).",
			INVOICE_PARTS_MANUF			=".$db->qstr($details[$i]['VENDOR']).", 
			INVOCIE_PARTS_MFID				=".$db->qstr($details[$i]['SKU']).",
			INVOICE_PARTS_DESCRIPTION		=".$db->qstr($details[$i]['DESCRIPTION']).",
			INVOICE_PARTS_AMOUNT			=".$db->qstr($details[$i]['PRICE']).",
			INVOICE_PARTS_SUBTOTA			=".$db->qstr($details[$i]['SUB_TOTAL']).", 
			INVOICE_PARTS_COUNT			=".$db->qstr($details[$i]['COUNT']);
	
			if(!$rs = $db->execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
				exit;	
			}
		}
		$i++;
	}

	
	
	/* clear cart (scope to this work order when available) */
	if ($workorder_id !== '' && (int)$workorder_id > 0) {
		$q = "DELETE FROM ".PRFX."CART WHERE WO_ID=".$db->qstr((int)$workorder_id);
	} else {
		$q = "TRUNCATE TABLE ".PRFX."CART";
	}
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	/* assign smarty and display page */

	$q = "SELECT * FROM ".PRFX."TABLE_COMPANY";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	$arr = $rs->GetArray();
	$smarty->assign('customer', $arr);

	if(!xml2php("parts")) {
	$smarty->assign('error_msg',"Error in language file");
	}
	
	/* get CRM ORDER details */
	$q = "SELECT * FROM ".PRFX."ORDERS WHERE  INVOICE_ID=".$db->qstr($crm_invoice_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	$invoice_details = array('ORDER_ID'=>$rs->fields['INVOICE_ID'],
									'CART_TOTAL'=>$rs->fields['SUB_TOTAL'],
									'SHIPPING'=>$rs->fields['SHIPPING'],
									'TAX'=>'0.00'	,
									'TOTAL'=>$rs->fields['TOTAL'],
									'WEIGHT'=>$rs->fields['WEIGHT'],
									'TOTAL_ITEMS'=>$rs->fields['ITEMS'],
									'WORKORDER'=>$rs->fields['WO_ID'], 
									'DATE'=>time());
	$smarty->assign('invoice_details',$invoice_details);	
	$smarty->assign('details',$details);

	$smarty->display('parts'.SEP.'results.tpl');
?>
