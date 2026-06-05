<?php
####################################################
# IN Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
# Company Edit file											#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################

if(isset($VAR['submit'])) {

	// Optional: upload/update company logo (stored in /images as company_logo.<ext>)
	$logo_error = '';
	if(isset($_FILES['company_logo']) && is_array($_FILES['company_logo']) && isset($_FILES['company_logo']['error']) && $_FILES['company_logo']['error'] !== UPLOAD_ERR_NO_FILE) {
		if($_FILES['company_logo']['error'] !== UPLOAD_ERR_OK) {
			$logo_error = 'Logo upload failed.';
		} else {
			$max_bytes = 2 * 1024 * 1024;
			if(isset($_FILES['company_logo']['size']) && $_FILES['company_logo']['size'] > $max_bytes) {
				$logo_error = 'Logo file is too large (max 2MB).';
			} else {
				$tmp_name = $_FILES['company_logo']['tmp_name'];
				$mime = '';
				if(function_exists('finfo_open')) {
					$finfo = @finfo_open(FILEINFO_MIME_TYPE);
					if($finfo) {
						$mime = @finfo_file($finfo, $tmp_name);
						@finfo_close($finfo);
					}
				}
				$allowed = array(
					'image/png'  => 'png',
					'image/jpeg' => 'jpg',
					'image/gif'  => 'gif',
					'image/webp' => 'webp',
				);

				$ext = isset($allowed[$mime]) ? $allowed[$mime] : '';
				if($ext === '') {
					// Fallback check for older PHP setups
					$img_info = @getimagesize($tmp_name);
					if(is_array($img_info) && isset($img_info['mime']) && isset($allowed[$img_info['mime']])) {
						$ext = $allowed[$img_info['mime']];
					}
				}

				if($ext === '') {
					$logo_error = 'Invalid logo image type. Please upload PNG, JPG, GIF, or WEBP.';
				} else {
					// Use project-relative path (relative to index.php in project root).
					$images_dir = 'images';
					if(!is_dir($images_dir)) {
						$logo_error = 'Images directory is missing: images/';
					} else {
						if(!is_writable($images_dir)) {
							// Best-effort permission fix (may fail depending on server ownership/config)
							@chmod($images_dir, 0775);
							if(!is_writable($images_dir)) {
								@chmod($images_dir, 0777);
							}
						}
						if(!is_writable($images_dir)) {
							$logo_error = 'Upload folder is not writable: images/. Please fix permissions (chown/chmod) and try again.';
						}
					}

					if($logo_error != '') {
						// keep error set; skip move/upload
					} else {
					$dest = $images_dir . DIRECTORY_SEPARATOR . 'company_logo.' . $ext;

					// Remove existing logo variants to keep a single canonical file
					foreach(array('png','jpg','jpeg','gif','webp') as $old_ext) {
						$old = $images_dir . DIRECTORY_SEPARATOR . 'company_logo.' . $old_ext;
						if(is_file($old)) {
							@unlink($old);
						}
					}

					if(!@move_uploaded_file($tmp_name, $dest)) {
						$logo_error = 'Could not save uploaded logo. Please check file permissions on /images.';
					}
					}
				}
			}
		}
	}


	/* get pdf printing option */
	if(isset($VAR['pdf_print']) && $VAR['pdf_print'] == 1) {
		$html_print = 0;
		$pdf_print  = 1;
	} else {
		$html_print = 1;
		$pdf_print  = 0;
	}

$q = 'UPDATE '.PRFX.'SETUP SET ';

/* Optional shipping columns (for upgraded DBs) */
$has_shipping_columns = false;
$rs_cols = $db->Execute("SHOW COLUMNS FROM ".PRFX."SETUP LIKE 'SHIPPING_PROVIDER'");
if($rs_cols && !$rs_cols->EOF) {
	$has_shipping_columns = true;
}

/* Optional company tax ID column support for older installs */
$rs_cols = $db->Execute("SHOW COLUMNS FROM ".PRFX."TABLE_COMPANY LIKE 'COMPANY_TAX_ID'");
if($rs_cols && $rs_cols->EOF) {
	$db->Execute("ALTER TABLE ".PRFX."TABLE_COMPANY ADD COLUMN COMPANY_TAX_ID varchar(60) NOT NULL default ''");
}

if(isset($VAR['ups_password']) && $VAR['ups_password'] != '') {
	$q .= 'UPS_PASSWORD		= '. $db->qstr( $VAR['ups_password']		) .', ';
}

if($has_shipping_columns && isset($VAR['fedex_password']) && $VAR['fedex_password'] != '') {
	$q .= 'FEDEX_PASSWORD		= '. $db->qstr( $VAR['fedex_password']	) .', ';
}
		$q .= '
			HTML_PRINT 			= '. $db->qstr( $html_print          	) .',
			PDF_PRINT				= '. $db->qstr( $pdf_print           	) .',
			INVOCIE_TAX 			= '. $db->qstr( isset($VAR['inv_tax']) && $VAR['inv_tax'] != '' ? $VAR['inv_tax'] : '0' ) .',
			INV_THANK_YOU 		= '. $db->qstr( isset($VAR['inv_thank_you']) && $VAR['inv_thank_you'] != '' ? $VAR['inv_thank_you'] : ' ' ) .',
			WELCOME_NOTE			= '. $db->qstr( isset($VAR['welcome']) && $VAR['welcome'] != '' ? $VAR['welcome'] : ' ' );

	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}




