<?php
require '../../conf.php';

$cfg = $db->GetRow("SELECT * FROM " . PRFX . "SETUP LIMIT 1");
$client_id = isset($cfg['OAUTH_MS_CLIENT_ID']) ? trim($cfg['OAUTH_MS_CLIENT_ID']) : '';
$redirect = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/microsoft_callback.php';
$scope = 'openid email profile offline_access User.Read';
$auth = 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize?client_id=' . urlencode($client_id) . '&response_type=code&redirect_uri=' . urlencode($redirect) . '&response_mode=query&scope=' . urlencode($scope);
header('Location: ' . $auth);
exit;

?>
