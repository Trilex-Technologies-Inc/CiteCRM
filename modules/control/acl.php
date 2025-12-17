<?php
####################################################
# IN Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Permisions													#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################

if(isset($VAR['submit'])) {

	//print_r($_POST);
	foreach($_POST as $page=>$val){

		if($page != 'submit') {
			//print $page."<br>";
				
			foreach($val as $perm=>$acl) {
				$values .=$perm."='".$acl."',";
				//print $perm." = ".$acl."<br>";
			}

			$values .="Admin='1' ";

			$q = "UPDATE ".PRFX."ACL SET ".$values."WHERE page='".$page."'";

			if(!$rs = $db->execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
				exit;	
			}

			$values ="";

		}

	}
	force_page('control', 'acl&msg=Permisions Updated');

} else {
	$q = "SELECT * FROM ".PRFX."ACL ORDER BY page";
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
	$arr = $rs->GetArray();
	//print_r($arr);
	$smarty->assign( 'acl', $arr );
	$smarty->display('control'.SEP.'acl.tpl');
}
?>