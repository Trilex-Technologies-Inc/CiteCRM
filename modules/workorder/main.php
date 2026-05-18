<?php
#####################################################
# Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM			#
#  www.citecrm.com  dev@onsitecrm.com				#
#  This program is distributed under the terms and 	#
#  conditions of the GPL							#
#  main.php											#
#  Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005	#
#													#
#####################################################
require_once('include.php');
if(!xml2php("workorder")) {
	$smarty->assign('error_msg',"Error in language file");
}

// Reassign work order (used by Awaiting Parts block)
if (isset($VAR['reassign']) && isset($VAR['wo_id']) && isset($VAR['employee_id'])) {
	$wo_id = (int)$VAR['wo_id'];
	$employee_id = (int)$VAR['employee_id'];

	if ($wo_id > 0) {
		$assign_value = ($employee_id > 0) ? $db->qstr($employee_id) : "NULL";

		$q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
				WORK_ORDER_ASSIGN_TO = ".$assign_value.",
				LAST_ACTIVE = ".$db->qstr(time())."
			  WHERE WORK_ORDER_ID = ".$db->qstr($wo_id);
		if(!$db->Execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

		$assignee_name = 'Unassigned';
		if ($employee_id > 0) {
			$q = "SELECT EMPLOYEE_DISPLAY_NAME FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_ID = ".$db->qstr($employee_id)." LIMIT 1";
			if(!$rs = $db->Execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
				exit;
			}
			if (!empty($rs->fields['EMPLOYEE_DISPLAY_NAME'])) {
				$assignee_name = $rs->fields['EMPLOYEE_DISPLAY_NAME'];
			}
		}

		insert_new_status($db, array(
			'wo_id' => $wo_id,
			'work_order_status_notes' => 'Work Order reassigned to '.$assignee_name
		));
		$smarty->assign('reassign_msg', 'Work Order '.$wo_id.' assigned to '.$assignee_name);
	}
}

// Employee list for quick reassignment dropdowns
$q = "SELECT EMPLOYEE_ID, EMPLOYEE_DISPLAY_NAME FROM ".PRFX."TABLE_EMPLOYEE ORDER BY EMPLOYEE_DISPLAY_NAME";
if(!$rs = $db->Execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$smarty->assign('employee_list', $rs->GetArray());

// Get the page number we are on if first page set to 1
	if(!isset($VAR["page_no"])) {
		$page_no = 1;
	} else {
		$page_no = $VAR['page_no'];
	}	

/* display new Workorders */	
$where = "WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURENT_STATUS= ".$db->qstr(1);
$smarty->assign('new', display_workorders($db, $page_no, $where));

/* display new Workorders */	
$where = "WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURENT_STATUS= ".$db->qstr(2);
$smarty->assign('assigned', display_workorders($db, $page_no, $where));

/* display new Workorders */	
$where = "WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURENT_STATUS= ".$db->qstr(3);
$smarty->assign('awaiting', display_workorders($db, $page_no, $where));

/* display work orders that need payment */
$where = "WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURENT_STATUS= ".$db->qstr(7);
$smarty->assign('payment', display_workorders($db, $page_no, $where));

//$smarty->assign('new_workorder', display_workorders($db, $page_no,$where));
$smarty->display('workorder'.SEP.'main.tpl');
?>
