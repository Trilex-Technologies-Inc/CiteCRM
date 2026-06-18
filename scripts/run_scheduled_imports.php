<?php
// Simple runner for scheduled imports: load schedules and run import using preset mapping
chdir(__DIR__ . '/..');
require_once 'conf.php';
if (!defined('PRFX')) { echo "conf.php load failed\n"; exit(1); }

function fetch_remote_to_temp($url) {
    $tmp = tempnam(sys_get_temp_dir(), 'leadimp_');
    $ch = curl_init($url);
    $fp = fopen($tmp, 'w');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $res = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    fclose($fp);
    if ($res === false) { unlink($tmp); throw new Exception('Fetch failed: ' . $err); }
    return $tmp;
}

// load schedules
$schedules = $db->GetArray("SELECT s.*, p.MAPPING FROM " . PRFX . "LEAD_IMPORT_SCHEDULES s JOIN " . PRFX . "LEAD_IMPORT_PRESETS p ON p.PRESET_ID = s.PRESET_ID WHERE s.ENABLED = 1");
foreach ($schedules as $s) {
    $path = $s['SOURCE_PATH'];
    $localPath = $path;
    $tempCreated = false;
    try {
        if (preg_match('#^https?://#i', $path) || preg_match('#^sftp://#i', $path)) {
            $localPath = fetch_remote_to_temp($path);
            $tempCreated = true;
        }
        if (!file_exists($localPath)) { throw new Exception("Source not found: $path"); }
        $mapping = json_decode($s['MAPPING'], true);
        if (!is_array($mapping)) { throw new Exception("Invalid mapping for preset {$s['PRESET_ID']}"); }
        if (($fh = fopen($localPath,'r')) === false) { throw new Exception("Cannot open $localPath"); }
    } catch (Exception $e) {
        // notify admin
        $err = $e->getMessage();
        echo $err . "\n";
        $rs = $db->GetRow("SELECT ADMIN_EMAIL FROM " . PRFX . "SETUP");
        $admin_email = ($rs && isset($rs['ADMIN_EMAIL'])) ? $rs['ADMIN_EMAIL'] : '';
        if ($admin_email) {
            $subject = "Leads import schedule error (preset {$s['PRESET_ID']})";
            $body = "Error when running schedule {$s['SCHEDULE_ID']} for source {$path}:\n\n" . $err;
            @mail($admin_email, $subject, $body);
        }
        continue;
    }
    $header = fgetcsv($fh);
    while ($row = fgetcsv($fh)) {
        $lead = array();
        foreach ($mapping as $m) {
            if (!isset($m['csv_column']) || !isset($m['lead_field'])) continue;
            $csvIdx = intval($m['csv_column']);
            $lead[$m['lead_field']] = isset($row[$csvIdx]) ? $row[$csvIdx] : '';
        }
        $titleVal = isset($lead['title']) ? $lead['title'] : 'Imported Lead';
        $descVal = isset($lead['description']) ? $lead['description'] : '';
        $statusVal = isset($lead['status']) ? $lead['status'] : 'New';
        $db->Execute("INSERT INTO " . PRFX . "LEADS (LEAD_TITLE,LEAD_DESCRIPTION,LEAD_STATUS) VALUES (?,?,?)", array(substr($titleVal,0,255), $descVal, $statusVal));
    }
    fclose($fh);
    $db->Execute("UPDATE " . PRFX . "LEAD_IMPORT_SCHEDULES SET LAST_RUN = NOW() WHERE SCHEDULE_ID = ?", array($s['SCHEDULE_ID']));
    echo "Ran schedule {$s['SCHEDULE_ID']} on $path\n";
    if ($tempCreated && file_exists($localPath)) @unlink($localPath);
}

?>
