<?php
####################################################
# 			Cite CRM	Customer Relations Management		#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.citecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Customer Functions											#
#  Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005		#
#																	#
####################################################
/* load translation for this module */
if(!xml2php("employees")) {
	$smarty->assign('error_msg',"Error in language file");
}

#####################################
#	Display							#
#####################################

function display_customer_info($db, $customer_id){

	$sql = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID=".$db->qstr($customer_id);
	
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$customer_array = array();
	}
	
	while($row = $result->FetchRow()){
		 array_push($customer_array, $row);
	}
	
	return $customer_array;
}


#####################################
#	Search							#
#####################################

function display_customer_search($db, $name, $page_no, $smarty) {
    global $smarty;
    
    // Define the number of results per page
    $max_results = 10;
    
    // Figure out the limit for the Execute based
    // on the current page number.
    $from = (($page_no * $max_results) - $max_results);  
    
    $sql = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_DISPLAY_NAME LIKE ". $db->qstr("$name%") ." ORDER BY CUSTOMER_DISPLAY_NAME LIMIT $from, $max_results";
    
    //print $sql;
    
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        $customer_search_result = array();
    }
    
    while($row = $result->FetchRow()){
         array_push($customer_search_result, $row);
    }
    
    // Figure out the total number of results in DB: 
    $results = $db->Execute("SELECT COUNT(*) as Num FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_DISPLAY_NAME LIKE ".$db->qstr("$name%") );
    
    if(!$total_results = $results->FetchRow()) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        $smarty->assign('total_results', $total_results['Num']);
    }
        
    // Figure out the total number of pages. Always round up using ceil()
    $total_pages = ceil($total_results["Num"] / $max_results); 
    $smarty->assign('total_pages', $total_pages);
    
    // Initialize prev and next variables
    $prev = 0;
    $next = 0;
    
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
    
    return $customer_search_result;
}

###############################
#	Open Work Orders				#
##############################

function display_open_workorders($db, $customer_id){

$sql = "SELECT ".PRFX."TABLE_WORK_ORDER.*,
			 ".PRFX."TABLE_CUSTOMER.*,
			 ".PRFX."TABLE_EMPLOYEE.*,
			 ".PRFX."TABLE_SCHEDUAL.SCHEDUAL_START,
			 ".PRFX."TABLE_SCHEDUAL.SCHEDUAL_END, 
			 ".PRFX."TABLE_SCHEDUAL.SCHEDUAL_NOTES,
			 ".PRFX."CONFIG_WORK_ORDER_STATUS.CONFIG_WORK_ORDER_STATUS
			 FROM ".PRFX."TABLE_WORK_ORDER
			 LEFT JOIN ".PRFX."TABLE_CUSTOMER ON ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID 				= ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID
			 LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ASSIGN_TO 	= ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID
			 LEFT JOIN ".PRFX."TABLE_SCHEDUAL ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID 				= ".PRFX."TABLE_SCHEDUAL.WORK_ORDER_ID
			 LEFT JOIN ".PRFX."CONFIG_WORK_ORDER_STATUS ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURENT_STATUS = ".PRFX."CONFIG_WORK_ORDER_STATUS.CONFIG_WORK_ORDER_STATUS_ID 
			 WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_STATUS ='10' AND ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID=".$db->qstr($customer_id);

	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$open_work_orders_array = $result->GetArray();
	}
	
	return $open_work_orders_array;
	
}

#####################################
#   Unpaid Invoices                 #
#####################################

function display_unpaid_invoices($db,$customer_id){
	$q = "SELECT ".PRFX."TABLE_INVOICE.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME 
			FROM ".PRFX."TABLE_INVOICE
			LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON (".PRFX."TABLE_INVOICE.EMPLOYEE_ID = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID) WHERE CUSTOMER_ID=".$db->qstr($customer_id)." AND INVOICE_PAID='0' ";
	
	if(!$rs = $db->execute($q)){
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$unpaid_invoices = $rs->GetArray();
	}
	return $unpaid_invoices;
}

###################################
#   Paid Invoices	                #
###################################

function display_paid_invoices($db,$customer_id){

	$q = "SELECT ".PRFX."TABLE_INVOICE.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME 
			FROM ".PRFX."TABLE_INVOICE
			LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON (".PRFX."TABLE_INVOICE.EMPLOYEE_ID = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID)
			WHERE CUSTOMER_ID=".$db->qstr($customer_id)." AND INVOICE_PAID='1' ";
	
	if(!$rs = $db->execute($q)){
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;	
	} else {
		$paid_invoices = $rs->GetArray();
	}
	
	return $paid_invoices;

}

#####################################
#	Validation						#
#####################################

function checkPhone($phone){

	$match =  "/^(\d{3}\-\d{3}\-\d{4})$/";
	
	if(preg_match($match, $phone)) {
		return true;
	} else {
		return false;
	}
      
}
    
