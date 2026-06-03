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

function normalize_product_measurement($value) {
	$value = trim((string)$value);
	if ($value === '') {
		return '0.00';
	}
	$value = preg_replace('/[^0-9.]/', '', $value);
	if ($value === '' || !is_numeric($value)) {
		return null;
	}
	return number_format((float)$value, 2, '.', '');
}

function inventory_table_has_column($db, $table, $column) {
	$q = "SELECT COUNT(*) AS cnt
		  FROM information_schema.COLUMNS
		  WHERE TABLE_SCHEMA = DATABASE()
		    AND TABLE_NAME = ".$db->qstr($table)."
		    AND COLUMN_NAME = ".$db->qstr($column);
	$rs = $db->Execute($q);
	return $rs && (int)$rs->fields['cnt'] > 0;
}

function inventory_table_exists($db, $table) {
	$q = "SELECT COUNT(*) AS cnt
		  FROM information_schema.TABLES
		  WHERE TABLE_SCHEMA = DATABASE()
		    AND TABLE_NAME = ".$db->qstr($table);
	$rs = $db->Execute($q);
	return $rs && (int)$rs->fields['cnt'] > 0;
}

function inventory_categories_self_related($db) {
	return inventory_table_has_column($db, PRFX.'CAT', 'PARENT_ID');
}

function inventory_products_has_cat_id($db) {
	return inventory_table_has_column($db, PRFX.'TABLE_PRODUCT', 'CAT_ID');
}

function inventory_ensure_product_shipping_columns($db) {
	$table = PRFX.'TABLE_PRODUCT';
	$columns = array(
		'PRODUCT_WEIGHT' => "ALTER TABLE `".$table."` ADD COLUMN `PRODUCT_WEIGHT` decimal(10,2) NOT NULL default '0.00' AFTER `PRODUCT_PRICE`",
		'PRODUCT_LENGTH' => "ALTER TABLE `".$table."` ADD COLUMN `PRODUCT_LENGTH` decimal(10,2) NOT NULL default '0.00' AFTER `PRODUCT_WEIGHT`",
		'PRODUCT_WIDTH' => "ALTER TABLE `".$table."` ADD COLUMN `PRODUCT_WIDTH` decimal(10,2) NOT NULL default '0.00' AFTER `PRODUCT_LENGTH`",
		'PRODUCT_HEIGHT' => "ALTER TABLE `".$table."` ADD COLUMN `PRODUCT_HEIGHT` decimal(10,2) NOT NULL default '0.00' AFTER `PRODUCT_WIDTH`",
	);

	foreach ($columns as $column => $sql) {
		if (!inventory_table_has_column($db, $table, $column)) {
			if (!$db->Execute($sql)) {
				return false;
			}
		}
	}

	return true;
}

function inventory_ensure_warehouse_table($db) {
	$table = PRFX.'TABLE_WAREHOUSE';
	if (inventory_table_exists($db, $table)) {
		return true;
	}

	$q = "CREATE TABLE IF NOT EXISTS `".$table."` (
		  `WAREHOUSE_ID` int(11) NOT NULL auto_increment,
		  `WAREHOUSE_NAME` varchar(120) NOT NULL default '',
		  `WAREHOUSE_CODE` varchar(40) NOT NULL default '',
		  `WAREHOUSE_ADDRESS` varchar(255) NOT NULL default '',
		  `WAREHOUSE_CITY` varchar(80) NOT NULL default '',
		  `WAREHOUSE_STATE` varchar(80) NOT NULL default '',
		  `WAREHOUSE_ZIP` varchar(20) NOT NULL default '',
		  `WAREHOUSE_COUNTRY` varchar(80) NOT NULL default '',
		  `WAREHOUSE_ACTIVE` tinyint(1) NOT NULL default '1',
		  PRIMARY KEY  (`WAREHOUSE_ID`),
		  UNIQUE KEY `WAREHOUSE_NAME` (`WAREHOUSE_NAME`),
		  KEY `WAREHOUSE_CODE` (`WAREHOUSE_CODE`)
		) ENGINE=MyISAM";

	return (bool)$db->Execute($q);
}

