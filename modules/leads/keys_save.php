<?php
if (!defined('PRFX')) exit;
$form_id = isset($_POST['form_id']) ? intval($_POST['form_id']) : null;
$desc = isset($_POST['description']) ? trim($_POST['description']) : '';
$api_key = (function_exists('leads_random_hex') ? leads_random_hex(16) : (function_exists('openssl_random_pseudo_bytes') ? bin2hex(openssl_random_pseudo_bytes(16)) : bin2hex(mt_rand())));
$db->Execute("INSERT INTO " . PRFX . "LEAD_FORM_KEYS (FORM_ID,API_KEY,DESCRIPTION) VALUES (?,?,?)", array($form_id, $api_key, $desc));
force_page('leads', 'keys_list');
exit;
