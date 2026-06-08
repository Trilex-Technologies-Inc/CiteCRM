{literal}
	<script>
		function go() {
			const box = document.forms[0].page_no;
			const destination = box.options[box.selectedIndex].value;
			if (destination) location.href = destination;
		}
	</script>
{/literal}

<div class="container my-4">

	<!-- Toolbar -->
	<div class="mb-3">
		{include file="core/tool_bar.tpl"}
	</div>

	<!-- Error Messages -->
	{if $error_msg != ""}
		<div class="alert alert-danger">
			{include file="core/error.tpl"}
		</div>
	{/if}

	<!-- Paid Invoices Card -->
	<div class="card shadow-sm">
	<div class="card-header d-flex justify-content-between align-items-center">
		<h5 class="mb-0">{$translate_incvoice_view_paid}</h5>
		<i class="bi bi-question-circle-fill fs-5 text-secondary" aria-hidden="true"></i>
	</div>
		<div class="card-body">

			<!-- Pagination Controls -->
			<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
				<div>
					<form class="d-flex align-items-center" id="paginationForm">
						<a href="?page=invoice:view_paid&submit=submit&page_no=1" class="me-1">
							<img src="images/rewnd_24.gif" border="0" alt="First Page">
						</a>

						{if $previous != ''}
							<a href="?page=invoice:view_paid&submit=submit&page_no={$previous}" class="me-1">
								<img src="images/back_24.gif" border="0" alt="Previous Page">
							</a>
						{/if}

						<select name="page_no" class="form-select form-select-sm me-1" style="width:auto;" onchange="go()">
							{section name=page loop=$total_pages start=1}
								<option value="?page=invoice:view_paid&submit=submit&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index } selected {/if}>
									{$translate_invoice_page} {$smarty.section.page.index} {$translate_invoice_of} {$total_pages}
								</option>
							{/section}
							<option value="?page=invoice:view_paid&submit=submit&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
								{$translate_invoice_page} {$total_pages} {$translate_invoice_of} {$total_pages}
							</option>
						</select>

						{if $next != ''}
							<a href="?page=invoice:view_paid&submit=submit&page_no={$next}" class="me-1">
								<img src="images/forwd_24.gif" border="0" alt="Next Page">
							</a>
						{/if}

						<a href="?page=invoice:view_paid&submit=submit&page_no={$total_pages}">
							<img src="images/fastf_24.gif" border="0" alt="Last Page">
						</a>
					</form>
				</div>

				<div>
					<small>{$total_results} {$translate_invoice_records}</small>
				</div>
			</div>

			<!-- Paid Invoice Table -->
			<div class="table-responsive">
				<table class="table table-bordered table-hover align-middle">
					<thead class="table-light">
					<tr>
						<th>{$translate_invoice_id}</th>
						<th>{$translate_invoice_date}</th>
						<th>{$translate_invoice_due}</th>
						<th>{$translate_invoice_customer}</th>
						<th>{$translate_invoice_work_order}</th>
						<th>{$translate_invoice_employee}</th>
						<th>{$translate_invoice_sub_total}</th>
						<th>{$translate_invoice_tax}</th>
						<th>{$translate_invoice_discount}</th>
						<th>{$translate_invoice_amount}</th>
					</tr>
					</thead>
					<tbody>
					{section name=q loop=$invoice}
						<tr class="table-row"
							ondblclick="window.location='index.php?page=invoice:view&invoice_id={$invoice[q].INVOICE_ID}&page_title={$translate_invoice_invoice}&customer_id={$invoice[q].CUSTOMER_ID}';">
							<td>
								<a href="index.php?page=invoice:view&invoice_id={$invoice[q].INVOICE_ID}&page_title=Invoice&customer_id={$invoice[q].CUSTOMER_ID}">
									{$invoice[q].INVOICE_ID}
								</a>
							</td>
							<td>{$invoice[q].INVOICE_DATE|date_format:"%m-%d-%Y"}</td>
							<td>{$invoice[q].INVOICE_DUE|date_format:"%m-%d-%Y"}</td>
							<td>
								<i class="bi bi-info-circle-fill text-primary fs-5"
								   aria-hidden="true"
								   onMouseOver="ddrivetip('<b>{$translate_invoice_phone} </b>{$invoice[q].CUSTOMER_PHONE}<br><b>Work: </b>{$invoice[q].CUSTOMER_WORK_PHONE}<br><b>Mobile: </b>{$invoice[q].CUSTOMER_MOBILE_PHONE}<br><br>{$invoice[q].CUSTOMER_ADDRESS}<br>{$invoice[q].CUSTOMER_CITY}, {$invoice[q].CUSTOMER_STATE}<br>{$invoice[q].CUSTOMER_ZIP}')"
								   onMouseOut="hideddrivetip()"></i>
								<a href="{$invoice[q].CUSTOMER_ID}">{$invoice[q].CUSTOMER_DISPLAY_NAME}</a>
							</td>
							<td>
								<a href="index.php?page=workorder:view&wo_id={$invoice[q].WORKORDER_ID}&page_title={$translate_invoice_wo_id}{$invoice[q].WORKORDER_ID}">
									{$invoice[q].WORKORDER_ID}
								</a>
							</td>
							<td>
								<i class="bi bi-info-circle-fill text-primary fs-5"
								   aria-hidden="true"
								   onMouseOver="ddrivetip('<b>Work: </b>{$invoice[q].EMPLOYEE_WORK_PHONE}<br><b>Mobile: </b>{$invoice[q].EMPLOYEE_MOBILE_PHONE}<br><b>Home: </b>{$invoice[q].EMPLOYEE_HOME_PHONE}')"
								   onMouseOut="hideddrivetip()"></i>
								<a href="?page=employees:employee_details&employee_id={$invoice[q].EMPLOYEE_ID}&page_title={$invoice[q].EMPLOYEE_DISPLAY_NAME}">
									{$invoice[q].EMPLOYEE_DISPLAY_NAME}
								</a>
							</td>
							<td>${$invoice[q].SUB_TOTAL|string_format:"%.2f"}</td>
							<td>${$invoice[q].TAX|string_format:"%.2f"}</td>
							<td>${$invoice[q].DISCOUNT|string_format:"%.2f"}</td>
							<td>${$invoice[q].PAID_AMOUNT|string_format:"%.2f"}</td>
						</tr>
					{/section}
					</tbody>
				</table>
			</div>

		</div>
	</div>

</div>
