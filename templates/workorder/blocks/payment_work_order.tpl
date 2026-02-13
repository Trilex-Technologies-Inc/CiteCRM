<!-- Unpaid Work Orders Block -->
<div class="card mb-3 shadow-sm">
	<div class="card-header">
		<b>{$translate_workorder_unpaid_title}</b>
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
				{foreach from=$payment item=payment}
					{if $payment.WORK_ORDER_ID > 0}
						<tr style="cursor:pointer;" ondblclick="window.location='?page=workorder:view&wo_id={$payment.WORK_ORDER_ID}&customer_id={$payment.CUSTOMER_ID}&page_title={$translate_workorder_page_title} {$payment.WORK_ORDER_ID}';">
							<td>
								<a href="?page=workorder:view&wo_id={$payment.WORK_ORDER_ID}&customer_id={$payment.CUSTOMER_ID}&page_title={$translate_workorder_page_title} {$payment.WORK_ORDER_ID}">
									{$payment.WORK_ORDER_ID}
								</a>
							</td>
							<td>{$payment.WORK_ORDER_OPEN_DATE|date_format:"%m-%d-%Y"}</td>
							<td nowrap>
								<img src="images/icons/16x16/view+.gif" border="0"
									 onMouseOver="ddrivetip('<b><center>Contact Info</b></center><hr><b>Phone: </b>{$payment.CUSTOMER_PHONE}<br><b>Work: </b>{$payment.CUSTOMER_WORK_PHONE}<br><b>Mobile: </b>{$payment.CUSTOMER_MOBILE_PHONE}<br><br>{$payment.CUSTOMER_ADDRESS}<br>{$payment.CUSTOMER_CITY}, {$payment.CUSTOMER_STATE}<br>{$payment.CUSTOMER_ZIP}')"
									 onMouseOut="hideddrivetip()">
								<a class="link1" href="?page=customer:customer_details&customer_id={$payment.CUSTOMER_ID}&page_title={$payment.CUSTOMER_DISPLAY_NAME}">
									{$payment.CUSTOMER_DISPLAY_NAME}
								</a>
							</td>
							<td>{$payment.WORK_ORDER_SCOPE}</td>
							<td>{$payment.CONFIG_WORK_ORDER_STATUS}</td>
							<td nowrap>
								{if $payment.EMPLOYEE_DISPLAY_NAME == ""}
									{$translate_workorder_not_assigned}
								{else}
									<img src="images/icons/16x16/view+.gif" border="0"
										 onMouseOver="ddrivetip('<center><b>Contact</b></center><hr><b>Work: </b>{$payment.EMPLOYEE_WORK_PHONE}<br><b>Mobile: </b>{$payment.EMPLOYEE_MOBILE_PHONE}<br><b>Home: </b>{$payment.EMPLOYEE_HOME_PHONE}')"
										 onMouseOut="hideddrivetip()">
									<a class="link1" href="?page=employees:employee_details&employee_id={$payment.EMPLOYEE_ID}&page_title={$payment.EMPLOYEE_DISPLAY_NAME}">
										{$payment.EMPLOYEE_DISPLAY_NAME}
									</a>
								{/if}
							</td>
							<td class="text-center" nowrap>
								<a href="?page=workorder:print&wo_id={$payment.WORK_ORDER_ID}&customer_id={$payment.CUSTOMER_ID}&page_title={$translate_workorder_print_title} {$payment.WORK_ORDER_ID}&escape=1" target="_blank">
									<img src="images/icons/16x16/fileprint.gif" border="0" onMouseOver="ddrivetip('Print The Work Order')" onMouseOut="hideddrivetip()">
								</a>
								<a href="?page=workorder:view&wo_id={$payment.WORK_ORDER_ID}&customer_id={$payment.CUSTOMER_ID}&page_title={$translate_workorder_page_title} {$payment.WORK_ORDER_ID}">
									<img src="images/icons/16x16/viewmag.gif" border="0" onMouseOver="ddrivetip('View The Work Order')" onMouseOut="hideddrivetip()">
								</a>
							</td>
						</tr>
					{else}
						<tr>
							<td colspan="7" class="text-center text-danger">{$translate_workorder_msg_3}</td>
						</tr>
					{/if}
				{/foreach}
				</tbody>
			</table>
		</div>
	</div>
</div>
