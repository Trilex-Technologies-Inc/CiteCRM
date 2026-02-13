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

	<!-- Work Orders / Invoice Card -->
	<div class="card shadow-sm">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h5 class="mb-0">{$translate_incvoice_view_un_paid}</h5>
			<img src="images/icons/16x16/help.gif" alt="Help">
		</div>
		<div class="card-body">

			<!-- Pagination Controls -->
			<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
				<div>
					<form class="d-flex align-items-center" id="paginationForm">
						<a href="?page=invoice:view_unpaid&name={$name}&submit=submit&page_no=1" class="btn btn-outline-secondary btn-sm me-1">
							<i class="bi bi-skip-start-fill"></i>
						</a>
						{if $previous != ''}
							<a href="?page=invoice:view_unpaid&name={$name}&submit=submit&page_no={$previous}" class="btn btn-outline-secondary btn-sm me-1">
								<i class="bi bi-caret-left-fill"></i>
							</a>
						{/if}

						<select name="page_no" class="form-select form-select-sm me-1" style="width:auto;" onchange="go()">
							{section name=page loop=$total_pages start=1}
								<option value="?page=invoice:view_unpaid&name={$name}&submit=submit&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index } selected {/if}>
									{$translate_invoice_page} {$smarty.section.page.index} {$translate_invoice_of} {$total_pages}
								</option>
							{/section}
							<option value="?page=invoice:view_unpaid&name={$name}&submit=submit&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
								{$translate_invoice_page} {$total_pages} {$translate_invoice_of} {$total_pages}
							</option>
						</select>

						{if $next != ''}
							<a href="?page=invoice:view_unpaid&name={$name}&submit=submit&page_no={$next}" class="btn btn-outline-secondary btn-sm me-1">
								<i class="bi bi-caret-right-fill"></i>
							</a>
						{/if}
						<a href="?page=invoice:view_unpaid&name={$name}&submit=submit&page_no={$total_pages}" class="btn btn-outline-secondary btn-sm">
							<i class="bi bi-skip-end-fill"></i>
						</a>
					</form>
				</div>
				<div>
					<small>{$total_results} {$translate_invoice_records}</small>
				</div>
			</div>

			<!-- Invoice Table -->
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
							ondblclick="window.location='index.php?page=invoice:new&wo_id={$invoice[q].WORKORDER_ID}&page_title={$translate_invoice_invoice}&customer_id={$invoice[q].CUSTOMER_ID}';">
							<td>
								<a href="index.php?page=invoice:new&wo_id={$invoice[q].WORKORDER_ID}&page_title={$translate_invoice_invoice}&customer_id={$invoice[q].CUSTOMER_ID}">
									{$invoice[q].INVOICE_ID}
								</a>
							</td>
							<td>{$invoice[q].INVOICE_DATE|date_format:"%m-%d-%Y"}</td>
							<td>{$invoice[q].INVOICE_DUE|date_format:"%m-%d-%Y"}</td>
							<td>
								<img src="images/icons/16x16/view+.gif"
									 onMouseOver="ddrivetip('<b><center>Contact Info</b></center><hr><b>Phone: </b>{$invoice[q].CUSTOMER_PHONE}<br> <b>Work: </b>{$invoice[q].CUSTOMER_WORK_PHONE}<br><b>Mobile: </b>{$invoice[q].CUSTOMER_MOBILE_PHONE}<br><br>{$invoice[q].CUSTOMER_ADDRESS}<br>{$invoice[q].CUSTOMER_CITY}, {$invoice[q].CUSTOMER_STATE}<br>{$invoice[q].CUSTOMER_ZIP}')"
									 onMouseOut="hideddrivetip()">
								<a href="{$invoice[q].CUSTOMER_ID}">{$invoice[q].CUSTOMER_DISPLAY_NAME}</a>
							</td>
							<td>
								<a href="index.php?page=workorder:view&wo_id={$invoice[q].WORKORDER_ID}&page_title=Work%20Order%20ID%20{$invoice[q].WORKORDER_ID}">
									{$invoice[q].WORKORDER_ID}
								</a>
							</td>
							<td>
								<img src="images/icons/16x16/view+.gif"
									 onMouseOver="ddrivetip('<center><b>Contact</b></center><hr><b>Work: </b>{$invoice[q].EMPLOYEE_WORK_PHONE}<br><b>Mobile: </b>{$invoice[q].EMPLOYEE_MOBILE_PHONE}<br><b>Home: </b>{$invoice[q].EMPLOYEE_HOME_PHONE}')"
									 onMouseOut="hideddrivetip()">
								<a href="?page=employees:employee_details&employee_id={$invoice[q].EMPLOYEE_ID}&page_title={$invoice[q].EMPLOYEE_DISPLAY_NAME}">
									{$invoice[q].EMPLOYEE_DISPLAY_NAME}
								</a>
							</td>
							<td>${$invoice[q].SUB_TOTAL}</td>
							<td>${$invoice[q].TAX}</td>
							<td>${$invoice[q].DISCOUNT}</td>
							<td>${$invoice[q].INVOICE_AMOUNT}</td>
						</tr>
					{/section}
					</tbody>
				</table>
			</div>

		</div>
	</div>

</div>
