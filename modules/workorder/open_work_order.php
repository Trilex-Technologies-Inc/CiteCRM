<?php
####################################
# 			Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM							#
#  www.citecrm.com  dev@onsitecrm.com						#
#  This program is distributed under the terms and 			#
#  conditions of the GPL													#
#  open_work_order.php													#
#  Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005				#
#																						#
####################################
require_once('modules'.SEP.'workorder'.SEP.'include.php');
$smarty->assign('return_array', display_open_workorders($db));

$smarty->display('workorder'.SEP.'blocks'.SEP.'open_work_orders_block.tpl');
?>