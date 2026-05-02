<?php
####################################################
# Cite CRM  Customer Relations Management           #
# Products CRUD                                     #
####################################################

function is_valid_product_name($name) {
	$name = trim((string)$name);
	return $name !== '' && mb_strlen($name) <= 120;
}

function normalize_price($price) {
	$price = trim((string)$price);
	if ($price === '') {
		return '0.00';
	}
	$price = preg_replace('/[^0-9.]/', '', $price);
	if ($price === '' || !is_numeric($price)) {
		return null;
	}
	return number_format((float)$price, 2, '.', '');
}

// Manufacturers for dropdowns
$q = "SELECT MANUFACTURER_ID, MANUFACTURER_NAME
	  FROM ".PRFX."TABLE_MANUFACTURER
	  WHERE MANUFACTURER_ACTIVE=1
	  ORDER BY MANUFACTURER_NAME";
if(!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$smarty->assign('manufacturer_options', $rs->GetArray());

if (isset($VAR['submit'])) {
	$submit = $VAR['submit'];

	if ($submit === 'New') {
		$manufacturer_id = isset($VAR['manufacturer_id']) ? (int)$VAR['manufacturer_id'] : 0;
		$sku = isset($VAR['product_sku']) ? trim($VAR['product_sku']) : '';
		$name = isset($VAR['product_name']) ? trim($VAR['product_name']) : '';
		$description = isset($VAR['product_description']) ? trim($VAR['product_description']) : '';
		$price = isset($VAR['product_price']) ? normalize_price($VAR['product_price']) : '0.00';
		$active = isset($VAR['product_active']) ? (int)$VAR['product_active'] : 1;

		if ($manufacturer_id <= 0) {
			force_page('inventory', 'products&page_title=Products&error_msg=Please select a manufacturer.');
			exit;
		}
		if (!is_valid_product_name($name)) {
			force_page('inventory', 'products&page_title=Products&error_msg=Product name is required (max 120 chars).');
			exit;
		}
		if ($price === null) {
			force_page('inventory', 'products&page_title=Products&error_msg=Invalid price.');
			exit;
		}

		$q = "INSERT INTO ".PRFX."TABLE_PRODUCT SET
				MANUFACTURER_ID=".$db->qstr($manufacturer_id).",
				PRODUCT_SKU=".$db->qstr($sku).",
				PRODUCT_NAME=".$db->qstr($name).",
				PRODUCT_DESCRIPTION=".$db->qstr($description).",
				PRODUCT_PRICE=".$db->qstr($price).",
				PRODUCT_ACTIVE=".$db->qstr($active ? 1 : 0);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

		force_page('inventory', 'products&page_title=Products&msg=Product Created');
		exit;
	}

	if ($submit === 'Edit') {
		$product_id = isset($VAR['product_id']) ? (int)$VAR['product_id'] : 0;
		$manufacturer_id = isset($VAR['manufacturer_id']) ? (int)$VAR['manufacturer_id'] : 0;
		$sku = isset($VAR['product_sku']) ? trim($VAR['product_sku']) : '';
		$name = isset($VAR['product_name']) ? trim($VAR['product_name']) : '';
		$description = isset($VAR['product_description']) ? trim($VAR['product_description']) : '';
		$price = isset($VAR['product_price']) ? normalize_price($VAR['product_price']) : '0.00';
		$active = isset($VAR['product_active']) ? (int)$VAR['product_active'] : 1;

		if ($product_id <= 0) {
			force_page('inventory', 'products&page_title=Products&error_msg=Invalid product id.');
			exit;
		}
		if ($manufacturer_id <= 0) {
			force_page('inventory', 'products&page_title=Products&error_msg=Please select a manufacturer.');
			exit;
		}
		if (!is_valid_product_name($name)) {
			force_page('inventory', 'products&page_title=Products&error_msg=Product name is required (max 120 chars).');
			exit;
		}
		if ($price === null) {
			force_page('inventory', 'products&page_title=Products&error_msg=Invalid price.');
			exit;
		}

		$q = "UPDATE ".PRFX."TABLE_PRODUCT SET
				MANUFACTURER_ID=".$db->qstr($manufacturer_id).",
				PRODUCT_SKU=".$db->qstr($sku).",
				PRODUCT_NAME=".$db->qstr($name).",
				PRODUCT_DESCRIPTION=".$db->qstr($description).",
				PRODUCT_PRICE=".$db->qstr($price).",
				PRODUCT_ACTIVE=".$db->qstr($active ? 1 : 0)."
			  WHERE PRODUCT_ID=".$db->qstr($product_id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

		force_page('inventory', 'products&page_title=Products&msg=Product Updated');
		exit;
	}

	if ($submit === 'Delete') {
		$product_id = isset($VAR['product_id']) ? (int)$VAR['product_id'] : 0;
		if ($product_id <= 0) {
			force_page('inventory', 'products&page_title=Products&error_msg=Invalid product id.');
			exit;
		}

		$q = "DELETE FROM ".PRFX."TABLE_PRODUCT WHERE PRODUCT_ID=".$db->qstr($product_id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

		force_page('inventory', 'products&page_title=Products&msg=Product Deleted');
		exit;
	}

	force_page('inventory', 'products&page_title=Products&error_msg=Unknown action.');
	exit;
}

$search = isset($VAR['q']) ? trim($VAR['q']) : '';
$where = '';
if ($search !== '') {
	$like = $db->qstr('%'.$search.'%');
	$where = "WHERE (p.PRODUCT_NAME LIKE $like OR p.PRODUCT_SKU LIKE $like OR m.MANUFACTURER_NAME LIKE $like)";
}

$q = "SELECT p.PRODUCT_ID, p.MANUFACTURER_ID, p.PRODUCT_SKU, p.PRODUCT_NAME, p.PRODUCT_DESCRIPTION, p.PRODUCT_PRICE, p.PRODUCT_ACTIVE,
			m.MANUFACTURER_NAME
	  FROM ".PRFX."TABLE_PRODUCT p
	  LEFT JOIN ".PRFX."TABLE_MANUFACTURER m ON (m.MANUFACTURER_ID = p.MANUFACTURER_ID)
	  $where
	  ORDER BY p.PRODUCT_NAME";
if(!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}

$smarty->assign('q', $search);
$smarty->assign('products', $rs->GetArray());
$smarty->display('inventory'.SEP.'products.tpl');

?>

