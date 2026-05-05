<?php
/* Cat Main page */
require_once("include.php");

if(!xml2php("cats")) {
    $smarty->assign('error_msg', "Error in language file");
}

$alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

$page_no = isset($VAR["page_no"]) ? $VAR['page_no'] : 1;
$description = isset($VAR['description']) ? $VAR['description'] : '';

$cat_search_result = display_cat_search($db, $description, $page_no);

// Add subcategories for each category
foreach ($cat_search_result as &$cat) {
    $cat['subcats'] = display_subcat_search($db, $cat['ID']);
}

$smarty->assign('alpha', $alpha);
$smarty->assign('cat_search_result', $cat_search_result);
$smarty->display('cats'.SEP.'main.tpl');
?>