<?php
if (!defined('PRFX')) exit;
$form_id = isset($_POST['form_id']) ? intval($_POST['form_id']) : 0;
$name = isset($_POST['form_name']) ? trim($_POST['form_name']) : '';
$slug = isset($_POST['form_slug']) ? trim($_POST['form_slug']) : '';
$mapping = isset($_POST['form_mapping']) ? $_POST['form_mapping'] : '';
$fields_json = isset($_POST['fields_json']) ? $_POST['fields_json'] : '[]';

if ($form_id) {
    $db->Execute("UPDATE " . PRFX . "LEAD_FORMS SET FORM_NAME=?, FORM_SLUG=?, FORM_MAPPING=? WHERE FORM_ID=?", array($name, $slug, $mapping, $form_id));
} else {
    $token = (function_exists('leads_random_hex') ? leads_random_hex(16) : (function_exists('openssl_random_pseudo_bytes') ? bin2hex(openssl_random_pseudo_bytes(16)) : bin2hex(mt_rand())));
    $db->Execute("INSERT INTO " . PRFX . "LEAD_FORMS (FORM_NAME,FORM_SLUG,FORM_MAPPING,PUBLIC_TOKEN) VALUES (?,?,?,?)", array($name, $slug, $mapping, $token));
    $form_id = $db->Insert_ID();
}

// Replace fields: simple approach — delete then insert
$db->Execute("DELETE FROM " . PRFX . "LEAD_FORM_FIELDS WHERE FORM_ID = ?", array($form_id));
$fields = json_decode($fields_json, true);
$pos = 0;
if (is_array($fields)) {
    foreach ($fields as $f) {
        $db->Execute("INSERT INTO " . PRFX . "LEAD_FORM_FIELDS (FORM_ID,FIELD_NAME,FIELD_KEY,FIELD_TYPE,POSITION) VALUES (?,?,?,?,?)", array($form_id, $f['name'], $f['key'], isset($f['type']) ? $f['type'] : 'text', $pos));
        $pos++;
    }
}

// Auto-generate a simple FORM_HTML from the saved fields so embed snippet isn't empty
$rows = $db->GetArray("SELECT FIELD_NAME,FIELD_KEY,FIELD_TYPE FROM " . PRFX . "LEAD_FORM_FIELDS WHERE FORM_ID = ? ORDER BY POSITION", array($form_id));

// Preserve any custom HTML the admin may have set via the full form editor.
// Only auto-generate FORM_HTML when the stored value is empty.
$existingRow = $db->GetRow("SELECT FORM_HTML, PUBLIC_TOKEN FROM " . PRFX . "LEAD_FORMS WHERE FORM_ID = ?", array($form_id));
$existingHtml = $existingRow ? $existingRow['FORM_HTML'] : '';
$token = $existingRow ? $existingRow['PUBLIC_TOKEN'] : '';

$regen = isset($_POST['regen_html']) && $_POST['regen_html'] ? true : false;

// Regenerate when requested or when there is no existing custom HTML.
if ($regen || empty($existingHtml)) {
    // Debug: record regen attempt
    $dbg = "[" . date('c') . "] regen=" . ($regen ? '1' : '0') . " form_id=" . intval($form_id) . " existing_len=" . strlen($existingHtml) . "\n";
    @file_put_contents(__DIR__ . '/../../log/form_builder_save_debug.log', $dbg, FILE_APPEND);
    // Ensure config is loaded so we can generate a full action URL.
    if ((!isset($CONF) || empty($CONF['SITE_URL'])) && !defined('CONF_LOADED_FOR_FORM_BUILDER')) {
        $confPath = __DIR__ . '/../../conf.php';
        if (file_exists($confPath)) {
            if (!defined('SKIP_AUTH')) define('SKIP_AUTH', true);
            define('CONF_LOADED_FOR_FORM_BUILDER', true);
            @include_once $confPath;
            $dbg2 = "[" . date('c') . "] loaded conf.php for form builder\n";
            @file_put_contents(__DIR__ . '/../../log/form_builder_save_debug.log', $dbg2, FILE_APPEND);
        } else {
            $dbg3 = "[" . date('c') . "] conf.php not found at " . $confPath . "\n";
            @file_put_contents(__DIR__ . '/../../log/form_builder_save_debug.log', $dbg3, FILE_APPEND);
        }
    }

    // Build a full action URL using site config when available
    $actionUrl = 'index.php?page=leads:forms_submit';
    if (isset($CONF) && !empty($CONF['SITE_URL'])) {
        $actionUrl = rtrim($CONF['SITE_URL'], '/') . '/modules/leads/forms_submit.php';
    } else if (defined('WWW_ROOT') && !empty(WWW_ROOT)) {
        $actionUrl = rtrim(WWW_ROOT, '/') . '/modules/leads/forms_submit.php';
    }

    $html = "<form action=\"" . $actionUrl . "\" method=\"post\">\n";
    if ($token) {
        $html .= '  <input type="hidden" name="form_token" value="' . htmlspecialchars($token, ENT_QUOTES) . '" />\n';
    } else {
        $html .= '  <input type="hidden" name="form_id" value="' . intval($form_id) . '" />\n';
    }

    foreach ($rows as $r) {
        $k = htmlspecialchars($r['FIELD_KEY'], ENT_QUOTES);
        $label = htmlspecialchars($r['FIELD_NAME'], ENT_QUOTES);
        $type = isset($r['FIELD_TYPE']) ? $r['FIELD_TYPE'] : 'text';
        if ($type === 'textarea') {
            $html .= "  <label>{$label}: <textarea name=\"{$k}\"></textarea></label>\n";
        } else {
            $inputType = ($type === 'email') ? 'email' : 'text';
            $html .= "  <label>{$label}: <input type=\"{$inputType}\" name=\"{$k}\"></label>\n";
        }
    }

    $html .= "  <button type=\"submit\">Submit</button>\n";
    $html .= "</form>\n";

    $db->Execute("UPDATE " . PRFX . "LEAD_FORMS SET FORM_HTML = ? WHERE FORM_ID = ?", array($html, $form_id));
}

force_page('leads', 'form_builder&form_id=' . $form_id);
exit;
