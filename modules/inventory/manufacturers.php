<?php
####################################################
# Cite CRM  Customer Relations Management           #
# Manufacturers CRUD                                #
####################################################

function is_valid_manufacturer_name($name) {
	$name = trim((string)$name);
	return $name !== '' && mb_strlen($name) <= 120;
}

if (isset($VAR['submit'])) {
	$submit = $VAR['submit'];

	if ($submit === 'New') {
		$name = isset($VAR['manufacturer_name']) ? trim($VAR['manufacturer_name']) : '';
		$website = isset($VAR['manufacturer_website']) ? trim($VAR['manufacturer_website']) : '';
		$active = isset($VAR['manufacturer_active']) ? (int)$VAR['manufacturer_active'] : 1;

		if (!is_valid_manufacturer_name($name)) {
			force_page('inventory', 'manufacturers&page_title=Manufacturers&error_msg=Manufacturer name is required (max 120 chars).');
			exit;
		}

		$q = "SELECT count(*) as cnt FROM ".PRFX."TABLE_MANUFACTURER WHERE MANUFACTURER_NAME=".$db->qstr($name);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
		if ((int)$rs->fields['cnt'] > 0) {
			force_page('inventory', 'manufacturers&page_title=Manufacturers&error_msg=Manufacturer already exists.');
			exit;
		}

		$q = "INSERT INTO ".PRFX."TABLE_MANUFACTURER SET
				MANUFACTURER_NAME=".$db->qstr($name).",
				MANUFACTURER_WEBSITE=".$db->qstr($website).",
				MANUFACTURER_ACTIVE=".$db->qstr($active ? 1 : 0);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

		force_page('inventory', 'manufacturers&page_title=Manufacturers&msg=Manufacturer Created');
		exit;
	}

	if ($submit === 'Edit') {
		$id = isset($VAR['manufacturer_id']) ? (int)$VAR['manufacturer_id'] : 0;
		$name = isset($VAR['manufacturer_name']) ? trim($VAR['manufacturer_name']) : '';
		$website = isset($VAR['manufacturer_website']) ? trim($VAR['manufacturer_website']) : '';
		$active = isset($VAR['manufacturer_active']) ? (int)$VAR['manufacturer_active'] : 1;

		if ($id <= 0) {
			force_page('inventory', 'manufacturers&page_title=Manufacturers&error_msg=Invalid manufacturer id.');
			exit;
		}
		if (!is_valid_manufacturer_name($name)) {
			force_page('inventory', 'manufacturers&page_title=Manufacturers&error_msg=Manufacturer name is required (max 120 chars).');
			exit;
		}

		$q = "SELECT count(*) as cnt FROM ".PRFX."TABLE_MANUFACTURER WHERE MANUFACTURER_NAME=".$db->qstr($name)." AND MANUFACTURER_ID<>".$db->qstr($id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
		if ((int)$rs->fields['cnt'] > 0) {
			force_page('inventory', 'manufacturers&page_title=Manufacturers&error_msg=Another manufacturer with that name already exists.');
			exit;
		}

		$q = "UPDATE ".PRFX."TABLE_MANUFACTURER SET
				MANUFACTURER_NAME=".$db->qstr($name).",
				MANUFACTURER_WEBSITE=".$db->qstr($website).",
				MANUFACTURER_ACTIVE=".$db->qstr($active ? 1 : 0)."
			  WHERE MANUFACTURER_ID=".$db->qstr($id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

		force_page('inventory', 'manufacturers&page_title=Manufacturers&msg=Manufacturer Updated');
		exit;
	}

	if ($submit === 'Delete') {
		$id = isset($VAR['manufacturer_id']) ? (int)$VAR['manufacturer_id'] : 0;
		if ($id <= 0) {
			force_page('inventory', 'manufacturers&page_title=Manufacturers&error_msg=Invalid manufacturer id.');
			exit;
		}

		$q = "SELECT count(*) as cnt FROM ".PRFX."TABLE_PRODUCT WHERE MANUFACTURER_ID=".$db->qstr($id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
		if ((int)$rs->fields['cnt'] > 0) {
			force_page('inventory', 'manufacturers&page_title=Manufacturers&error_msg=Cannot delete: manufacturer has products.');
			exit;
		}

		$q = "DELETE FROM ".PRFX."TABLE_MANUFACTURER WHERE MANUFACTURER_ID=".$db->qstr($id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

		force_page('inventory', 'manufacturers&page_title=Manufacturers&msg=Manufacturer Deleted');
		exit;
	}

	force_page('inventory', 'manufacturers&page_title=Manufacturers&error_msg=Unknown action.');
	exit;
}

$q = "SELECT m.MANUFACTURER_ID, m.MANUFACTURER_NAME, m.MANUFACTURER_WEBSITE, m.MANUFACTURER_ACTIVE,
			COUNT(p.PRODUCT_ID) as PRODUCT_COUNT
	  FROM ".PRFX."TABLE_MANUFACTURER m
	  LEFT JOIN ".PRFX."TABLE_PRODUCT p ON (p.MANUFACTURER_ID = m.MANUFACTURER_ID)
	  GROUP BY m.MANUFACTURER_ID, m.MANUFACTURER_NAME, m.MANUFACTURER_WEBSITE, m.MANUFACTURER_ACTIVE
	  ORDER BY m.MANUFACTURER_NAME";
if(!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}

$smarty->assign('manufacturers', $rs->GetArray());
$smarty->display('inventory'.SEP.'manufacturers.tpl');

?>

