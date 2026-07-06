<?php
if (!defined('PRFX')) exit;
// Return available form presets as JSON
$presetsFile = __DIR__ . '/presets.json';
if (!file_exists($presetsFile)) {
    header('Content-Type: application/json'); echo json_encode(array()); exit;
}
$json = @file_get_contents($presetsFile);
$data = @json_decode($json, true);
header('Content-Type: application/json'); echo json_encode($data);
exit;

?>
