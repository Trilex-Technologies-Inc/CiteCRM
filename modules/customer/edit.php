<?php
#######################################################
# Cite CRM	Customer Relations Management					#
# Copyright (C) 2003 - 2005 In-Site CRM						#
# www.citecrm.com  dev@onsitecrm.com							#
# This program is distributed under the terms and 		#
# conditions of the GPL											#
# edit Customer .php 												#
# Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005				#
#																		#
#######################################################
require_once ('include.php');
if(!xml2php("customer")) {
	$smarty->assign('error_msg',"Error in language file");
}

/* load customer details */
$customer_details = display_customer_info($db, $VAR['customer_id']);

if(isset($VAR['submit'])) {	
		
	if (!update_customer($db, $VAR)){
		force_page('customer', 'edit&error_msg=Falied to Update Customer Information&customer_id='.$VAR['customer_id']);
		exit;
	} else {
		force_page('customer', 'customer_details&msg=The Customers information was updated&customer_id='.$VAR['customer_id'].'&page_title='.$VAR['displayName']);
		exit;
	}

} else {	

	$smarty->assign('customer', $customer_details);
	$smarty->display('customer'.SEP.'edit.tpl');
}	
?>