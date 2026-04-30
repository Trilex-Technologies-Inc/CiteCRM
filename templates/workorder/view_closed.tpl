{literal}
	<script>
		function go() {
			const box = document.forms[0].page_no;
			const destination = box.options[box.selectedIndex].value;
			if (destination) location.href = destination;
		}
	</script>
{/literal}

<!-- Toolbar -->
<div class="mb-3">
	{include file="core/tool_bar.tpl"}
</div>

<!-- Card Container -->
<div class="card">
	<div class="card-header d-flex justify-content-between align-items-center">
		{$translate_workorder_view_closed}
		<a href="http://www.citecrm.com/docs/#work_orders" target="_blank">
			<i class="bi bi-question-circle-fill fs-5 text-secondary" aria-hidden="true"></i>
		</a>
	</div>

	<div class="card-body">
		{if $error_msg != ""}
			<div class="alert alert-danger">
				{include file="core/error.tpl"}
			</div>
		{/if}

		<!-- Pagination -->
		<div class="d-flex justify-content-between align-items-center mb-3">
			<div>
				<a href="?page=workorder:view_closed&submit=submit&page_no=1" class="btn btn-outline-secondary btn-sm">&#xab;</a>
				{if $previous != ''}
					<a href="?page=workorder:view_closed&submit=submit&page_no={$previous}" class="btn btn-outline-secondary btn-sm">&#8249;</a>
				{/if}
			</div>
			<form class="d-inline" id="1">
				<select name="page_no" class="form-select form-select-sm d-inline-block w-auto" onchange="go()">
					{section name=page loop=$total_pages start=1}
						<option value="?page=workorder:view_closed&submit=submit&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index} selected {/if}>
							{$translate_workorder_page} {$smarty.section.page.index} {$translate_workorder_of} {$total_pages}
						</option>
					{/section}
					<option value="?page=workorder:view_closed&submit=submit&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
						{$translate_workorder_page} {$total_pages} {$translate_workorder_of} {$total_pages}
					</option>
				</select>
			</form>
			<div>
				{if $next != ''}
					<a href="?page=workorder:view_closed&submit=submit&page_no={$next}" class="btn btn-outline-secondary btn-sm">&#8250;</a>
				{/if}
				<a href="?page=workorder:view_closed&submit=submit&page_no={$total_pages}" class="btn btn-outline-secondary btn-sm">&#xbb;</a>
			</div>
		</div>
		<div class="mb-2">{$total_results} {$translate_workorder_records}</div>

		<!-- Work Orders Table -->
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead class="table-dark">
				<tr>
					<th>{$translate_workorder_id}</th>
					<th>{$translate_workorder_opened}</th>
					<th>{$translate_workorder_closed}</th>
					<th>{$translate_workorder_customer}</th>
					<th>{$translate_workorder_scope}</th>
					<th>{$translate_workorder_status}</th>
					<th>{$translate_workorder_tech}</th>
				</tr>
				</thead>
				<tbody>
				{foreach from=$work_order item=work_order}
					{if $work_order.WORK_ORDER_ID != ""}
						<tr class="table-row" style="cursor:pointer;" onclick="window.location='?page=workorder:view&wo_id={$work_order.WORK_ORDER_ID}&customer_id={$work_order.CUSTOMER_ID}&page_title={$translate_workorder_page_title} {$work_order.WORK_ORDER_ID}';">
							<td><a href="?page=workorder:view&wo_id={$work_order.WORK_ORDER_ID}&customer_id={$work_order.CUSTOMER_ID}&page_title={$translate_workorder_page_title} {$work_order.WORK_ORDER_ID}">{$work_order.WORK_ORDER_ID}</a></td>
							<td>{$work_order.WORK_ORDER_OPEN_DATE|date_format:"%m-%d-%Y"}</td>
							<td>{$work_order.WORK_ORDER_CLOSE_DATE|date_format:"%m/%d/%Y"}</td>
							<td>
								<i class="bi bi-info-circle-fill text-primary fs-5"
								   aria-hidden="true"
								   onMouseOver="ddrivetip('<b><center>Contact Info</b></center><hr><b>Phone: </b>{$work_order.CUSTOMER_PHONE}<br> <b>Work: </b>{$work_order.CUSTOMER_WORK_PHONE}<br><b>Mobile: </b>{$work_order.CUSTOMER_MOBILE_PHONE}<br><br>{$work_order.CUSTOMER_ADDRESS}<br>{$work_order.CUSTOMER_CITY}, {$work_order.CUSTOMER_STATE}<br>{$work_order.CUSTOMER_ZIP}')"
								   onMouseOut="hideddrivetip()"></i>
								<a class="link1" href="?page=customer:customer_details&customer_id={$work_order.CUSTOMER_ID}&page_title={$work_order.CUSTOMER_DISPLAY_NAME}">{$work_order.CUSTOMER_DISPLAY_NAME}</a>
							</td>
							<td>{$work_order.WORK_ORDER_SCOPE}</td>
							<td>{$work_order.CONFIG_WORK_ORDER_STATUS}</td>
							<td>
								<i class="bi bi-info-circle-fill text-primary fs-5"
								   aria-hidden="true"
								   onMouseOver="ddrivetip('<center><b>Contact</b></center><hr><b>Work: </b>{$work_order.EMPLOYEE_WORK_PHONE}<br><b>Mobile: </b>{$work_order.EMPLOYEE_MOBILE_PHONE}<br><b>Home: </b>{$work_order.EMPLOYEE_HOME_PHONE}')"
								   onMouseOut="hideddrivetip()"></i>
								<a class="link1" href="?page=employees:employee_details&employee_id={$work_order.EMPLOYEE_ID}&page_title={$work_order.EMPLOYEE_DISPLAY_NAME}">{$work_order.EMPLOYEE_DISPLAY_NAME}</a>
							</td>
						</tr>
					{else}
						<tr>
							<td colspan="7" class="text-center text-danger">{$translate_workorder_msg_6}</td>
						</tr>
					{/if}
				{/foreach}
				</tbody>
			</table>
		</div>
	</div>
</div>
