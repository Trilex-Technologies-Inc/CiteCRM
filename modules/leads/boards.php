<?php
/* Leads - boards view */
require_once 'modules' . SEP . 'leads' . SEP . 'include.php';

$boards = leads_get_boards();

$smarty->assign('boards', $boards);
$smarty->display('leads' . SEP . 'boards.tpl');
