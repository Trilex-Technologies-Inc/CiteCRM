<!-- new.tpl -->
{literal}
	</script>
<link rel="stylesheet" type="text/css" media="all" href="include/jscalendar/calendar-blue.css" title="win2k-1" />
	<script type="text/javascript" src="include/jscalendar/calendar_stripped.js"></script>
	<script type="text/javascript" src="include/jscalendar/lang/calendar-english.js"></script>
	<script type="text/javascript" src="include/jscalendar/calendar-setup_stripped.js"></script>

<script type="text/javascript">
	function addRowToTableLabor(){
		var tbl = document.getElementById('labor');
		var lastRow = tbl.rows.length;
		// if there's no header row in the table, then iteration = lastRow + 1
		var iteration = lastRow;
		var row = tbl.insertRow(lastRow);

		// left cell
		var cellLeft = row.insertCell(0);
		var textNode = document.createTextNode(iteration);
		row.setAttribute('class', 'olotd4')
		cellLeft.appendChild(textNode);

		// right cell
		var cellRight = row.insertCell(1);
		var el = document.createElement('input');
		el.setAttribute('type', 'text');
		el.setAttribute('name', 'hour['+iteration+']');
		el.setAttribute('id', 'hour['+ iteration+']');
		el.setAttribute('size', '4');
		el.setAttribute('class', 'olotd4');
		cellRight.appendChild(el);

		// right cell
		var cellRight = row.insertCell(2);
		row.setAttribute('class', 'olotd4');
		var el = document.createElement('input');
		el.setAttribute('type', 'text');
		el.setAttribute('name', 'description['+ iteration+']');
		el.setAttribute('id', 'description['+iteration+']');
		el.setAttribute('size', '40');
		el.setAttribute('class', 'olotd4');
		cellRight.appendChild(el);

		var cellRightSel = row.insertCell(3);
		var sel = document.createElement('select');
		sel.setAttribute('name', 'rate[' + iteration+']');

		{/literal}
		{section loop=$rate name=i}
		sel.options[{$smarty.section.i.index}] = new Option('${$rate[i].LABOR_RATE_AMOUT} {$rate[i].LOABOR_RATE_NAME}', '{$rate[i].LABOR_RATE_AMOUT}');
		{/section}
		{literal}
		sel.setAttribute('class', 'olotd4');
		cellRightSel.appendChild(sel);
	}

	function keyPressTestLabor(e, obj){
		var validateChkb = document.getElementById('chkValidateOnKeyPress');
		if (validateChkb.checked) {
			var displayObj = document.getElementById('spanOutput');
			var key;
			if(window.event) {
				key = window.event.keyCode;
			}
			else if(e.which) {
				key = e.which;
			}
			var objId;
			if (obj != null) {
				objId = obj.id;
			} else {
				objId = this.id;
			}
			displayObj.innerHTML = objId + ' : ' + String.fromCharCode(key);
		}
	}

	function removeRowFromTableLabor(){
		var tbl = document.getElementById('labor');
		var lastRow = tbl.rows.length;
		if (lastRow > 1) tbl.deleteRow(lastRow - 1);
	}


	function validateRowLabor(frm){
		var chkb = document.getElementById('chkValidate');
		if (chkb.checked) {
			var tbl = document.getElementById('labor');
			var lastRow = tbl.rows.length - 1;
			var i;
			for (i=1; i<=lastRow; i++) {
				var aRow = document.getElementById('txtRow' + i);
				if (aRow.value.length <= 0) {
					alert('Row ' + i + ' is empty');
					return;
				}
			}
		}
		openInNewWindow(frm);
	}
	// end of Labor


	function addRowToTableParts(){
		var tbl = document.getElementById('parts');
		var lastRow = tbl.rows.length;
		// if there's no header row in the table, then iteration = lastRow + 1
		var iteration = lastRow;
		var row = tbl.insertRow(lastRow);

		// Number
		var cellLeft = row.insertCell(0);
		var textNode = document.createTextNode(iteration);
		row.setAttribute('class', 'olotd4')
		cellLeft.appendChild(textNode);

		// Count
		var cellRight = row.insertCell(1);
		var el = document.createElement('input');
		el.setAttribute('type', 'text');
		el.setAttribute('name', 'count['+iteration+']');
		el.setAttribute('id', 'count['+ iteration+']');
		el.setAttribute('size', '4');
		el.setAttribute('class', 'olotd4');
		cellRight.appendChild(el);

		// Prts Description
		var cellRight = row.insertCell(2);
		row.setAttribute('class', 'olotd4');
		var el = document.createElement('input');
		el.setAttribute('type', 'text');
		el.setAttribute('name', 'parts_description['+ iteration+']');
		el.setAttribute('id', 'parts_description['+iteration+']');
		el.setAttribute('size', '40');
		el.setAttribute('class', 'olotd4');
		//el.onkeypress = keyPressTestLabor;
		cellRight.appendChild(el);

		// Manufacture
		var cellRightSel = row.insertCell(3);
		var sel = document.createElement('select');
		sel.setAttribute('name', 'manufacture[' + iteration +']');
		sel.options[0] = new Option('None', 'None');
		sel.options[1] = new Option('text one', 'value1');
		sel.setAttribute('class', 'olotd4');
		cellRightSel.appendChild(sel);

		// Price
		var cellRight = row.insertCell(4);
		var el = document.createElement('input');
		el.setAttribute('type', 'text');
		el.setAttribute('name', 'parts_price['+iteration+']');
		el.setAttribute('id', 'parts_price['+ iteration+']');
		el.setAttribute('size', '5');
		el.setAttribute('class', 'olotd4');
		cellRight.appendChild(el);

	}

	function keyPressTestParts(e, obj){

	}

	function removeRowFromTableParts(){
		var tbl = document.getElementById('parts');
		var lastRow = tbl.rows.length;
		if (lastRow > 1) tbl.deleteRow(lastRow - 1);
	}


	function validateRowParts(frm){

		var tbl = document.getElementById('parts');
		var lastRow = tbl.rows.length - 1;
		var i;
		for (i=1; i<=lastRow; i++) {
			var aRow = document.getElementById('txtRow' + i);
			if (aRow.value.length <= 0) {
				alert('Row ' + i + ' is empty');
				return;
			}
		}
	}
