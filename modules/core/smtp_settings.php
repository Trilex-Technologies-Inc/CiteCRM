<?php
/* SMTP Settings UI for CiteCRM
   - Adds missing SETUP columns if required
   - Saves SMTP settings into SETUP table
*/

/* use merged request array from index.php */
global $VAR;

if (isset($VAR['submit'])) {
    $host   = isset($VAR['smtp_host']) ? $VAR['smtp_host'] : '';
    $port   = isset($VAR['smtp_port']) ? $VAR['smtp_port'] : '';
    $user   = isset($VAR['smtp_user']) ? $VAR['smtp_user'] : '';
    $pass   = isset($VAR['smtp_pass']) ? $VAR['smtp_pass'] : '';
    $secure = isset($VAR['smtp_secure']) ? $VAR['smtp_secure'] : '';
    $auth   = isset($VAR['smtp_auth']) ? (int)$VAR['smtp_auth'] : 0;

    // Store SMTP password in plain text and preserve current password when the field is left blank.
    $stored_pass = $pass;
    if ($pass === '') {
        $current = $db->GetOne('SELECT SMTP_PASS FROM ' . PRFX . 'SETUP LIMIT 1');
        if ($current !== false) {
            $stored_pass = $current;
        }
    }

    $cols = array(
        'SMTP_HOST'   => "varchar(255) NOT NULL default ''",
        'SMTP_PORT'   => "varchar(10) NOT NULL default '25'",
        'SMTP_USER'   => "varchar(255) NOT NULL default ''",
        'SMTP_PASS'   => "varchar(255) NOT NULL default ''",
        'SMTP_SECURE' => "varchar(10) NOT NULL default ''",
        'SMTP_AUTH'   => "tinyint(1) NOT NULL default 0"
    );

    foreach ($cols as $col => $def) {
        $rs = $db->Execute("SHOW COLUMNS FROM " . PRFX . "SETUP LIKE '" . $col . "'");
        if (!$rs || $rs->EOF) {
            $db->Execute("ALTER TABLE " . PRFX . "SETUP ADD COLUMN " . $col . " " . $def);
        }
    }

    $q = "UPDATE " . PRFX . "SETUP SET ";
    $q .= "SMTP_HOST=" . $db->qstr($host) . ", ";
    $q .= "SMTP_PORT=" . $db->qstr($port) . ", ";
    $q .= "SMTP_USER=" . $db->qstr($user) . ", ";
    $q .= "SMTP_PASS=" . $db->qstr($stored_pass) . ", ";
    $q .= "SMTP_SECURE=" . $db->qstr($secure) . ", ";
    $q .= "SMTP_AUTH=" . $db->qstr($auth);

    if (!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    } else {
        // show a confirmation page with company logo
        $company_logo_url = '';
        $logo_candidates = array(
            'images/company_logo.png',
            'images/company_logo.jpg',
            'images/company_logo.jpeg',
            'images/company_logo.gif',
            'images/company_logo.webp',
        );
        foreach ($logo_candidates as $candidate) {
            if (is_file($candidate)) {
                $mtime = @filemtime($candidate);
                $company_logo_url = $candidate . ($mtime ? ('?v=' . $mtime) : '');
                break;
            }
        }
        $smarty->assign('company_logo_url', $company_logo_url);
        $smarty->display('core' . SEP . 'smtp_settings_result.tpl');
        exit;
    }
} else {
    /* load setup Information */
    $q = 'SELECT * FROM ' . PRFX . 'SETUP';
    if (!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    } else {
        $setup = $rs->GetArray();
        $setup = isset($setup[0]) ? $setup[0] : array();
    }

    $smarty->assign('setup', $setup);
    $smarty->display('core' . SEP . 'smtp_settings.tpl');
}
