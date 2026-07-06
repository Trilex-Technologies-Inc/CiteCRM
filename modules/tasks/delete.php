<?php
/* Confirm and delete a task. */
require_once 'modules' . SEP . 'tasks' . SEP . 'include.php';

$task_id = isset($VAR['id']) ? (int)$VAR['id'] : 0;
if ($task_id < 1) {
    tasks_redirect('main', 'Invalid task ID.');
}

$task = tasks_get_one($db, $task_id);
if (!$task) {
    tasks_redirect('main', 'Task not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($VAR['confirm']) && $VAR['confirm'] === 'yes') {
    $sql = "DELETE FROM " . PRFX . "TASKS
            WHERE TASK_ID=" . $db->qstr($task_id);

    if (!$db->Execute($sql)) {
        $smarty->assign('error_msg', 'Could not delete the task: ' . $db->ErrorMsg());
    } else {
        tasks_redirect('main', 'Task deleted successfully.');
    }
}

$smarty->assign('task', $task);
$smarty->assign('task_id', $task_id);
$smarty->assign('page_title', 'Delete Task');
$smarty->display('tasks' . SEP . 'delete.tpl');
