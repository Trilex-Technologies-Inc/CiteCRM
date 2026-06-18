<?php
// Records a 'click' event and redirects to the target URL.
// Usage: /scripts/email_click.php?u=BASE64URL_ENCODED_TARGET&t=TRACK_TOKEN&eid=EMAIL_ID

require_once __DIR__ . "/../conf.php";

function base64url_decode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}

$u = isset($_GET['u']) ? $_GET['u'] : '';
$t = isset($_GET['t']) ? substr($_GET['t'],0,255) : '';
$email_id = isset($_GET['eid']) ? substr($_GET['eid'],0,255) : '';

$target = '';
if (!empty($u)) {
    $target = base64url_decode($u);
}

$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

if (!empty($t)) {
    $meta = '';
    if (function_exists('getallheaders')) {
        $meta = json_encode(array('headers'=>getallheaders()));
    }
    $q = "INSERT INTO `" . PRFX . "EMAIL_TRACKING` (`ACCOUNT_ID`, `EMAIL_ID`, `RECIPIENT`, `EVENT`, `LINK`, `IP`, `USER_AGENT`, `REFERRER`, `META`) VALUES (0, " . $db->qstr($email_id) . ", " . $db->qstr($t) . ", 'click', " . $db->qstr($target) . ", " . $db->qstr($ip) . ", " . $db->qstr($ua) . ", " . $db->qstr($ref) . ", " . $db->qstr($meta) . ")";
    @$db->Execute($q);

    // try to notify assigned lead owner
    try {
        $r = $db->Execute("SELECT l.ASSIGNED_TO FROM " . PRFX . "LEADS l LEFT JOIN " . PRFX . "LEAD_CONTACTS et ON l.CONTACT_ID = et.CONTACT_ID WHERE et.CONTACT_EMAIL=" . $db->qstr($t) . " LIMIT 1");
        if ($r && !$r->EOF && !empty($r->fields['ASSIGNED_TO'])) {
            $assigned = (int)$r->fields['ASSIGNED_TO'];
            $notif_q = "INSERT INTO `" . PRFX . "NOTIFICATIONS` (`EMPLOYEE_ID`, `TRACK_ID`, `EMAIL`, `EVENT`, `LINK`, `DATA`) VALUES (" . $db->qstr($assigned) . ", NULL, " . $db->qstr($t) . ", 'click', " . $db->qstr($target) . ", " . $db->qstr($meta) . ")";
            @$db->Execute($notif_q);
        }
    } catch (Exception $ex) {
        // ignore
    }
}

// Redirect to target (fallback to homepage)
if (!empty($target)) {
    header('Location: ' . $target);
    exit;
}

header('Location: /');
exit;
