<?php
require("conf.php");

$smarty->assign('page_title', 'Forgot Password');

$msg = '';
$error_msg = '';

function crm_build_base_url() {
	$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
	$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
	if ($host === '') {
		return '';
	}
	return $scheme.'://'.$host;
}

function crm_ensure_password_reset_table($db) {
	$q = "CREATE TABLE IF NOT EXISTS `".PRFX."TABLE_PASSWORD_RESET` (
		`RESET_ID` int(11) NOT NULL auto_increment,
		`EMPLOYEE_ID` int(11) NOT NULL,
		`TOKEN_HASH` char(64) NOT NULL,
		`EXPIRES_AT` int(20) NOT NULL,
		`USED_AT` int(20) NOT NULL default '0',
		`CREATED_AT` int(20) NOT NULL,
		`REQUEST_IP` varchar(45) NOT NULL default '',
		PRIMARY KEY (`RESET_ID`),
		KEY `EMPLOYEE_ID` (`EMPLOYEE_ID`),
		KEY `TOKEN_HASH` (`TOKEN_HASH`),
		KEY `EXPIRES_AT` (`EXPIRES_AT`)
	) ENGINE=MyISAM";
	$db->Execute($q);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$login_or_email = isset($_POST['login_or_email']) ? trim((string)$_POST['login_or_email']) : '';
	if ($login_or_email === '') {
		$error_msg = 'Please enter your login or email.';
	} else {
		crm_ensure_password_reset_table($db);

		$employee_id = 0;
		$employee_email = '';
		$employee_login = '';

		// Allow lookup by login OR email.
		$q = "SELECT EMPLOYEE_ID, EMPLOYEE_EMAIL, EMPLOYEE_LOGIN
			  FROM ".PRFX."TABLE_EMPLOYEE
			  WHERE EMPLOYEE_LOGIN=".$db->qstr($login_or_email)."
				 OR EMPLOYEE_EMAIL=".$db->qstr($login_or_email)."
			  LIMIT 1";
		$rs = $db->Execute($q);
		if ($rs) {
			$employee_id = (int)$rs->fields['EMPLOYEE_ID'];
			$employee_email = trim((string)$rs->fields['EMPLOYEE_EMAIL']);
			$employee_login = (string)$rs->fields['EMPLOYEE_LOGIN'];
		}

		// Always show a generic message to avoid account enumeration.
		$msg = 'If an account matches, a reset link has been sent.';

		if ($employee_id > 0 && $employee_email !== '' && filter_var($employee_email, FILTER_VALIDATE_EMAIL)) {
			$token = bin2hex(random_bytes(32));
			$token_hash = hash('sha256', $token);
			$now = time();
			$expires_at = $now + (60 * 60); // 1 hour
			$request_ip = isset($_SERVER['REMOTE_ADDR']) ? (string)$_SERVER['REMOTE_ADDR'] : '';

			$q = "INSERT INTO ".PRFX."TABLE_PASSWORD_RESET SET
					EMPLOYEE_ID=".$db->qstr($employee_id).",
					TOKEN_HASH=".$db->qstr($token_hash).",
					EXPIRES_AT=".$db->qstr($expires_at).",
					USED_AT='0',
					CREATED_AT=".$db->qstr($now).",
					REQUEST_IP=".$db->qstr($request_ip);
			$db->Execute($q);

			$company_email = '';
			$company_name = 'CiteCRM';
			$q = "SELECT COMPANY_EMAIL, COMPANY_NAME FROM ".PRFX."TABLE_COMPANY";
			$rs = $db->Execute($q);
			if ($rs) {
				$company_email = (string)$rs->fields['COMPANY_EMAIL'];
				if (trim((string)$rs->fields['COMPANY_NAME']) !== '') {
					$company_name = (string)$rs->fields['COMPANY_NAME'];
				}
			}

			$base_url = crm_build_base_url();
			$reset_link = ($base_url !== '')
				? ($base_url . "/reset_password.php?token=" . urlencode($token))
				: ("reset_password.php?token=" . urlencode($token));

			$subject = $company_name.' - Password Reset';
			$lines = array();
			$lines[] = 'A password reset was requested for your account: '.$employee_login;
			$lines[] = '';
			$lines[] = 'Reset link (valid for 1 hour):';
			$lines[] = $reset_link;
			$lines[] = '';
			$lines[] = 'If you did not request this, you can ignore this email.';
			$message = implode("\r\n", $lines);

			$headers = array();
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-Type: text/plain; charset=UTF-8';
			if ($company_email !== '' && filter_var(trim($company_email), FILTER_VALIDATE_EMAIL)) {
				$headers[] = 'From: '.$company_name.' <'.trim($company_email).'>';
				$headers[] = 'Reply-To: '.trim($company_email);
			}

			$sendmail_path = (string)ini_get('sendmail_path');
			$sendmail_bin = trim(strtok($sendmail_path, " \t"));
			$has_sendmail = ($sendmail_bin !== '' && @file_exists($sendmail_bin));
			if ($has_sendmail) {
				@mail($employee_email, $subject, $message, implode("\r\n", $headers));
			} else {
				@error_log('['.date('c').'] Password reset email NOT sent (sendmail missing): '.$employee_email."\n", 3, 'log/mail.log');
			}
		}
	}
}

$smarty->assign('msg', $msg);
$smarty->assign('error_msg', $error_msg);
$smarty->display('core'.SEP.'forgot_password.tpl');

?>

