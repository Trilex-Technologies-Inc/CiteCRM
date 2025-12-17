<?php
#######################################################
# 			Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM					#
#  www.citecrm.com  dev@onsitecrm.com						#
#  This program is distributed under the terms and 		#
#  conditions of the GPL											#
#  New Customer 													#
#  Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005			#
#																		#
#######################################################
require_once ("include.php");
if(!xml2php("customer")) {
	$smarty->assign('error_msg',"Error in language file");
}
if(isset($VAR['submit'])) {

	if (!check_customer_ex($db, $VAR['displayName'])){
			$smarty->assign('VAR', $VAR);
			$smarty->assign('error_msg', 'The customer Display Name, '.$VAR["displayName"].',  already exists! Please use a differnt name.');
			$smarty->display('customer'.SEP.'new.tpl');
		} else {
			if (!$customer_id = insert_new_customer($db,$VAR)){
				$smarty->assign('error_msg', 'Falied to insert customer');
				$smarty->display('core'.SEP.'error.tpl');
			} else {
				force_page('customer', 'customer_details&customer_id='.$customer_id.'&msg=Added New Customer '.$VAR["displayName"].' &page_title='.$VAR["displayName"]);
				exit;	
			}
			
		}
	
} else {
	
	$smarty->display('customer'.SEP.'new.tpl');

}


	


?>