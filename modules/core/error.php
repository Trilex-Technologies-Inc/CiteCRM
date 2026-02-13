<?php
$error = isset($VAR['error_msg']) ? $VAR['error_msg'] : '';
$menu  = isset($VAR['menu']) ? $VAR['menu'] : 0;
$type  = isset($VAR['type']) ? $VAR['type'] : 'error';

if(!empty($error)) {
    switch($type) {
        case 'error':
            $smarty->assign('type', 'Error:');
            $VAR['page_title'] = 'Error';
            break;
        case 'info':
            $smarty->assign('type', 'Info:');
            $VAR['page_title'] = 'Info';
            break;
        case 'warning':
            $smarty->assign('type', 'Warning:');
            $VAR['page_title'] = "Warning";
            break;
        case 'database':
            $smarty->assign('type', 'Database Error:');
            $VAR['page_title'] = "Database Error";
            break;
        case 'system':
            $smarty->assign('type', 'System Error');
            $VAR['page_title'] = "System Error";
            break;
        default:
            $smarty->assign('type', 'Error:');
            $VAR['page_title'] = "Error";
            break;
    }

    $smarty->assign('error_msg', $error);
}
?>