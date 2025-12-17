<?php
####################################################
# IN Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Control Edit Billing Rates								#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################
if(isset($VAR['submit'])) {
	/* edit */
	if($VAR['submit'] == 'Edit') {
		$q = "UPDATE ".PRFX."TABLE_LABOR_RATE SET
				LOABOR_RATE_NAME	=". $db->qstr($VAR['display']) .",
				LABOR_RATE_AMOUT	=". $db->qstr($VAR['amount']) .",
				LABOR_RATE_ACTIVE 	=". $db->qstr($VAR['active']) ."
				WHERE LABOR_RATE_ID =".$db->qstr($VAR['id']);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
	}

	/* delete */
	if($VAR['submit'] == 'Delete') {
		$q="DELETE FROM ".PRFX."TABLE_LABOR_RATE WHERE LABOR_RATE_ID =".$db->qstr($VAR['id']);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

	}

	/* New */
	if($VAR['submit'] == 'New') {
		$q = "INSERT INTO ".PRFX."TABLE_LABOR_RATE SET
			 	LOABOR_RATE_NAME	=". $db->qstr($VAR['display']) .",
				LABOR_RATE_AMOUT	=". $db->qstr($VAR['amount']) .",
				LABOR_RATE_ACTIVE 	=". $db->qstr(1);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
	}

	/* back to labor edit */
	force_page('control', 'edit_rate&page_title=Edit Billing Rates');
	exit;
} else {
	$q = "SELECT * FROM ".PRFX."TABLE_LABOR_RATE";
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
	
	$arr = $rs->GetArray();
	$smarty->assign('rate', $arr);
	$smarty->display('control'.SEP.'edit_rate.tpl');
}
?>