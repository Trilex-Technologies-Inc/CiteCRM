<div class="container mt-4">

	{section name=a loop=$arr}
		<div class="card mb-3 shadow-sm">
			<div class="card-header d-flex justify-content-between align-items-center">
				<div>
					<strong>{$arr[a].SCHEDUAL_START|date_format:"%m-%d-%y %r"}</strong>
					to
					<strong>{$arr[a].SCHEDUAL_END|date_format:"%m-%d-%y %r"}</strong>
				</div>
				<div>
					<a href="?page=schedual:edit&sch_id={$arr[a].SCHEDUAL_ID}&y={$y}&m={$m}&d={$d}" class="btn btn-sm btn-outline-primary">
						{$translate_schedule_edit}
					</a>
					<a href="?page=schedual:delete&sch_id={$arr[a].SCHEDUAL_ID}&y={$y}&m={$m}&d={$d}" class="btn btn-sm btn-outline-danger">
						{$translate_schedule_delete}
					</a>
				</div>
			</div>

			<div class="card-body">
				<p>
					<strong>{$translate_schedul_start}:</strong> {$arr[a].SCHEDUAL_START|date_format:"%m-%d-%y %r"}<br>
					<strong>{$translate_schedule_end}:</strong> {$arr[a].SCHEDUAL_END|date_format:"%m-%d-%y %r"}
				</p>

				<p>
					{$arr[a].SCHEDUAL_NOTES}
				</p>

				<p>
					<strong>{$translate_schedule_tech}:</strong> {$arr[a].EMPLOYEE_DISPLAY_NAME}
				</p>
			</div>
		</div>
	{/section}

</div>
