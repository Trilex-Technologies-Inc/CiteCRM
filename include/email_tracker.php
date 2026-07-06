<?php
// Helper functions to generate tracking pixel and tracked links for outgoing emails.
// Usage: include 'include/email_tracker.php' and call crm_tracking_pixel($token, $email_id)

function crm_generate_tracking_token($email_id, $recipient_email)
{
    global $strKey;
    $raw = $email_id . '|' . $recipient_email . '|' . time();
    if (function_exists('encrypt')) {
        return urlencode(encrypt($raw, $strKey));
    }
    return urlencode(base64_encode($raw));
}

function crm_tracking_pixel($token, $email_id = '')
{
    $t = urlencode(substr($token, 0, 255));
    $eid = urlencode(substr($email_id, 0, 255));
    return "/scripts/email_tracking_pixel.php?t={$t}&eid={$eid}";
}

function crm_trackable_link($target_url, $token, $email_id = '')
{
    // base64url encode the target
    $b64 = rtrim(strtr(base64_encode($target_url), '+/', '-_'), '=');
    $t = urlencode(substr($token, 0, 255));
    $eid = urlencode(substr($email_id, 0, 255));
    return "/scripts/email_click.php?u={$b64}&t={$t}&eid={$eid}";
}
