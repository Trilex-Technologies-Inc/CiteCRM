<?php
/* Toggle task completion. This action accepts POST only. */
require_once 'modules' . SEP . 'tasks' . SEP . 'include.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    tasks_redirect('main', 'Task status changes must be submitted from the task list.');
}

$task_id = isset($VAR['id']) ? (int)$VAR['id'] : 0;
$complete = isset($VAR['complete']) && (string)$VAR['complete'] === '1';

if ($task_id < 1 || !tasks_get_one($db, $task_id)) {
    tasks_redirect('main', 'Task not found.');
}

$login_id = isset($_SESSION['login_id']) ? $_SESSION['login_id'] : '';

if ($complete) {
    $sql = "UPDATE " . PRFX . "TASKS SET
            IS_COMPLETE=1,
            COMPLETED_BY=" . ($login_id === '' ? 'NULL' : $db->qstr($login_id)) . ",
            COMPLETED_AT=NOW(),
            UPDATED_AT=NOW()
            WHERE TASK_ID=" . $db->qstr($task_id);
    $message = 'Task marked complete.';
} else {
    $sql = "UPDATE " . PRFX . "TASKS SET
            IS_COMPLETE=0,
            COMPLETED_BY=NULL,
            COMPLETED_AT=NULL,
            UPDATED_AT=NOW()
            WHERE TASK_ID=" . $db->qstr($task_id);
    $message = 'Task reopened.';
}

if (!$db->Execute($sql)) {
    tasks_redirect('main', 'Could not update the task.');
}

tasks_redirect('main', $message);
