<div class="card mb-3">
	<div class="card-header">
		{$translate_workorder_schedule_title}
	</div>

	<div class="card-body">
		{section name=e loop=$work_order_sched}
			<div class="mb-3 p-3 border rounded">
				<p class="mb-1">
					<b>{$translate_workorder_start}:</b> {$work_order_sched[e].SCHEDUAL_START|date_format:"%m-%d-%Y %I:%M %p"}
					&nbsp; | &nbsp;
					<b>{$translate_workorder_end}:</b> {$work_order_sched[e].SCHEDUAL_END|date_format:"%m-%d-%Y %I:%M %p"}
				</p>
				<p class="mb-0">
					<b>{$translate_workorder_notes}:</b><br>
					{$work_order_sched[e].SCHEDUAL_NOTES|nl2br}
				</p>
			</div>
			{sectionelse}
			<div class="alert alert-warning mb-0">
				<strong>{$translate_workorder_warning}:</strong> {$translate_workorder_msg_5}
			</div>
		{/section}
	</div>
</div>
