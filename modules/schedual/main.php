<?php
#########################################################
# 			Cite CRM	Customer Relations Management	#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.citecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 		#
#  conditions of the GPL								#
#  schedual.php											#
#  Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005		#
#														#
#########################################################
require('include.php');
if(!xml2php("schedual")) {
	$smarty->assign('error_msg',"Error in language file");
}
/* load the date formate from the js calendar */
$wo_id = $_GET['wo_id'];

/* check if work order closed we don't want to reschedual a work order if it's closed */
if(isset($wo_id)) {
	$q = "SELECT WORK_ORDER_CURENT_STATUS FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;	
	} else {
		$status = $rs->fields['WORK_ORDER_CURENT_STATUS'];
	}
	
	
	if($status == '6') {
		force_page('workorder', 'view&wo_id='.$wo_id.'&error_msg=Can not set a schedual for closed work order&page_title=Work Order ID '.$wo_id.'&type=warning');
	} elseif ($status == '7') {
		force_page('workorder', 'view&wo_id='.$wo_id.'&error_msg=Can not set a schedual for closed work order&page_title=Work Order ID '.$wo_id.'&type=warning');
	} elseif ($status == '8') {
		force_page('workorder', 'view&wo_id='.$wo_id.'&error_msg=Can not set a schedual for closed work order&page_title=Work Order ID '.$wo_id.'&type=warning');
	} elseif ($status == '9') {
		force_page('workorder', 'view&wo_id='.$wo_id.'&error_msg=Can not set a schedual for closed work order&page_title=Work Order ID '.$wo_id.'&type=warning');
	}
		
}


$y = $VAR['y'] ;
$m = $VAR['m'];
$d = $VAR['d'];
$cur_date = $m."/".$d."/".$y; 



$date_array = array('y'=>$y, 'm'=>$m, 'd'=>$d, 'wo_id'=>$wo_id);
$smarty->assign('date_array',$date_array);

