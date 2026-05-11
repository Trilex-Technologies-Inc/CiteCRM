<?php

$settings = crm_get_captcha_settings($db);

if (isset($VAR['submit'])) {
	$enabled = isset($VAR['enabled']) && (string)$VAR['enabled'] === '1' ? 1 : 0;
	$provider = isset($VAR['provider']) ? trim((string)$VAR['provider']) : 'turnstile';
	if ($provider !== 'turnstile') {
		$provider = 'turnstile';
	}
	$site_key = isset($VAR['site_key']) ? trim((string)$VAR['site_key']) : '';
	$secret_key = isset($VAR['secret_key']) ? trim((string)$VAR['secret_key']) : '';

	crm_ensure_captcha_settings_table($db);
	$q = "REPLACE INTO ".PRFX."TABLE_CAPTCHA_SETTINGS
			(SETTINGS_ID, PROVIDER, ENABLED, SITE_KEY, SECRET_KEY, UPDATED_AT)
		  VALUES
			(1, ".$db->qstr($provider).", ".$db->qstr($enabled).", ".$db->qstr($site_key).", ".$db->qstr($secret_key).", ".$db->qstr(time()).")";
	if (!$db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

	force_page('control', 'captcha&page_title=Captcha%20Settings&msg=Captcha settings updated');
	exit;
}

$smarty->assign('captcha', $settings);
$smarty->display('control'.SEP.'captcha.tpl');

?>

