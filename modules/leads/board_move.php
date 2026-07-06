<?php
/* Leads - move board item (AJAX) */
require_once 'modules' . SEP . 'leads' . SEP . 'include.php';

$item_id = isset($_POST['item_id']) ? (int)$_POST['item_id'] : 0;
$board_id = isset($_POST['board_id']) ? (int)$_POST['board_id'] : 0;
$pos = isset($_POST['position']) ? (int)$_POST['position'] : 0;

if ($item_id > 0) {
    $db->Execute("UPDATE " . PRFX . "LEAD_BOARD_ITEMS SET BOARD_ID=" . $db->qstr($board_id) . ", POSITION=" . $db->qstr($pos) . " WHERE ITEM_ID=" . $db->qstr($item_id));
    echo json_encode(array('success'=>true));
} else {
    echo json_encode(array('success'=>false,'error'=>'missing item'));
}

?>
