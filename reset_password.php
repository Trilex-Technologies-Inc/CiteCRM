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

		if (strlen($new_password) < 8) {
			$error_msg = 'Password must be at least 8 characters.';
		} else if ($new_password !== $confirm_password) {
			$error_msg = 'Passwords do not match.';
		} else {
			$employee_id = (int)$reset_row['EMPLOYEE_ID'];
			$hashed = md5($new_password);

			$q = "UPDATE ".PRFX."TABLE_EMPLOYEE SET EMPLOYEE_PASSWD=".$db->qstr($hashed)."
				  WHERE EMPLOYEE_ID=".$db->qstr($employee_id);
			$ok = $db->Execute($q);
			if ($ok) {
				$q = "UPDATE ".PRFX."TABLE_PASSWORD_RESET SET USED_AT=".$db->qstr(time())."
					  WHERE RESET_ID=".$db->qstr((int)$reset_row['RESET_ID']);
				$db->Execute($q);

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
