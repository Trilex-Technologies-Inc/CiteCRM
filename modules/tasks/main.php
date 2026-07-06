<?php
/* Tasks list page. */
require_once 'modules' . SEP . 'tasks' . SEP . 'include.php';

$error_msg = '';
if (!tasks_table_exists($db)) {
    if (!tasks_install_table($db) || !tasks_table_exists($db)) {
        $error_msg = 'Tasks module is installed, but its database table is missing. Reinstall it from Control > Modules.';
    }
}

$status = isset($VAR['status']) ? (string)$VAR['status'] : 'open';
$allowed_statuses = array('open', 'overdue', 'completed', 'all');

if (!in_array($status, $allowed_statuses, true)) {
    $status = 'open';
}

$tasks = array();
if ($error_msg === '') {
    $tasks = tasks_get_all($db, $status);

    if ($tasks === false) {
        $tasks = array();
        $error_msg = 'Could not load tasks: ' . $db->ErrorMsg();
    }
}

$smarty->assign('tasks', $tasks);
$smarty->assign('task_status', $status);
$smarty->assign('today', date('Y-m-d'));
$smarty->assign('error_msg', $error_msg);
$smarty->assign('page_title', 'Tasks');
$smarty->display('tasks' . SEP . 'main.tpl');
