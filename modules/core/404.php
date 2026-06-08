<?php

$smarty->assign('page_title', 'ERROR: 404');
$smarty->assign('pagename', $_SERVER['REQUEST_URI']);
$admin_email = '';
if (isset($db)) {
	$q = "SELECT ADMIN_EMAIL FROM ".PRFX."SETUP";
	$rs = $db->Execute($q);
	if ($rs && isset($rs->fields['ADMIN_EMAIL'])) {
		$admin_email = $rs->fields['ADMIN_EMAIL'];
	}
}
$smarty->assign('admin_email', $admin_email);

$smarty->display('core/404.tpl');

?>
