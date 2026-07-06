<?php
// Name to use for login variable e.g. $_POST['login']
@define('USER_LOGIN_VAR', 'login');
// Name to use for password variable e.g. $_POST['password']
@define('USER_PASSW_VAR', 'password');


class Auth {
  var $session;
  var $redirect;
  var $hashKey;
  var $md5;

  function Auth($db, $redirect, $hashKey, $md5 = true)
  {
    $this->db       = $db;
    $this->redirect = $redirect;
    $this->hashKey  = $hashKey;
    $this->md5      = $md5;

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $this->session  = new Session();
    $this->login();
  }
   
 
 
  function login()
  {
    // See if we have values already stored in the session
    if ($this->session->get('login_hash')) {
      $this->confirmAuth();
      return;
    }

    // If this is a fresh login, check $_POST variables
    if (!isset($_POST[USER_LOGIN_VAR]) ||
        !isset($_POST[USER_PASSW_VAR])) {
      $this->redirect();
    }

    if ($this->md5) {
      $password = md5($_POST[USER_PASSW_VAR]);
    } else {
      $password = $_POST[USER_PASSW_VAR];
    }
	
		// Optional captcha gate (Cloudflare Turnstile / Google reCAPTCHA) for login.
		$captcha = crm_get_captcha_settings($this->db);
		if (!empty($captcha['ENABLED']) && (int)$captcha['ENABLED'] === 1) {
			$provider = isset($captcha['PROVIDER']) ? (string)$captcha['PROVIDER'] : 'turnstile';
			$secret = isset($captcha['SECRET_KEY']) ? trim((string)$captcha['SECRET_KEY']) : '';
			$remoteip = isset($_SERVER['REMOTE_ADDR']) ? (string)$_SERVER['REMOTE_ADDR'] : '';

			$response = '';
			$ok = false;
			if ($provider === 'turnstile') {
				$response = isset($_POST['cf-turnstile-response']) ? trim((string)$_POST['cf-turnstile-response']) : '';
				$ok = ($secret !== '' && $response !== '' && crm_verify_turnstile($secret, $response, $remoteip));
			} else if ($provider === 'recaptcha') {
				$response = isset($_POST['g-recaptcha-response']) ? trim((string)$_POST['g-recaptcha-response']) : '';
				$ok = ($secret !== '' && $response !== '' && crm_verify_recaptcha($secret, $response, $remoteip));
			} else {
				$ok = false;
			}

			if (!$ok) {
				$login_raw_fail = isset($_POST[USER_LOGIN_VAR]) ? (string)$_POST[USER_LOGIN_VAR] : '';
				$this->writeLog('Captcha Failed', $login_raw_fail);
				$this->force_page('login.php?error_msg=Captcha Failed');
				exit;
			}
		}

    // Use the database abstraction layer to safely quote values
    $login_raw = isset($_POST[USER_LOGIN_VAR]) ? $_POST[USER_LOGIN_VAR] : '';
    $raw_password = isset($_POST[USER_PASSW_VAR]) ? $_POST[USER_PASSW_VAR] : '';

    // Fetch stored password for this login and verify in PHP to support modern hashes
    $login_q = $this->db->qstr($login_raw);
    $sql = "SELECT EMPLOYEE_ID, EMPLOYEE_PASSWD FROM " . PRFX . "TABLE_EMPLOYEE WHERE EMPLOYEE_LOGIN=" . $login_q . " LIMIT 1";
    $result = $this->db->Execute($sql);
    if (!$result) {
      error_log("Database query failed: " . $this->db->ErrorMsg());
      $this->writeLog('Database Error', $login_raw);
      $this->force_page('login.php?error_msg=System Error. Please try again.');
      exit;
    }
    $row = $result->FetchRow();
    if (!$row || !isset($row['EMPLOYEE_ID'])) {
      $this->writeLog('Failed Login', $login_raw);
      $this->force_page('login.php?error_msg=Login Failed');
      return;
    }

    $stored = isset($row['EMPLOYEE_PASSWD']) ? $row['EMPLOYEE_PASSWD'] : '';
    $login_id = (int)$row['EMPLOYEE_ID'];

    $authenticated = false;
    // Prefer password_verify for modern hashed passwords
    if (function_exists('password_verify') && strlen($stored) > 0) {
      if (@password_verify($raw_password, $stored)) {
        $authenticated = true;
      }
    }
    // Fallback: legacy MD5 check if configured
    if (!$authenticated) {
      if ($this->md5) {
        if (md5($raw_password) === $stored) $authenticated = true;
      } else {
        if ($raw_password === $stored) $authenticated = true;
      }
    }

    if (!$authenticated) {
      $this->writeLog('Failed Login', $login_raw);
      $this->force_page('login.php?error_msg=Login Failed');
      return;
    }

    // For session consistency keep stored session password same format as before
    $password_to_store = $this->md5 ? md5($raw_password) : $raw_password;
    $this->storeAuth($login_raw, $password_to_store, $login_id);
  }
  

