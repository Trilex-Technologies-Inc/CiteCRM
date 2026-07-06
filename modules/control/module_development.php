<?php
$guide_path = dirname(dirname(dirname(__FILE__))) . SEP . 'MODULE_DEVELOPMENT.md';
$guide_content = '';
$error_msg = '';

if (is_file($guide_path) && is_readable($guide_path)) {
    $guide_content = file_get_contents($guide_path);
    if ($guide_content === false) {
        $guide_content = '';
        $error_msg = 'Could not read the module development guide.';
    }
} else {
    $error_msg = 'Module development guide was not found.';
}

$smarty->assign('page_title', 'Module Development');

echo '<div class="container-fluid">';
echo '<div class="card shadow-sm">';
echo '<div class="card-header d-flex justify-content-between align-items-center">';
echo '<h2 class="mb-0">Module Development Guide</h2>';
echo '<a class="btn btn-outline-secondary btn-sm" href="index.php?page=control:modules&page_title=Modules">Back to Modules</a>';
echo '</div>';
echo '<div class="card-body">';

if ($error_msg !== '') {
    echo '<div class="alert alert-danger">' . htmlspecialchars($error_msg, ENT_QUOTES, 'UTF-8') . '</div>';
} else {
    echo '<pre class="bg-light border rounded p-3 mb-0" style="white-space: pre-wrap; overflow:auto;">' . htmlspecialchars($guide_content, ENT_QUOTES, 'UTF-8') . '</pre>';
}

echo '</div>';
echo '</div>';
echo '</div>';
?>
