<?php
// Save handler for modules/messaging/template_editor.php
$templatesDir = __DIR__ . '/../../templates/messaging';
if (!is_dir($templatesDir)) mkdir($templatesDir, 0755, true);

$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$subject = isset($_POST['subject']) ? $_POST['subject'] : '';
$content = isset($_POST['content']) ? $_POST['content'] : '';
$slug = isset($_POST['slug']) ? trim($_POST['slug']) : '';
$existing = isset($_POST['existing_slug']) ? trim($_POST['existing_slug']) : '';

if (empty($title) && empty($existing)) die('Title required');
if (empty($slug)) {
    $slug = preg_replace('/[^a-z0-9_\-]+/i', '-', strtolower($title));
    $slug = trim($slug, '-');
}

$file = $templatesDir . '/' . basename($slug) . '.json';
$data = ['slug' => $slug, 'title' => $title, 'subject' => $subject, 'content' => $content, 'updated_at' => date('c')];

if ($existing && $existing !== $slug) {
    $old = $templatesDir . '/' . basename($existing) . '.json';
    if (is_file($old)) @unlink($old);
}

file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
// Use app's force_page (JS redirect) to avoid "headers already sent" when Smarty/header output exists
if (function_exists('force_page')) {
    force_page('messaging', 'templates&slug=' . urlencode($slug));
    exit;
} else {
    header('Location: index.php?page=messaging:templates&slug=' . urlencode($slug));
    exit;
}