function inventory_ensure_product_warehouse_column($db) {
	$table = PRFX.'TABLE_PRODUCT';
	if (inventory_table_has_column($db, $table, 'WAREHOUSE_ID')) {
		return true;
	}

	if (!$db->Execute("ALTER TABLE `".$table."` ADD COLUMN `WAREHOUSE_ID` int(11) NOT NULL default '0' AFTER `MANUFACTURER_ID`")) {
		return false;
	}
	$db->Execute("ALTER TABLE `".$table."` ADD KEY `WAREHOUSE_ID` (`WAREHOUSE_ID`)");
	return true;
}

// AJAX: fetch subcategories (child categories) for a category (used by product add/edit forms)
if (isset($VAR['ajax']) && $VAR['ajax'] === 'subcats') {
	$cat_id = isset($VAR['cat_id']) ? trim((string)$VAR['cat_id']) : '';
	header('Content-Type: application/json; charset=UTF-8');

	if ($cat_id === '') {
		echo json_encode(array());
		exit;
	}

	if (!inventory_categories_self_related($db)) {
		http_response_code(500);
		echo json_encode(array('error' => 'Database upgrade required: CAT.PARENT_ID missing'));
		exit;
	}

	$q = "SELECT ID, DESCRIPTION
		  FROM ".PRFX."CAT
		  WHERE PARENT_ID=".$db->qstr($cat_id)."
		  ORDER BY DESCRIPTION";
	if(!$rs = $db->execute($q)) {
		http_response_code(500);
		echo json_encode(array('error' => $db->ErrorMsg()));
		exit;
	}

	echo json_encode($rs->GetArray());
	exit;
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

// Categories for dropdowns
$where = "WHERE (PARENT_ID = '' OR PARENT_ID IS NULL)";
$supports_parent = inventory_categories_self_related($db);
$supports_cat_id = inventory_products_has_cat_id($db);
if (!$supports_parent || !$supports_cat_id) {
	force_page('core', 'error&error_msg=Database upgrade required: missing CAT.PARENT_ID and/or TABLE_PRODUCT.CAT_ID.&menu=1&type=validation');
	exit;
}
if (!inventory_ensure_product_shipping_columns($db)) {
	force_page('core', 'error&error_msg=Database upgrade required: unable to add product shipping columns.&menu=1&type=database');
	exit;
}
if (!inventory_ensure_warehouse_table($db) || !inventory_ensure_product_warehouse_column($db)) {
	force_page('core', 'error&error_msg=Database upgrade required: unable to add warehouse catalog support.&menu=1&type=database');
	exit;
}
$q = "SELECT ID, DESCRIPTION
	  FROM ".PRFX."CAT
	  $where
	  ORDER BY DESCRIPTION";
if(!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$smarty->assign('category_options', $rs->GetArray());

$q = "SELECT WAREHOUSE_ID, WAREHOUSE_NAME, WAREHOUSE_CODE
	  FROM ".PRFX."TABLE_WAREHOUSE
	  WHERE WAREHOUSE_ACTIVE=1
	  ORDER BY WAREHOUSE_NAME";
if(!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$smarty->assign('warehouse_options', $rs->GetArray());

function product_subcat_matches_cat($db, $cat_id, $subcat_id) {
	$cat_id = trim((string)$cat_id);
	$subcat_id = trim((string)$subcat_id);
	if ($cat_id === '' || $subcat_id === '') {
		return false;
	}
	$q = "SELECT COUNT(*) AS cnt
		  FROM ".PRFX."CAT
		  WHERE ID=".$db->qstr($subcat_id)." AND PARENT_ID=".$db->qstr($cat_id);
	$rs = $db->Execute($q);
	return $rs && (int)$rs->fields['cnt'] > 0;
}

function product_warehouse_exists($db, $warehouse_id) {
	$warehouse_id = (int)$warehouse_id;
	if ($warehouse_id <= 0) {
		return true;
	}
	$q = "SELECT COUNT(*) AS cnt
		  FROM ".PRFX."TABLE_WAREHOUSE
		  WHERE WAREHOUSE_ID=".$db->qstr($warehouse_id);
	$rs = $db->Execute($q);
	return $rs && (int)$rs->fields['cnt'] > 0;
}

if (isset($VAR['submit'])) {
	$submit = $VAR['submit'];

	if ($submit === 'New') {
		$manufacturer_id = isset($VAR['manufacturer_id']) ? (int)$VAR['manufacturer_id'] : 0;
		$warehouse_id = isset($VAR['warehouse_id']) ? (int)$VAR['warehouse_id'] : 0;
		$cat_id = isset($VAR['cat_id']) ? trim((string)$VAR['cat_id']) : '';
		$subcat_id = isset($VAR['subcat_id']) ? trim((string)$VAR['subcat_id']) : '';
		$sku = isset($VAR['product_sku']) ? trim($VAR['product_sku']) : '';
		$name = isset($VAR['product_name']) ? trim($VAR['product_name']) : '';
		$description = isset($VAR['product_description']) ? trim($VAR['product_description']) : '';
		$price = isset($VAR['product_price']) ? normalize_price($VAR['product_price']) : '0.00';
		$weight = isset($VAR['product_weight']) ? normalize_product_measurement($VAR['product_weight']) : '0.00';
		$length = isset($VAR['product_length']) ? normalize_product_measurement($VAR['product_length']) : '0.00';
		$width = isset($VAR['product_width']) ? normalize_product_measurement($VAR['product_width']) : '0.00';
		$height = isset($VAR['product_height']) ? normalize_product_measurement($VAR['product_height']) : '0.00';
		$active = isset($VAR['product_active']) ? (int)$VAR['product_active'] : 1;

		if ($manufacturer_id <= 0) {
			force_page('inventory', 'products&page_title=Products&error_msg=Please select a manufacturer.');
			exit;
		}
		if (!product_warehouse_exists($db, $warehouse_id)) {
			force_page('inventory', 'products&page_title=Products&error_msg=Please select a valid warehouse.');
			exit;
		}
		if ($cat_id === '') {
			force_page('inventory', 'products&page_title=Products&error_msg=Please select a category.');
			exit;
		}
		if ($subcat_id === '' || !product_subcat_matches_cat($db, $cat_id, $subcat_id)) {
			force_page('inventory', 'products&page_title=Products&error_msg=Please select a subcategory.');
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
		if ($weight === null || $length === null || $width === null || $height === null) {
			force_page('inventory', 'products&page_title=Products&error_msg=Invalid shipping weight or dimensions.');
			exit;
		}

		$q = "INSERT INTO ".PRFX."TABLE_PRODUCT SET
				MANUFACTURER_ID=".$db->qstr($manufacturer_id).",
				WAREHOUSE_ID=".$db->qstr($warehouse_id).",
				CAT_ID=".$db->qstr($subcat_id).",
				PRODUCT_SKU=".$db->qstr($sku).",
				PRODUCT_NAME=".$db->qstr($name).",
				PRODUCT_DESCRIPTION=".$db->qstr($description).",
				PRODUCT_PRICE=".$db->qstr($price).",
				PRODUCT_WEIGHT=".$db->qstr($weight).",
				PRODUCT_LENGTH=".$db->qstr($length).",
				PRODUCT_WIDTH=".$db->qstr($width).",
				PRODUCT_HEIGHT=".$db->qstr($height).",
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
		$warehouse_id = isset($VAR['warehouse_id']) ? (int)$VAR['warehouse_id'] : 0;
		$cat_id = isset($VAR['cat_id']) ? trim((string)$VAR['cat_id']) : '';
		$subcat_id = isset($VAR['subcat_id']) ? trim((string)$VAR['subcat_id']) : '';
		$sku = isset($VAR['product_sku']) ? trim($VAR['product_sku']) : '';
		$name = isset($VAR['product_name']) ? trim($VAR['product_name']) : '';
		$description = isset($VAR['product_description']) ? trim($VAR['product_description']) : '';
		$price = isset($VAR['product_price']) ? normalize_price($VAR['product_price']) : '0.00';
		$weight = isset($VAR['product_weight']) ? normalize_product_measurement($VAR['product_weight']) : '0.00';
		$length = isset($VAR['product_length']) ? normalize_product_measurement($VAR['product_length']) : '0.00';
		$width = isset($VAR['product_width']) ? normalize_product_measurement($VAR['product_width']) : '0.00';
		$height = isset($VAR['product_height']) ? normalize_product_measurement($VAR['product_height']) : '0.00';
		$active = isset($VAR['product_active']) ? (int)$VAR['product_active'] : 1;

		if ($product_id <= 0) {
			force_page('inventory', 'products&page_title=Products&error_msg=Invalid product id.');
			exit;
		}
		if ($manufacturer_id <= 0) {
			force_page('inventory', 'products&page_title=Products&error_msg=Please select a manufacturer.');
			exit;
		}
		if (!product_warehouse_exists($db, $warehouse_id)) {
			force_page('inventory', 'products&page_title=Products&error_msg=Please select a valid warehouse.');
			exit;
		}
		if ($cat_id === '') {
			force_page('inventory', 'products&page_title=Products&error_msg=Please select a category.');
			exit;
		}
		if ($subcat_id === '' || !product_subcat_matches_cat($db, $cat_id, $subcat_id)) {
			force_page('inventory', 'products&page_title=Products&error_msg=Please select a subcategory.');
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
		if ($weight === null || $length === null || $width === null || $height === null) {
			force_page('inventory', 'products&page_title=Products&error_msg=Invalid shipping weight or dimensions.');
			exit;
		}

		$q = "UPDATE ".PRFX."TABLE_PRODUCT SET
				MANUFACTURER_ID=".$db->qstr($manufacturer_id).",
				WAREHOUSE_ID=".$db->qstr($warehouse_id).",
				CAT_ID=".$db->qstr($subcat_id).",
				PRODUCT_SKU=".$db->qstr($sku).",
				PRODUCT_NAME=".$db->qstr($name).",
				PRODUCT_DESCRIPTION=".$db->qstr($description).",
				PRODUCT_PRICE=".$db->qstr($price).",
				PRODUCT_WEIGHT=".$db->qstr($weight).",
				PRODUCT_LENGTH=".$db->qstr($length).",
				PRODUCT_WIDTH=".$db->qstr($width).",
				PRODUCT_HEIGHT=".$db->qstr($height).",
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
	$where = "WHERE (p.PRODUCT_NAME LIKE $like OR p.PRODUCT_SKU LIKE $like OR m.MANUFACTURER_NAME LIKE $like OR w.WAREHOUSE_NAME LIKE $like OR w.WAREHOUSE_CODE LIKE $like)";
}

$q = "SELECT p.PRODUCT_ID, p.MANUFACTURER_ID, p.WAREHOUSE_ID, p.PRODUCT_SKU, p.PRODUCT_NAME, p.PRODUCT_DESCRIPTION, p.PRODUCT_PRICE,
			p.PRODUCT_WEIGHT, p.PRODUCT_LENGTH, p.PRODUCT_WIDTH, p.PRODUCT_HEIGHT, p.PRODUCT_ACTIVE,
			p.CAT_ID AS SUBCAT_ID,
			m.MANUFACTURER_NAME,
			w.WAREHOUSE_NAME, w.WAREHOUSE_CODE,
			parent.ID AS CAT_ID, parent.DESCRIPTION AS CAT_DESCRIPTION,
			child.DESCRIPTION AS SUBCAT_DESCRIPTION, child.ID AS SUBCAT_CODE
	  FROM ".PRFX."TABLE_PRODUCT p
	  LEFT JOIN ".PRFX."TABLE_MANUFACTURER m ON (m.MANUFACTURER_ID = p.MANUFACTURER_ID)
	  LEFT JOIN ".PRFX."TABLE_WAREHOUSE w ON (w.WAREHOUSE_ID = p.WAREHOUSE_ID)
	  LEFT JOIN ".PRFX."CAT child ON (child.ID = p.CAT_ID)
	  LEFT JOIN ".PRFX."CAT parent ON (parent.ID = child.PARENT_ID)
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
