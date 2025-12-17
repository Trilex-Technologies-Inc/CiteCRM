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
	function insert_new_schedual($db,$VAR){
		global $smarty;
		$wo_id = $VAR['wo_id'];
		list($s_month, $s_day, $s_year) = split('[/.-]', $VAR['start']['schedual_date']);
		list($e_month, $e_day, $e_year) = split('[/.-]', $VAR['end']['schedual_date']);
		
		$s_hour = $VAR['start']['Time_Hour'];
		$s_min  = $VAR['start']['Time_Minute'];
		$s_med  = $VAR['start']['Time_Meridian'];
		
		$e_hour = $VAR['end']['Time_Hour'];
		$e_min  = $VAR['end']['Time_Minute'];
		$e_med  = $VAR['end']['Time_Meridian'];
		
		$secs   = 00;

		$start_time = strtotime("$s_month/$s_day/$s_year $s_hour:$s_min:$secs $s_med");
		$end_time   = strtotime("$e_month/$e_day/$e_year $e_hour:$e_min:$secs $e_med");
		
		/* check for stupid*/
		if($start_time > $end_time) {
			$error_msg  = 'Schedual Ends Before It Starts.';
			$smarty->assign('error_msg',$error_msg);
			return false;
		}
		
		if($start_time == $end_time) {
			$error_msg = 'Start Time and End Time are the Same';
			$smarty->assign('error_msg',$error_msg);
			return false;
		}
		
		/*get todays schedual*/
		$db_start = mktime(0,0,0,date("m",$start_time),date("d",$start_time),date("Y",$start_time));
		$db_end   = mktime(23,59,59,date("m",$start_time),date("d",$start_time),date("Y",$start_time));
		
		$q = "SELECT  SCHEDUAL_START,SCHEDUAL_END, SCHEDUAL_ID  FROM ".PRFX."TABLE_SCHEDUAL WHERE SCHEDUAL_START >= ".$db_start." AND SCHEDUAL_END <=".$db_end." AND  EMPLOYEE_ID ='".$VAR['tech']."' ORDER BY SCHEDUAL_START ASC";
		//print $q;
		
		if(!$rs = $db->Execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
		
		
		$counter = 1;

		while (!$rs->EOF ){
			//print $start_time . '>= '.$rs->fields["SCHEDUAL_START"].' AND '.$start_time <= $rs->fields["SCHEDUAL_END"].'<br>';

			/* Check if start time starts when another is already set */
			if($start_time >= $rs->fields["SCHEDUAL_START"] && $start_time <= $rs->fields["SCHEDUAL_END"]) {
				$error_msg = 'Start Time Starts before Another Schedual Ends<br>';
				$smarty->assign('error_msg',$error_msg);	
				return false;
			}
			
			/* Check if start time starts befor one ends */

			//print $end_time.' >= '.$rs->fields["SCHEDUAL_START"].' && '.$start_time.' <= '.$rs->fields["SCHEDUAL_START"].'<br>';
			if($end_time >= $rs->fields["SCHEDUAL_START"] && $start_time <= $rs->fields["SCHEDUAL_START"]) {
			
				$error_msg = "End Time runs into Next Schedual";
				$smarty->assign('error_msg',$error_msg);	
				return false;
			}
			
			$rs->MoveNext();
		}

		if($wo_id != 0 ) {
		
			/* Update work order and assign to tech */
			$q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET 
				  WORK_ORDER_ASSIGN_TO		=".$db->qstr($VAR['tech']).",  	  
				  WORK_ORDER_CURENT_STATUS	=".$db->qstr(2).",
				  LAST_ACTIVE 				=".$db->qstr(time())."  
				  WHERE  WORK_ORDER_ID=".$db->qstr($VAR['wo_id']);

			if(!$rs = $db->Execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
				exit;
			}	

			/* get employee ID and Login */
			$q = "SELECT EMPLOYEE_DISPLAY_NAME FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_ID=".$db->qstr($VAR['tech']);
			
			if(!$rs = $db->execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().' SQL: '.$q.'&menu=1&type=database');
				exit;
			} else {
				$tech = $rs->fields['EMPLOYEE_DISPLAY_NAME'];
			}
			
			
			/* update Notes */
			$msg ="Work Order Assigned to ".$tech;
			$q = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
				  WORK_ORDER_ID					= ".$db->qstr($VAR['wo_id']).",
				  WORK_ORDER_STATUS_NOTES  	= ".$db->qstr($msg).",
				  WORK_ORDER_STATUS_ENTER_BY 	= ".$db->qstr($_SESSION['login_id']).",
				  WORK_ORDER_STATUS_DATE  		= ".$db->qstr(time());
			if(!$rs = $db->Execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
				exit;
			}
				  
			/* update Notes */
			$msg ="Schedaul Has Been Set.";
			$q = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
				  WORK_ORDER_ID					= ".$db->qstr($VAR['wo_id']).",
				  WORK_ORDER_STATUS_NOTES  	= ".$db->qstr($msg).",
				  WORK_ORDER_STATUS_ENTER_BY  	= ".$db->qstr($_SESSION['login_id']).",
				  WORK_ORDER_STATUS_DATE  		= ".$db->qstr(time());
			if(!$rs = $db->Execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
				exit;
			}	  
				  
			/* build query */
			$q = "SELECT count(*) as count FROM ".PRFX."TABLE_SCHEDUAL WHERE WORK_ORDER_ID='".$wo_id."'";
			if(!$rs = $db->execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
				exit;
			}
			
			$count = $rs->fields['count'];
			
			if($count != 0) {
				$sql = "UPDATE ".PRFX."TABLE_SCHEDUAL SET ";
				$where = " WHERE WORK_ORDER_ID='".$wo_id."'";
			} else {
				$sql = "INSERT INTO ".PRFX."TABLE_SCHEDUAL SET ";
			}
		} else {
			$sql = "INSERT INTO ".PRFX."TABLE_SCHEDUAL SET ";
		}	
		  
		
		$sql .="SCHEDUAL_START	= '".$start_time."',
				 SCHEDUAL_END		= '".$end_time."',
				 WORK_ORDER_ID		= '".$VAR['wo_id']."',
				 EMPLOYEE_ID			= '".$VAR['tech']."',
				 SCHEDUAL_NOTES		= '".$VAR['schedaul_notes']."'
				" .$where;
	
		if(!$rs = $db->Execute($sql)) {
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
