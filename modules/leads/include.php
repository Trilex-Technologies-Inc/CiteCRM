<?php
// Leads module helper functions
if (!defined('PRFX')) define('PRFX', '');

function leads_get_boards()
{
    global $db;
    $rows = array();
    $q = "SELECT * FROM " . PRFX . "LEAD_BOARDS ORDER BY BOARD_NAME";
    if ($rs = @$db->Execute($q)) {
        $rows = $rs->GetArray();
        // load items for each board
        foreach ($rows as &$b) {
            $b['items'] = array();
            $q2 = "SELECT I.ITEM_ID, I.LEAD_ID, I.BOARD_ID, I.POSITION, L.LEAD_TITLE FROM " . PRFX . "LEAD_BOARD_ITEMS I LEFT JOIN " . PRFX . "LEADS L ON L.LEAD_ID = I.LEAD_ID WHERE I.BOARD_ID=" . $db->qstr($b['BOARD_ID']) . " ORDER BY I.POSITION";
            if ($rs2 = @$db->Execute($q2)) {
                $b['items'] = $rs2->GetArray();
            }
        }
    }
    return $rows;
}

function leads_get_lead($id)
{
    global $db;
    $id = (int)$id;
    $q = "SELECT * FROM " . PRFX . "LEADS WHERE LEAD_ID=" . $db->qstr($id) . " LIMIT 1";
    if ($rs = @$db->Execute($q)) {
        if (!$rs->EOF) return $rs->fields;
    }
    return null;
}

// generate cryptographic random hex string with fallbacks
function leads_random_hex($bytes = 16)
{
    $bytes = (int)$bytes;
    if ($bytes <= 0) $bytes = 16;
    if (function_exists('random_bytes')) {
        return bin2hex(random_bytes($bytes));
    }
    if (function_exists('openssl_random_pseudo_bytes')) {
        $strong = false;
        $b = openssl_random_pseudo_bytes($bytes, $strong);
        if ($b !== false) return bin2hex($b);
    }
    // try /dev/urandom
    $fp = @fopen('/dev/urandom', 'rb');
    if ($fp) {
        $b = @fread($fp, $bytes);
        @fclose($fp);
        if ($b !== false && strlen($b) === $bytes) return bin2hex($b);
    }
    // last resort: pseudo-random
    $str = '';
    for ($i = 0; $i < $bytes; $i++) {
        $str .= chr(mt_rand(0, 255));
    }
    return bin2hex($str);
}
