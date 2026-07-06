<?php
require 'conf.php';
set_time_limit(0);
require_once 'include/email_logger.php';

// Simple sync worker: refresh tokens, fetch recent messages and log them.
$rs = $db->Execute("SELECT * FROM " . PRFX . "EMAIL_ACCOUNTS WHERE ENABLED=1");
if (!$rs) exit("No accounts\n");

while ($rs && !$rs->EOF) {
    $acc = $rs->fields;
    $acc_id = (int)$acc['ACCOUNT_ID'];
    $provider = $acc['PROVIDER'];

    // decrypt tokens
    require_once(INCLUDE_URL . 'smtp_crypt.php');
    $access = $acc['ACCESS_TOKEN'];
    $refresh = $acc['REFRESH_TOKEN'];
    if (is_string($access) && substr($access,0,4)==='ENC:') $access = citecrm_decrypt_smtp_pass($access);
    if (is_string($refresh) && substr($refresh,0,4)==='ENC:') $refresh = citecrm_decrypt_smtp_pass($refresh);

    $expires = isset($acc['EXPIRES_AT']) ? (int)$acc['EXPIRES_AT'] : 0;
    // refresh if needed
    if ($expires > 0 && $expires < time() + 60 && $refresh) {
        if ($provider === 'gmail') {
            $cfg = $db->GetRow("SELECT * FROM " . PRFX . "SETUP LIMIT 1");
            $client_id = isset($cfg['OAUTH_GOOGLE_CLIENT_ID']) ? $cfg['OAUTH_GOOGLE_CLIENT_ID'] : '';
            $client_secret = isset($cfg['OAUTH_GOOGLE_CLIENT_SECRET']) ? $cfg['OAUTH_GOOGLE_CLIENT_SECRET'] : '';
            $post = http_build_query(array('client_id'=>$client_id,'client_secret'=>$client_secret,'refresh_token'=>$refresh,'grant_type'=>'refresh_token'));
            $ch = curl_init('https://oauth2.googleapis.com/token');
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            $r = curl_exec($ch); curl_close($ch);
            $d = json_decode($r,true);
            if (!empty($d['access_token'])) {
                $access = $d['access_token'];
                $expires = isset($d['expires_in']) ? time()+ (int)$d['expires_in'] : $expires;
                $enc = citecrm_encrypt_smtp_pass($access);
                $db->Execute("UPDATE " . PRFX . "EMAIL_ACCOUNTS SET ACCESS_TOKEN=" . $db->qstr($enc) . ", EXPIRES_AT=" . $db->qstr($expires) . " WHERE ACCOUNT_ID=" . $db->qstr($acc_id));
            }
        } elseif ($provider === 'outlook') {
            $cfg = $db->GetRow("SELECT * FROM " . PRFX . "SETUP LIMIT 1");
            $client_id = isset($cfg['OAUTH_MS_CLIENT_ID']) ? $cfg['OAUTH_MS_CLIENT_ID'] : '';
            $client_secret = isset($cfg['OAUTH_MS_CLIENT_SECRET']) ? $cfg['OAUTH_MS_CLIENT_SECRET'] : '';
            $post = http_build_query(array('client_id'=>$client_id,'client_secret'=>$client_secret,'refresh_token'=>$refresh,'grant_type'=>'refresh_token'));
            $ch = curl_init('https://login.microsoftonline.com/common/oauth2/v2.0/token');
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            $r = curl_exec($ch); curl_close($ch);
            $d = json_decode($r,true);
            if (!empty($d['access_token'])) {
                $access = $d['access_token'];
                $expires = isset($d['expires_in']) ? time()+ (int)$d['expires_in'] : $expires;
                $enc = citecrm_encrypt_smtp_pass($access);
                $db->Execute("UPDATE " . PRFX . "EMAIL_ACCOUNTS SET ACCESS_TOKEN=" . $db->qstr($enc) . ", EXPIRES_AT=" . $db->qstr($expires) . " WHERE ACCOUNT_ID=" . $db->qstr($acc_id));
            }
        }
    }

    // fetch recent messages
    if ($provider === 'gmail' && $access) {
        $list = json_decode(crm_http_get('https://www.googleapis.com/gmail/v1/users/me/messages?maxResults=5', $access), true);
        if (isset($list['messages']) && is_array($list['messages'])) {
            foreach ($list['messages'] as $m) {
                $mid = $m['id'];
                $full = json_decode(crm_http_get('https://www.googleapis.com/gmail/v1/users/me/messages/' . $mid . '?format=full', $access), true);
                if ($full) {
                    $hdrs = array();
                    if (isset($full['payload']['headers'])) foreach ($full['payload']['headers'] as $h) $hdrs[strtolower($h['name'])] = $h['value'];
                    $from = isset($hdrs['from']) ? $hdrs['from'] : '';
                    $to = isset($hdrs['to']) ? $hdrs['to'] : '';
                    $subject = isset($hdrs['subject']) ? $hdrs['subject'] : '';
                    // body extraction (simple)
                    $body = '';
                    if (isset($full['payload']['parts']) && is_array($full['payload']['parts'])) {
                        foreach ($full['payload']['parts'] as $p) {
                            if (isset($p['mimeType']) && strpos($p['mimeType'],'text/plain') !== false && isset($p['body']['data'])) {
                                $body = base64_decode(strtr($p['body']['data'], '-_', '+/'));
                                break;
                            }
                        }
                    } elseif (isset($full['payload']['body']['data'])) {
                        $body = base64_decode(strtr($full['payload']['body']['data'], '-_', '+/'));
                    }
                    crm_log_email_activity(array('account_id'=>$acc_id,'message_id'=>$mid,'thread_id'=>isset($full['threadId'])?$full['threadId']:'','direction'=>'in','from'=>$from,'to'=>$to,'subject'=>$subject,'body'=>$body,'raw'=>json_encode($full)));
                }
            }
        }
    }

    if ($provider === 'outlook' && $access) {
        $list = json_decode(crm_http_get('https://graph.microsoft.com/v1.0/me/messages?$top=5', $access), true);
        if (isset($list['value']) && is_array($list['value'])) {
            foreach ($list['value'] as $m) {
                $mid = isset($m['id']) ? $m['id'] : '';
                $from = isset($m['from']['emailAddress']['address']) ? $m['from']['emailAddress']['address'] : '';
                $to = '';
                if (isset($m['toRecipients'])) {
                    $to = implode(',', array_map(function($r){return $r['emailAddress']['address'];}, $m['toRecipients']));
                }
                $subject = isset($m['subject']) ? $m['subject'] : '';
                $body = is_array($m['body']) && isset($m['body']['content']) ? $m['body']['content'] : '';
                crm_log_email_activity(array('account_id'=>$acc_id,'message_id'=>$mid,'thread_id'=>isset($m['conversationId'])?$m['conversationId']:'','direction'=>'in','from'=>$from,'to'=>$to,'subject'=>$subject,'body'=>$body,'raw'=>json_encode($m)));
            }
        }
    }

    $db->Execute("UPDATE " . PRFX . "EMAIL_ACCOUNTS SET LAST_SYNC=" . $db->qstr(time()) . " WHERE ACCOUNT_ID=" . $db->qstr($acc_id));
    $rs->MoveNext();
}

// helper: http GET with Bearer
function crm_http_get($url, $access)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access));
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}

?>
