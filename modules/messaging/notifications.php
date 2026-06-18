<?php
// List notifications for the logged-in employee
require_once __DIR__ . '/../../conf.php';

session_start();
$login_id = isset($_SESSION['login_id']) ? (int)$_SESSION['login_id'] : 0;

if ($login_id <= 0) {
    echo "Unauthorized";
    exit;
}

// mark a notification read
if (isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
    $nid = (int)$_GET['mark_read'];
    @$db->Execute("UPDATE " . PRFX . "NOTIFICATIONS SET IS_READ=1 WHERE NOTIFICATION_ID=" . $db->qstr($nid) . " AND EMPLOYEE_ID=" . $db->qstr($login_id));
}

$r = $db->Execute("SELECT * FROM " . PRFX . "NOTIFICATIONS WHERE EMPLOYEE_ID=" . $db->qstr($login_id) . " ORDER BY CREATED_AT DESC LIMIT 100");
$notes = array();
if ($r) {
    while (!$r->EOF) {
        $notes[] = $r->fields;
        $r->MoveNext();
    }
}

$smarty->assign('notifications', $notes);
$smarty->display('messaging' . SEP . 'notifications.tpl');

?>
