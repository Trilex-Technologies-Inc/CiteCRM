<?php
require 'conf.php';

if (!isset($_GET['code'])) {
    echo 'Missing code';
    exit;
}

$code = (string)$_GET['code'];

$cfg = $db->GetRow("SELECT * FROM " . PRFX . "SETUP LIMIT 1");
$client_id = isset($cfg['OAUTH_MS_CLIENT_ID']) ? trim($cfg['OAUTH_MS_CLIENT_ID']) : '';
$client_secret = isset($cfg['OAUTH_MS_CLIENT_SECRET']) ? trim($cfg['OAUTH_MS_CLIENT_SECRET']) : '';

if ($client_id === '' || $client_secret === '') {
    echo 'Microsoft OAuth client not configured (OAUTH_MS_CLIENT_ID/OAUTH_MS_CLIENT_SECRET in SETUP).';
    exit;
}

$redirect = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/outlook_callback.php';

$token_url = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';
$post = http_build_query(array(
    'client_id' => $client_id,
    'scope' => 'https://graph.microsoft.com/.default',
    'code' => $code,
    'redirect_uri' => $redirect,
    'grant_type' => 'authorization_code',
    'client_secret' => $client_secret
));

$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
$res = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($res === false) {
    echo 'Token request failed: ' . htmlspecialchars($err);
    exit;
}

$data = json_decode($res, true);
if (!is_array($data) || empty($data['access_token'])) {
    echo 'Invalid token response';
    exit;
}

$access_token = $data['access_token'];
$refresh_token = isset($data['refresh_token']) ? $data['refresh_token'] : '';
$expires_in = isset($data['expires_in']) ? (int)$data['expires_in'] : 0;
$expires_at = $expires_in > 0 ? time() + $expires_in : 0;

// get user email
$ui = curl_init('https://graph.microsoft.com/v1.0/me');
curl_setopt($ui, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ui, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
$ur = curl_exec($ui);
curl_close($ui);
$uinfo = json_decode($ur, true);
$email = isset($uinfo['mail']) && $uinfo['mail'] !== '' ? $uinfo['mail'] : (isset($uinfo['userPrincipalName']) ? $uinfo['userPrincipalName'] : 'unknown');

require_once(INCLUDE_URL . 'smtp_crypt.php');
$enc_access = citecrm_encrypt_smtp_pass($access_token);
$enc_refresh = $refresh_token !== '' ? citecrm_encrypt_smtp_pass($refresh_token) : '';

$sql = "INSERT INTO " . PRFX . "EMAIL_ACCOUNTS (PROVIDER,EMAIL,ACCESS_TOKEN,REFRESH_TOKEN,EXPIRES_AT,SCOPES,ENABLED,CREATED_AT) VALUES (" .
    $db->qstr('outlook') . "," . $db->qstr($email) . "," . $db->qstr($enc_access) . "," . $db->qstr($enc_refresh) . "," . $db->qstr($expires_at) . "," . $db->qstr(isset($data['scope']) ? $data['scope'] : '') . ",1," . $db->qstr(time()) . ")";

$db->Execute($sql);

echo 'Outlook connected: ' . htmlspecialchars($email);

?>
