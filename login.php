<?php
#####################################################
# Cite CRM	Customer Relations Management			#	
# Copyright (C) 2003 - 2005 In-Site CRM				#
# www.citecrm.com  dev@onsitecrm.com				#
# This program is distributed under the terms and 	#
# conditions of the GPL								#
# login.php											#
# Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005		#
#													#
#####################################################

#####################################
#	Load all Configs				#
#####################################
require("conf.php");

#####################################
#	Init Authorization				#
#####################################

$smarty->assign('page_title', 'Login');

// Company name/logo for the standalone login page.
$company_name = 'Cite CRM';
if (isset($db) && defined('PRFX')) {
	$rs_company = @$db->Execute("SELECT COMPANY_NAME FROM ".PRFX."TABLE_COMPANY");
	if ($rs_company && !$rs_company->EOF && !empty($rs_company->fields['COMPANY_NAME'])) {
		$company_name = (string)$rs_company->fields['COMPANY_NAME'];
	}
}
$smarty->assign('company_name', $company_name);

$company_logo_url = '';
$logo_candidates = array(
	'images/company_logo.png',
	'images/company_logo.jpg',
	'images/company_logo.jpeg',
	'images/company_logo.gif',
	'images/company_logo.webp',
	'images/logo.jpg',
);
foreach ($logo_candidates as $candidate) {
	if (is_file($candidate)) {
		$mtime = @filemtime($candidate);
		$company_logo_url = $candidate . ($mtime ? ('?v=' . $mtime) : '');
		break;
	}
}
$smarty->assign('company_logo_url', $company_logo_url);

// Captcha settings (Cloudflare Turnstile) for login page.
$captcha_enabled = 0;
$captcha_provider = 'turnstile';
$captcha_site_key = '';
if (isset($db) && defined('PRFX')) {
	$rs = @$db->Execute("SELECT PROVIDER, ENABLED, SITE_KEY FROM ".PRFX."TABLE_CAPTCHA_SETTINGS WHERE SETTINGS_ID=1");
	if ($rs && !$rs->EOF) {
		$captcha_enabled = (int)$rs->fields['ENABLED'];
		$captcha_provider = (string)$rs->fields['PROVIDER'];
		$captcha_site_key = (string)$rs->fields['SITE_KEY'];
	}
}
$smarty->assign('captcha_enabled', $captcha_enabled);
$smarty->assign('captcha_provider', $captcha_provider);
$smarty->assign('captcha_site_key', $captcha_site_key);

// SSO availability: read OAUTH client IDs from SETUP and expose to template
$sso_google = 0;
$sso_ms = 0;
if (isset($db) && defined('PRFX')) {
	$cfg = @$db->GetRow("SELECT * FROM " . PRFX . "SETUP LIMIT 1");
	if ($cfg) {
		$sso_google = !empty($cfg['OAUTH_GOOGLE_CLIENT_ID']) && (!empty($cfg['OAUTH_GOOGLE_ENABLED']) && $cfg['OAUTH_GOOGLE_ENABLED'] == 1);
		$sso_ms = !empty($cfg['OAUTH_MS_CLIENT_ID']) && (!empty($cfg['OAUTH_MS_ENABLED']) && $cfg['OAUTH_MS_ENABLED'] == 1);
	}
}
$smarty->assign('sso_google_enabled', $sso_google);
$smarty->assign('sso_ms_enabled', $sso_ms);

#####################################
#	Display Any Errors				#
##################################### 

if(isset($_GET["error_msg"]))
{
	$smarty->assign('error_msg', $_GET["error_msg"]);
}


#####################################
#	Display the pages				#
#####################################

$smarty->display('core'.SEP.'login.tpl');

?>
