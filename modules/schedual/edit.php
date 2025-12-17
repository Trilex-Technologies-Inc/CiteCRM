<?php
####################################################
# IN Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Schedual Edit												#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################
$sch_id = $VAR['sch_id'];
$y = $VAR['y'];
$m = $VAR['m'];
$d = $VAR['d'];


if(isset($VAR['submit'])) {
	$q = "UPDATE ".PRFX."TABLE_SCHEDUAL SET
			SCHEDUAL_NOTES  	=". $db->qstr($VAR['schedual_notes']) ."
			WHERE SCHEDUAL_ID =".$db->qstr($sch_id);
		
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		} else {
			force_page('schedual', 'main&sch_id='.$sch_id.'&y='.$y.'&m='.$m.'&d='.$d);
			exit;
		}
} else {
	$q = "SELECT SCHEDUAL_NOTES FROM ".PRFX."TABLE_SCHEDUAL WHERE SCHEDUAL_ID=".$db->qstr($sch_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	$smarty->assign('y',$y);
	$smarty->assign('m',$m);
	$smarty->assign('d',$d);
	$smarty->assign('schedual_notes', $rs->fields['SCHEDUAL_NOTES']);
	$smarty->assign('sch_id',$sch_id);
	$smarty->display('schedual'.SEP.'edit.tpl');
}
?>