<?php
####################################################
# IN Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Unpaid Invoices												#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################

#########################################
# 			Dipslay Open Invoice		#
#########################################

function display_open_invoice($db,$page_no,$smarty) {
	
global $smarty;
	
	// Define the number of results per page
	$max_results = 10;
	
	// Figure out the limit for the Execute based
	// on the current page number.
	$from = (($page_no * $max_results) - $max_results);  
	
	$sql = "SELECT ".PRFX."TABLE_INVOICE.*,
				".PRFX."TABLE_CUSTOMER.*,
				".PRFX."TABLE_EMPLOYEE.*
			FROM ".PRFX."TABLE_INVOICE 
				LEFT JOIN ".PRFX."TABLE_CUSTOMER ON (".PRFX."TABLE_INVOICE.CUSTOMER_ID = ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID)
				LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON (".PRFX."TABLE_INVOICE.EMPLOYEE_ID = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID)
			WHERE INVOICE_PAID=".$db->qstr(0)." ORDER BY INVOICE_ID LIMIT $from, $max_results";

	if(!$rs = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;	
	} else {
		$invoice_arr = $rs->GetArray();
	}
	// Figure out the total number of results in DB: 
	$q = "SELECT COUNT(*) as Num FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_PAID=".$db->qstr(0);

	if(!$results = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	

	if(!$total_results = $results->FetchRow()) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$smarty->assign('total_results', $total_results['Num']);
	}
		
	// Figure out the total number of pages. Always round up using ceil()
	$total_pages = ceil($total_results["Num"] / $max_results); 
	$smarty->assign('total_pages', $total_pages);
	
	// Assign the first page
	if($page_no > 1) {
    	$prev = ($page_no - 1);	 
	} 	

	// Build Next Link
	if($page_no < $total_pages){
    	$next = ($page_no + 1); 
	}


	$smarty->assign('name', $name);
	$smarty->assign('page_no', $page_no);
	$smarty->assign("previous", $prev);	
	$smarty->assign("next", $next);
	return $invoice_arr;
}

########################################
# Paid Invocies									#
########################################
function display_paid_invoice($db,$page_no,$smarty) {
	
global $smarty;
	
	// Define the number of results per page
	$max_results = 10;
	
	// Figure out the limit for the Execute based
	// on the current page number.
	$from = (($page_no * $max_results) - $max_results);  
	
	$sql = "SELECT ".PRFX."TABLE_INVOICE.*,
				".PRFX."TABLE_CUSTOMER.*,
				".PRFX."TABLE_EMPLOYEE.*
			FROM ".PRFX."TABLE_INVOICE 
				LEFT JOIN ".PRFX."TABLE_CUSTOMER ON (".PRFX."TABLE_INVOICE.CUSTOMER_ID = ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID)
				LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON (".PRFX."TABLE_INVOICE.EMPLOYEE_ID = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID)
			WHERE INVOICE_PAID=".$db->qstr(1)." ORDER BY INVOICE_ID LIMIT $from, $max_results";

	if(!$rs = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$invoice_arr = $rs->GetArray();
	}
	
	// Figure out the total number of results in DB: 
	$q = "SELECT COUNT(*) as Num FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_PAID=".$db->qstr(1);
	if(!$results = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}


	if(!$total_results = $results->FetchRow()) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$smarty->assign('total_results', $total_results['Num']);
	}
		
	// Figure out the total number of pages. Always round up using ceil()
	$total_pages = ceil($total_results["Num"] / $max_results); 
	$smarty->assign('total_pages', $total_pages);
	
	// Assign the first page
	if($page_no > 1) {
    	$prev = ($page_no - 1);	 
	} 	

	// Build Next Link
	if($page_no < $total_pages){
    	$next = ($page_no + 1); 
	}


	$smarty->assign('name', $name);
	$smarty->assign('page_no', $page_no);
	$smarty->assign("previous", $prev);	
	$smarty->assign("next", $next);
	return $invoice_arr;
}

?>