</script>
{/literal}
<div class="container my-4">

	<!-- Toolbar -->
	<div class="mb-3">
		{include file="core/tool_bar.tpl"}
	</div>

	<!-- Invoice Header -->
	<div class="card mb-4">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h5 class="mb-0">{$translate_invoice_for} {$wo_id}</h5>
			<a href="http://www.citecrm.com/docs/#billing" target="_blank">
				<i class="bi bi-question-circle-fill fs-5 text-secondary" aria-hidden="true"></i>
			</a>
		</div>

		<div class="card-body">
			{if $error_msg != ""}
				<div class="alert alert-danger">
					{include file="core/error.tpl"}
				</div>
			{/if}

			<form action="index.php?page=invoice:new" method="POST" name="new_invoice" id="new_invoice"
					{if $has_validator}
				onsubmit="return validate_new_invoice(this);"
					{/if}>

				<!-- Invoice Info Table -->
				<div class="table-responsive mb-4">
					<table class="table table-bordered table-striped">
						<thead class="table-light">
						<tr>
							<th>{$translate_invoice_id}</th>
							<th>{$translate_invoice_date}</th>
							<th>{$translate_invoice_due}</th>
							<th>{$translate_invoice_amount}</th>
							<th>{$translate_invoice_tech}</th>
							<th>{$translate_invoice_work_order}</th>
							<th>{$translate_invoice_balance}</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td>{$invoice.INVOICE_ID}</td>
							<td>
								<input type="text" size="10" name="date" id="date" value="{$invoice.INVOICE_DATE|date_format:"%m/%d/%y"}" class="form-control form-control-sm d-inline w-auto">
								<button type="button" id="trigger_date" class="btn btn-sm btn-secondary">+</button>
								{literal}
									<script>
										Calendar.setup({
											inputField  : "date",
											ifFormat    : "%m/%d/%Y",
											button      : "trigger_date"
										});
									</script>
								{/literal}
							</td>
							<td>
								<input type="text" size="10" name="due_date" id="due_date" value="{$invoice.INVOICE_DUE|date_format:"%m/%d/%y"}" class="form-control form-control-sm d-inline w-auto">
								<button type="button" id="trigger_due_date" class="btn btn-sm btn-secondary">+</button>
								{literal}
									<script>
										Calendar.setup({
											inputField  : "due_date",
											ifFormat    : "%m/%d/%Y",
											button      : "trigger_due_date"
										});
									</script>
								{/literal}
							</td>
							<td>${$invoice.INVOICE_AMOUNT|string_format:"%.2f"}</td>
							<td>{$invoice.EMPLOYEE_DISPLAY_NAME}</td>
							<td><a href="?page=workorder:view&wo_id={$invoice.WORKORDER_ID}&page_title={$translate_invoice_wo_id} {$invoice.WORKORDER_ID}">{$invoice.WORKORDER_ID}</a></td>
							<td>
								{if $invoice.BALLANCE > 0}
									<span class="text-danger">${$invoice.BALLANCE|string_format:"%.2f"}</span>
								{else}
									${$invoice.INVOICE_AMOUNT|string_format:"%.2f"}
								{/if}
							</td>
						</tr>
						</tbody>
					</table>
				</div>

				<!-- Billing & Payment Info -->
				<div class="row mb-4">
					<div class="col-md-6">
						<h6>{$translate_invoice_bill}</h6>
						{foreach item=item from=$customer_details}
							<div class="mb-2">
								<a href="?page=customer:customer_details&customer_id={$item.CUSTOMER_ID}&page_title={$item.CUSTOMER_DISPLAY_NAME}">
									{$item.CUSTOMER_DISPLAY_NAME}
								</a><br>
								{$item.CUSTOMER_ADDRESS}<br>
								{$item.CUSTOMER_CITY}, {$item.CUSTOMER_STATE} {$item.CUSTOMER_ZIP}<br>
								{$item.CUSTOMER_PHONE}<br>
								{$item.CUSTOMER_EMAIL}<br>
								Discount Rate:
								<input type="hidden" name="customer_id" value="{$item.CUSTOMER_ID}">
								<input type="text" name="discount" class="form-control form-control-sm d-inline w-auto" size="6" value="{$item.DISCOUNT}">%
							</div>
						{/foreach}
					</div>
					<div class="col-md-6">
						<h6>{$translate_invoice_pay}</h6>
						{section name=x loop=$company}
							<div class="mb-2">
								{$company[x].COMPANY_NAME}<br>
								{$company[x].COMPANY_ADDRESS}<br>
								{$company[x].COMPANY_CITY}, {$company[x].COMPANY_STATE} {$company[x].COMPANY_ZIP}<br>
								{$company[x].COMPNAY_PHONE}<br>
							</div>
						{/section}
					</div>
				</div>

				<!-- Transactions Log -->
				{if $invoice.BALLANCE > 0}
					<div class="card mb-4">
						<div class="card-header">
							{$translate_invoice_trans_log}
						</div>
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table table-sm table-bordered mb-0">
									<thead class="table-light">
									<tr>
										<th>{$translate_invoice_trans_id}</th>
										<th>{$translate_invoice_date}</th>
										<th>{$translate_invoice_amount}</th>
										<th>{$translate_invoice_type}</th>
									</tr>
									</thead>
									<tbody>
									{section name=r loop=$trans}
										<tr>
											<td>{$trans[r].TRANSACTION_ID}</td>
											<td>{$trans[r].DATE|date_format:"%m/%d/%y %r"}</td>
											<td><b>$</b>{$trans[r].AMOUNT|string_format:"%.2f"}</td>
											<td>
												{if $trans[r].TYPE == 1} {$translate_invoice_cc}
												{elseif $trans[r].TYPE == 2} {$translate_invoice_check}
												{elseif $trans[r].TYPE == 3} {$translate_invoice_cash}
												{elseif $trans[r].TYPE == 4} {$translate_invoice_gift}
												{elseif $trans[r].TYPE == 5} {$translate_invoice_paypal}
												{/if}
											</td>
										</tr>
										<tr>
											<td colspan="4"><b>{$translate_invoice_memo}</b>: {$trans[r].MEMO}</td>
										</tr>
									{/section}
									</tbody>
								</table>
							</div>
						</div>
					</div>
				{/if}

				<!-- Submit Buttons -->
				<div class="d-flex justify-content-between mb-4">
					<div>
						<input type="hidden" name="chkValidateOnKeyPress" value="checked">
						<input type="hidden" name="invoice_id" value="{$invoice.INVOICE_ID}">
						<input type="hidden" name="sub_total" value="{$invoice.SUB_TOTAL|string_format:"%.2f"}">
						<input type="hidden" name="page" value="invoice:new">
						<input type="hidden" name="create_by" value="{$login_id}">
						<input type="hidden" name="wo_id" value="{$wo_id}">
						<input  name="submit" type="submit" class="btn btn-primary" value="{$translate_invoice_submit}" >
					</div>
				</div>

			</form>
		</div>
	</div>
</div>
