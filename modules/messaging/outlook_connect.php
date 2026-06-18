<?php
require 'conf.php';

if (empty($_SESSION['ADMIN'])) {
    header('HTTP/1.1 403 Forbidden');
    echo 'Forbidden';
    exit;
}

$cfg = $db->GetRow("SELECT * FROM " . PRFX . "SETUP LIMIT 1");
$client_id = isset($cfg['OAUTH_MS_CLIENT_ID']) ? trim($cfg['OAUTH_MS_CLIENT_ID']) : '';
if ($client_id === '') {
    echo 'Microsoft OAuth client not configured (OAUTH_MS_CLIENT_ID).';
    exit;
}

$redirect = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/outlook_callback.php';
$scope = urlencode('offline_access openid User.Read Mail.ReadWrite Mail.Send');
$auth = 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize?client_id=' . urlencode($client_id) . '&response_type=code&redirect_uri=' . urlencode($redirect) . '&response_mode=query&scope=' . $scope;

header('Location: ' . $auth);
exit;

?>
