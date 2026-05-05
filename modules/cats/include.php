<?php
#####################################
#	Display Cat Info		      #
#####################################

function display_cat_info($db, $cat_id) {
    $q = "SELECT * FROM ".PRFX."CAT WHERE ID=". $db->qstr($cat_id);
    
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    return $rs->GetArray();
}

function cats_map_child_row($row) {
	// Keys used by existing templates/pages
	$row['CAT'] = isset($row['PARENT_ID']) ? $row['PARENT_ID'] : '';
	$row['SUB_CATEGORY'] = isset($row['ID']) ? $row['ID'] : '';
	return $row;
}

#####################################
#	Search Cats                    #
#####################################

function display_cat_search($db, $description, $page_no) {
    global $smarty;
    
    $max_results = 10;
    $from = (($page_no * $max_results) - $max_results);
    
    // Search query
    // Only show top-level categories in the main list
    $where_parent = " AND (PARENT_ID = '' OR PARENT_ID IS NULL)";
    $q = "SELECT * FROM ".PRFX."CAT
          WHERE DESCRIPTION LIKE ". $db->qstr($description.'%') ."
          $where_parent
          ORDER BY DESCRIPTION
          LIMIT $from, $max_results";
    
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    $cat_search_result = $rs->GetArray();
    
    // Get total count for pagination
    $q = "SELECT COUNT(*) as Num FROM ".PRFX."CAT WHERE DESCRIPTION LIKE ". $db->qstr($description.'%') . $where_parent;
    
    if(!$results = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    $total_results = $results->FetchRow();
    $total_pages = ceil($total_results["Num"] / $max_results);
    
    // Pagination variables
    $prev = ($page_no > 1) ? ($page_no - 1) : 0;
    $next = ($page_no < $total_pages) ? ($page_no + 1) : 0;
    
    $smarty->assign('total_results', $total_results['Num']);
    $smarty->assign('total_pages', $total_pages);
    $smarty->assign('description', $description);
    $smarty->assign('page_no', $page_no);
    $smarty->assign("previous", $prev);	
    $smarty->assign("next", $next);
    
    return $cat_search_result;
}

#####################################
#	Insert New Cat                 #
#####################################

function insert_new_cat($db, $VAR) {
    $q = "INSERT INTO ".PRFX."CAT SET
          ID            = ". $db->qstr($VAR["id"]).",
          DESCRIPTION   = ". $db->qstr($VAR["description"]);
    
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    return $db->insert_id();
}

#####################################
#	Update Cat Description         #
#####################################

function update_cat($db, $VAR, $cat_id) {
    $q = "UPDATE ".PRFX."CAT SET
          DESCRIPTION = ". $db->qstr($VAR["description"])."
          WHERE ID = ". $db->qstr($cat_id);
    
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    return true;
}

#####################################
#	Delete Cat                     #
#####################################

function delete_cat($db, $cat_id) {
    // Check if category has subcategories (children in CAT)
    $check_q = "SELECT COUNT(*) as num FROM ".PRFX."CAT WHERE PARENT_ID = ". $db->qstr($cat_id);
    $check_rs = $db->Execute($check_q);
    if($check_rs && (int)$check_rs->fields['num'] > 0) {
        force_page('core', 'error&error_msg=Cannot delete category with existing subcategories. Delete subcategories first.&menu=1&type=validation');
        exit;
    }
    
    $q = "DELETE FROM ".PRFX."CAT WHERE ID = ". $db->qstr($cat_id);
    
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    return true;
}

#####################################
#	Check If Cat Exists             #
#####################################

function check_cat_exists($db, $id) {
    $q = "SELECT COUNT(*) AS num_cats FROM ".PRFX."CAT WHERE ID = ". $db->qstr($id);
    $result = $db->Execute($q);
    
    if($result->fields['num_cats'] > 0) {
        return true; // Cat exists
    } else {
        return false; // Cat doesn't exist
    }
}

#####################################
#	Display Subcat Info             #
#####################################

function display_subcat_info($db, $subcat_id) {
    $q = "SELECT ID, DESCRIPTION, PARENT_ID
          FROM ".PRFX."CAT
          WHERE ID=". $db->qstr($subcat_id);
    
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    $rows = $rs->GetArray();
    for ($i = 0; $i < count($rows); $i++) {
        $rows[$i] = cats_map_child_row($rows[$i]);
    }
    return $rows;
}

#####################################
#	Search Subcats by Category      #
#####################################

function display_subcat_search($db, $cat_id) {
    $q = "SELECT ID, DESCRIPTION, PARENT_ID
          FROM ".PRFX."CAT
          WHERE PARENT_ID = ". $db->qstr($cat_id) ."
          ORDER BY DESCRIPTION";
    
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    $rows = $rs->GetArray();
    for ($i = 0; $i < count($rows); $i++) {
        $rows[$i] = cats_map_child_row($rows[$i]);
    }
    return $rows;
}

#####################################
#	Insert New Subcat               #
#####################################

function insert_new_subcat($db, $VAR) {
    // Store subcategory as a child category record (ID is the subcategory code)
    $q = "INSERT INTO ".PRFX."CAT SET
          ID          = ". $db->qstr($VAR["sub_category"]).",
          DESCRIPTION = ". $db->qstr($VAR["description"]).",
          PARENT_ID   = ". $db->qstr($VAR["cat_id"]);
    
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    return (string)$VAR["sub_category"];
}

#####################################
#	Update Subcat                   #
#####################################

function update_subcat($db, $VAR, $subcat_id) {
    // Keep ID as the identifier; allow changing it by rewriting the record (MyISAM has no FK constraints)
    $new_id = trim((string)$VAR["sub_category"]);
    if ($new_id !== '' && $new_id !== (string)$subcat_id) {
        $q = "UPDATE ".PRFX."CAT SET ID=".$db->qstr($new_id)." WHERE ID=".$db->qstr($subcat_id);
        if(!$rs = $db->Execute($q)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        }
        $subcat_id = $new_id;
    }
    $q = "UPDATE ".PRFX."CAT SET
          DESCRIPTION = ". $db->qstr($VAR["description"])."
          WHERE ID = ". $db->qstr($subcat_id);
    
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    return true;
}

#####################################
#	Delete Subcat                   #
#####################################

function delete_subcat($db, $subcat_id) {
    $q = "DELETE FROM ".PRFX."CAT WHERE ID = ". $db->qstr($subcat_id);
    
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    return true;
}
?>
