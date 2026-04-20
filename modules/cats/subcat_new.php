<?php
/* New Subcat page */
require_once("include.php");

// Load language file
if(!xml2php("cats")) {
    $smarty->assign('error_msg', "Error in language file");
}

// Get parent category ID from URL
$cat_id = isset($VAR['cat_id']) ? $VAR['cat_id'] : '';

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
    
    if(empty($VAR['cat_id'])) {
        $error .= '- Parent category is required<br>';
    }
    
    // Check if subcategory ID already exists for this category
    if(!empty($VAR['sub_category']) && !empty($VAR['cat_id'])) {
        $check_q = "SELECT COUNT(*) as num FROM ".PRFX."SUB_CAT WHERE SUB_CATEGORY = ". $db->qstr($VAR['sub_category']) . " AND CAT = ". $db->qstr($VAR['cat_id']);
        $check_rs = $db->Execute($check_q);
        if($check_rs->fields['num'] > 0) {
            $error .= '- Subcategory ID already exists for this category<br>';
        }
    }
    
    if(empty($error)) {
        // Insert new subcat
        $subcat_id = insert_new_subcat($db, $VAR);
        
        if(!$subcat_id) {
            $smarty->assign('error_msg', "MySQL Error: ".$db->ErrorMsg());
        } else {
            // Redirect to category details page after successful insert
            force_page('cats', 'cat_details&id=' . $VAR['cat_id'] . '&success_msg=Subcategory added successfully');
            exit;
        }
    } else {
        $smarty->assign('error_msg', $error);
        $smarty->assign('sub_category', $VAR['sub_category']);
        $smarty->assign('description', $VAR['description']);
    }
}

$smarty->assign('cat_id', $cat_id);
$smarty->assign('page_title', 'Add New Subcategory');
$smarty->display('cats'.SEP.'subcat_new.tpl');
?>