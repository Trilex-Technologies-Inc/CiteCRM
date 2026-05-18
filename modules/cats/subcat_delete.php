<?php
/* Delete Subcat page */
require_once("include.php");

// Load language file
if(!xml2php("cats")) {
    $smarty->assign('error_msg', "Error in language file");
}

// Check if subcat ID is provided
if(!isset($VAR['id']) || empty($VAR['id'])) {
    force_page('cats', 'main&error_msg=No Subcategory ID provided');
    exit;
}

$subcat_id = $VAR['id'];

// Delete the subcat
if(!delete_subcat($db, $subcat_id)) {
    force_page('cats', 'main&error_msg=MySQL Error: '.$db->ErrorMsg());
    exit;
}

// Redirect to main page with success message
force_page('cats', 'main&success_msg=Subcategory deleted successfully');
exit;
?>