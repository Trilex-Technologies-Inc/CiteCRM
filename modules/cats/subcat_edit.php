<?php
/* Edit Subcat page */
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

// Get subcat information for editing (needed for redirect)
$subcat_details = display_subcat_info($db, $subcat_id);

if(empty($subcat_details)) {
    force_page('cats', 'main&error_msg=Subcategory not found');
    exit;
}

// Handle form submission
if(isset($VAR['submit'])) {
    
    $error = '';
    
    // Validate required fields
    if(empty($VAR['sub_category'])) {
        $error .= '- Subcategory ID is required<br>';
    }
    
    if(empty($VAR['description'])) {
        $error .= '- Description is required<br>';
    }
    
    if(empty($error)) {
        // Update the subcat
        if(!update_subcat($db, $VAR, $subcat_id)) {
            $smarty->assign('error_msg', "MySQL Error: ".$db->ErrorMsg());
        } else {
            // Redirect to category details page after successful update
            force_page('cats', 'cat_details&id=' . $subcat_details[0]['CAT'] . '&success_msg=Subcategory updated successfully');
            exit;
        }
    } else {
        $smarty->assign('error_msg', $error);
    }
}

// Assign variables to template
$smarty->assign('subcat_details', $subcat_details[0]); // Since display_subcat_info returns array
$smarty->assign('page_title', 'Edit Subcategory: ' . $subcat_details[0]['SUB_CATEGORY']);
$smarty->display('cats'.SEP.'subcat_edit.tpl');