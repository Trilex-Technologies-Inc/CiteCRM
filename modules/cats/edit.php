<?php
/* Edit Cat page */
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

// Handle form submission
if(isset($VAR['submit'])) {
    
    $error = '';
    
    // Validate required fields
    if(empty($VAR['description'])) {
        $error .= '- Description is required<br>';
    }
    
    if(empty($error)) {
        // Update the cat
        $q = "UPDATE ".PRFX."CAT SET 
              DESCRIPTION = ". $db->qstr($VAR['description']) ."
              WHERE ID = ". $db->qstr($cat_id);
        
        if(!$rs = $db->Execute($q)) {
            $smarty->assign('error_msg', "MySQL Error: ".$db->ErrorMsg());
        } else {
            // Redirect to main page after successful update
            force_page('cats', 'main&success_msg=Cat updated successfully');
            exit;
        }
    } else {
        $smarty->assign('error_msg', $error);
    }
}

// Get cat information for editing
$q = "SELECT * FROM ".PRFX."CAT WHERE ID = ". $db->qstr($cat_id);

if(!$rs = $db->Execute($q)) {
    force_page('cats', 'main&error_msg=MySQL Error: '.$db->ErrorMsg());
    exit;
}

if($rs->RecordCount() == 0) {
    force_page('cats', 'main&error_msg=Cat not found');
    exit;
}

$cat_details = $rs->FetchRow(); // Use FetchRow instead of GetArray to get single row

// Assign variables to template
$smarty->assign('cat_details', $cat_details);
$smarty->assign('page_title', 'Edit Cat: ' . $cat_details['ID']);
$smarty->display('cats'.SEP.'edit.tpl');
?>