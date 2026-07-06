<?php
if (!defined('PRFX')) exit;
$key_id = isset($_GET['key_id']) ? intval($_GET['key_id']) : 0;
if ($key_id) $db->Execute("DELETE FROM " . PRFX . "LEAD_FORM_KEYS WHERE KEY_ID = ?", array($key_id));
force_page('leads', 'keys_list');
exit;
