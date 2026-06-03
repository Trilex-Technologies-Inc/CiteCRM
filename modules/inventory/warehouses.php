<?php
####################################################
# Cite CRM  Customer Relations Management           #
# Warehouses CRUD                                   #
####################################################

function is_valid_warehouse_name($name) {
	$name = trim((string)$name);
	return $name !== '' && mb_strlen($name) <= 120;
}

function inventory_warehouses_table_exists($db, $table) {
	$q = "SELECT COUNT(*) AS cnt
		  FROM information_schema.TABLES
		  WHERE TABLE_SCHEMA = DATABASE()
		    AND TABLE_NAME = ".$db->qstr($table);
	$rs = $db->Execute($q);
	return $rs && (int)$rs->fields['cnt'] > 0;
}

function inventory_warehouses_column_exists($db, $table, $column) {
	$q = "SELECT COUNT(*) AS cnt
		  FROM information_schema.COLUMNS
		  WHERE TABLE_SCHEMA = DATABASE()
		    AND TABLE_NAME = ".$db->qstr($table)."
		    AND COLUMN_NAME = ".$db->qstr($column);
	$rs = $db->Execute($q);
	return $rs && (int)$rs->fields['cnt'] > 0;
}

function inventory_ensure_warehouse_table($db) {
	$table = PRFX."TABLE_WAREHOUSE";
	if (inventory_warehouses_table_exists($db, $table)) {
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
	$table = PRFX."TABLE_PRODUCT";
	if (!inventory_warehouses_table_exists($db, $table)) {
		return true;
	}
	if (inventory_warehouses_column_exists($db, $table, "WAREHOUSE_ID")) {
		return true;
	}
	if (!$db->Execute("ALTER TABLE `".$table."` ADD COLUMN `WAREHOUSE_ID` int(11) NOT NULL default '0' AFTER `MANUFACTURER_ID`")) {
		return false;
	}
	$db->Execute("ALTER TABLE `".$table."` ADD KEY `WAREHOUSE_ID` (`WAREHOUSE_ID`)");
	return true;
}

if (!inventory_ensure_warehouse_table($db) || !inventory_ensure_product_warehouse_column($db)) {
	force_page('core', 'error&error_msg=Database upgrade required: unable to create warehouse catalog support.&menu=1&type=database');
	exit;
}

$q = "SELECT * FROM ".PRFX."COUNTRY";
if (!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$country = $rs->GetArray();
$smarty->assign('country', $country);

$company_country_default = '';
$q = "SELECT COMPANY_COUNTRY FROM ".PRFX."TABLE_COMPANY";
if ($rs_company = $db->execute($q)) {
	$company_country_default = strtoupper(trim((string)$rs_company->fields['COMPANY_COUNTRY']));
}

$selected_country = '';
if (isset($VAR['warehouse_country']) && trim((string)$VAR['warehouse_country']) !== '') {
	$selected_country = strtoupper(trim((string)$VAR['warehouse_country']));
} else if ($company_country_default !== '') {
	$selected_country = $company_country_default;
}
$smarty->assign('selected_country', $selected_country);

if (isset($VAR['submit'])) {
	$submit = $VAR['submit'];

	if ($submit === 'New' || $submit === 'Edit') {
		$id = isset($VAR['warehouse_id']) ? (int)$VAR['warehouse_id'] : 0;
		$name = isset($VAR['warehouse_name']) ? trim($VAR['warehouse_name']) : '';
		$code = isset($VAR['warehouse_code']) ? trim($VAR['warehouse_code']) : '';
		$address = isset($VAR['warehouse_address']) ? trim($VAR['warehouse_address']) : '';
		$city = isset($VAR['warehouse_city']) ? trim($VAR['warehouse_city']) : '';
		$state = isset($VAR['warehouse_state']) ? trim($VAR['warehouse_state']) : '';
		$zip = isset($VAR['warehouse_zip']) ? trim($VAR['warehouse_zip']) : '';
		$country = isset($VAR['warehouse_country']) ? strtoupper(trim((string)$VAR['warehouse_country'])) : '';
		if ($country !== '') {
			$country = substr($country, 0, 3);
		}
		$active = isset($VAR['warehouse_active']) ? (int)$VAR['warehouse_active'] : 1;

		if ($submit === 'Edit' && $id <= 0) {
			force_page('inventory', 'warehouses&page_title=Warehouses&error_msg=Invalid warehouse id.');
			exit;
		}
		if (!is_valid_warehouse_name($name)) {
			force_page('inventory', 'warehouses&page_title=Warehouses&error_msg=Warehouse name is required (max 120 chars).');
			exit;
		}

		$q = "SELECT count(*) as cnt FROM ".PRFX."TABLE_WAREHOUSE WHERE WAREHOUSE_NAME=".$db->qstr($name);
		if ($submit === 'Edit') {
			$q .= " AND WAREHOUSE_ID<>".$db->qstr($id);
		}
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
		if ((int)$rs->fields['cnt'] > 0) {
			force_page('inventory', 'warehouses&page_title=Warehouses&error_msg=Warehouse already exists.');
			exit;
		}

		if ($submit === 'New') {
			$q = "INSERT INTO ".PRFX."TABLE_WAREHOUSE SET
					WAREHOUSE_NAME=".$db->qstr($name).",
					WAREHOUSE_CODE=".$db->qstr($code).",
					WAREHOUSE_ADDRESS=".$db->qstr($address).",
					WAREHOUSE_CITY=".$db->qstr($city).",
					WAREHOUSE_STATE=".$db->qstr($state).",
					WAREHOUSE_ZIP=".$db->qstr($zip).",
					WAREHOUSE_COUNTRY=".$db->qstr($country).",
					WAREHOUSE_ACTIVE=".$db->qstr($active ? 1 : 0);
		} else {
			$q = "UPDATE ".PRFX."TABLE_WAREHOUSE SET
					WAREHOUSE_NAME=".$db->qstr($name).",
					WAREHOUSE_CODE=".$db->qstr($code).",
					WAREHOUSE_ADDRESS=".$db->qstr($address).",
					WAREHOUSE_CITY=".$db->qstr($city).",
					WAREHOUSE_STATE=".$db->qstr($state).",
					WAREHOUSE_ZIP=".$db->qstr($zip).",
					WAREHOUSE_COUNTRY=".$db->qstr($country).",
					WAREHOUSE_ACTIVE=".$db->qstr($active ? 1 : 0)."
				  WHERE WAREHOUSE_ID=".$db->qstr($id);
		}

		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

		force_page('inventory', 'warehouses&page_title=Warehouses&msg=Warehouse '.($submit === 'New' ? 'Created' : 'Updated'));
		exit;
	}

	if ($submit === 'Delete') {
		$id = isset($VAR['warehouse_id']) ? (int)$VAR['warehouse_id'] : 0;
		if ($id <= 0) {
			force_page('inventory', 'warehouses&page_title=Warehouses&error_msg=Invalid warehouse id.');
			exit;
		}

		$q = "SELECT count(*) as cnt FROM ".PRFX."TABLE_PRODUCT WHERE WAREHOUSE_ID=".$db->qstr($id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
		if ((int)$rs->fields['cnt'] > 0) {
			force_page('inventory', 'warehouses&page_title=Warehouses&error_msg=Cannot delete: warehouse has products.');
			exit;
		}

		$q = "DELETE FROM ".PRFX."TABLE_WAREHOUSE WHERE WAREHOUSE_ID=".$db->qstr($id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

		force_page('inventory', 'warehouses&page_title=Warehouses&msg=Warehouse Deleted');
		exit;
	}

	force_page('inventory', 'warehouses&page_title=Warehouses&error_msg=Unknown action.');
	exit;
}

$q = "SELECT w.WAREHOUSE_ID, w.WAREHOUSE_NAME, w.WAREHOUSE_CODE, w.WAREHOUSE_ADDRESS,
			w.WAREHOUSE_CITY, w.WAREHOUSE_STATE, w.WAREHOUSE_ZIP, w.WAREHOUSE_COUNTRY,
			w.WAREHOUSE_ACTIVE, COUNT(p.PRODUCT_ID) as PRODUCT_COUNT
	  FROM ".PRFX."TABLE_WAREHOUSE w
	  LEFT JOIN ".PRFX."TABLE_PRODUCT p ON (p.WAREHOUSE_ID = w.WAREHOUSE_ID)
	  GROUP BY w.WAREHOUSE_ID, w.WAREHOUSE_NAME, w.WAREHOUSE_CODE, w.WAREHOUSE_ADDRESS,
			w.WAREHOUSE_CITY, w.WAREHOUSE_STATE, w.WAREHOUSE_ZIP, w.WAREHOUSE_COUNTRY,
			w.WAREHOUSE_ACTIVE
	  ORDER BY w.WAREHOUSE_NAME";
if(!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}

$smarty->assign('warehouses', $rs->GetArray());
$smarty->display('inventory'.SEP.'warehouses.tpl');

?>
