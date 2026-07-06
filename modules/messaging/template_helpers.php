<?php
// Helpers for messaging module templates
function messaging_render_template($slug, $placeholders = [])
{
    $base = __DIR__ . '/../../templates/messaging';
    $path = $base . '/' . basename($slug) . '.json';
    if (!is_file($path)) return null;
    $data = json_decode(file_get_contents($path), true);
    $subject = isset($data['subject']) ? $data['subject'] : '';
    $html = isset($data['content']) ? $data['content'] : '';
    foreach ($placeholders as $k => $v) {
        $subject = str_replace('{{' . $k . '}}', $v, $subject);
        $html = str_replace('{{' . $k . '}}', $v, $html);
    }
    $subject = preg_replace('/{{\s*[^}]+\s*}}/', '', $subject);
    $html = preg_replace('/{{\s*[^}]+\s*}}/', '', $html);
    $text = trim(strip_tags($html));
    return ['subject' => $subject, 'html' => $html, 'text' => $text];
}
