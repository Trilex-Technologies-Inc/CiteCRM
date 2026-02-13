<div class="card mb-3">
	<div class="card-header d-flex justify-content-between align-items-center">
		<span>{$translate_workorder_notes}</span>
		{if $single_workorder_array[i].WORK_ORDER_STATUS != 6}
			<a href="?page=workorder:new_note&wo_id={$single_workorder_array[i].WORK_ORDER_ID}&page_title={$translate_workorder_new_note_title}"
			   class="btn btn-sm btn-outline-secondary"
			   data-bs-toggle="tooltip"
			   title="New Note">
				<img src="images/icons/16x16/small_new_work_order.gif" alt="New Note">
			</a>
		{/if}
	</div>

	{if $hide_work_order_notes != 1}
		<div class="card-body">
			{section name=b loop=$work_order_notes}
				<div class="mb-3 p-2 border rounded">
					<p class="mb-1">
						<b>{$translate_workorder_enter_by}:</b> {$work_order_notes[b].EMPLOYEE_DISPLAY_NAME}
						<b>{$translate_workorder_date}:</b> {$work_order_notes[b].WORK_ORDER_NOTES_DATE|date_format:"%m-%d-%Y"}
					</p>
					<p class="mb-0">
						{$work_order_notes[b].WORK_ORDER_NOTES_DESCRIPTION|nl2br}
					</p>
				</div>
			{/section}
		</div>
	{/if}
</div>
