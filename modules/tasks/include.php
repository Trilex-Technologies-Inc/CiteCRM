<?php
/* Shared functions for the Tasks module. */

function tasks_allowed_priorities()
{
    return array('Low', 'Normal', 'High');
}

function tasks_valid_date($date)
{
    if ($date === '') {
        return true;
    }

    if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $date, $parts)) {
        return false;
    }

    return checkdate((int)$parts[2], (int)$parts[3], (int)$parts[1]);
}

function tasks_get_employees($db)
{
    $employees = array();
    $sql = "SELECT EMPLOYEE_ID, EMPLOYEE_DISPLAY_NAME
            FROM " . PRFX . "TABLE_EMPLOYEE
            ORDER BY EMPLOYEE_DISPLAY_NAME";

    if ($result = $db->Execute($sql)) {
        $employees = $result->GetArray();
    }

    return $employees;
}

function tasks_employee_exists($db, $employee_id)
{
    if ($employee_id === '') {
        return true;
    }

    $sql = "SELECT EMPLOYEE_ID
            FROM " . PRFX . "TABLE_EMPLOYEE
            WHERE EMPLOYEE_ID=" . $db->qstr($employee_id) . "
            LIMIT 1";
    $result = $db->Execute($sql);

    return ($result && !$result->EOF);
}

function tasks_get_one($db, $task_id)
{
    $sql = "SELECT T.*,
                   A.EMPLOYEE_DISPLAY_NAME AS ASSIGNED_NAME,
                   C.EMPLOYEE_DISPLAY_NAME AS CREATED_BY_NAME,
                   F.EMPLOYEE_DISPLAY_NAME AS COMPLETED_BY_NAME
            FROM " . PRFX . "TASKS T
            LEFT JOIN " . PRFX . "TABLE_EMPLOYEE A
                ON A.EMPLOYEE_ID=T.ASSIGNED_TO
            LEFT JOIN " . PRFX . "TABLE_EMPLOYEE C
                ON C.EMPLOYEE_ID=T.CREATED_BY
            LEFT JOIN " . PRFX . "TABLE_EMPLOYEE F
                ON F.EMPLOYEE_ID=T.COMPLETED_BY
            WHERE T.TASK_ID=" . $db->qstr($task_id) . "
            LIMIT 1";
    $result = $db->Execute($sql);

    if (!$result || $result->EOF) {
        return false;
    }

    return $result->FetchRow();
}

function tasks_get_all($db, $status)
{
    $where = '';
    if ($status === 'open') {
        $where = "WHERE T.IS_COMPLETE=0";
    } elseif ($status === 'completed') {
        $where = "WHERE T.IS_COMPLETE=1";
    } elseif ($status === 'overdue') {
        $where = "WHERE T.IS_COMPLETE=0
                  AND T.DUE_DATE IS NOT NULL
                  AND T.DUE_DATE < CURDATE()";
    }

    $sql = "SELECT T.*,
                   A.EMPLOYEE_DISPLAY_NAME AS ASSIGNED_NAME,
                   C.EMPLOYEE_DISPLAY_NAME AS CREATED_BY_NAME
            FROM " . PRFX . "TASKS T
            LEFT JOIN " . PRFX . "TABLE_EMPLOYEE A
                ON A.EMPLOYEE_ID=T.ASSIGNED_TO
            LEFT JOIN " . PRFX . "TABLE_EMPLOYEE C
                ON C.EMPLOYEE_ID=T.CREATED_BY
            " . $where . "
            ORDER BY T.IS_COMPLETE ASC,
                     CASE WHEN T.DUE_DATE IS NULL THEN 1 ELSE 0 END,
                     T.DUE_DATE ASC,
                     T.TASK_ID DESC";
    $result = $db->Execute($sql);

    if (!$result) {
        return false;
    }

    return $result->GetArray();
}

function tasks_redirect($page, $message)
{
    force_page('tasks', $page . '&msg=' . rawurlencode($message));
    exit;
}
