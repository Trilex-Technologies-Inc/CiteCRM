<?php
/* Messaging - list customers to email */

// load language for this module if available
if (function_exists('xml2php')) {
    @xml2php("customer");
}

$q = "SELECT CUSTOMER_ID, CUSTOMER_DISPLAY_NAME, CUSTOMER_EMAIL FROM " . PRFX . "TABLE_CUSTOMER ORDER BY CUSTOMER_DISPLAY_NAME";
if (!$rs = $db->Execute($q)) {
    force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
    exit;
}

$customers = $rs->GetArray();
$smarty->assign('customers', $customers);

$smarty->display('messaging' . SEP . 'list.tpl');

?>
