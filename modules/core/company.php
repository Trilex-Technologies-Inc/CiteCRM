<?php

/* Stats */
$q = 'SELECT count(*) AS OPEN_COUNT FROM '.PRFX.'TABLE_WORK_ORDER WHERE WORK_ORDER_STATUS='.$db->qstr(10);
$rs = $db->Execute($q);
$open_count = $rs->fields['OPEN_COUNT'];
$smarty->assign('open_count', $open_count);

/* Assigned Work Orders */
$q = 'SELECT count(*) AS ASSIGNED FROM '.PRFX.'TABLE_WORK_ORDER WHERE  WORK_ORDER_CURENT_STATUS='.$db->qstr(2);
$rs = $db->Execute($q);
$assigned = $rs->fields['ASSIGNED'];
$smarty->assign('assigned', $assigned);

/* Awaiting Payment */
$q = 'SELECT count(*) AS AWAITING FROM '.PRFX.'TABLE_WORK_ORDER WHERE  WORK_ORDER_CURENT_STATUS='.$db->qstr(7);
$rs = $db->Execute($q);
$awaiting = $rs->fields['AWAITING'];
$smarty->assign('awaiting', $awaiting);



/* CRM Version */
$smarty->assign('VERSION', CITE_CRM_VERSION);

$smarty->display('core'.SEP.'company.tpl');
?>