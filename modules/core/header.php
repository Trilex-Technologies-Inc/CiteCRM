<?php


if(!xml2php("core")) {
	$smarty->assign('error_msg',"Error in language file");
}

$login = $_SESSION['login'];

if(!$login)
{
	$smarty->assign('login', '');
} else {
	$smarty->assign('login', $login);
	$smarty->assign('display_login', $login);
	$smarty->assign('login_id', $_SESSION['login_id']);
}

$smarty->display('core'.SEP.'header.tpl');
?>
