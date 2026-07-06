<?php
require '../../conf.php';

if (!isset($_GET['code'])) { echo 'Missing code'; exit; }
$code = (string)$_GET['code'];

$cfg = $db->GetRow("SELECT * FROM " . PRFX . "SETUP LIMIT 1");
$client_id = isset($cfg['OAUTH_GOOGLE_CLIENT_ID']) ? trim($cfg['OAUTH_GOOGLE_CLIENT_ID']) : '';
$client_secret = isset($cfg['OAUTH_GOOGLE_CLIENT_SECRET']) ? trim($cfg['OAUTH_GOOGLE_CLIENT_SECRET']) : '';
if ($client_id === '' || $client_secret === '') { echo 'OAuth not configured'; exit; }

$redirect = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/google_callback.php';

$token_url = 'https://oauth2.googleapis.com/token';
$post = http_build_query(array('code'=>$code,'client_id'=>$client_id,'client_secret'=>$client_secret,'redirect_uri'=>$redirect,'grant_type'=>'authorization_code'));
$ch = curl_init($token_url); curl_setopt($ch,CURLOPT_POST,1); curl_setopt($ch,CURLOPT_POSTFIELDS,$post); curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: application/x-www-form-urlencoded'));
$res = curl_exec($ch); curl_close($ch);
$data = json_decode($res,true);
if (!isset($data['access_token'])) { echo 'Token error'; exit; }
$access = $data['access_token']; $refresh = isset($data['refresh_token'])?$data['refresh_token']:''; $expires = isset($data['expires_in'])?time()+intval($data['expires_in']):0;

// get userinfo
$ui = curl_init('https://www.googleapis.com/oauth2/v2/userinfo'); curl_setopt($ui,CURLOPT_RETURNTRANSFER,1); curl_setopt($ui,CURLOPT_HTTPHEADER,array('Authorization: Bearer ' . $access)); $ur = curl_exec($ui); curl_close($ui);
$u = json_decode($ur,true);
$external_id = isset($u['id']) ? $u['id'] : null; $email = isset($u['email']) ? $u['email'] : null;

require_once(INCLUDE_URL . 'smtp_crypt.php');
$enc_access = citecrm_encrypt_smtp_pass($access);
$enc_refresh = $refresh !== '' ? citecrm_encrypt_smtp_pass($refresh) : '';

// insert or update identity
$row = $db->GetRow("SELECT * FROM " . PRFX . "OAUTH_IDENTITIES WHERE PROVIDER='google' AND EXTERNAL_ID=" . $db->qstr($external_id) . " LIMIT 1");
if ($row && isset($row['IDENTITY_ID'])) {
    $emp_id = $row['EMPLOYEE_ID'];
    $db->Execute("UPDATE " . PRFX . "OAUTH_IDENTITIES SET ACCESS_TOKEN=" . $db->qstr($enc_access) . ", REFRESH_TOKEN=" . $db->qstr($enc_refresh) . ", EXPIRES_AT=" . $db->qstr($expires) . ", UPDATED_AT=NOW() WHERE IDENTITY_ID=" . $db->qstr($row['IDENTITY_ID']));
} else {
    // try to find employee by email
    $emp_id = $db->GetOne("SELECT EMPLOYEE_ID FROM " . PRFX . "TABLE_EMPLOYEE WHERE EMPLOYEE_EMAIL=" . $db->qstr($email) . " LIMIT 1");
    if (!$emp_id) {
        // create minimal employee
        $login = preg_replace('/[^a-z0-9._-]/i','',strtolower(strtok($email,'@')));
        $rnd = substr(md5(uniqid('',true)),0,10);
        $login = $login . $rnd;
        $passwd = md5(bin2hex(random_bytes(8)));
        $db->Execute("INSERT INTO " . PRFX . "TABLE_EMPLOYEE (EMPLOYEE_LOGIN,EMPLOYEE_PASSWD,EMPLOYEE_EMAIL,EMPLOYEE_DISPLAY_NAME) VALUES (" . $db->qstr($login) . "," . $db->qstr($passwd) . "," . $db->qstr($email) . "," . $db->qstr($email) . ")");
        $emp_id = $db->Insert_ID();
    }
    $db->Execute("INSERT INTO " . PRFX . "OAUTH_IDENTITIES (PROVIDER,EXTERNAL_ID,EMAIL,ACCESS_TOKEN,REFRESH_TOKEN,EXPIRES_AT,EMPLOYEE_ID) VALUES (" . $db->qstr('google') . "," . $db->qstr($external_id) . "," . $db->qstr($email) . "," . $db->qstr($enc_access) . "," . $db->qstr($enc_refresh) . "," . $db->qstr($expires) . "," . $db->qstr($emp_id) . ")");
}

// create session for employee
require_once(INCLUDE_URL . 'session.php');
$s = new Session();
$emp = $db->GetRow("SELECT EMPLOYEE_LOGIN, EMPLOYEE_PASSWD, EMPLOYEE_ID FROM " . PRFX . "TABLE_EMPLOYEE WHERE EMPLOYEE_ID=" . $db->qstr($emp_id) . " LIMIT 1");
$login = $emp['EMPLOYEE_LOGIN']; $stored_pass = $emp['EMPLOYEE_PASSWD'];
$s->set(USER_LOGIN_VAR, $login);
$s->set(USER_PASSW_VAR, $stored_pass);
$s->set('login_id', (int)$emp['EMPLOYEE_ID']);
$s->set('login_hash', md5('secret' . $login . $stored_pass));

header('Location: ../../index.php');
exit;

?>
