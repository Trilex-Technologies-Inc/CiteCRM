<!-- Awaiting Parts Work Orders Block -->
<div class="card mb-3 shadow-sm">
	<div class="card-header">
		<b>{$translate_workorder_waiting_parts_title}</b>
	</div>
	<div class="card-body p-0">
		<div class="table-responsive">
			<table class="table table-hover table-striped mb-0">
				<thead class="table-secondary">
				<tr>
					<th>{$translate_workorder_id}</th>
					<th>{$translate_workorder_opened}</th>
					<th>{$translate_workorder_customer}</th>
					<th>{$translate_workorder_scope}</th>
					<th>{$translate_workorder_status}</th>
					<th>{$translate_workorder_tech}</th>
					<th>{$translate_workorder_action}</th>
				</tr>
				</thead>
				<tbody>
				{foreach from=$awaiting item=awaiting}
					{if $awaiting.WORK_ORDER_ID > 0}
						<tr style="cursor:pointer;" ondblclick="window.location='?page=workorder:view&wo_id={$awaiting.WORK_ORDER_ID}&customer_id={$awaiting.CUSTOMER_ID}&page_title={$translate_workorder_page_title} {$awaiting.WORK_ORDER_ID}';">
							<td>
								<a href="?page=workorder:view&wo_id={$awaiting.WORK_ORDER_ID}&customer_id={$awaiting.CUSTOMER_ID}&page_title={$translate_workorder_page_title} {$awaiting.WORK_ORDER_ID}">
									{$awaiting.WORK_ORDER_ID}
								</a>
							</td>
							<td>{$awaiting.WORK_ORDER_OPEN_DATE|date_format:"%m-%d-%Y"}</td>
							<td nowrap>
								<i class="bi bi-info-circle-fill text-primary fs-5"
								   aria-hidden="true"
								   onMouseOver="ddrivetip('<b><center>Contact Info</b></center><hr><b>Phone: </b>{$awaiting.CUSTOMER_PHONE}<br><b>Work: </b>{$awaiting.CUSTOMER_WORK_PHONE}<br><b>Mobile: </b>{$awaiting.CUSTOMER_MOBILE_PHONE}<br><br>{$awaiting.CUSTOMER_ADDRESS}<br>{$awaiting.CUSTOMER_CITY}, {$awaiting.CUSTOMER_STATE}<br>{$awaiting.CUSTOMER_ZIP}')"
								   onMouseOut="hideddrivetip()"></i>
								<a class="link1" href="?page=customer:customer_details&customer_id={$awaiting.CUSTOMER_ID}&page_title={$awaiting.CUSTOMER_DISPLAY_NAME}">
									{$awaiting.CUSTOMER_DISPLAY_NAME}
								</a>
							</td>
							<td>{$awaiting.WORK_ORDER_SCOPE}</td>
							<td>{$awaiting.CONFIG_WORK_ORDER_STATUS}</td>
							<td nowrap>
								{if $awaiting.EMPLOYEE_DISPLAY_NAME == ""}
									{$translate_workorder_not_assigned}
								{else}
									<i class="bi bi-info-circle-fill text-primary fs-5"
									   aria-hidden="true"
									   onMouseOver="ddrivetip('<center><b>Contact</b></center><hr><b>Work: </b>{$awaiting.EMPLOYEE_WORK_PHONE}<br><b>Mobile: </b>{$awaiting.EMPLOYEE_MOBILE_PHONE}<br><b>Home: </b>{$awaiting.EMPLOYEE_HOME_PHONE}')"
									   onMouseOut="hideddrivetip()"></i>
									<a class="link1" href="?page=employees:employee_details&employee_id={$awaiting.EMPLOYEE_ID}&page_title={$awaiting.EMPLOYEE_DISPLAY_NAME}">
										{$awaiting.EMPLOYEE_DISPLAY_NAME}
									</a>
								{/if}
							</td>
							<td class="text-center" nowrap>
								<a href="?page=workorder:print&wo_id={$awaiting.WORK_ORDER_ID}&customer_id={$awaiting.CUSTOMER_ID}&page_title={$translate_workorder_print_title} {$awaiting.WORK_ORDER_ID}&escape=1" target="new">
									<i class="bi bi-printer-fill text-secondary  fs-5"
									   aria-hidden="true"
									   onMouseOver="ddrivetip('Print The Work Order')"
									   onMouseOut="hideddrivetip()"></i>
								</a>
								<a href="?page=workorder:view&wo_id={$awaiting.WORK_ORDER_ID}&customer_id={$awaiting.CUSTOMER_ID}&page_title={$translate_workorder_page_title} {$awaiting.WORK_ORDER_ID}">
									<i class="bi bi-eye-fill text-secondary fs-5"
									   aria-hidden="true"
									   onMouseOver="ddrivetip('View The Work Order')"
									   onMouseOut="hideddrivetip()"></i>
								</a>
							</td>
						</tr>
					{else}
						<tr>
							<td colspan="7" class="text-center text-danger">{$translate_workorder_msg_4}</td>
						</tr>
					{/if}
				{/foreach}
				</tbody>
			</table>
		</div>
	</div>
</div>
