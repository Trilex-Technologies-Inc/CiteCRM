<?php
#########################################################
# 			Cite CRM	Customer Relations Management	#
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.citecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 		#
#  conditions of the GPL								#
#  delete.php 											#
#  Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005		#
#														#
#########################################################
require('include.php');
$customer_id = $VAR['customer_id'];


/* make sure we got an ID number */
if(!isset($customer_id) || $customer_id =="") { 
	$smarty->assign('results', 'Please go back and select a customer');
	die;
}	

$q = "SELECT count(*) as count FROM ".PRFX."TABLE_WORK_ORDER WHERE CUSTOMER_ID=".$db->qstr($customer_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	
	if($rs->fields['count'] > 0 ) {
		force_page('customer', 'view&page_title=Customers&error_msg=You can not delete a customer who has work history.');
		exit;
	} else {
		/* run the function and return the results */
		if(!delete_customer($db,$customer_id)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;	
		} else {
			force_page('customer', 'view&page_title=Customers');
			exit;
		}
	}

?>