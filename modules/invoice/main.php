<?php
####################################
# 			Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM							#
#  www.citecrm.com  dev@onsitecrm.com						#
#  This program is distributed under the terms and 			#
#  conditions of the GPL													#
#  main.php																		#
#  Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005				#
#																						#
####################################

require_once ('include.php');

/* Get the page number we are on if first page set to 1 */
	if(!isset($VAR['page_no']))
	{
		$page_no = 1;
	} else {
		$page_no = $VAR['page_no'];
	}
	
/* assign the smarty array */	
$smarty->assign('invoice_array', display_open_invoice($db, $page_no));
$smarty->display("workorder'.SEP.'main.tpl");


?>

