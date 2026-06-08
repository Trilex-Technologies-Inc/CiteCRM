<html>
<head>
	<title>{$translate_parts_order_complete}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="css/default.css" rel="stylesheet" type="text/css">
	{literal}
	<style type="text/css">
		/* Print-friendly, no table-based layout, no Bootstrap dependency */
		body { font-family: Arial, Helvetica, sans-serif; color: #111; }
		.print-wrap { max-width: 700px; margin: 0 auto; }
		.header { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; }
		.brand img { max-width: 220px; height: auto; display: block; }
		.company { font-size: 12px; line-height: 1.35; }
		.company .name { font-size: 18px; font-weight: 700; }
		.meta { border: 1px solid #333; padding: 10px; font-size: 12px; line-height: 1.5; min-width: 200px; }
		.row { display: flex; justify-content: space-between; gap: 24px; margin-top: 18px; }
		.col { flex: 1; }
		.box { border: 1px solid #333; padding: 10px; font-size: 12px; line-height: 1.5; }
		.box .name { font-size: 16px; font-weight: 700; }
		.title { margin: 22px 0 10px; text-align: center; font-size: 20px; font-weight: 800; letter-spacing: 1px; }
		.section { margin-top: 18px; }
		.section h3 { margin: 0 0 8px; font-size: 14px; }
		.items { border: 1px solid #333; }
		.item-row { display: grid; grid-template-columns: 90px 1fr 90px 110px; gap: 8px; padding: 6px 10px; border-top: 1px solid #ddd; font-size: 12px; }
		.item-row:first-child { border-top: 0; }
		.item-head { background: #f2f2f2; font-weight: 700; border-bottom: 1px solid #333; }
		.right { text-align: right; }
		.totals { margin-top: 16px; display: flex; justify-content: flex-end; }
		.totals .box { min-width: 260px; }
		.totals .line { display: flex; justify-content: space-between; gap: 12px; }
		.totals .line + .line { margin-top: 6px; }
		.totals .grand { font-weight: 800; margin-top: 10px; padding-top: 8px; border-top: 1px solid #333; }
		.thanks { margin-top: 18px; border: 1px solid #333; padding: 10px; font-size: 12px; }
		@media print {
			.print-wrap { margin: 0; }
		}
	</style>
	{/literal}
</head>
<body>
<div class="print-wrap">
	<div class="header">
		<div class="brand">
			<img src="images/cite_crm.jpg" alt="CiteCRM">
		</div>
		<div class="meta">
			<div><b>Invoice ID</b> {$invoice.INVOICE_ID}</div>
			<div><b>Invoice Date</b> {$invoice.INVOICE_DATE|date_format:"%m/%d/%y"}</div>
			<div><b>Due Date</b> {$invoice.INVOICE_DUE|date_format:"%m/%d/%y"}</div>
		</div>
	</div>

	<div class="row">
		<div class="col company">
			{foreach item=item from=$company}
				<div class="name">{$item.COMPANY_NAME}</div>
				<div>{$item.COMPANY_ADDRESS}</div>
				<div>{$item.COMPANY_CITY}, {$item.COMPANY_STATE} {$item.COMPANY_ZIP}</div>
				{if $item.COMPANY_TAX_ID != ''}<div>Tax ID: {$item.COMPANY_TAX_ID}</div>{/if}
				<div>{$item.COMPNAY_PHONE}</div>
			{/foreach}
		</div>
	</div>

	<div class="row">
		<div class="col">
			<div class="box">
				<div><b>Bill:</b></div>
				{foreach item=item from=$customer_details}
					<div class="name">{$item.CUSTOMER_DISPLAY_NAME}</div>
					<div>{$item.CUSTOMER_ADDRESS}</div>
					<div>{$item.CUSTOMER_CITY}, {$item.CUSTOMER_STATE} {$item.CUSTOMER_ZIP}</div>
					<div>{$item.CUSTOMER_PHONE}</div>
					<div>{$item.CUSTOMER_EMAIL}</div>
				{/foreach}
			</div>
		</div>
		<div class="col" style="max-width: 260px;">
			<div class="box">
				<div><b>Work Order ID#</b> {$invoice.WORKORDER_ID}</div>
				<div><b>Tech</b> {$invoice.EMPLOYEE_DISPLAY_NAME}</div>
			</div>
		</div>
	</div>

	<div class="title">INVOICE</div>

	<div class="section">
		<h3>Labor</h3>
		<div class="items">
			<div class="item-row item-head">
				<div>Hours</div>
				<div>Description</div>
				<div class="right">Rate</div>
				<div class="right">Sub Total</div>
			</div>
			{section name=q loop=$labor}
				<div class="item-row">
					<div>{$labor[q].INVOICE_LABOR_UNIT}</div>
					<div>{$labor[q].INVOICE_LABOR_DESCRIPTION}</div>
					<div class="right">${$labor[q].INVOICE_LABOR_RATE|string_format:"%.2f"}</div>
					<div class="right">${$labor[q].INVOICE_LABOR_SUBTOTAL|string_format:"%.2f"}</div>
				</div>
			{/section}
		</div>
	</div>

	<div class="section">
		<h3>Parts</h3>
		<div class="items">
			<div class="item-row item-head" style="grid-template-columns: 90px 1fr 90px 110px;">
				<div>Count</div>
				<div>Description</div>
				<div class="right">Amount</div>
				<div class="right">Sub Total</div>
			</div>
			{section name=w loop=$parts}
				<div class="item-row" style="grid-template-columns: 90px 1fr 90px 110px;">
					<div>{$parts[w].INVOICE_PARTS_COUNT}</div>
					<div>{$parts[w].INVOICE_PARTS_DESCRIPTION}</div>
					<div class="right">${$parts[w].INVOICE_PARTS_AMOUNT|string_format:"%.2f"}</div>
					<div class="right">${$parts[w].INVOICE_PARTS_SUBTOTA|string_format:"%.2f"}</div>
				</div>
			{/section}
		</div>
	</div>

	<div class="totals">
		<div class="box">
			<div class="line"><div><b>Sub Total</b></div><div class="right">${$invoice.SUB_TOTAL|string_format:"%.2f"}</div></div>
			<div class="line"><div><b>Tax</b></div><div class="right">${$invoice.TAX|string_format:"%.2f"}</div></div>
			<div class="line"><div><b>Shipping</b></div><div class="right">${$invoice.SHIPPING|string_format:"%.2f"}</div></div>
			<div class="line"><div><b>Discount</b></div><div class="right">- ${$invoice.DISCOUNT|string_format:"%.2f"}</div></div>
			<div class="line grand"><div>Total</div><div class="right">${$invoice.INVOICE_AMOUNT|string_format:"%.2f"}</div></div>
		</div>
	</div>

	<div class="thanks">{$thank_you}</div>
</div>
</body>
</html>
