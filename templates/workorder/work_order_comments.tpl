<div class="card mb-3">
	<div class="card-header d-flex justify-content-between align-items-center">
		<span>{$translate_workorder_comments_title}</span>
		{if $single_workorder_array[i].WORK_ORDER_STATUS != 6}
			<a href="?page=workorder:edit_comment&wo_id={$single_workorder_array[i].WORK_ORDER_ID}&page_title={$translate_workorder_edit_comments}"
			   class="btn btn-sm btn-outline-secondary"
			   data-bs-toggle="tooltip"
			   title="Edit Comment">
				<i class="bi bi-pencil-square" aria-hidden="true"></i>
			</a>
		{/if}
	</div>

	{if $hide_work_order_comment != 1}
		<div class="card-body">
			<p class="mb-0">
				{$single_workorder_array[i].WORK_ORDER_COMMENT|nl2br}
			</p>
		</div>
	{/if}
</div>
