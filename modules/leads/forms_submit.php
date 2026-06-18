<?php
// Public submission endpoint for embedded lead forms
// Accepts POST: form_id OR form_token, plus form fields
chdir(__DIR__ . '/..');
// Allow public submissions without requiring a logged-in session
if (!defined('SKIP_AUTH')) define('SKIP_AUTH', true);
require_once 'conf.php';
// allow submission without authentication; validate token or API key
$form_id = isset($_POST['form_id']) ? intval($_POST['form_id']) : 0;
$token = isset($_POST['form_token']) ? $_POST['form_token'] : null;
$api_key = isset($_POST['api_key']) ? $_POST['api_key'] : null;

$db = isset($db) ? $db : null;
if (!$db) {
    // try to include DB via include/session.php if available
    if (file_exists('include/session.php')) require_once 'include/session.php';
}

if (!$db) {
    header('HTTP/1.1 500 Internal Server Error');
    echo "Server misconfigured";
    exit;
}

if ($form_id) {
    $form = $db->GetRow("SELECT * FROM " . PRFX . "LEAD_FORMS WHERE FORM_ID = ?", array($form_id));
} else if ($token) {
    $form = $db->GetRow("SELECT * FROM " . PRFX . "LEAD_FORMS WHERE PUBLIC_TOKEN = ?", array($token));
} else if ($api_key) {
    $row = $db->GetRow("SELECT * FROM " . PRFX . "LEAD_FORM_KEYS WHERE API_KEY = ?", array($api_key));
    if ($row && $row['FORM_ID']) $form = $db->GetRow("SELECT * FROM " . PRFX . "LEAD_FORMS WHERE FORM_ID = ?", array($row['FORM_ID']));
}

if (empty($form)) {
    header('HTTP/1.1 404 Not Found');
    echo "Form not found";
    exit;
}

$data = $_POST;
unset($data['form_id'], $data['form_token'], $data['api_key']);

$db->Execute("INSERT INTO " . PRFX . "LEAD_FORM_SUBMISSIONS (FORM_ID,DATA,SOURCE_IP) VALUES (?,?,?)", array($form['FORM_ID'], json_encode($data), $_SERVER['REMOTE_ADDR']));

// Optionally create a lead using mapping if provided
if (!empty($form['FORM_MAPPING'])) {
    $mapping = json_decode($form['FORM_MAPPING'], true);
    if (is_array($mapping)) {
        $lead = array();
        foreach ($mapping as $leadKey => $fieldKey) {
            if (isset($data[$fieldKey])) $lead[$leadKey] = $data[$fieldKey];
        }
        $titleVal = isset($lead['title']) ? $lead['title'] : (isset($data['name']) ? $data['name'] : 'New Lead');
        $descVal = isset($lead['description']) ? $lead['description'] : '';
        $assignedVal = isset($lead['assigned_to']) ? intval($lead['assigned_to']) : 0;
        $db->Execute("INSERT INTO " . PRFX . "LEADS (LEAD_TITLE,LEAD_DESCRIPTION,ASSIGNED_TO,CREATED_AT) VALUES (?,?,?,NOW())", array(substr($titleVal, 0, 255), $descVal, $assignedVal));
    }
}

header('Content-Type: application/json');
echo json_encode(array('status' => 'ok'));
exit;
