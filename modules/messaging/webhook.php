<?php
require 'conf.php';

// Accept webhook events from Gmail Pub/Sub or Microsoft Graph change notifications
$raw = file_get_contents('php://input');
if ($raw === false) {
    http_response_code(400);
    echo 'No payload';
    exit;
}

// store payload into EMAIL_LOG raw for admin review (best-effort)
require_once(INCLUDE_URL . 'smtp_crypt.php');
require_once(INCLUDE_URL . 'email_logger.php');

$payload = $raw;
$db->Execute("INSERT INTO " . PRFX . "EMAIL_LOG (ACCOUNT_ID,MESSAGE_ID,RAW,CREATED_AT) VALUES (NULL,'webhook'," . $db->qstr($payload) . "," . $db->qstr(time()) . ")");

http_response_code(200);
echo 'OK';

?>
