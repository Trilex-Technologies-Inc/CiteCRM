<?php
#####################################################
# 	Cite CRM	Customer Relations Management		#	
#	 Copyright (C) 2003 - 2005 In-Site CRM			#
#  www.citecrm.com  dev@onsitecrm.com				#
#  This program is distributed under the terms and 	#
#  conditions of the GPL							#
#  include.php										#
#  Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005	#
#													#
#####################################################
require_once ("include.php");
if(!xml2php("schedual")) {
	$smarty->assign('error_msg',"Error in language file");
}
if(isset($VAR['submit'])){


		if (!insert_new_schedual($db,$VAR)) {
				/* If db insert fails send em the error */	
				$day        = $VAR['start']['schedual_date'];
				$start_time = $VAR['start']['Time_Hour'].":".$VAR['start']['Time_Minute']." ".$VAR['start']['Time_Meridian'];
				$notes      = $VAR['schedaul_notes']; 
				$end_time   = $VAR['end']['Time_Hour'].":".$VAR['end']['Time_Minute']." ".$VAR['end']['Time_Meridian'];
				
				$smarty->assign('end_time', $end_time);
				$smarty->assign('start_day', $day);
				$smarty->assign('start_time', $start_time);
				$smarty->assign('schedaul_notes', $notes);
				$smarty->assign('tech',  $VAR['tech']);
				$smarty->assign('wo_id', $VAR['wo_id']);
				$smarty->display("schedual/new.tpl");
			} else {
				list($s_month, $s_day, $s_year) = split('[/.-]', $VAR['start']['schedual_date']);
				force_page('schedual','main&y='.$s_year.'&m='.$s_month.'&d='.$s_day.'&wo_id=0&page_title=Schedual&tech='.$VAR['tech']);
			}

	
} else {

		// Load html form to smarty
		$start_time = $VAR['starttime'];
		$day = $VAR['day'];
		$wo_id = $VAR['wo_id'];
		$tech  = $VAR['tech'];
		$smarty->assign('tech', $tech);
		$smarty->assign('wo_id', $wo_id);
		$smarty->assign('start_day', $day);
		$smarty->assign('start_time', $start_time);
		$smarty->display('schedual'.SEP.'new.tpl');
}

?>