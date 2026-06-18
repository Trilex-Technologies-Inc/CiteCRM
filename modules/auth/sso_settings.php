<?php
/* SSO Provider Settings (Google / Microsoft)
   Save client IDs and secrets into the SETUP table (encrypted where appropriate)
*/

global $VAR;



if (isset($VAR['submit'])) {
    $g_id = isset($VAR['OAUTH_GOOGLE_CLIENT_ID']) ? trim((string)$VAR['OAUTH_GOOGLE_CLIENT_ID']) : '';
    $g_secret = isset($VAR['OAUTH_GOOGLE_CLIENT_SECRET']) ? trim((string)$VAR['OAUTH_GOOGLE_CLIENT_SECRET']) : '';
    $g_enabled = isset($VAR['OAUTH_GOOGLE_ENABLED']) && ($VAR['OAUTH_GOOGLE_ENABLED'] == '1' || $VAR['OAUTH_GOOGLE_ENABLED'] === 1) ? 1 : 0;

    $ms_id = isset($VAR['OAUTH_MS_CLIENT_ID']) ? trim((string)$VAR['OAUTH_MS_CLIENT_ID']) : '';
    $ms_secret = isset($VAR['OAUTH_MS_CLIENT_SECRET']) ? trim((string)$VAR['OAUTH_MS_CLIENT_SECRET']) : '';
    $ms_enabled = isset($VAR['OAUTH_MS_ENABLED']) && ($VAR['OAUTH_MS_ENABLED'] == '1' || $VAR['OAUTH_MS_ENABLED'] === 1) ? 1 : 0;

    // encrypt secrets
    $enc_g_secret = ($g_secret !== '') ? encrypt($g_secret, $strKey) : '';
    $enc_ms_secret = ($ms_secret !== '') ? encrypt($ms_secret, $strKey) : '';

    $cols = array(
        'OAUTH_GOOGLE_CLIENT_ID' => "varchar(255) NOT NULL default ''",
        'OAUTH_GOOGLE_CLIENT_SECRET' => "varchar(255) NOT NULL default ''",
        'OAUTH_GOOGLE_ENABLED' => "tinyint(1) NOT NULL default '0'",
        'OAUTH_MS_CLIENT_ID' => "varchar(255) NOT NULL default ''",
        'OAUTH_MS_CLIENT_SECRET' => "varchar(255) NOT NULL default ''",
        'OAUTH_MS_ENABLED' => "tinyint(1) NOT NULL default '0'",
    );

    foreach ($cols as $col => $def) {
        $rs = $db->Execute("SHOW COLUMNS FROM " . PRFX . "SETUP LIKE '" . $col . "'");
        if (!$rs || $rs->EOF) {
            $db->Execute("ALTER TABLE " . PRFX . "SETUP ADD COLUMN " . $col . " " . $def);
        }
    }

    $q = "UPDATE " . PRFX . "SETUP SET ";
    $q .= "OAUTH_GOOGLE_CLIENT_ID=" . $db->qstr($g_id) . ", ";
    $q .= "OAUTH_GOOGLE_CLIENT_SECRET=" . $db->qstr($enc_g_secret) . ", ";
    $q .= "OAUTH_GOOGLE_ENABLED=" . $db->qstr($g_enabled) . ", ";
    $q .= "OAUTH_MS_CLIENT_ID=" . $db->qstr($ms_id) . ", ";
    $q .= "OAUTH_MS_CLIENT_SECRET=" . $db->qstr($enc_ms_secret) . ", ";
    $q .= "OAUTH_MS_ENABLED=" . $db->qstr($ms_enabled);

    if (!$db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    }

    force_page('control', 'main&msg=SSO%20Settings%20Updated');
    exit;
}

// load setup
$q = 'SELECT * FROM ' . PRFX . 'SETUP';
if (!$rs = $db->Execute($q)) {
    force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
    exit;
}

$setup = $rs->GetArray();
$setup = isset($setup[0]) ? $setup[0] : array();

// decrypt secrets for display (if present)
if (!empty($setup['OAUTH_GOOGLE_CLIENT_SECRET'])) {
    $setup['OAUTH_GOOGLE_CLIENT_SECRET'] = decrypt($setup['OAUTH_GOOGLE_CLIENT_SECRET'], $strKey);
}
if (!empty($setup['OAUTH_MS_CLIENT_SECRET'])) {
    $setup['OAUTH_MS_CLIENT_SECRET'] = decrypt($setup['OAUTH_MS_CLIENT_SECRET'], $strKey);
}

$smarty->assign('setup', $setup);
$smarty->display('control'.SEP.'sso_settings.tpl');

?>
