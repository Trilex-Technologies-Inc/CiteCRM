<?php
// Helpers to log inbound/outbound emails to the CRM
function crm_log_email_activity($params)
{
    // expected keys: account_id, message_id, thread_id, direction, from, to, cc, bcc, subject, body, raw
    global $db;
    if (!isset($db)) return false;

    $account_id = isset($params['account_id']) ? (int)$params['account_id'] : null;
    $message_id = isset($params['message_id']) ? substr($params['message_id'], 0, 255) : null;
    $thread_id = isset($params['thread_id']) ? substr($params['thread_id'], 0, 255) : null;
    $direction = isset($params['direction']) ? $params['direction'] : 'in';
    $from = isset($params['from']) ? $params['from'] : '';
    $to = isset($params['to']) ? $params['to'] : '';
    $cc = isset($params['cc']) ? $params['cc'] : '';
    $bcc = isset($params['bcc']) ? $params['bcc'] : '';
    $subject = isset($params['subject']) ? substr($params['subject'], 0, 512) : '';
    $body = isset($params['body']) ? $params['body'] : '';
    $raw = isset($params['raw']) ? $params['raw'] : '';

    // try to link to a customer by email
    $linked_customer = null;
    $emails_to_check = array();
    if ($from) $emails_to_check[] = $from;
    if ($to) {
        foreach (explode(',', $to) as $e) $emails_to_check[] = trim($e);
    }
    foreach ($emails_to_check as $em) {
        if ($em === '') continue;
        $cid = $db->GetOne("SELECT CUSTOMER_ID FROM " . PRFX . "TABLE_CUSTOMER WHERE CUSTOMER_EMAIL=" . $db->qstr($em) . " LIMIT 1");
        if ($cid) {
            $linked_customer = (int)$cid;
            break;
        }
    }

    $q = "INSERT INTO " . PRFX . "EMAIL_LOG (ACCOUNT_ID,MESSAGE_ID,THREAD_ID,DIRECTION,FROM_EMAIL,TO_EMAIL,CC_EMAIL,BCC_EMAIL,SUBJECT,BODY,RAW,LINKED_CUSTOMER_ID,CREATED_AT) VALUES (" .
        ($account_id !== null ? $db->qstr($account_id) : 'NULL') . "," .
        $db->qstr($message_id) . "," .
        $db->qstr($thread_id) . "," .
        $db->qstr($direction) . "," .
        $db->qstr($from) . "," .
        $db->qstr($to) . "," .
        $db->qstr($cc) . "," .
        $db->qstr($bcc) . "," .
        $db->qstr($subject) . "," .
        $db->qstr($body) . "," .
        $db->qstr($raw) . "," .
        ($linked_customer ? $db->qstr($linked_customer) : 'NULL')
        . ")";

    $result = $db->Execute($q);

    if (!$result) {
        error_log(
            "CRM Email Log Error: " . $db->ErrorMsg() .
                "\nQuery: " . $q
        );
        return false;
    }

    return true;
}
