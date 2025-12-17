<?php
#####################################################
# 	Cite CRM	Customer Relations Management		#	
#	 Copyright (C) 2003 - 2005 In-Site CRM			#
#  www.citecrm.com  dev@onsitecrm.com				#
#  This program is distributed under the terms and 	#
#  conditions of the GPL							#
#  view.php											#
#  Version 0.0.1	Sat Oct  8 08:18:17 PDT 2005	#
#													#
#####################################################
require('include.php');
if(!xml2php("schedual")) {
	$smarty->assign('error_msg',"Error in language file");
}
$sch_id = $VAR['sch_id'];
$y =	$VAR['y'];
$m =	$VAR['m'];
$d =	$VAR['d'];

$arr = view_schedual($db, $sch_id);

if($arr) {
	$smarty->assign('y',$y);
	$smarty->assign('m',$m);
	$smarty->assign('d',$d);
	$smarty->assign('arr', $arr);
	$smarty->display('schedual'.SEP.'view.tpl');
} else {
	echo "No schedual found";
}

?>