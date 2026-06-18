<?php
require 'conf.php';

// simple admin list and enable/disable
if (empty($_SESSION['ADMIN'])) {
    header('HTTP/1.1 403 Forbidden');
    echo 'Forbidden';
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
if ($action === 'disable' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $db->Execute("UPDATE " . PRFX . "EMAIL_ACCOUNTS SET ENABLED=0 WHERE ACCOUNT_ID=" . $db->qstr($id));
    header('Location: modules/messaging/accounts.php');
    exit;
}

$accounts = array();
$rs = $db->Execute("SELECT * FROM " . PRFX . "EMAIL_ACCOUNTS ORDER BY CREATED_AT DESC");
if ($rs) {
    while (!$rs->EOF) {
        $accounts[] = $rs->fields;
        $rs->MoveNext();
    }
}

$smarty->assign('accounts', $accounts);
$smarty->display('messaging' . SEP . 'accounts.tpl');

?>
