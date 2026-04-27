<?php
/*
 * Shared monthly stats used by:
 * - stats:main page
 * - core:main (home) dashboard row
 */

$month_start = mktime(0, 0, 0, date("m"), 1, date("Y"));
$month_end = mktime(23, 59, 59, date("m") + 1, 0, date("Y"));

/* open work orders this month */
$q = "SELECT count(*) AS count
	FROM ".PRFX."TABLE_WORK_ORDER
	WHERE WORK_ORDER_OPEN_DATE >= ".$db->qstr($month_start)."
	  AND WORK_ORDER_OPEN_DATE <= ".$db->qstr($month_end);
if(!$rs = $db->Execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$smarty->assign('month_open', (int)$rs->fields['count']);

/* closed work orders this month (based on close date) */
$q = "SELECT count(*) AS count
	FROM ".PRFX."TABLE_WORK_ORDER
	WHERE WORK_ORDER_CLOSE_DATE IS NOT NULL
	  AND WORK_ORDER_CLOSE_DATE >= ".$db->qstr($month_start)."
	  AND WORK_ORDER_CLOSE_DATE <= ".$db->qstr($month_end);
if(!$rs = $db->Execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$smarty->assign('month_closed', (int)$rs->fields['count']);

/* customers */
$q = "SELECT count(*) AS count
	FROM ".PRFX."TABLE_CUSTOMER
	WHERE CREATE_DATE >= ".$db->qstr($month_start)."
	  AND CREATE_DATE <= ".$db->qstr($month_end);
if(!$rs = $db->Execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$smarty->assign('new_customers', (int)$rs->fields['count']);

$q = "SELECT count(*) AS count FROM ".PRFX."TABLE_CUSTOMER";
if(!$rs = $db->Execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$smarty->assign('total_customers', (int)$rs->fields['count']);

/* invoices */
$q = "SELECT count(*) AS count
	FROM ".PRFX."TABLE_INVOICE
	WHERE INVOICE_PAID = ".$db->qstr(0)."
	  AND INVOICE_DATE >= ".$db->qstr($month_start)."
	  AND INVOICE_DATE <= ".$db->qstr($month_end);
if(!$rs = $db->Execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$smarty->assign('open_invoices', (int)$rs->fields['count']);

$q = "SELECT count(*) AS count
	FROM ".PRFX."TABLE_INVOICE
	WHERE INVOICE_PAID = ".$db->qstr(1)."
	  AND PAID_DATE >= ".$db->qstr($month_start)."
	  AND PAID_DATE <= ".$db->qstr($month_end);
if(!$rs = $db->Execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$smarty->assign('closed_invoices', (int)$rs->fields['count']);

/* revenue this month (sum of payments) */
$q = "SELECT IFNULL(SUM(AMOUNT), 0) AS sum
	FROM ".PRFX."TABLE_TRANSACTION
	WHERE DATE >= ".$db->qstr($month_start)."
	  AND DATE <= ".$db->qstr($month_end);
if(!$rs = $db->Execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$smarty->assign('total_revenue', number_format((float)$rs->fields['sum'], 2, '.', ','));

/* losses this month (outstanding invoice amounts created this month) */
$q = "SELECT IFNULL(SUM(INVOICE_AMOUNT), 0) AS sum
	FROM ".PRFX."TABLE_INVOICE
	WHERE INVOICE_PAID = ".$db->qstr(0)."
	  AND BALLANCE = ".$db->qstr(0)."
	  AND INVOICE_DATE >= ".$db->qstr($month_start)."
	  AND INVOICE_DATE <= ".$db->qstr($month_end);
if(!$rs = $db->Execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$unpaid_sum = (float)$rs->fields['sum'];

$q = "SELECT IFNULL(SUM(BALLANCE), 0) AS sum
	FROM ".PRFX."TABLE_INVOICE
	WHERE INVOICE_PAID = ".$db->qstr(0)."
	  AND BALLANCE > ".$db->qstr(0)."
	  AND INVOICE_DATE >= ".$db->qstr($month_start)."
	  AND INVOICE_DATE <= ".$db->qstr($month_end);
if(!$rs = $db->Execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$partial_sum = (float)$rs->fields['sum'];
$smarty->assign('total_losses', number_format($unpaid_sum + $partial_sum, 2, '.', ','));

