<?php
#####################################################
# 	Cite CRM	Customer Relations Management		#	
#	Copyright (C) 2003 - 2005 In-Site CRM			#
#	www.citecrm.com  dev@onsitecrm.com				#
#	This program is distributed under the terms and #
#	conditions of the GPL							#
#	new.php											#
#	Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005	#
#													#
#####################################################
/* ex: set tabstop=4 */

######################################
# Insert New Schedual  				 #
######################################
function insert_new_schedual($db, $VAR){
	global $smarty;
	$wo_id = $VAR['wo_id'];

	// Extract date components
	list($s_year, $s_month, $s_day) = explode('-', $VAR['start']['schedual_date']);
	list($e_year, $e_month, $e_day) = explode('-', $VAR['end']['schedual_date']);

	// Extract time components
	$s_hour = (int)$VAR['start']['Time_Hour'];
	$s_min  = (int)$VAR['start']['Time_Minute'];
	$e_hour = (int)$VAR['end']['Time_Hour'];
	$e_min  = (int)$VAR['end']['Time_Minute'];

	$s_meridian = strtolower($VAR['start']['Time_Meridian']);
	$e_meridian = strtolower($VAR['end']['Time_Meridian']);

	// Convert to 24-hour format
	if($s_meridian == 'pm' && $s_hour != 12) $s_hour += 12;
	if($s_meridian == 'am' && $s_hour == 12) $s_hour = 0;

	if($e_meridian == 'pm' && $e_hour != 12) $e_hour += 12;
	if($e_meridian == 'am' && $e_hour == 12) $e_hour = 0;

	// Create timestamps
	$start_time = mktime($s_hour, $s_min, 0, $s_month, $s_day, $s_year);
	$end_time   = mktime($e_hour, $e_min, 0, $e_month, $e_day, $e_year);

	// Check if start and end are valid
	if($start_time > $end_time){
		$smarty->assign('error_msg','Schedule Ends Before It Starts.');
		return false;
	}

	if($start_time == $end_time){
		$smarty->assign('error_msg','Start Time and End Time are the Same');
		return false;
	}

	// Get the day's schedule for the employee
	$db_start = mktime(0,0,0,$s_month,$s_day,$s_year);
	$db_end   = mktime(23,59,59,$s_month,$s_day,$s_year);

	$q = "SELECT SCHEDUAL_START, SCHEDUAL_END, SCHEDUAL_ID
          FROM ".PRFX."TABLE_SCHEDUAL
          WHERE SCHEDUAL_START >= $db_start
            AND SCHEDUAL_END <= $db_end
            AND EMPLOYEE_ID = '".$VAR['tech']."'
          ORDER BY SCHEDUAL_START ASC";

	if(!$rs = $db->Execute($q)){
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

	// Check for schedule overlaps
	while (!$rs->EOF){
		if($start_time >= $rs->fields["SCHEDUAL_START"] && $start_time <= $rs->fields["SCHEDUAL_END"]){
			$smarty->assign('error_msg','Start Time Starts before Another Schedule Ends');
			return false;
		}

		if($end_time >= $rs->fields["SCHEDUAL_START"] && $start_time <= $rs->fields["SCHEDUAL_START"]){
			$smarty->assign('error_msg','End Time runs into Next Schedule');
			return false;
		}

		$rs->MoveNext();
	}

	// Update work order if exists
	if($wo_id != 0){
		$q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET 
              WORK_ORDER_ASSIGN_TO = ".$db->qstr($VAR['tech']).",
              WORK_ORDER_CURENT_STATUS = ".$db->qstr(2).",
              LAST_ACTIVE = ".$db->qstr(time())."
              WHERE WORK_ORDER_ID = ".$db->qstr($wo_id);

		if(!$db->Execute($q)){
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

		// Get employee display name
		$q = "SELECT EMPLOYEE_DISPLAY_NAME FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_ID = ".$db->qstr($VAR['tech']);
		if(!$rs = $db->Execute($q)){
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().' SQL: '.$q.'&menu=1&type=database');
			exit;
		}
		$tech = $rs->fields['EMPLOYEE_DISPLAY_NAME'];

		// Update work order status notes
		$msgs = [
			"Work Order Assigned to ".$tech,
			"Schedule Has Been Set."
		];
		foreach($msgs as $msg){
			$q = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
                  WORK_ORDER_ID = ".$db->qstr($wo_id).",
                  WORK_ORDER_STATUS_NOTES = ".$db->qstr($msg).",
                  WORK_ORDER_STATUS_ENTER_BY = ".$db->qstr($_SESSION['login_id']).",
                  WORK_ORDER_STATUS_DATE = ".$db->qstr(time());
			$db->Execute($q);
		}

		// Check if schedule exists
		$q = "SELECT count(*) as count FROM ".PRFX."TABLE_SCHEDUAL WHERE WORK_ORDER_ID='".$wo_id."'";
		$rs = $db->Execute($q);
		$count = $rs->fields['count'];

		if($count != 0){
			$sql = "UPDATE ".PRFX."TABLE_SCHEDUAL SET ";
			$where = " WHERE WORK_ORDER_ID='".$wo_id."'";
		} else {
			$sql = "INSERT INTO ".PRFX."TABLE_SCHEDUAL SET ";
			$where = '';
		}
	} else {
		$sql = "INSERT INTO ".PRFX."TABLE_SCHEDUAL SET ";
		$where = '';
	}

	// Build final schedule query
	$sql .= "SCHEDUAL_START = '".$start_time."',
             SCHEDUAL_END   = '".$end_time."',
             WORK_ORDER_ID  = '".$VAR['wo_id']."',
             EMPLOYEE_ID    = '".$VAR['tech']."',
             SCHEDUAL_NOTES = '".$VAR['schedaul_notes']."'"
		.$where;

	if(!$db->Execute($sql)){
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

	return true;
}


######################################
# View New Schedual  				 #
######################################
function view_schedual($db, $sch_id) {

	$q = "SELECT ".PRFX."TABLE_SCHEDUAL.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME FROM ".PRFX."TABLE_SCHEDUAL 
				LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON (".PRFX."TABLE_SCHEDUAL.EMPLOYEE_ID=".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID )
				WHERE SCHEDUAL_ID='".$sch_id."'";

	if(!$rs = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

	$arr = $rs->GetAll();
	return $arr;

}

######################################
# Tech List  						 #
######################################	
function display_tech($db){
	$sql = "SELECT  EMPLOYEE_ID, EMPLOYEE_LOGIN FROM ".PRFX."TABLE_EMPLOYEE";
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

	$tech_array = $result->GetArray();
	return $tech_array;
}

?>
