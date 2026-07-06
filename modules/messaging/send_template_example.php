<?php
// Example: render a messaging template and send via existing send.php or mail()
require_once __DIR__ . '/template_helpers.php';

$template = 'welcome';
$placeholders = ['name' => 'Jane', 'link' => 'https://example.com/confirm', 'date' => date('Y-m-d')];
$render = messaging_render_template($template, $placeholders);
if (!$render) {
    echo "Template not found: $template";
    exit;
}

$to = 'recipient@example.com';
$subject = $render['subject'];
$html = $render['html'];

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: no-reply@example.com\r\n";

if (mail($to, $subject, $html, $headers)) echo "Sent";
else echo "Failed";
