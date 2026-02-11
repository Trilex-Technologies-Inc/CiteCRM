<?php
#####################################################
# Cite CRM	Customer Relations Management			#
# Copyright (C) 2003 - 2005 In-Site CRM				#
# www.citecrm.com  dev@onsitecrm.com				#
# This program is distributed under the terms and 	#
# conditions of the GPL								#
# main.php											#
# Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005		#
#													#
#####################################################
if(!xml2php("workorder")) {
	$smarty->assign('error_msg',"Error in language file");
}
require_once ("include.php");


$customer_id = $VAR['customer_id'];


if (isset($VAR['submit'])) {
        $submit      = $VAR['submit'];
		if (!insert_new_workorder($db,$VAR)) {
			$smarty->display('workorder'.SEP.'new.tpl');
		} 
	
} else {	
		// Grab customers Information
		if(!isset($customer_id)){
			// redirect to customer search page
			//header ("location", "?page=customer:view");
		} else {
			$smarty->assign('customer_details', display_customer_info($db, $customer_id));
		}
		
		$smarty->display('workorder'.SEP.'new.tpl');
}
	


?>