/* load start time from setup */
$q = "SELECT OFFICE_HOUR_START,OFFICE_HOUR_END FROM ".PRFX."SETUP";
if(!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$H = $rs->fields['OFFICE_HOUR_START'];
$E = $rs->fields['OFFICE_HOUR_END'];

if(empty($H) || empty($E)) {
	force_page('core', 'error&error_msg=You must first set a start and stop times in the control center');
	exit;
}

/* get the curent login */ 
if(!isset($VAR['tech'])) {
	$tech = $_SESSION['login_id'];
} else {
	$tech = $VAR['tech'];
}

/* get list of techs for dropdown*/
$tech_array =  display_tech($db);
$smarty->assign('selected', $tech);
$smarty->assign('tech',$tech_array);
$smarty->assign('y',$y);
$smarty->assign('m',$m);
$smarty->assign('d',$d);

/* this will be pulled from the database latter */
$business_start = mktime($H,0,0,$m,$d,$y);
$business_end = mktime($E,0,0,$m,$d,$y);


/* look in the database for a schedualed event and build the calander */	
	$q = "SELECT * FROM ".PRFX."TABLE_SCHEDUAL WHERE SCHEDUAL_START >= " . $business_start. " AND SCHEDUAL_START  <= " .$business_end. "
			AND  EMPLOYEE_ID ='".$tech."' ORDER BY SCHEDUAL_START ASC";
	if(!$rs = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
	}
	
	$sch = array();
	while (!$rs->EOF ){
		array_push($sch, array(
				 "SCHEDUAL_ID"		=> $rs->fields["SCHEDUAL_ID"],
				 "SCHEDUAL_START"	=> $rs->fields["SCHEDUAL_START"],
				 "SCHEDUAL_END"		=> $rs->fields["SCHEDUAL_END"],	
				 "SCHEDUAL_NOTES"	=> $rs->fields["SCHEDUAL_NOTES"],
				 "WORK_ORDER_ID"	=> $rs->fields["WORK_ORDER_ID"]
				 ));
		$rs->MoveNext();
	}
		


/* start the calendar var */
$calendar .= "<table  cellpadding=\"0\" cellspacing=\"0\"  class=\"olotable\">\n
		<tr>\n
			<td class=\"olohead\" width=\"75\">&nbsp;</td>\n
			<td class=\"olohead\" width=\"600\">&nbsp;</td>\n
		</tr>\n"	;

$i = 0;
$start = mktime($H,0,0,$m,$d,$y);

while($start <= $business_end){

	if(date("i",$start) == 0) {
	
		$calendar .= "<tr><td class=\"olotd\" nowrap>&nbsp;<b>".date("h:i a", $start)."</b></td>\n";
		
		if($start >= $sch[$i]['SCHEDUAL_START'] && $start <= $sch[$i]['SCHEDUAL_END']){
		
			if($start == $sch[$i]['SCHEDUAL_START']){
				
					if($sch[$i]['WORK_ORDER_ID'] != 0) {
						$calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=workorder:view&wo_id=".$sch[$i]['WORK_ORDER_ID']."page_title=Work Order ID ".$sch[$i]['WORK_ORDER_ID ']."'\"><b>\n";
						$calendar .= "Work Order ID ". $sch[$i]['WORK_ORDER_ID']." From: ".date("h:i a",$sch[$i]['SCHEDUAL_START'])." To: ".date("h:i a",$sch[$i]['SCHEDUAL_END'])." ".$sch[$i]['SCHEDUAL_NOTES']."\n";
						$calendar . "</b></td>\n";
					} else {
						$calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=schedual:view&sch_id=".$sch[$i]['SCHEDUAL_ID']."&y=".$y."&m=".$m."&d=".$d."'\">";
						$calendar .= "<b>From: ".date("h:i a",$sch[$i]['SCHEDUAL_START'])." To: ".date("h:i a",$sch[$i]['SCHEDUAL_END']).' '.$sch[$i]['SCHEDUAL_NOTES']."\n";
						$calendar .= "</b></td>\n";
					}
					
			} else {
				$calendar .= "<td class=\"menutd2\">&nbsp;</td>\n";
			}
			
		} else {
		
			$calendar .= "<td class=\"olotd\" onClick=\"window.location='?page=schedual:new&starttime=".date("h:i a", $start)."&day=".$cur_date."&wo_id=".$wo_id."&tech=".$tech."'\"></td>\n";
		}
		
		$calendar .= "</tr>";
	} else {
		$calendar .= "<tr>\n<td></td>\n";
		
		if($start >= $sch[$i]['SCHEDUAL_START'] && $start <= $sch[$i]['SCHEDUAL_END']){
		
			if($start == $sch[$i]['SCHEDUAL_START']) {
			
				if($sch[$i]['WORK_ORDER_ID'] != 0) {
					$calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=workorder:view&wo_id=".$sch[$i]['WORK_ORDER_ID']."page_title=Work Order ID ".$sch[$i]['WORK_ORDER_ID ']."'\"><b>\n";
					$calendar .= "Work Order ID ". $sch[$i]['WORK_ORDER_ID']." From: ".date("h:i a",$sch[$i]['SCHEDUAL_START'])." To: ".date("h:i a",$sch[$i]['SCHEDUAL_END'])." ".$sch[$i]['SCHEDUAL_NOTES']."\n";
					$calendar . "</b></td>\n";
				} else {
					$calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=schedual:view&sch_id=".$sch[$i]['SCHEDUAL_ID']."&y=".$y."&m=".$m."&d=".$d."'\">";
					$calendar .= "<b>From: ".date("h:i a",$sch[$i]['SCHEDUAL_START'])." To: ".date("h:i a",$sch[$i]['SCHEDUAL_END']).' '.$sch[$i]['SCHEDUAL_NOTES']."\n";
					$calendar .= "</b></td>\n";
				}
					
			}  else {
				$calendar .= "<td class=\"menutd2\"><br></td>\n</tr>";
			}
			
		} else {
			$calendar .= "<td class=\"olotd4\" onClick=\"window.location='?page=schedual:new&starttime=".date("h:i a", $start) ."&day=".$cur_date."&wo_id=".$wo_id."&tech=".$tech."'\">&nbsp; ".date("h:i a", $start)."</td>\n</tr>";
		}
		
	}

	if($start == $sch[$i]['SCHEDUAL_END']) {
		$i++;
	}
	$start = mktime(date("H",$start),date("i",$start)+15,0,$m,$d,$y);
	
		
}

$calendar .= "\n</table>";
/* get employee Display Name */

/* feed smarty */
$smarty->assign('calendar', $calendar);
$smarty->assign('cur_date', $cur_date);
$smarty->display('schedual'.SEP.'main.tpl');


?>
