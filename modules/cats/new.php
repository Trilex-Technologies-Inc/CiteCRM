<?php
/* New Cat page */
require_once("include.php");

// Load language file
if(!xml2php("cats")) {
    $smarty->assign('error_msg', "Error in language file");
}

// Handle form submission
if(isset($VAR['submit'])) {
    
    $error = '';
    
    // Validate required fields
    if(empty($VAR['id'])) {
        $error .= '- Cat ID is required<br>';
    }
    
    if(empty($VAR['description'])) {
        $error .= '- Description is required<br>';
    }
    
    // Check if cat ID already exists
    if(!empty($VAR['id'])) {
        $check_q = "SELECT COUNT(*) as num FROM ".PRFX."CAT WHERE ID = ". $db->qstr($VAR['id']);
        $check_rs = $db->Execute($check_q);
        if($check_rs->fields['num'] > 0) {
            $error .= '- Cat ID already exists<br>';
        }
    }
    
    if(empty($error)) {
        // Insert new cat
        $q = "INSERT INTO ".PRFX."CAT SET
              ID = ". $db->qstr($VAR['id']) .",
              DESCRIPTION = ". $db->qstr($VAR['description']);
        
        if(!$rs = $db->Execute($q)) {
            $smarty->assign('error_msg', "MySQL Error: ".$db->ErrorMsg());
        } else {
            // Redirect to main page after successful insert
            force_page('cats', 'main&success_msg=Cat added successfully');
            exit;
        }
    } else {
        $smarty->assign('error_msg', $error);
        $smarty->assign('cat_id', $VAR['id']);
        $smarty->assign('cat_description', $VAR['description']);
    }
}

$smarty->assign('page_title', 'Add New Cat');
$smarty->display('cats'.SEP.'new.tpl');
?>