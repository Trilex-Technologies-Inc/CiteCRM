<?php
/* Cat Details page */
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

// Get cat information
$cat_info = display_cat_info($db, $cat_id);

if(empty($cat_info)) {
    force_page('cats', 'main&error_msg=Category not found');
    exit;
}

// Get subcategories
$subcats = display_subcat_search($db, $cat_id);

// Assign variables to template
$smarty->assign('cat_info', $cat_info[0]);
$smarty->assign('subcats', $subcats);
$smarty->assign('page_title', 'Category Details: ' . $cat_info[0]['DESCRIPTION']);
$smarty->display('cats'.SEP.'cat_details.tpl');
?>