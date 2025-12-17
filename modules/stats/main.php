<?php
#########################################################
# 			Cite CRM	Customer Relations Management	#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.citecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 		#
#  conditions of the GPL								#
#  stats.php											#
#  Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005		#
#														#
#########################################################

$today_start = mktime(0,0,0,date("m"), date("d"), date("Y"));
$today_end 	 = mktime(23,59,59,date("m"), date("d"), date("Y"));

$month_start = mktime(0,0,0,date("m"), 1, date("Y"));
$month_end	 = mktime(0,0,0,date("m")+1, 0, date("Y"));

/* open work orders this month */
$q = "SELECT count(*) AS count FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_OPEN_DATE  >= '$month_start' AND WORK_ORDER_OPEN_DATE  <= '$month_end'";
if(!$rs = $db->Execute($q)){
	echo 'Error: '. $db->ErrorMsg();
	die;
}
$month_open = $rs->fields['count'];
$smarty->assign('month_open', $month_open);


$smarty->display('stats'.SEP.'main.tpl');



?>