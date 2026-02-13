<div class="card mb-3">
	<div class="card-header d-flex justify-content-between align-items-center">
		<span>{$translate_workorder_cutomer_contact_title}</span>
		<a href="?page=customer:edit&customer_id={$single_workorder_array[i].CUSTOMER_ID}&page_title={$single_workorder_array[i].CUSTOMER_DISPLAY_NAME}"
		   class="btn btn-sm btn-outline-secondary"
		   data-bs-toggle="tooltip"
		   title="Edit Customer">
			<img src="images/icons/16x16/small_edit.gif" alt="Edit">
		</a>
	</div>

	{if $hide_customer_contact != 1}
		<div class="card-body p-3">
			<div class="table-responsive">
				<table class="table table-sm table-bordered mb-0">
					<tbody>
					<tr>
						<th>{$translate_workorder_contact}</th>
						<td>
							<a href="?page=customer:customer_details&customer_id={$single_workorder_array[i].CUSTOMER_ID}&page_title={$single_workorder_array[i].CUSTOMER_FIRST_NAME} {$single_workorder_array[i].CUSTOMER_LAST_NAME}">
								{$single_workorder_array[i].CUSTOMER_FIRST_NAME} {$single_workorder_array[i].CUSTOMER_LAST_NAME}
							</a>
						</td>
						<th>{$translate_workorder_email}</th>
						<td>{$single_workorder_array[i].CUSTOMER_EMAIL}</td>
					</tr>
					<tr>
						<th>{$translate_workorder_address}</th>
						<td>{$single_workorder_array[i].CUSTOMER_ADDRESS}</td>
						<th>{$translate_workorder_phone_1}</th>
						<td>{$single_workorder_array[i].CUSTOMER_PHONE}</td>
					</tr>
					<tr>
						<td>{$single_workorder_array[i].CUSTOMER_DISPLAY_NAME}</td>
						<td>{$single_workorder_array[i].CUSTOMER_CITY}, {$single_workorder_array[i].CUSTOMER_STATE} {$single_workorder_array[i].CUSTOMER_ZIP}</td>
						<th>{$translate_workorder_phone_2}</th>
						<td>{$single_workorder_array[i].CUSTOMER_WORK_PHONE}</td>
					</tr>
					<tr>
						<th>{$translate_workorder_type}</th>
						<td colspan="3">
							{if $single_workorder_array[i].CUSTOMER_TYPE == 1} {$translate_workorder_type_1} {/if}
							{if $single_workorder_array[i].CUSTOMER_TYPE == 2} {$translate_workorder_type_2} {/if}
							{if $single_workorder_array[i].CUSTOMER_TYPE == 3} {$translate_workorder_type_3} {/if}
							{if $single_workorder_array[i].CUSTOMER_TYPE == 4} {$translate_workorder_type_4} {/if}
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	{/if}
</div>
