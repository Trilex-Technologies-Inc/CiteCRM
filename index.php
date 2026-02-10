<?php
#########################################################
# 			Cite CRM	Customer Relations Management	#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.citecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 		#
#  conditions of the GPL								#
#  index.php											#
#  Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005		#
#														#
#########################################################
/* check if lock file exists if not we need to install */
session_start();
require('conf.php');

if(!is_file('lock') ) {
	echo("
		<script type=\"text/javascript\">
			<!--
			window.location = \"install\"
			//-->
		</script>");
} else if(is_dir('install') ) { 
	echo("<font color=\"red\">The install Directory Exists!! Please Rename or remove the install directory.</font>");
	die;
} 
	
$VAR = array_merge($_GET, $_POST);
$page_title = isset($VAR['page_title']) ? $VAR['page_title'] : 'Home'; // FIXED: Check if set

$auth = &new Auth($db, 'login.php', 'secret');
require(INCLUDE_URL.SEP.'acl.php');

require('modules/core/translate.php');
############################
#		Debuging					#
############################

function getMicroTime() {
  list($usec, $sec) = explode(" ", microtime()); 
	return (float)$usec + (float)$sec;
} 

$start = getMicroTime();



// If logg of is set then we log off
if (isset($VAR['action']) && $VAR['action'] == 'logout') {
  $auth->logout('login.php');
}

/* get company info for defaults */
$q = 'SELECT * FROM '.PRFX.'TABLE_COMPANY';
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
$smarty->assign('company_name', $rs->fields['COMPANY_NAME']);
$smarty->assign('company_address', $rs->fields['COMPANY_ADDRESS']);
$smarty->assign('company_city', $rs->fields['COMPANY_CITY']);
$smarty->assign('company_state', $rs->fields['COMPANY_STATE']);
$smarty->assign('company_zip', $rs->fields['COMPANY_ZIP']);
$smarty->assign('company_country', $rs->fields['COMPANY_COUNTRY']);
$smarty->assign('company_phone',$rs->fields['COMPNAY_PHONE']);
$smarty->assign('company_email',$rs->fields['COMPANY_EMAIL']); 
$smarty->assign('company_toll_free',$rs->fields['COMPANY_TOLL_FREE']);
$smarty->assign('compnay_mobile',$rs->fields['COMPNAY_MOBILE']);


#############################################################
#	Url Builder This grabs gets and post and builds the url	# 
#	conection strings										#
#############################################################
$module = 'core';
$page = 'main';
$the_page = 'modules'.SEP.'core'.SEP.'main.php';

if(!isset($_POST['page'])) {
    if (isset($_GET['page']) && !empty($_GET['page'])) { // FIXED: Check if set
        // Explode the url so we can get the module and page
        list($module, $page) = explode(":", $_GET['page']);
        $the_page = 'modules'.SEP.$module.SEP.$page.'.php';

        // remove page from the $_GET array we dont want it to pass the options
        unset($_GET['page']);

        // Define the global options for each page
        foreach($_GET as $key=>$val){
            @define($key, $val);
        }

        // Check to see if the page is real other wise send em a 404
        if ( file_exists ($the_page) ) {
            $the_page = 'modules'.SEP.$module.SEP.$page.'.php';
        } else {
            $the_page = 'modules'.SEP.'core'.SEP.'404.php';
            $module = 'core';
            $page = '404';
        }
    } else {
        // If no page is supplied then go to the main page
        $the_page = 'modules'.SEP.'core'.SEP.'main.php';
        $module = 'core';
        $page = 'main';
    }
} else {
    if (isset($_POST['page']) && !empty($_POST['page'])) { // FIXED: Check if set
        // Explode the url so we can get the module and page
        list($module, $page) = explode(":", $_POST['page']);
        $the_page = 'modules'.SEP.$module.SEP.$page.'.php';

        // remove page from the $_GET array we dont want it to pass the options
        unset($_POST['page']);

        // Define the global options for each page
        foreach($_POST as $key=>$val){
            @define($key, $val);
        }

        // Check to see if the page is real other wise send em a 404
        if ( file_exists ($the_page) ) {
            $the_page = 'modules'.SEP.$module.SEP.$page.'.php';
        } else {
            $the_page = 'modules'.SEP.'core'.SEP.'404.php';
            $module = 'core';
            $page = '404';
        }
    }
}


$tracker_page = "$module:$page";


#####################################
#	Display the pages				#
#####################################  

if(isset($_GET['wo_id'])) {
	$smarty->assign('wo_id', $_GET['wo_id']);
	global $wo_id;
} else {
	$smarty->assign('wo_id','0');
}
require('modules'.SEP.'core'.SEP.'error.php');

$smarty->assign('page_title', $page_title); // FIXED: Now uses initialized variable

if(isset($VAR['msg'])) {
	$smarty->assign('msg', $VAR['msg']);
}

if(!isset($VAR['escape']) || $VAR['escape'] != 1 ) { // FIXED: Check if escape is set
	require('modules'.SEP.'core'.SEP.'header.php');
	require('modules'.SEP.'core'.SEP.'navagation.php');
	require('modules'.SEP.'core'.SEP.'company.php');
}

$menu = isset($VAR['menu']) ? $VAR['menu'] : 0; // FIXED: Initialize menu variable
if($menu == 1 ) {
	$smarty->assign('menu', '1');
	$smarty->display('core'.SEP.'error.tpl');
} else {
	
	/* check acl for page request */
	if(!check_acl($db,$module,$page)) {
		force_page('core','error&error_msg=You do not have permission to access this '.$module.':'.$page.'&menu=1');
	} else { 
		require($the_page);
	}
}

if(!isset($VAR['escape']) || $VAR['escape'] != 1 ) { // FIXED: Check if escape is set
	require('modules'.SEP.'core'.SEP.'footer.php');
}

/* tracker code */
function getIP() {
	$ip;
	if (getenv("HTTP_CLIENT_IP")) $ip = getenv("HTTP_CLIENT_IP");
	else if(getenv("HTTP_X_FORWARDED_FOR")) $ip = getenv("HTTP_X_FORWARDED_FOR");
	else if(getenv("REMOTE_ADDR")) $ip = getenv("REMOTE_ADDR");
	else $ip = "UNKNOWN";
	return $ip;
}



$logtime = time();
$q = 'INSERT into '.PRFX.'tracker SET
   date						='. $db->qstr( $logtime						).',
   ip							='. $db->qstr( getIP()						).',
   uagent						='. $db->qstr( isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''	).', // FIXED: Use $_SERVER
   full_page					='. $db->qstr( $the_page						).',
   module					='. $db->qstr( $module						).',
   page						='. $db->qstr( $page							).',
   referer					='. $db->qstr( isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''	); // FIXED: Use $_SERVER

   if(!$rs = $db->Execute($q)) {
      echo 'Error inserting tracker :'. $db->ErrorMsg();
   }
 
?>