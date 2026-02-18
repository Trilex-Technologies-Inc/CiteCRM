<?php
/* Delete Cat page */
require_once("include.php");

// Load language file
if(!xml2php("cats")) {
    $smarty->assign('error_msg', "Error in language file");
}

// Check if cat ID is provided
if(!isset($VAR['id']) || empty($VAR['id'])) {
    force_page('cats', 'main&error_msg=No Cat ID provided');
    exit;
}

$cat_id = $VAR['id'];

// Delete the cat
$q = "DELETE FROM ".PRFX."CAT WHERE ID = ". $db->qstr($cat_id);

if(!$rs = $db->Execute($q)) {
    force_page('cats', 'main&error_msg=MySQL Error: '.$db->ErrorMsg());
    exit;
}

// Redirect to main page with success message
force_page('cats', 'main&success_msg=Cat deleted successfully');
exit;
?>