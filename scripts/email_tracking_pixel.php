<?php
// Records an 'open' event and returns a 1x1 transparent GIF.
// Usage: /scripts/email_tracking_pixel.php?t=TRACK_TOKEN&eid=EMAIL_ID

require_once __DIR__ . "/../conf.php";

$t = isset($_GET['t']) ? substr($_GET['t'],0,255) : '';
$email_id = isset($_GET['eid']) ? substr($_GET['eid'],0,255) : '';

// Basic input capture
$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

if (!empty($t)) {
    $meta = '';
    if (function_exists('getallheaders')) {
        $meta = json_encode(array('headers'=>getallheaders()));
    }
    $q = "INSERT INTO `" . PRFX . "EMAIL_TRACKING` (`ACCOUNT_ID`, `EMAIL_ID`, `RECIPIENT`, `EVENT`, `IP`, `USER_AGENT`, `REFERRER`, `META`) VALUES (0, " . $db->qstr($email_id) . ", " . $db->qstr($t) . ", 'open', " . $db->qstr($ip) . ", " . $db->qstr($ua) . ", " . $db->qstr($ref) . ", " . $db->qstr($meta) . ")";
    @$db->Execute($q);

    // try to locate a lead owner and create a notification
    try {
        $r = $db->Execute("SELECT l.ASSIGNED_TO, et.CONTACT_EMAIL FROM " . PRFX . "LEADS l LEFT JOIN " . PRFX . "LEAD_CONTACTS et ON l.CONTACT_ID = et.CONTACT_ID WHERE et.CONTACT_EMAIL=" . $db->qstr($t) . " LIMIT 1");
        if ($r && !$r->EOF && !empty($r->fields['ASSIGNED_TO'])) {
            $assigned = (int)$r->fields['ASSIGNED_TO'];
            $notif_q = "INSERT INTO `" . PRFX . "NOTIFICATIONS` (`EMPLOYEE_ID`, `TRACK_ID`, `EMAIL`, `EVENT`, `LINK`, `DATA`) VALUES (" . $db->qstr($assigned) . ", NULL, " . $db->qstr($t) . ", 'open', '', " . $db->qstr($meta) . ")";
            @$db->Execute($notif_q);
        }
    } catch (Exception $ex) {
        // ignore notification errors
    }
}

// output 1x1 transparent GIF
header('Content-Type: image/gif');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');

$gif = base64_decode('R0lGODlhAQABAPAAAAAAAAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
echo $gif;
exit;
