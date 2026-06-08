<?php
####################################################
# IN Cite CRM Customer Relations Management
# Compatible with old PHP versions (PHP 5.6+)
# Replaced deprecated mcrypt with OpenSSL
####################################################

function check_acl($db, $module, $page)
{
    if (!isset($_SESSION['login_id']) || empty($_SESSION['login_id'])) {
        return false;
    }

    $uid = $_SESSION['login_id'];

    /* get group id */
    $q = 'SELECT ' . PRFX . 'CONFIG_EMPLOYEE_TYPE.TYPE_NAME
            FROM ' . PRFX . 'TABLE_EMPLOYEE,' . PRFX . 'CONFIG_EMPLOYEE_TYPE
            WHERE ' . PRFX . 'TABLE_EMPLOYEE.EMPLOYEE_TYPE =
                  ' . PRFX . 'CONFIG_EMPLOYEE_TYPE.TYPE_ID
            AND EMPLOYEE_ID=' . $db->qstr($uid);

    if (!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=Could not get Group ID for user');
        exit;
    } else {
        $gid = $rs->fields['TYPE_NAME'];
    }

    // Validate role name
    if (!preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $gid)) {
        return false;
    }

    /* check page access */
    if (!isset($module)) {
        $page = "core:main";
    } else {
        $page = $module . ":" . $page;
    }

    // Ensure ACL column exists
    $q = "SHOW COLUMNS FROM " . PRFX . "ACL LIKE " . $db->qstr($gid);

    if (!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=Could not validate Role ACL Column');
        exit;
    }

    if ($rs->EOF) {
        return false;
    }

    // Ensure page row exists
    $q = 'SELECT ACL_ID
          FROM ' . PRFX . 'ACL
          WHERE page=' . $db->qstr($page) . '
          LIMIT 1';

    if (!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=Could not get Page ACL');
        exit;
    }

    if ($rs->EOF) {

        $cols = array();

        $q = "SHOW COLUMNS FROM " . PRFX . "ACL";

        if ($cols_rs = $db->execute($q)) {
            $cols = $cols_rs->GetArray();
        }

        $sets = array();

        foreach ($cols as $col) {

            if (!isset($col['Field'])) {
                continue;
            }

            $field = $col['Field'];

            if ($field == 'ACL_ID' || $field == 'page') {
                continue;
            }

            $default_allow = in_array(
                $field,
                array('Admin', 'Manager', 'Supervisor'),
                true
            ) ? 1 : 0;

            $sets[] = "`" . str_replace('`', '``', $field) . "`=" . $default_allow;
        }

        $q = "INSERT INTO " . PRFX . "ACL
              SET page=" . $db->qstr($page);

        if (!empty($sets)) {
            $q .= ", " . implode(',', $sets);
        }

        $db->execute($q);
    }

    $q = 'SELECT `' . $gid . '` as ACL
          FROM ' . PRFX . 'ACL
          WHERE page=' . $db->qstr($page);

    if (!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=Could not get Page ACL');
        exit;
    } else {

        $acl = $rs->fields['ACL'];

        if ($acl != 1) {
            return false;
        } else {
            return true;
        }
    }
}


/**
 * Encrypt string
 * Compatible with PHP 5.6+
 */
function encrypt($data, $key)
{
    if ($data == "") {
        return $data;
    }

    $cipher = 'AES-256-CBC';

    // Create secure key
    $key = hash('sha256', $key, true);

    // IV length
    $ivLength = openssl_cipher_iv_length($cipher);

    // Generate IV
    if (function_exists('openssl_random_pseudo_bytes')) {
        $iv = openssl_random_pseudo_bytes($ivLength);
    } else {
        $iv = substr(md5(mt_rand(), true), 0, $ivLength);
    }

    // Encrypt
    $encrypted = openssl_encrypt(
        $data,
        $cipher,
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );

    // Encode result
    return base64_encode($iv . $encrypted);
}


/**
 * Decrypt string
 * Compatible with PHP 5.6+
 */
function decrypt($data, $key)
{
    if ($data == "") {
        return $data;
    }

    $cipher = 'AES-256-CBC';

    // Create secure key
    $key = hash('sha256', $key, true);

    // Decode data
    $data = base64_decode($data);

    // IV length
    $ivLength = openssl_cipher_iv_length($cipher);

    // Extract IV
    $iv = substr($data, 0, $ivLength);

    // Extract encrypted text
    $encrypted = substr($data, $ivLength);

    // Decrypt
    $decrypted = openssl_decrypt(
        $encrypted,
        $cipher,
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );

    return trim($decrypted);
}

?>