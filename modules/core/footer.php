<?php
####################################
# 			Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM							#
#  www.citecrm.com  dev@onsitecrm.com						#
#  This program is distributed under the terms and 			#
#  conditions of the GPL													#
#  Footer.php																	#
#  Version 0.0.1	Fri Sep 30 04:42:07 PDT 2005				#
#																						#
####################################

####################################
#									Dsplay footer								#
####################################
$smarty->display('core'.SEP.'footer.tpl');

####################################
#							Dsplay debugg info								#
####################################
if(debug == 'yes')	{
	echo('Debuging is set on!<br>');
	
    echo('Dump of URL options passed ');
	print_r ($_GET);
	echo('<br>
	Page pulled:  $the_page<br>');
	echo 'Script exec time: ' . (getMicroTime() - $start .' secs<br>');
	echo('Cite CRM version: '.CITE_CRM_VERSION.'<br>');
	
	unset($VAR);
}
?>