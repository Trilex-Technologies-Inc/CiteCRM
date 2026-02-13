<div class="card mb-3">
	<div class="card-header">
		{$translate_workorder_history_title}
	</div>

	{if $hide_work_order_status != 1}
		<div class="card-body">
			{section name=c loop=$work_order_status}
				<div class="mb-3 p-3 border rounded">
					<p class="mb-1">
						<b>{$translate_workorder_enter_by}:</b>
						<a href="?page=employees:employee_details&employee_id={$work_order_status[c].WORK_ORDER_STATUS_ENTER_BY}&page_title={$translate_workorder_employee} {$work_order_status[c].EMPLOYEE_DISPLAY_NAME}">
							{$work_order_status[c].EMPLOYEE_DISPLAY_NAME}
						</a>
						&nbsp; | &nbsp;
						<b>{$translate_workorder_date}:</b> {$work_order_status[c].WORK_ORDER_STATUS_DATE|date_format:"%m-%d-%Y %I:%M %p"}
					</p>
					<p class="mb-0">
						{$work_order_status[c].WORK_ORDER_STATUS_NOTES|nl2br}
					</p>
				</div>
			{/section}
		</div>
	{/if}
</div>