function checkZip($zip){

	$match = "/[^0-9]+$/ ";
	
	if(preg_match($match, $zip)) {
		return true;
	} else {
		return false;
	}
}

#####################################
#	Duplicate						#
#####################################
	
function check_customer_ex($db, $displayName) {
	$sql = "SELECT COUNT(*) AS num_users FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_DISPLAY_NAME=".$db->qstr($displayName);
	
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$row = $result->FetchRow();
	}

	if ($row['num_users'] == 1) {
		return false;	
	} else {
		return true;
	}
}

#####################################
#	Add								#
#####################################

function insert_new_customer($db,$VAR) {

	$sql = "INSERT INTO ".PRFX."TABLE_CUSTOMER SET
			CUSTOMER_DISPLAY_NAME	= ". $db->qstr( $VAR["displayName"]  ).",
			CUSTOMER_ADDRESS		= ". $db->qstr( $VAR["address"]      ).", 
			CUSTOMER_CITY			= ". $db->qstr( $VAR["city"]         ).", 
			CUSTOMER_STATE			= ". $db->qstr( $VAR["state"]        ).", 
			CUSTOMER_ZIP				= ". $db->qstr( $VAR["zip"]          ).",
			CUSTOMER_PHONE			= ". $db->qstr( $VAR["homePhone"]    ).",
			CUSTOMER_WORK_PHONE	= ". $db->qstr( $VAR["workPhone"]    ).",
			CUSTOMER_MOBILE_PHONE	= ". $db->qstr( $VAR["mobilePhone"]  ).",
			CUSTOMER_EMAIL			= ". $db->qstr( $VAR["email"]        ).", 
			CUSTOMER_TYPE			= ". $db->qstr( $VAR["customerType"] ).", 
			CREATE_DATE				= ". $db->qstr( time()                      ).",
			LAST_ACTIVE				= ". $db->qstr( time()                      ).",
			CUSTOMER_FIRST_NAME		= ". $db->qstr( $VAR["firstName"]    ).", 
			DISCOUNT 					= ". $db->qstr( $VAR['discount']		).", 
			CUSTOMER_LAST_NAME		= ". $db->qstr( $VAR["lastName"]     ); 
			
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
    } else {
		$customer_id = $db->Insert_ID();
		return  $customer_id;
    }
	
} 

#####################################
#	Edit							#
#####################################

function edit_info($db, $customer_id){
	$sql = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID=".$db->qstr($customer_id);
	
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$row = $result->FetchRow();
		return $row;
	}
}

#####################################
#	Update							#
#####################################

function update_customer($db,$VAR) {

	$sql = "UPDATE ".PRFX."TABLE_CUSTOMER SET
			CUSTOMER_DISPLAY_NAME	= ". $db->qstr( $VAR["displayName"]	).",
			CUSTOMER_ADDRESS		= ". $db->qstr( $VAR["address"]		).", 
			CUSTOMER_CITY			= ". $db->qstr( $VAR["city"]			).", 
			CUSTOMER_STATE			= ". $db->qstr( $VAR["state"]			).", 
			CUSTOMER_ZIP				= ". $db->qstr( $VAR["zip"]				).",
			CUSTOMER_PHONE			= ". $db->qstr( $VAR["homePhone"]		).",
			CUSTOMER_WORK_PHONE	= ". $db->qstr( $VAR["workPhone"]		).",
			CUSTOMER_MOBILE_PHONE	= ". $db->qstr( $VAR["mobilePhone"]	).",
			CUSTOMER_EMAIL			= ". $db->qstr( $VAR["email"]			).", 
			CUSTOMER_TYPE			= ". $db->qstr( $VAR["customerType"]	).", 
			CUSTOMER_FIRST_NAME		= ". $db->qstr( $VAR["firstName"]		).", 
			CUSTOMER_LAST_NAME		= ". $db->qstr( $VAR["lastName"]		).",
			DISCOUNT 					= ". $db->qstr( $VAR['discount']		)."
			WHERE CUSTOMER_ID		= ". $db->qstr( $VAR['customer_id']	);
			
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
    } else {
      return true;
    }
	
} 

#####################################
#	Delete							#
#####################################

function delete_customer($db,$customer_id){
	$sql = "DELETE FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID=".$db->qstr($customer_id);
	
	if(!$rs = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;	
	} else {
		return true;
	}	
}

/* The select aray we will change this to database options later */
	$customer_type = array('Residential'=>'Residential', 'Comercial'=>'Comercial');

function display_gift($db, $customer_id) {
	$q = "SELECT * FROM ".PRFX."GIFT_CERT WHERE CUSTOMER_ID=".$db->qstr( $customer_id );
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
		
	$arr = $rs->GetArray();
	return $arr;
}

function display_memo($db,$customer_id) {
	$q = "SELECT * FROM ".PRFX."CUSTOMER_NOTES WHERE CUSTOMER_ID=".$db->qstr( $customer_id );
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
		
	$arr = $rs->GetArray();
	return $arr;
}
?>
