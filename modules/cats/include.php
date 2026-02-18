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

#####################################
#	Search Cats                    #
#####################################

function display_cat_search($db, $description, $page_no) {
    global $smarty;
    
    $max_results = 10;
    $from = (($page_no * $max_results) - $max_results);
    
    // Search query
    $q = "SELECT * FROM ".PRFX."CAT 
          WHERE DESCRIPTION LIKE ". $db->qstr($description.'%') ." 
          ORDER BY DESCRIPTION 
          LIMIT $from, $max_results";
    
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    $cat_search_result = $rs->GetArray();
    
    // Get total count for pagination
    $q = "SELECT COUNT(*) as Num FROM ".PRFX."CAT WHERE DESCRIPTION LIKE ". $db->qstr($description.'%');
    
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
?>