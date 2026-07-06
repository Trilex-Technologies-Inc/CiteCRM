<?php
// Loads the messaging template editor inside the app routing (index.php?page=messaging:templates)
// Render a Smarty template at templates/messaging/template_editor.tpl so it uses the site's header/footer.

$templatesDir = __DIR__ . '/../../templates/messaging';
if (!is_dir($templatesDir)) mkdir($templatesDir, 0755, true);

$files = glob($templatesDir . '/*.json');
$templates = [];
foreach ($files as $f) {
    $data = json_decode(file_get_contents($f), true);
    if (!$data) continue;
    $templates[] = [
        'slug' => basename($f, '.json'),
        'title' => isset($data['title']) ? $data['title'] : basename($f, '.json'),
        'subject' => isset($data['subject']) ? $data['subject'] : '',
        'updated_at' => isset($data['updated_at']) ? $data['updated_at'] : ''
    ];
}

$editing = null;
if (!empty($_GET['slug'])) {
    $path = $templatesDir . '/' . basename($_GET['slug']) . '.json';
    if (is_file($path)) $editing = json_decode(file_get_contents($path), true);
}

$smarty->assign('templates_list', $templates);
$smarty->assign('editing', $editing);

$smarty->display('messaging' . SEP . 'template_editor.tpl');
