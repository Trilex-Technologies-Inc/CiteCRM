<?php
####################################################
# IN Cite CRM	Customer Relations Management			#	
#	 Copyright (C) 2003 - 2005 In-Site CRM				#
#  www.incitecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Print Invoice												#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################
require_once ('include.php');
/* Assign company information */
$q = 'SELECT * FROM '.PRFX.'TABLE_COMPANY';
$rs = $db->Execute($q);
$company = $rs->GetArray();
$smarty->assign('company', $company);

$invoice_id  = $VAR['invoice_id'];
$customer_id = $VAR['customer_id'];

/* Generic error control */
if(empty($invoice_id)) {
	/* If no work order ID then we dont belong here */
	force_page('core', 'error&error_msg=Invoice Not found: Invoice ID: '.$invoice_id.'&menu=1');
}

/* check if we have a customer id and if so get details */
if($customer_id == "" || $customer_id == "0"){
	force_page('core', 'error&error_msg=No Customer ID&menu=1');
	exit;
} else {
	$q = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID=".$db->qstr($customer_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	
	$customer_details = $rs->GetAssoc();
	if(empty($customer_details)){
		force_page('core', 'error&error_msg=No Customer details found for Customer ID '.$customer_id.'.&menu=1');
		exit;
	}
	
	
}
	/* get invoice details */
	$q = "SELECT  ".PRFX."TABLE_INVOICE.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME FROM  ".PRFX."TABLE_INVOICE 
			LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON (".PRFX."TABLE_INVOICE.EMPLOYEE_ID = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID)
			WHERE INVOICE_ID=".$db->qstr($invoice_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	$invoice = $rs->FetchRow();
	
	/* get any labor details */
	$q = "SELECT * FROM ".PRFX."TABLE_INVOICE_LABOR WHERE INVOICE_ID=".$db->qstr($invoice['INVOICE_ID']);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	$labor = $rs->GetArray();

	/* get any parts */
	$q = "SELECT * FROM ".PRFX."TABLE_INVOICE_PARTS WHERE INVOICE_ID=".$db->qstr($invoice['INVOICE_ID']);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	$parts = $rs->GetArray();
	
	
	
	/* Get trans action information */
	$q = "SELECT * FROM ".PRFX."TABLE_TRANSACTION WHERE INVOCIE_ID=".$db->qstr($invoice['INVOICE_ID']);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	$trans = $rs->GetArray();

	

/* get printing options */
$q = "SELECT  HTML_PRINT, PDF_PRINT, INV_THANK_YOU  FROM ".PRFX."SETUP";
$rs = $db->execute($q);
$html_print = $rs->fields['HTML_PRINT'];
$pdf_print  = $rs->fields['PDF_PRINT'];
$thank_you  =  $rs->fields['INV_THANK_YOU'];


if($html_print == 1) {
/* html Print out */
	$smarty->assign('customer_details',$customer_details);
	$smarty->assign('invoice',$invoice);

	if(empty($labor)){
		$smarty->assign('labor', 0);
	} else {
		$smarty->assign('labor', $labor);
	}
	
	if(empty($parts)){
		$smarty->assign('parts', 0);
	} else {
		$smarty->assign('parts', $parts);
	}
	
	$smarty->assign('thank_you',$thank_you);
	$smarty->assign('trans',$trans);
	$smarty->display('invoice'.SEP.'print.tpl');

} else if($pdf_print == 1) {
	/* create pdf */
	require(INCLUDE_URL.SEP.'fpdf'.SEP.'fpdf.php');
	class PDF extends FPDF {
	//Page header
	function Header() {
		$this->SetFont('Arial','B',15);
	}

//Page footer
	function Footer(){
		//Position at 1.5 cm from bottom
		$this->SetY(-15);
		//Arial italic 8
		$this->SetFont('Arial','I',8);
		//Page number
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

//Instanciation of inherited class
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

	$pdf->Cell(0,10,'',1,1);
	$pdf->Cell(10,0,'TECHNICIAN COPY',1,1);
$pdf->Output();	

	
} else {
	force_page('core', "error&menu=1&error_msg=No Printing Options set. Please set up printing options in the Control Center.&type=error");
	exit;
}

?>