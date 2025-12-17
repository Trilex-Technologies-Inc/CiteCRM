<html>
<head>
	<title>{$translate_parts_order_complete}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="css/default.css" rel="stylesheet" type="text/css">
</head>
<body>
<table  width="700" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
	<tr>
		<td valign="top">
			<img src="images/cite_crm.jpg" border="0">
		</td>
	</tr>
</table>
<table  width="700" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
	<tr>
		<td valign="top">
			<!-- Left Column -->
			{foreach item=item from=$company}
				<font size="+2">{$item.COMPANY_NAME}</font><br>
				{$item.COMPANY_ADDRESS}<br>
				{$item.COMPANY_CITY}, {$item.COMPANY_STATE} {$item.COMPANY_ZIP}<br>
				{$item.COMPNAY_PHONE}<br>
			{/foreach}
		<td valign="top" align="right" width="205">
			<!-- Right Column -->
			<table width="205" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
				<tr>
					<td class="olotd5"><b>Invoice ID</b> {$invoice.INVOICE_ID}<br>
							<b>Invoice Date</b> {$invoice.INVOICE_DATE|date_format:"%m/%d/%y"}<br>
							<b>Due Date</b> {$invoice.INVOICE_DUE|date_format:"%m/%d/%y"}
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<table  width="700" border="0" cellpadding="3" cellspacing="0" >
	<tr>
		<td valign="top" width="30%" align="left"><b>Bill:</b><br>			
			{foreach item=item from=$customer_details}
				<font size="+2">{$item.CUSTOMER_DISPLAY_NAME}</font><br>
				{$item.CUSTOMER_ADDRESS}<br>
				{$item.CUSTOMER_CITY}, {$item.CUSTOMER_STATE} {$item.CUSTOMER_ZIP}<br>
				{$item.CUSTOMER_PHONE}<br>
				{$item.CUSTOMER_EMAIL}
			{/foreach}
		</td>
		<td valign="top" align="right" width="200">
			<table width="200" border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">
				<tr>
					<td class="olotd5">
						<b>Work Order ID#</b> {$invoice.WORKORDER_ID}<br>
						<b>Tech</b> {$invoice.EMPLOYEE_DISPLAY_NAME}
					</td>
				</tr>
			</table>
	</tr>
</table>
<br>
<br>
<table width="700" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
	<tr>
		<td align="center" class="olotd5" ><font size="+2">INVOICE</font></td>
	</tr>
</table>
<br>
<br>
<b>Labor</b>
<table width="700" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
	<tr>
		<td width="40" class="olohead"><b>Hours</b></td>
		<td class="olohead"><b>Description</b></td>
		<td class="olohead" width="40"><b>Rate</b></td>
		<td class="olohead" width="80"><b>Sub Total</b></td>
	</tr>
	{section name=q loop=$labor}
		<tr>
			<td class="olotd4" width="40">{$labor[q].INVOICE_LABOR_UNIT}</td>
			<td class="olotd4" >{$labor[q].INVOICE_LABOR_DESCRIPTION}</td>
			<td class="olotd4" width="40" align="right">${$labor[q].INVOICE_LABOR_RATE|string_format:"%.2f"}</td>
			<td class="olotd4" width="80" align="right">${$labor[q].INVOICE_LABOR_SUBTOTAL|string_format:"%.2f"}</td>
		</tr>
	{/section}
</table>
<br>
<b>Parts</b>
<table width="700" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
	<tr>
		<td class="olohead" width="40"><b>Count</b></td>
		<td class="olohead"><b>Description</b></td>
		<td class="olohead" width="40"><b>Amount</b></td>
		<td class="olohead" width="80"><b>Sub Total</b></td>
	</tr>
		{section name=w loop=$parts}		
		<tr class="olotd4">
			<td class="olotd4">{$parts[w].INVOICE_PARTS_COUNT}</td>
			<td class="olotd4">{$parts[w].INVOICE_PARTS_DESCRIPTION}</td>
			<td class="olotd4" align="right">${$parts[w].INVOICE_PARTS_AMOUNT|string_format:"%.2f"}</td>
			<td class="olotd4" align="right">${$parts[w].INVOICE_PARTS_SUBTOTA|string_format:"%.2f"}</td>
		</tr>	
	{/section}
</table>
<br>
<br>
<table width="700" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
	<tr>
		<td align="right">
			<table width="200" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
				<tr>
			
						<td class="olotd4"><b>Sub Total</b></td>
						<td class="olotd4" width="80" align="right">${$invoice.SUB_TOTAL|string_format:"%.2f"}</td>
				</tr><tr>
						<td class="olotd4"><b>Tax</b></td>
						<td class="olotd4" width="80" align="right">${$invoice.TAX|string_format:"%.2f"}</td>
				</tr><tr>
						<td class="olotd4"><b>Shipping</b></td>
						<td class="olotd4" width="80" align="right">${$invoice.SHIPPING|string_format:"%.2f"}</td>
				</tr><t>
						<td class="olotd4"><b>Discount</b></td>
						<td class="olotd4" width="80" align="right">- ${$invoice.DISCOUNT|string_format:"%.2f"}</td>
				</tr><t>
						<td class="olotd4"><b>Total</b></td>
						<td class="olotd4" width="80" align="right">${$invoice.INVOICE_AMOUNT|string_format:"%.2f"}</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<table width="700" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
	<tr>
		<td class="olotd5"><font size="-1">{$thank_you}</font></td>
	</tr>
</table>
<br>
<br>
</body>
</html>