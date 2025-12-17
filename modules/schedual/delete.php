<?php
####################################################
# IN Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Schedule Delet												#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################
$sch_id = $VAR['sch_id'];
$y =	$VAR['y'];
$m =	$VAR['m'];
$d =	$VAR['d'];

	$q = "DELETE FROM ".PRFX."TABLE_SCHEDUAL WHERE SCHEDUAL_ID =".$db->qstr($sch_id);
		
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		} else {
			force_page('schedual', 'main&y='.$y.'&m='.$m.'&d='.$d);
			exit;
		}


?>