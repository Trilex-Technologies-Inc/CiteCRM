<?php
require("conf.php");

$smarty->assign('page_title', 'Reset Password');

$token = isset($_REQUEST['token']) ? trim((string)$_REQUEST['token']) : '';
$msg = '';
$error_msg = '';

$reset_row = null;
if ($token !== '' && ctype_xdigit($token) && strlen($token) >= 32) {
	$token_hash = hash('sha256', $token);
	$q = "SELECT RESET_ID, EMPLOYEE_ID, EXPIRES_AT, USED_AT
		  FROM ".PRFX."TABLE_PASSWORD_RESET
		  WHERE TOKEN_HASH=".$db->qstr($token_hash)."
		  ORDER BY RESET_ID DESC
		  LIMIT 1";
	$rs = $db->Execute($q);
	if ($rs && !$rs->EOF) {
		$reset_row = array(
			'RESET_ID' => (int)$rs->fields['RESET_ID'],
			'EMPLOYEE_ID' => (int)$rs->fields['EMPLOYEE_ID'],
			'EXPIRES_AT' => (int)$rs->fields['EXPIRES_AT'],
			'USED_AT' => (int)$rs->fields['USED_AT'],
		);
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!$reset_row) {
		$error_msg = 'Invalid or expired reset link.';
	} else if ($reset_row['USED_AT'] > 0) {
		$error_msg = 'This reset link was already used.';
	} else if ($reset_row['EXPIRES_AT'] < time()) {
		$error_msg = 'This reset link has expired. Please request a new one.';
	} else {
		$new_password = isset($_POST['new_password']) ? (string)$_POST['new_password'] : '';
		$confirm_password = isset($_POST['confirm_password']) ? (string)$_POST['confirm_password'] : '';

			// Password policy: minimum length 8 and must contain at least one letter and one number
			if (strlen($new_password) < 8) {
			$error_msg = 'Password must be at least 8 characters.';
			} else if (!preg_match('/[A-Za-z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
				$error_msg = 'Password must contain at least one letter and one number.';
		} else if ($new_password !== $confirm_password) {
			$error_msg = 'Passwords do not match.';
		} else {
			$employee_id = (int)$reset_row['EMPLOYEE_ID'];
				// Use password_hash() for secure storage (PASSWORD_DEFAULT)
				if (!function_exists('password_hash')) {
					$error_msg = 'Server does not support secure password hashing.';
				} else {
					$hashed = password_hash($new_password, PASSWORD_DEFAULT);
					$q = "UPDATE ".PRFX."TABLE_EMPLOYEE SET EMPLOYEE_PASSWD=".$db->qstr($hashed)."
					  WHERE EMPLOYEE_ID=".$db->qstr($employee_id);
					$ok = $db->Execute($q);
				}
			if ($ok) {
					// Mark this token used and clear other outstanding tokens for this user
					$nowt = time();
					$q = "UPDATE ".PRFX."TABLE_PASSWORD_RESET SET USED_AT=".$db->qstr($nowt)." WHERE RESET_ID=".$db->qstr((int)$reset_row['RESET_ID']);
					$db->Execute($q);
					$db->Execute("UPDATE ".PRFX."TABLE_PASSWORD_RESET SET USED_AT=".$db->qstr($nowt)." WHERE EMPLOYEE_ID=".$db->qstr($employee_id)." AND USED_AT=0 AND RESET_ID<>".$db->qstr((int)$reset_row['RESET_ID']));

				$msg = 'Password updated. You can now log in.';
			} else {
				$error_msg = 'Could not update password. Please try again.';
			}
		}
	}
}

$smarty->assign('token', $token);
$smarty->assign('msg', $msg);
$smarty->assign('error_msg', $error_msg);
$smarty->assign('valid_link', ($reset_row && $reset_row['USED_AT'] == 0 && $reset_row['EXPIRES_AT'] >= time()) ? 1 : 0);
$smarty->display('core'.SEP.'reset_password.tpl');

?>
