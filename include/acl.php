<?php
####################################################
# IN Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Account include file										#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################


function check_acl($db,$module,$page	) {
	if (!isset($_SESSION['login_id']) || empty($_SESSION['login_id'])) {
		// No logged-in user; deny access cleanly
		return false;
	}

	$uid = $_SESSION['login_id'];
	
	/* get group id */
	$q = 'SELECT '.PRFX.'CONFIG_EMPLOYEE_TYPE.TYPE_NAME
			FROM '.PRFX.'TABLE_EMPLOYEE,'.PRFX.'CONFIG_EMPLOYEE_TYPE 
			WHERE '.PRFX.'TABLE_EMPLOYEE.EMPLOYEE_TYPE  = '.PRFX.'CONFIG_EMPLOYEE_TYPE.TYPE_ID AND EMPLOYEE_ID='.$db->qstr($uid);
	if(!$rs = $db->execute($q)) {
		force_page('core','error&error_msg=Could not get Group ID for user');
		exit;
	} else {
		$gid = $rs->fields['TYPE_NAME'];
	}

	/* check page to see if we have access */
	if(!isset($module)) {
		$page= "core:main";
	} else {
		$page= $module.":".$page;
	}

	$q = 'SELECT '.$gid.' as ACL FROM '.PRFX.'ACL WHERE page='.$db->qstr($page);

	if(!$rs = $db->execute($q)) {
		force_page('core','error&error_msg=Could not get Page ACL');
		exit;
	} else {
		$acl = $rs->fields['ACL'];
		if($acl != 1) {
			return false;	
		} else {
			return true;	
		}
	}
}


function encrypt ($strString, $strKey) {

	if ($strString=="") {
		return $strString;
	}
	$iv = mcrypt_create_iv (mcrypt_get_iv_size (MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND);
	$enString=mcrypt_ecb(MCRYPT_BLOWFISH, $strKey, $strString, MCRYPT_ENCRYPT, $iv);
	$enString=bin2hex($enString);

	return ($enString);
	
}

function decrypt ($strString, $strKey) {
	
	if ($strString=="") {
		return $strString;
	}
	$iv = mcrypt_create_iv (mcrypt_get_iv_size (MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND);
	$strString=hex2bin($strString);
	$deString=mcrypt_ecb(MCRYPT_BLOWFISH, $strKey, $strString, MCRYPT_DECRYPT, $iv);

	return ($deString);

}
?>