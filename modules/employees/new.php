<?php
#########################################################
# 			Cite CRM	Customer Relations Management	#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.citecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 		#
#  conditions of the GPL								#
#  new.php												#
#  Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005		#
#														#
#########################################################
require_once("include.php");
if(!xml2php("employees")) {
	$smarty->assign('error_msg',"Error in language file");
}
$VAR['page_title'] = "Add New Employee";
 
if(isset($VAR['submit'])) {
	$smarty->assign('VAR', $VAR);
	$smarty->assign('employee_type', employee_type($db));
	
	if (!check_employee_ex($db,$VAR)) {
			$smarty->assign('error_msg', 'The employees Display Name, '.$VAR["displayName"].',  already exists! Please use a differnt name.');
			$smarty->display('employees'.SEP.'new.tpl');
		} else {
			if (!$employee_id = insert_new_employee($db,$VAR)){
				$smarty->assign('error_msg', 'Falied to insert Employee');
			} else {
				force_page('employees', 'employee_details&employee_id='.$employee_id.'&page_title=Employees');	
			}
			
		}

} else {

	$smarty->assign('employee_type', employee_type($db));
	$smarty->display('employees'.SEP.'new.tpl');

}


?>
