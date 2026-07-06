<?php
if (!defined('PRFX')) exit;
// Save form metadata and fields
$form_id = isset($_POST['form_id']) ? intval($_POST['form_id']) : 0;
$name = isset($_POST['form_name']) ? trim($_POST['form_name']) : '';
$slug = isset($_POST['form_slug']) ? trim($_POST['form_slug']) : '';
$html = isset($_POST['form_html']) ? $_POST['form_html'] : '';
$mapping = isset($_POST['form_mapping']) ? $_POST['form_mapping'] : '';

if ($form_id) {
    $db->Execute("UPDATE " . PRFX . "LEAD_FORMS SET FORM_NAME=?, FORM_SLUG=?, FORM_HTML=?, FORM_MAPPING=? WHERE FORM_ID=?", array($name, $slug, $html, $mapping, $form_id));
} else {
    $token = (function_exists('leads_random_hex') ? leads_random_hex(16) : (function_exists('openssl_random_pseudo_bytes') ? bin2hex(openssl_random_pseudo_bytes(16)) : bin2hex(mt_rand())));
    $db->Execute("INSERT INTO " . PRFX . "LEAD_FORMS (FORM_NAME,FORM_SLUG,FORM_HTML,FORM_MAPPING,PUBLIC_TOKEN) VALUES (?,?,?,?,?)", array($name, $slug, $html, $mapping, $token));
    $form_id = $db->Insert_ID();
}

// Note: field saving is simplified — expect CSV or JSON mapping in form_mapping
force_page('leads', 'forms_list');
exit;
