<?php
#####################################################
# Cite CRM	Customer Relations Management			#	
# Copyright (C) 2003 - 2005 In-Site CRM				#
# www.citecrm.com  dev@onsitecrm.com				#
# This program is distributed under the terms and 	#
# conditions of the GPL								#
# customer_details.php								#
# Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005		#
#													#
#####################################################
require_once("include.php");
if(!xml2php("customer")) {
	$smarty->assign('error_msg',"Error in language file");
}
// Get the customers id from the url
$customer_id = $VAR['customer_id'];

// assign the arrays
$smarty->assign('open_work_orders',	display_open_workorders($db, $customer_id));
$smarty->assign('customer_details',	display_customer_info($db, $customer_id));
$smarty->assign('unpaid_invoices',		display_unpaid_invoices($db,$customer_id));
$smarty->assign('paid_invoices',		display_paid_invoices($db,$customer_id));
$smarty->assign('memo',					display_memo($db,$customer_id));
$smarty->assign('gift', 					display_gift($db, $customer_id));

$smarty->display('customer'.SEP.'customer_details.tpl');
?>
