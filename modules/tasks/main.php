<?php
/* Tasks list page. */
require_once 'modules' . SEP . 'tasks' . SEP . 'include.php';

$status = isset($VAR['status']) ? (string)$VAR['status'] : 'open';
$allowed_statuses = array('open', 'overdue', 'completed', 'all');

if (!in_array($status, $allowed_statuses, true)) {
    $status = 'open';
}

$tasks = tasks_get_all($db, $status);
$error_msg = '';

if ($tasks === false) {
    $tasks = array();
    $error_msg = 'Could not load tasks: ' . $db->ErrorMsg();
}

$smarty->assign('tasks', $tasks);
$smarty->assign('task_status', $status);
$smarty->assign('today', date('Y-m-d'));
$smarty->assign('error_msg', $error_msg);
$smarty->assign('page_title', 'Tasks');
$smarty->display('tasks' . SEP . 'main.tpl');
