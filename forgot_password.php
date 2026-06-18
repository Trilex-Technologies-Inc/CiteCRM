<?php
require 'conf.php';

$smarty->assign('page_title', 'Forgot Password');

$msg = '';
$error_msg = '';

function cr_forgot_random_bytes($len) {
	$len = (int)$len;
	if ($len <= 0) return false;
	if (function_exists('random_bytes')) return random_bytes($len);
	if (function_exists('openssl_random_pseudo_bytes')) {
		$strong = false;
		$b = openssl_random_pseudo_bytes($len, $strong);
		if ($b !== false && $strong === true) return $b;
	}
	$fp = @fopen('/dev/urandom', 'rb');
	if ($fp) {
		$b = @fread($fp, $len);
		@fclose($fp);
		if ($b !== false && strlen($b) === $len) return $b;
	}
	return false;
}

function crm_build_base_url() {
	$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
	$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
	if ($host === '') return '';
	$path = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : (isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '');
	$dir = $path ? rtrim(dirname(str_replace('\\', '/', $path)), '/') : '';
	if ($dir === '.' || $dir === '/') $dir = '';
	return $scheme . '://' . $host . $dir;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$login_or_email = isset($_POST['login_or_email']) ? trim((string)$_POST['login_or_email']) : '';
	if ($login_or_email === '') {
		$error_msg = 'Please enter your login or email.';
	} else {
		$msg = 'If an account matches, a reset link has been sent.'; // generic

		$sql = "SELECT EMPLOYEE_ID, EMPLOYEE_EMAIL, EMPLOYEE_LOGIN FROM " . PRFX . "TABLE_EMPLOYEE WHERE EMPLOYEE_LOGIN=" . $db->qstr($login_or_email) . " OR EMPLOYEE_EMAIL=" . $db->qstr($login_or_email) . " LIMIT 1";
		$rs = $db->Execute($sql);
		if ($rs && !$rs->EOF) {
			$employee_id = (int)$rs->fields['EMPLOYEE_ID'];
			$employee_email = trim((string)$rs->fields['EMPLOYEE_EMAIL']);
			$employee_login = (string)$rs->fields['EMPLOYEE_LOGIN'];

			if ($employee_id > 0 && $employee_email !== '' && filter_var($employee_email, FILTER_VALIDATE_EMAIL)) {
				$ip = isset($_SERVER['REMOTE_ADDR']) ? (string)$_SERVER['REMOTE_ADDR'] : '';
				$one_hour = time() - 3600;
				$cnt_account = (int)$db->GetOne("SELECT COUNT(*) FROM " . PRFX . "TABLE_PASSWORD_RESET WHERE EMPLOYEE_ID=" . $db->qstr($employee_id) . " AND CREATED_AT>=" . $db->qstr($one_hour));
				$cnt_ip = (int)$db->GetOne("SELECT COUNT(*) FROM " . PRFX . "TABLE_PASSWORD_RESET WHERE REQUEST_IP=" . $db->qstr($ip) . " AND CREATED_AT>=" . $db->qstr($one_hour));
				if ($cnt_account < 3 && $cnt_ip < 20) {
					$captcha_ok = true;
					$captcha = crm_get_captcha_settings($db);
					if (!empty($captcha['ENABLED']) && (int)$captcha['ENABLED'] === 1) {
						$provider = isset($captcha['PROVIDER']) ? (string)$captcha['PROVIDER'] : 'turnstile';
						$secret = isset($captcha['SECRET_KEY']) ? trim((string)$captcha['SECRET_KEY']) : '';
						$remoteip = isset($_SERVER['REMOTE_ADDR']) ? (string)$_SERVER['REMOTE_ADDR'] : '';
						if ($provider === 'turnstile') {
							$resp = isset($_POST['cf-turnstile-response']) ? trim((string)$_POST['cf-turnstile-response']) : '';
							$captcha_ok = ($secret !== '' && $resp !== '' && crm_verify_turnstile($secret, $resp, $remoteip));
						} else {
							$resp = isset($_POST['g-recaptcha-response']) ? trim((string)$_POST['g-recaptcha-response']) : '';
							$captcha_ok = ($secret !== '' && $resp !== '' && crm_verify_recaptcha($secret, $resp, $remoteip));
						}
					}

					if ($captcha_ok) {
						$bytes = cr_forgot_random_bytes(48);
						if ($bytes !== false) {
							$token = bin2hex($bytes);
							$token_hash = hash('sha256', $token);
							$now = time();
							$expires = $now + 7200;
							$request_ip = $ip;
							$ins = "INSERT INTO " . PRFX . "TABLE_PASSWORD_RESET (EMPLOYEE_ID,TOKEN_HASH,EXPIRES_AT,USED_AT,CREATED_AT,REQUEST_IP) VALUES (" .
								$db->qstr($employee_id) . "," .
								$db->qstr($token_hash) . "," .
								$db->qstr($expires) . ",0," .
								$db->qstr($now) . "," .
								$db->qstr($request_ip) . ")";
							$db->Execute($ins);

							// load company info
							$company_email = '';
							$company_name = 'CiteCRM';
							$rco = $db->Execute("SELECT COMPANY_EMAIL, COMPANY_NAME FROM " . PRFX . "TABLE_COMPANY");
							if ($rco && !$rco->EOF) {
								$company_email = (string)$rco->fields['COMPANY_EMAIL'];
								if (trim((string)$rco->fields['COMPANY_NAME']) !== '') $company_name = (string)$rco->fields['COMPANY_NAME'];
							}

							$base = crm_build_base_url();
							$reset_link = $base ? ($base . '/reset_password.php?token=' . urlencode($token)) : ('reset_password.php?token=' . urlencode($token));

							$subject = $company_name . ' - Password Reset';
							$body = "A password reset was requested for your account: " . $employee_login . "\r\n\r\n" .
								"Reset link (valid for 2 hours):\r\n" . $reset_link . "\r\n\r\nIf you did not request this, you can ignore this email.";

							$sent = false;
							if (is_file('vendor/autoload.php')) require_once 'vendor/autoload.php';
							if (is_file('include/smtp_crypt.php')) require_once 'include/smtp_crypt.php';

							// attempt to load SMTP settings
							$smtp = array();
							$cfg = @$db->Execute("SHOW COLUMNS FROM " . PRFX . "SETUP LIKE 'SMTP_HOST'");
							if ($cfg && !$cfg->EOF) {
								$rsetup = $db->Execute("SELECT * FROM " . PRFX . "SETUP LIMIT 1");
								if ($rsetup && !$rsetup->EOF) {
									$smtp['host'] = isset($rsetup->fields['SMTP_HOST']) ? $rsetup->fields['SMTP_HOST'] : '';
									$smtp['port'] = isset($rsetup->fields['SMTP_PORT']) ? $rsetup->fields['SMTP_PORT'] : 25;
									$smtp['user'] = isset($rsetup->fields['SMTP_USER']) ? $rsetup->fields['SMTP_USER'] : '';
									$smtp['pass'] = isset($rsetup->fields['SMTP_PASS']) ? $rsetup->fields['SMTP_PASS'] : '';
									$smtp['secure'] = isset($rsetup->fields['SMTP_SECURE']) ? $rsetup->fields['SMTP_SECURE'] : '';
									$smtp['auth'] = isset($rsetup->fields['SMTP_AUTH']) ? $rsetup->fields['SMTP_AUTH'] : 0;
								}
							}
							if (!empty($smtp['pass']) && function_exists('citecrm_decrypt_smtp_pass')) {
								$dec = citecrm_decrypt_smtp_pass($smtp['pass']);
								if ($dec !== null) $smtp['pass'] = $dec;
							}

							if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
								try {
									$mail = new PHPMailer\PHPMailer\PHPMailer(true);
									if (!empty($smtp['host'])) {
										$mail->isSMTP();
										$mail->Host = $smtp['host'];
										$mail->Port = (int)$smtp['port'];
										if (!empty($smtp['secure'])) $mail->SMTPSecure = $smtp['secure'];
										if (!empty($smtp['auth']) && $smtp['auth']) {
											$mail->SMTPAuth = true;
											$mail->Username = $smtp['user'];
											$mail->Password = $smtp['pass'];
										}
									}
									if ($company_email !== '' && filter_var($company_email, FILTER_VALIDATE_EMAIL)) $mail->setFrom($company_email, $company_name);
									$mail->addAddress($employee_email);
									$mail->Subject = $subject;
									$mail->Body = $body;
									$sent = $mail->send();
								} catch (Exception $e) {
									$sent = false;
								}
							}

							if (!$sent) {
								$headers = "MIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\n";
								if ($company_email !== '' && filter_var(trim($company_email), FILTER_VALIDATE_EMAIL)) {
									$headers .= 'From: ' . $company_name . ' <' . trim($company_email) . "\r\n";
								}
								@mail($employee_email, $subject, $body, $headers);
							}
						}
					}
				}
			}
		}
	}
}

$smarty->assign('msg', $msg);
$smarty->assign('error_msg', $error_msg);
$smarty->display('core' . SEP . 'forgot_password.tpl');

?>
