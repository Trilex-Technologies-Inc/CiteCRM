<?php
require_once('include.php');

if (!xml2php("customer")) {
	$smarty->assign('error_msg', "Error in language file");
}

if (!customer_has_brand_new_column($db)) {
	force_page('customer', 'view&error_msg=Database upgrade required: missing CUSTOMER_BRAND_NEW column.');
	exit;
}

$customer_id = isset($VAR['customer_id']) ? (int)$VAR['customer_id'] : 0;
if ($customer_id < 1) {
	force_page('customer', 'view&error_msg=Missing customer_id.');
	exit;
}

$brand_new = !empty($VAR['brand_new']) ? 1 : 0;

$q = "UPDATE ".PRFX."TABLE_CUSTOMER SET CUSTOMER_BRAND_NEW=".$db->qstr($brand_new)." WHERE CUSTOMER_ID=".$db->qstr($customer_id);
if (!$db->Execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}

$name = isset($VAR['name']) ? (string)$VAR['name'] : '';
$page_no = isset($VAR['page_no']) ? (int)$VAR['page_no'] : 1;
if ($page_no < 1) {
	$page_no = 1;
}

force_page('customer', 'view&msg=Customer updated.&name='.urlencode($name).'&submit=submit&page_no='.$page_no);
exit;

?>

