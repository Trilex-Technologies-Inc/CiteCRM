<?php
####################################################
# IN Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Update Status										#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################
require_once ("include.php");

if(empty($VAR['wo_id'])){
	force_page('core', 'error&error_msg=No Work Order ID');
	exit;
}
	
if(isset($VAR['submit'])){

	if (!update_status($db,$VAR)){
		force_page('core', 'error&error_msg=Falied to update work order status');
		exit;
	} else {
		force_page('workorder', 'view&wo_id='.$VAR['wo_id'].'&page_title=Work%20Order%20ID%20'.$VAR['wo_id']);
		exit;
	}

} else {
		$smarty->assign('wo_id', $VAR['wo_id']);
		$smarty->display('workorder'.SEP.'new_status.tpl');
}

?>