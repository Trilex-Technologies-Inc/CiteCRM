<?php
####################################################
# IN Cite CRM	Customer Relations Management			#
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Control Roles (Employee Types)						#
#  Version 0.0.1											#
#																	#
####################################################

function is_valid_role_identifier($role_name) {
	return (bool)preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $role_name);
}

function quote_identifier($identifier) {
	return '`'.str_replace('`', '``', $identifier).'`';
}

if (isset($VAR['submit'])) {
	$submit = $VAR['submit'];

	if ($submit === 'New') {
		$role_name = isset($VAR['type_name']) ? trim($VAR['type_name']) : '';
		if ($role_name === '' || !is_valid_role_identifier($role_name)) {
			force_page('control', 'roles&page_title=Roles&error_msg=Role name must be letters/numbers/underscore and start with a letter.');
			exit;
		}

		$q = "SELECT count(*) as cnt FROM ".PRFX."CONFIG_EMPLOYEE_TYPE WHERE TYPE_NAME=".$db->qstr($role_name);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
		if ((int)$rs->fields['cnt'] > 0) {
			force_page('control', 'roles&page_title=Roles&error_msg=Role name already exists.');
			exit;
		}

		$q = "INSERT INTO ".PRFX."CONFIG_EMPLOYEE_TYPE SET TYPE_NAME=".$db->qstr($role_name);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

		// Add a column for this role in the ACL table (default deny).
		$q = "ALTER TABLE ".PRFX."ACL ADD COLUMN ".quote_identifier($role_name)." int(2) NOT NULL default '0'";
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

		force_page('control', 'roles&page_title=Roles&msg=Role Created');
		exit;
	}

	if ($submit === 'Edit') {
		$type_id = isset($VAR['type_id']) ? (int)$VAR['type_id'] : 0;
		$new_name = isset($VAR['type_name']) ? trim($VAR['type_name']) : '';

		if ($type_id <= 0) {
			force_page('control', 'roles&page_title=Roles&error_msg=Invalid role id.');
			exit;
		}

		$q = "SELECT TYPE_NAME FROM ".PRFX."CONFIG_EMPLOYEE_TYPE WHERE TYPE_ID=".$db->qstr($type_id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
		$old_name = isset($rs->fields['TYPE_NAME']) ? $rs->fields['TYPE_NAME'] : '';
		if ($old_name === '') {
			force_page('control', 'roles&page_title=Roles&error_msg=Role not found.');
			exit;
		}

		if ($old_name === 'Admin') {
			force_page('control', 'roles&page_title=Roles&error_msg=Admin role cannot be renamed.');
			exit;
		}

		if ($new_name === '' || !is_valid_role_identifier($new_name)) {
			force_page('control', 'roles&page_title=Roles&error_msg=Role name must be letters/numbers/underscore and start with a letter.');
			exit;
		}

		$q = "SELECT count(*) as cnt FROM ".PRFX."CONFIG_EMPLOYEE_TYPE WHERE TYPE_NAME=".$db->qstr($new_name)." AND TYPE_ID<>".$db->qstr($type_id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
		if ((int)$rs->fields['cnt'] > 0) {
			force_page('control', 'roles&page_title=Roles&error_msg=Role name already exists.');
			exit;
		}

		$q = "UPDATE ".PRFX."CONFIG_EMPLOYEE_TYPE SET TYPE_NAME=".$db->qstr($new_name)." WHERE TYPE_ID=".$db->qstr($type_id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

		if ($new_name !== $old_name) {
			$q = "ALTER TABLE ".PRFX."ACL CHANGE ".quote_identifier($old_name)." ".quote_identifier($new_name)." int(2) NOT NULL default '0'";
			if(!$rs = $db->execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
				exit;
			}
		}

		force_page('control', 'roles&page_title=Roles&msg=Role Updated');
		exit;
	}

	if ($submit === 'Delete') {
		$type_id = isset($VAR['type_id']) ? (int)$VAR['type_id'] : 0;
		if ($type_id <= 0) {
			force_page('control', 'roles&page_title=Roles&error_msg=Invalid role id.');
			exit;
		}

		$q = "SELECT TYPE_NAME FROM ".PRFX."CONFIG_EMPLOYEE_TYPE WHERE TYPE_ID=".$db->qstr($type_id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
		$role_name = isset($rs->fields['TYPE_NAME']) ? $rs->fields['TYPE_NAME'] : '';
		if ($role_name === '') {
			force_page('control', 'roles&page_title=Roles&error_msg=Role not found.');
			exit;
		}

		if ($role_name === 'Admin') {
			force_page('control', 'roles&page_title=Roles&error_msg=Admin role cannot be deleted.');
			exit;
		}

		$q = "SELECT count(*) as cnt FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_TYPE=".$db->qstr($type_id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
		if ((int)$rs->fields['cnt'] > 0) {
			force_page('control', 'roles&page_title=Roles&error_msg=Cannot delete: role is assigned to employees.');
			exit;
		}

		$q = "DELETE FROM ".PRFX."CONFIG_EMPLOYEE_TYPE WHERE TYPE_ID=".$db->qstr($type_id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

		$q = "ALTER TABLE ".PRFX."ACL DROP COLUMN ".quote_identifier($role_name);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

		force_page('control', 'roles&page_title=Roles&msg=Role Deleted');
		exit;
	}

	force_page('control', 'roles&page_title=Roles&error_msg=Unknown action.');
	exit;
} else {
	$q = "SELECT t.TYPE_ID, t.TYPE_NAME, count(e.EMPLOYEE_ID) as EMPLOYEE_COUNT
			FROM ".PRFX."CONFIG_EMPLOYEE_TYPE t
			LEFT JOIN ".PRFX."TABLE_EMPLOYEE e ON (e.EMPLOYEE_TYPE = t.TYPE_ID)
			GROUP BY t.TYPE_ID, t.TYPE_NAME
			ORDER BY t.TYPE_ID";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	$arr = $rs->GetArray();
	$smarty->assign('roles', $arr);
	$smarty->display('control'.SEP.'roles.tpl');
}

?>

