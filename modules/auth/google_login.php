<?php
require '../../conf.php';

// Start Google OAuth login (public)
$cfg = $db->GetRow("SELECT * FROM " . PRFX . "SETUP LIMIT 1");
$client_id = isset($cfg['OAUTH_GOOGLE_CLIENT_ID']) ? trim($cfg['OAUTH_GOOGLE_CLIENT_ID']) : '';
$redirect = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/google_callback.php';
$scope = 'openid email profile';
$auth_url = 'https://accounts.google.com/o/oauth2/v2/auth?response_type=code&client_id=' . urlencode($client_id) . '&redirect_uri=' . urlencode($redirect) . '&scope=' . urlencode($scope) . '&access_type=offline&prompt=select_account';
header('Location: ' . $auth_url);
exit;

?>