  function storeAuth($login, $password, $login_id)
  {
    $this->session->set(USER_LOGIN_VAR, $login);
    $this->session->set(USER_PASSW_VAR, $password);
    $this->session->set('login_id', $login_id);
    
    // Create a session variable to use to confirm sessions
    $hashKey = md5($this->hashKey . $login . $password);
    $this->session->set('login_hash', $hashKey);
    
    $this->writeLog('Login', $login);
    
  }
  
  function writeLog ($status, $login)
  {
  // Code to log to a file
    //get current date and time
    $month = date("M");
    $day = date("d");
    $year = date("Y");
    $time =  date("H").":".date("i").":".date("s");
    //get environment variables
    $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    
    // Create entry
    $data = $status.",".$login.",".$hostname.",".$month."-".$day."-".$year.",".$time.",\n";
    // Write File
    $fp = fopen(ACCESS_LOG,'a') or die("can't open access.log: $php_errormsg");
    fwrite($fp, $data);
    fclose($fp);
  }
 
  function confirmAuth()
  {
    $login = $this->session->get(USER_LOGIN_VAR);
    $password = $this->session->get(USER_PASSW_VAR);
    $hashKey = $this->session->get('login_hash');
    if (md5($this->hashKey . $login . $password) != $hashKey)
    {
      $this->logout(true);
    }
  }
  
 
  function logout($from)
  {
    $login = $this->session->get(USER_LOGIN_VAR);
    $this->writeLog('Log Out', $login);
    $this->session->del(USER_LOGIN_VAR);
    $this->session->del(USER_PASSW_VAR);
    $this->session->del('login_hash');
    $this->redirect($from);
  }
  
 
  function redirect($from = true)
  {
    if ($from) {
      header('Location: ' . $this->redirect . '?from=' .
             $_SERVER['REQUEST_URI']);
    } else {
      header('Location: ' . $this->redirect);
    }
    exit();
  }

  
    function force_page($page) {
            echo("
                <script type=\"text/javascript\">
                    <!--
                    window.location = \"$page\"
                    //-->
                </script>");
    }
}

function crm_get_captcha_settings($db) {
	static $cached = null;
	if ($cached !== null) {
		return $cached;
	}
	$cached = array('PROVIDER' => 'turnstile', 'ENABLED' => 0, 'SITE_KEY' => '', 'SECRET_KEY' => '', 'UPDATED_AT' => 0);
	if (!defined('PRFX')) {
		return $cached;
	}
	$q = "SELECT * FROM ".PRFX."TABLE_CAPTCHA_SETTINGS WHERE SETTINGS_ID=1";
	$rs = @$db->Execute($q);
	if ($rs && !$rs->EOF) {
		$cached = array(
			'PROVIDER' => (string)$rs->fields['PROVIDER'],
			'ENABLED' => (int)$rs->fields['ENABLED'],
			'SITE_KEY' => (string)$rs->fields['SITE_KEY'],
			'SECRET_KEY' => (string)$rs->fields['SECRET_KEY'],
			'UPDATED_AT' => (int)$rs->fields['UPDATED_AT'],
		);
	}
	return $cached;
}

function crm_verify_turnstile($secret, $response, $remoteip = '') {
	$secret = trim((string)$secret);
	$response = trim((string)$response);
	if ($secret === '' || $response === '') {
		return false;
	}

	$payload = http_build_query(array(
		'secret' => $secret,
		'response' => $response,
		'remoteip' => $remoteip,
	), '', '&');

	$json = '';

	if (function_exists('curl_init')) {
		$ch = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		$json = (string)curl_exec($ch);
		curl_close($ch);
	} else {
		$opts = array(
			'http' => array(
				'method' => 'POST',
				'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
							"Content-Length: ".strlen($payload)."\r\n",
				'content' => $payload,
				'timeout' => 5,
			),
		);
		$ctx = stream_context_create($opts);
		$json = (string)@file_get_contents('https://challenges.cloudflare.com/turnstile/v0/siteverify', false, $ctx);
	}

	if ($json === '') {
		return false;
	}
	$data = json_decode($json, true);
	return (is_array($data) && !empty($data['success']));
}

function crm_verify_recaptcha($secret, $response, $remoteip = '') {
	$secret = trim((string)$secret);
	$response = trim((string)$response);
	if ($secret === '' || $response === '') {
		return false;
	}

	$payload = http_build_query(array(
		'secret' => $secret,
		'response' => $response,
		'remoteip' => $remoteip,
	), '', '&');

	$json = '';
	if (function_exists('curl_init')) {
		$ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		$json = (string)curl_exec($ch);
		curl_close($ch);
	} else {
		$opts = array(
			'http' => array(
				'method' => 'POST',
				'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
							"Content-Length: ".strlen($payload)."\r\n",
				'content' => $payload,
				'timeout' => 5,
			),
		);
		$ctx = stream_context_create($opts);
		$json = (string)@file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $ctx);
	}

	if ($json === '') {
		return false;
	}
	$data = json_decode($json, true);
	return (is_array($data) && !empty($data['success'])) ? true : false;
}

function force_page($module, $cur_page) {
    echo("
        <script type=\"text/javascript\">
            <!--
            window.location = \"index.php?page=$module:$cur_page\"
            //-->
        </script>");
}
?>
