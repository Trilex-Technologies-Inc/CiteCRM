<?php
/* Create a task. */
require_once 'modules' . SEP . 'tasks' . SEP . 'include.php';

$title = isset($VAR['title']) ? trim((string)$VAR['title']) : '';
$description = isset($VAR['description']) ? trim((string)$VAR['description']) : '';
$priority = isset($VAR['priority']) ? (string)$VAR['priority'] : 'Normal';
$due_date = isset($VAR['due_date']) ? trim((string)$VAR['due_date']) : '';
$assigned_to = isset($VAR['assigned_to']) ? trim((string)$VAR['assigned_to']) : '';
$error_msg = '';

if (isset($VAR['submit'])) {
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
        $created_by = isset($_SESSION['login_id'])
            ? $_SESSION['login_id']
            : '';

        $sql = "INSERT INTO " . PRFX . "TASKS SET
                TITLE=" . $db->qstr($title) . ",
                DESCRIPTION=" . $db->qstr($description) . ",
                PRIORITY=" . $db->qstr($priority) . ",
                DUE_DATE=" . ($due_date === '' ? 'NULL' : $db->qstr($due_date)) . ",
                ASSIGNED_TO=" . ($assigned_to === '' ? 'NULL' : $db->qstr($assigned_to)) . ",
                CREATED_BY=" . ($created_by === '' ? 'NULL' : $db->qstr($created_by));

        if (!$db->Execute($sql)) {
            $error_msg = 'Could not create the task: ' . $db->ErrorMsg();
        } else {
            tasks_redirect('main', 'Task created successfully.');
        }
    }
}

$smarty->assign('employees', tasks_get_employees($db));
$smarty->assign('task', array(
    'TITLE' => $title,
    'DESCRIPTION' => $description,
    'PRIORITY' => $priority,
    'DUE_DATE' => $due_date,
    'ASSIGNED_TO' => $assigned_to,
));
$smarty->assign('error_msg', $error_msg);
$smarty->assign('form_heading', 'New Task');
$smarty->assign('form_action', 'new');
$smarty->assign('submit_label', 'Create Task');
$smarty->assign('page_title', 'New Task');
$smarty->display('tasks' . SEP . 'form.tpl');