unset($q);


	/* update company information */
	$q = 'UPDATE '.PRFX.'TABLE_COMPANY SET
		  	COMPANY_NAME			= '. $db->qstr( isset($VAR['company_name']) && $VAR['company_name'] != '' ? $VAR['company_name'] : ' ' ) .',
		  	COMPANY_ADDRESS 	= '. $db->qstr( isset($VAR['address']) && $VAR['address'] != '' ? $VAR['address'] : ' ' ) .',
			COMPANY_CITY 		= '. $db->qstr( isset($VAR['city']) && $VAR['city'] != '' ? $VAR['city'] : ' ' ) .',
			COMPANY_STATE		= '. $db->qstr( isset($VAR['state']) && $VAR['state'] != '' ? $VAR['state'] : ' ' ) .',
			COMPANY_ZIP 			= '. $db->qstr( isset($VAR['zip']) && $VAR['zip'] != '' ? $VAR['zip'] : ' ' ) .',
			COMPANY_COUNTRY		= '. $db->qstr( isset($VAR['country']) && $VAR['country'] != '' ? $VAR['country'] : ' ' ) .',
			COMPNAY_PHONE		= '. $db->qstr( isset($VAR['phone']) && $VAR['phone'] != '' ? $VAR['phone'] : ' ' ) .',
			COMPNAY_MOBILE		= '. $db->qstr( isset($VAR['mobile_phone']) && $VAR['mobile_phone'] != '' ? $VAR['mobile_phone'] : ' ' ) .', 
			COMPANY_TOLL_FREE	= '. $db->qstr( isset($VAR['toll_free']) && $VAR['toll_free'] != '' ? $VAR['toll_free'] : ' ' ) .',
			COMPANY_TAX_ID		= '. $db->qstr( isset($VAR['company_tax_id']) && $VAR['company_tax_id'] != '' ? $VAR['company_tax_id'] : ' ' );
		

	
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		if($logo_error != '') {
			force_page('control', 'company_edit&error_msg=' . urlencode($logo_error));
			exit;
		}

		force_page('control', 'company_edit&msg=The Company information was updated');
		exit;
	}

} else {

	/* get current Company information */
	$q = 'SELECT * FROM '.PRFX.'TABLE_COMPANY';
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		} else {
			$arr = $rs->GetArray();
		}
	
	/* load setup Information */
	$q = 'SELECT * FROM '.PRFX.'SETUP';
	if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		} else {
			$setup = $rs->GetArray();
		}
	
	/* get country codes */
	$q = 'SELECT * FROM '.PRFX.'COUNTRY';
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	$country = $rs->GetArray();
	
	$smarty->assign('country', $country);
	$smarty->assign('setup', $setup);
	$smarty->assign('company', $arr);
	$smarty->display('control/company_edit.tpl');
}
?>
