<?php
if (!defined('PRFX')) exit;
// Simple CRUD for import schedules
$action = isset($_REQUEST['sub']) ? $_REQUEST['sub'] : 'list';
if ($action == 'save' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['schedule_id']) ? intval($_POST['schedule_id']) : 0;
    $preset = isset($_POST['preset_id']) ? intval($_POST['preset_id']) : 0;
    $source = isset($_POST['source_path']) ? $_POST['source_path'] : '';
    $cron = isset($_POST['cron']) ? $_POST['cron'] : '';
    $enabled = isset($_POST['enabled']) ? 1 : 0;
    if ($id) {
        $db->Execute("UPDATE " . PRFX . "LEAD_IMPORT_SCHEDULES SET PRESET_ID=?, SOURCE_PATH=?, CRON_EXPRESSION=?, ENABLED=? WHERE SCHEDULE_ID=", array($preset, $source, $cron, $enabled, $id));
    } else {
        $db->Execute("INSERT INTO " . PRFX . "LEAD_IMPORT_SCHEDULES (PRESET_ID,SOURCE_PATH,CRON_EXPRESSION,ENABLED) VALUES (?,?,?,?)", array($preset, $source, $cron, $enabled));
    }
    force_page('leads', 'import_schedule');
    exit;
}

if ($action == 'delete' && isset($_GET['id'])) {
    $db->Execute("DELETE FROM " . PRFX . "LEAD_IMPORT_SCHEDULES WHERE SCHEDULE_ID = ?", array(intval($_GET['id'])));
    force_page('leads', 'import_schedule');
    exit;
}

$presets = $db->GetArray("SELECT PRESET_ID,NAME FROM " . PRFX . "LEAD_IMPORT_PRESETS ORDER BY NAME");
$schedules = $db->GetArray("SELECT s.*, p.NAME as PRESET_NAME FROM " . PRFX . "LEAD_IMPORT_SCHEDULES s LEFT JOIN " . PRFX . "LEAD_IMPORT_PRESETS p ON p.PRESET_ID = s.PRESET_ID ORDER BY s.CREATED_AT DESC");
$smarty->assign('presets', $presets);
$smarty->assign('schedules', $schedules);
$smarty->display('leads/import_schedule.tpl');
