<?php
####################################################
# IN Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Check Updates												#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################

$page	= 'page=update:check&crm_version='.CITE_CRM_VERSION.'&escape=1';

/* get curent version and check against citecrm */
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, INCITCRM);
curl_setopt ($ch, CURLOPT_POST, 1);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $page);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
$content = curl_exec ($ch); # This returns HTML
curl_close ($ch);

/* check for response */
if( $content == '') {
	$smarty->assign('status','0');
	$smarty->assign('message','No response from server');
	$smarty->display('control'.SEP.'check.tpl');
	exit;
}

/* pars xml */
$parser = xml_parser_create();
xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
xml_parse_into_struct($parser, $content, $values, $tags);
xml_parser_free($parser);


foreach($values as $xml){
		if($xml['tag'] == "UPDATE_STATUS" && $xml['value'] != ""){
			$status = $xml['value'];
		}
		
		if($xml['tag'] == "UPDATE_FILE" && $xml['value'] != ""){
			$file= $xml['value'];
		}

		if($xml['tag'] == "UPDATE_DATE" && $xml['value'] != ""){
			$date= $xml['value'];
		}

		if($xml['tag'] == "UPDATE_MESSAGE" && $xml['value'] != ""){
			$message= $xml['value'];
		}

		if($xml['tag'] == "CUR_VERSION" && $xml['value'] != ""){
			$cur_version = $xml['value'];
		}

}

$smarty->assign('status',$status);
$smarty->assign('file',$file);
$smarty->assign('date',$date);
$smarty->assign('message',$message);
$smarty->assign('cur_version',$cur_version);

$smarty->display('control'.SEP.'check.tpl');
	

?>