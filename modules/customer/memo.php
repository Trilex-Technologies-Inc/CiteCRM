<?php
####################################################
# IN Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Customer Memo												#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################

$smarty->assign('customer_id', isset($VAR['customer_id']) ? $VAR['customer_id'] : '');
$smarty->assign('customer_name', isset($VAR['customer_name']) ? $VAR['customer_name'] : '');


if(isset($VAR['submit'])) {

	$q = "INSERT INTO ".PRFX."CUSTOMER_NOTES SET
			CUSTOMER_ID	=". $db->qstr( $VAR['customer_id']	) .",
			DATE			=". $db->qstr(	time() 					) .",
			NOTE			=". $db->qstr( $VAR['memo']				);

	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

	force_page('customer', 'customer_details&page_title='.$VAR['customer_name'].'&customer_id='.$VAR['customer_id']);
} else {

	if(isset($VAR['action']) && $VAR['action'] == 'delete') {
		$q = "DELETE FROM ".PRFX."CUSTOMER_NOTES WHERE ID=".$db->qstr( $VAR['note_id'] );

		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

	force_page('customer', 'customer_details&page_title='.$VAR['customer_name'].'&customer_id='.$VAR['customer_id']);

	} else {

		$smarty->display('customer'.SEP.'memo.tpl');
	}
}
?>