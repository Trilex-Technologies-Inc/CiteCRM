<?php
require 'conf.php';

// Admin-only
if (empty($_SESSION['ADMIN'])) {
    header('HTTP/1.1 403 Forbidden');
    echo 'Forbidden';
    exit;
}

// Read Google client config from PRFX.SETUP or config file
$client_id = $db->GetOne("SELECT SMTP_HOST FROM " . PRFX . "SETUP LIMIT 1"); // placeholder - replace with real keys in SETUP
$redirect = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/gmail_callback.php';

$scope = urlencode('https://www.googleapis.com/auth/gmail.modify https://www.googleapis.com/auth/userinfo.email');
$auth_url = 'https://accounts.google.com/o/oauth2/v2/auth?response_type=code&client_id=' . urlencode($client_id) . '&redirect_uri=' . urlencode($redirect) . '&scope=' . $scope . '&access_type=offline&prompt=consent';

header('Location: ' . $auth_url);
exit;

?>
