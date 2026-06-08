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

function quote_identifier($identifier) {
	return '`'.str_replace('`', '``', $identifier).'`';
}

// Load roles (employee types). ACL columns are expected to match TYPE_NAME values.
$q = "SELECT TYPE_ID, TYPE_NAME FROM ".PRFX."CONFIG_EMPLOYEE_TYPE ORDER BY TYPE_ID";
if(!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$roles = $rs->GetArray();
$role_names = array();
foreach($roles as $r) {
	if(isset($r['TYPE_NAME']) && $r['TYPE_NAME'] !== '') {
		$role_names[] = $r['TYPE_NAME'];
	}
}

if(isset($VAR['submit'])) {

	foreach($_POST as $page=>$val){

		if($page != 'submit' && is_array($val) && strpos($page, ':') !== false) {
			$sets = array();

			foreach($val as $role=>$acl) {
				if (!in_array($role, $role_names, true)) {
					continue;
				}
				if ($role === 'Admin') {
					continue;
				}
				$sets[] = quote_identifier($role)."=".( ((int)$acl) ? 1 : 0 );
			}

			// Admin is always allowed
			if (in_array('Admin', $role_names, true)) {
				$sets[] = quote_identifier('Admin')."=1";
			}

			if (!empty($sets)) {
				$q = "UPDATE ".PRFX."ACL SET ".implode(',', $sets)." WHERE page=".$db->qstr($page);
				if(!$rs = $db->execute($q)) {
					force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
					exit;
				}
			}

		}

	}
	$redirect = 'acl&page_title=Permissions&msg=Permisions Updated';
	if (isset($VAR['role']) && $VAR['role'] !== '') {
		$redirect .= '&role='.rawurlencode($VAR['role']);
	}
	force_page('control', $redirect);

} else {
	$q = "SELECT * FROM ".PRFX."ACL ORDER BY page";
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
	$arr = $rs->GetArray();
	$smarty->assign( 'acl', $arr );
	$smarty->assign( 'roles', $roles );
	$smarty->assign( 'role_filter', isset($VAR['role']) ? $VAR['role'] : '' );
	$smarty->display('control'.SEP.'acl.tpl');
}
?>
