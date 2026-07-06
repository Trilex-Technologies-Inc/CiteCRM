<?php
/* Messaging - send email to customer(s) */

if (function_exists('xml2php')) {
    @xml2php("customer");
}

$to_raw = isset($_POST['to']) ? trim($_POST['to']) : '';
$bcc_raw = isset($_POST['bcc']) ? trim($_POST['bcc']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$is_html = isset($_POST['is_html']) && $_POST['is_html'] == '1';
$customer_id = isset($_POST['customer_id']) ? (int)$_POST['customer_id'] : 0;

// optional: template name (from compose select)
$template_name = '';
if (isset($_POST['template_name'])) {
    $template_name = trim($_POST['template_name']);
} elseif (isset($_POST['template'])) {
    $template_name = trim($_POST['template']);
}

// get company details for From/Reply-To
$company_email = '';
$company_name = '';
$q = 'SELECT * FROM ' . PRFX . 'TABLE_COMPANY LIMIT 1';
if ($rs = $db->Execute($q)) {
    $company_email = isset($rs->fields['COMPANY_EMAIL']) ? $rs->fields['COMPANY_EMAIL'] : '';
    $company_name = isset($rs->fields['COMPANY_NAME']) ? $rs->fields['COMPANY_NAME'] : '';
}

$result = array('sent' => array(), 'failed' => array(), 'invalid' => array());

function parse_email_address($input)
{
    $input = trim((string)$input);
    if ($input === '') {
        return false;
    }
    if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
        return $input;
    }
    if (preg_match('/<\s*([^>\s]+@[^>\s]+)\s*>$/', $input, $matches)) {
        $email = trim($matches[1]);
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : false;
    }
    return false;
}

