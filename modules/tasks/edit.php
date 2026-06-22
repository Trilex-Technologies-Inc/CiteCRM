<?php
/* Edit a task. */
require_once 'modules' . SEP . 'tasks' . SEP . 'include.php';

$task_id = isset($VAR['id']) ? (int)$VAR['id'] : 0;
if ($task_id < 1) {
    tasks_redirect('main', 'Invalid task ID.');
}

$task = tasks_get_one($db, $task_id);
if (!$task) {
    tasks_redirect('main', 'Task not found.');
}

$error_msg = '';

if (isset($VAR['submit'])) {
    $title = isset($VAR['title']) ? trim((string)$VAR['title']) : '';
    $description = isset($VAR['description']) ? trim((string)$VAR['description']) : '';
    $priority = isset($VAR['priority']) ? (string)$VAR['priority'] : 'Normal';
    $due_date = isset($VAR['due_date']) ? trim((string)$VAR['due_date']) : '';
    $assigned_to = isset($VAR['assigned_to']) ? trim((string)$VAR['assigned_to']) : '';

    if ($title === '') {
        $error_msg = 'Title is required.';
    } elseif (strlen($title) > 255) {
        $error_msg = 'Title cannot be longer than 255 characters.';
    } elseif (!in_array($priority, tasks_allowed_priorities(), true)) {
        $error_msg = 'Please select a valid priority.';
    } elseif (!tasks_valid_date($due_date)) {
        $error_msg = 'Please enter a valid due date.';
    } elseif (!tasks_employee_exists($db, $assigned_to)) {
        $error_msg = 'Please select a valid employee.';
    } else {
        $sql = "UPDATE " . PRFX . "TASKS SET
                TITLE=" . $db->qstr($title) . ",
                DESCRIPTION=" . $db->qstr($description) . ",
                PRIORITY=" . $db->qstr($priority) . ",
                DUE_DATE=" . ($due_date === '' ? 'NULL' : $db->qstr($due_date)) . ",
                ASSIGNED_TO=" . ($assigned_to === '' ? 'NULL' : $db->qstr($assigned_to)) . ",
                UPDATED_AT=NOW()
                WHERE TASK_ID=" . $db->qstr($task_id);

        if (!$db->Execute($sql)) {
            $error_msg = 'Could not update the task: ' . $db->ErrorMsg();
        } else {
            tasks_redirect('main', 'Task updated successfully.');
        }
    }

    $task['TITLE'] = $title;
    $task['DESCRIPTION'] = $description;
    $task['PRIORITY'] = $priority;
    $task['DUE_DATE'] = $due_date;
    $task['ASSIGNED_TO'] = $assigned_to;
}

$smarty->assign('employees', tasks_get_employees($db));
$smarty->assign('task', $task);
$smarty->assign('task_id', $task_id);
$smarty->assign('error_msg', $error_msg);
$smarty->assign('form_heading', 'Edit Task');
$smarty->assign('form_action', 'edit');
$smarty->assign('submit_label', 'Update Task');
$smarty->assign('page_title', 'Edit Task');
$smarty->display('tasks' . SEP . 'form.tpl');
