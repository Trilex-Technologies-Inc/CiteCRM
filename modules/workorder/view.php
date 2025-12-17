<?php
#####################################################
# Cite CRM	Customer Relations Management			#	
# Copyright (C) 2003 - 2005 In-Site CRM				#
# www.citecrm.com  dev@onsitecrm.com				#
# This program is distributed under the terms and 	#
# conditions of the GPL								#
# index.php											#
# Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005		#
#													#
#####################################################
require_once("include.php");

if(!xml2php("workorder")) {
	$smarty->assign('error_msg',"Error in language file");
}

$wo_id = $VAR['wo_id'];

/* check for open part Orders */
$q = "SELECT count(*) as count  FROM ".PRFX."ORDERS WHERE WO_ID=".$db->qstr($wo_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
$smarty->assign('part', $rs->fields['count']);

if(!$single_work_order = display_single_open_workorder($db, $VAR['wo_id'])){
	force_page('core', "error&menu=1&error_msg=The Work Order you Requested was not found&type=error");
	exit;
}
	$smarty->assign('single_workorder_array', 	$single_work_order);
	$smarty->assign('work_order_notes', 			display_workorder_notes($db, $VAR['wo_id']));
	$smarty->assign('order',							display_parts($db,$VAR['wo_id'])				);;				
	$smarty->assign('work_order_status', 			display_workorder_status($db, $VAR['wo_id']));
	$smarty->assign('work_order_sched', 			get_work_order_schedual ($db,$VAR['wo_id']));	
	$smarty->assign('resolution', 					display_resolution($db,$VAR['wo_id']));


$smarty->display('workorder'.SEP.'view.tpl');
?>