if ($to_raw === '') {
    $result['error'] = 'No recipient specified';
} else {
    // allow comma-separated list for To and BCC
    $tos = array_filter(array_map('trim', explode(',', $to_raw)));
    $bccs = array_filter(array_map('trim', explode(',', $bcc_raw)));

    // attempt to load PHPMailer via composer autoload if present
    $has_phpmailer = false;
    if (is_file('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            $has_phpmailer = true;
        }
    }

    // load SMTP settings from SETUP table if available
    $smtp = array();
    $rs_cfg = @$db->Execute("SHOW COLUMNS FROM " . PRFX . "SETUP LIKE 'SMTP_HOST'");
    if ($rs_cfg && !$rs_cfg->EOF) {
        $r = $db->Execute("SELECT * FROM " . PRFX . "SETUP LIMIT 1");
        if ($r && !$r->EOF) {
            $smtp['host'] = isset($r->fields['SMTP_HOST']) ? $r->fields['SMTP_HOST'] : '';
            $smtp['port'] = isset($r->fields['SMTP_PORT']) ? $r->fields['SMTP_PORT'] : 25;
            $smtp['user'] = isset($r->fields['SMTP_USER']) ? $r->fields['SMTP_USER'] : '';
            $smtp['pass'] = isset($r->fields['SMTP_PASS']) ? $r->fields['SMTP_PASS'] : '';
            $smtp['secure'] = isset($r->fields['SMTP_SECURE']) ? $r->fields['SMTP_SECURE'] : '';
            $smtp['auth'] = isset($r->fields['SMTP_AUTH']) ? $r->fields['SMTP_AUTH'] : 0;
        }
    }

    // decrypt SMTP password if encrypted
    require_once(INCLUDE_URL . 'smtp_crypt.php');
    if (!empty($smtp['pass'])) {
        $dec = citecrm_decrypt_smtp_pass($smtp['pass']);
        if ($dec !== null) $smtp['pass'] = $dec;
    }

    // include email tracker helper
    if (is_file(INCLUDE_URL . 'email_tracker.php')) {
        require_once(INCLUDE_URL . 'email_tracker.php');
    }
    // include email logger helper
    if (is_file(INCLUDE_URL . 'email_logger.php')) {
        require_once(INCLUDE_URL . 'email_logger.php');
    }

    foreach ($tos as $to) {
        $to_email = parse_email_address($to);
        if ($to_email === false) {
            $result['invalid'][] = $to;
            continue;
        }
        $to = $to_email;

        // if template selected and customer matching, try replace placeholders
        $personal_message = $message;
        $personal_name = '';
        if ($customer_id > 0) {
            $r2 = $db->Execute("SELECT CUSTOMER_DISPLAY_NAME FROM " . PRFX . "TABLE_CUSTOMER WHERE CUSTOMER_ID=" . $db->qstr($customer_id) . " LIMIT 1");
            if ($r2 && !$r2->EOF) $personal_name = $r2->fields['CUSTOMER_DISPLAY_NAME'];
        } else {
            // attempt to find customer by email
            $r2 = $db->Execute("SELECT CUSTOMER_ID, CUSTOMER_DISPLAY_NAME FROM " . PRFX . "TABLE_CUSTOMER WHERE CUSTOMER_EMAIL=" . $db->qstr($to) . " LIMIT 1");
            if ($r2 && !$r2->EOF) {
                $personal_name = $r2->fields['CUSTOMER_DISPLAY_NAME'];
            }
        }

        if ($template_name !== '') {
            // try JSON template first: templates/messaging/<slug>.json
            $json_path = 'templates' . SEP . 'messaging' . SEP . basename($template_name) . '.json';
            if (is_file($json_path)) {
                $tpl = json_decode(file_get_contents($json_path), true);
                if ($tpl && isset($tpl['content'])) {
                    $personal_message = $tpl['content'];
                    // if subject empty, use template subject
                    if ($subject === '' && isset($tpl['subject'])) $subject = $tpl['subject'];
                }
            } else {
                // fallback to legacy plain file templates (email_templates folder)
                $tpl_path = 'templates' . SEP . 'messaging' . SEP . 'email_templates' . SEP . basename($template_name);
                if (is_file($tpl_path)) {
                    $tpl_content = file_get_contents($tpl_path);
                    $personal_message = $tpl_content;
                }
            }

            // standardize placeholder replacements for JSON or legacy templates
            $replacements = array(
                '{{name}}' => $personal_name,
                '{{email}}' => $to,
                '{{date}}' => date('Y-m-d'),
                '{{company}}' => $company_name,
                '{CUSTOMER_NAME}' => $personal_name,
                '{{customer_name}}' => $personal_name,
                '{{CUSTOMER_NAME}}' => $personal_name
            );
            $personal_message = str_replace(array_keys($replacements), array_values($replacements), $personal_message);

            // auto-detect HTML in template and enable HTML sending if template contains tags
            if (!$is_html && preg_match('/<[^>]+>/', $personal_message)) {
                $is_html = true;
            }
        }

        // Prepare tracking for this recipient
        $email_id = uniqid('crm_');
        $token = '';
        if (function_exists('crm_generate_tracking_token')) {
            $token = crm_generate_tracking_token($email_id, $to);
        }

        // embed tracking into HTML messages: rewrite links and append pixel
        $personal_message_original = $personal_message;
        if ($is_html && $token !== '') {
            // replace href targets with tracked redirect
            $personal_message = preg_replace_callback('/href=["\'](https?:\\/\\/[^"\']+)["\']/', function ($m) use ($token, $email_id) {
                $url = $m[1];
                $b64 = rtrim(strtr(base64_encode($url), '+/', '-_'), '=');
                $tracked = "/scripts/email_click.php?u=" . $b64 . "&t=" . urlencode($token) . "&eid=" . urlencode($email_id);
                return 'href="' . $tracked . '"';
            }, $personal_message);

            // append invisible tracking pixel
            if (function_exists('crm_tracking_pixel')) {
                $personal_message .= '<img src="' . crm_tracking_pixel($token, $email_id) . '" width="1" height="1" alt="" style="display:none;" />';
            }
        } elseif (!$is_html && $token !== '') {
            // for plain text append a short tracking notice with a tracked link to the first URL found
            if (preg_match('/https?:\\/\\/[^\s]+/', $personal_message, $murl)) {
                $b64 = rtrim(strtr(base64_encode($murl[0]), '+/', '-_'), '=');
                $tracked = "/scripts/email_click.php?u=" . $b64 . "&t=" . urlencode($token) . "&eid=" . urlencode($email_id);
                $personal_message .= "\n\nTrack link: " . $tracked;
            }
        }

        // Try sending via PHPMailer if available
        $sent = false;
        $send_error = '';
        if ($has_phpmailer) {
            try {
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                if (!empty($smtp['host'])) {
                    $mail->isSMTP();
                    $mail->Host = $smtp['host'];
                    $mail->Port = (int)$smtp['port'];
                    if (!empty($smtp['secure'])) $mail->SMTPSecure = $smtp['secure'];
                    if (!empty($smtp['auth']) && $smtp['auth']) {
                        $mail->SMTPAuth = true;
                        $mail->Username = $smtp['user'];
                        $mail->Password = $smtp['pass'];
                    }
                }
                // From
                if ($company_email !== '' && filter_var($company_email, FILTER_VALIDATE_EMAIL)) {
                    $mail->setFrom($company_email, $company_name);
                }
                $mail->addAddress($to);
                // add BCCs
                foreach ($bccs as $b) {
                    $bcc_email = parse_email_address($b);
                    if ($bcc_email !== false) $mail->addBCC($bcc_email);
                }
                $mail->Subject = $subject;
                if ($is_html) {
                    $mail->isHTML(true);
                    $mail->Body = $personal_message;
                    $mail->AltBody = strip_tags($personal_message);
                } else {
                    $mail->Body = $personal_message;
                }
                $sent = $mail->send();
                if (!$sent) {
                    $send_error = $mail->ErrorInfo;
                }
            } catch (Exception $e) {
                $send_error = $e->getMessage();
                $sent = false;
            }
        } else {
            // fallback to mail()
            // prepare headers and body for mail() fallback
            $headers = array();
            $headers[] = 'MIME-Version: 1.0';

            // prepare valid BCC list
            $valid_bcc = array();
            if (!empty($bccs)) {
                foreach ($bccs as $b) {
                    $bcc_email = parse_email_address($b);
                    if ($bcc_email !== false) $valid_bcc[] = $bcc_email;
                }
            }

            if ($is_html) {
                $boundary = '=_' . md5(uniqid((string)time(), true));
                $headers[] = 'Content-Type: multipart/alternative; boundary="' . $boundary . '"';
            } else {
                $headers[] = 'Content-Type: text/plain; charset=UTF-8';
            }

            if ($company_email !== '' && filter_var(trim($company_email), FILTER_VALIDATE_EMAIL)) {
                $headers[] = 'From: ' . $company_name . ' <' . trim($company_email) . '>';
                $headers[] = 'Reply-To: ' . trim($company_email);
            }

            if (!empty($valid_bcc)) {
                $headers[] = 'Bcc: ' . implode(',', $valid_bcc);
            }

            // build body: multipart/alternative when HTML, otherwise plain
            if ($is_html) {
                $textPart = trim(strip_tags(html_entity_decode($personal_message)));
                $htmlPart = $personal_message;

                $body = "--" . $boundary . "\r\n";
                $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
                $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
                $body .= $textPart . "\r\n\r\n";

                $body .= "--" . $boundary . "\r\n";
                $body .= "Content-Type: text/html; charset=UTF-8\r\n";
                $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
                $body .= $htmlPart . "\r\n\r\n";

                $body .= "--" . $boundary . "--";
            } else {
                $body = $personal_message;
            }

            $sent = @mail($to, $subject, $body, implode("\r\n", $headers));
            if (!$sent) {
                $send_error = 'PHP mail() failed';
            }
        }

        // Log the outbound email attempt
        if (function_exists('crm_log_email_activity')) {

            $log_params = array(
                'message_id' => $email_id,
                'direction' => 'out',
                'from' => $company_email,
                'to' => $to,
                'cc' => implode(',', $bccs),
                'bcc' => '',
                'subject' => $subject,
                'body' => $personal_message_original,
                'raw' => ($send_error !== '' ? $send_error : '')
            );
            crm_log_email_activity($log_params);
        }

        if ($sent) {
            $result['sent'][] = $to;
        } else {
            $error_message = $send_error !== '' ? ' (' . $send_error . ')' : '';
            $result['failed'][] = $to . $error_message;
        }
    }
}

$smarty->assign('result', $result);
$smarty->assign('subject', htmlspecialchars($subject));
$smarty->assign('message', htmlspecialchars($message));

$smarty->display('messaging' . SEP . 'result.tpl');
