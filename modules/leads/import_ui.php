<?php
if (!defined('PRFX')) exit;
// Upload CSV and map columns to lead fields; save presets
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_preset') {
    $name = isset($_POST['preset_name']) ? $_POST['preset_name'] : 'Preset';
    $mapping = isset($_POST['mapping_json']) ? $_POST['mapping_json'] : '[]';
    $db->Execute("INSERT INTO " . PRFX . "LEAD_IMPORT_PRESETS (NAME,MAPPING) VALUES (?,?)", array($name, $mapping));
    force_page('leads', 'import_ui');
    exit;
}

$presets = $db->GetArray("SELECT * FROM " . PRFX . "LEAD_IMPORT_PRESETS ORDER BY CREATED_AT DESC");
$smarty->assign('presets', $presets);
$smarty->display('leads/import.tpl');
