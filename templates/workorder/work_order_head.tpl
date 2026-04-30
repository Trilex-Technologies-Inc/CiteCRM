<div class="table-responsive">
	<table class="table table-bordered table-striped align-middle">
		<thead class="table-dark">
		<tr>
			<th>{$translate_workorder_id}</th>
			<th>{$translate_workorder_opened}</th>
			<th>{$translate_workorder_state}</th>
			<th>{$translate_workorder_scope}</th>
			<th>{$translate_workorder_status}</th>
			<th>{$translate_workorder_tech}</th>
			<th>{$translate_workorder_last_change}</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>{$single_workorder_array[i].WORK_ORDER_ID}</td>
			<td>{$single_workorder_array[i].WORK_ORDER_OPEN_DATE|date_format:"%m-%d-%y"}</td>
			<td>
				{if $single_workorder_array[i].WORK_ORDER_STATUS == "10"}
					{$translate_workorder_open}
				{elseif $single_workorder_array[i].WORK_ORDER_STATUS == "9"}
					{$translate_workorder_pending}
				{elseif $single_workorder_array[i].WORK_ORDER_STATUS == "6"}
					{$translate_workorder_closed}
				{/if}
			</td>
			<td>{$single_workorder_array[i].WORK_ORDER_SCOPE}</td>
			<td>
				{if $single_workorder_array[i].WORK_ORDER_CURENT_STATUS == "1"}
					{$translate_workorder_created}
				{elseif $single_workorder_array[i].WORK_ORDER_CURENT_STATUS == "2"}
					{$translate_workorder_assigned}
				{elseif $single_workorder_array[i].WORK_ORDER_CURENT_STATUS == "3"}
					{$translate_workorder_waiting_for_parts}
				{elseif $single_workorder_array[i].WORK_ORDER_CURENT_STATUS == "6"}
					{$translate_workorder_closed}
				{elseif $single_workorder_array[i].WORK_ORDER_CURENT_STATUS == "7"}
					{$translate_workorder_waiting_for_payment}
				{elseif $single_workorder_array[i].WORK_ORDER_CURENT_STATUS == "8"}
					{$translate_workorder_payment_made}
				{elseif $single_workorder_array[i].WORK_ORDER_CURENT_STATUS == "9"}
					{$translate_workorder_pending}
				{/if}
			</td>
			<td>
				{if $single_workorder_array[i].EMPLOYEE_DISPLAY_NAME == ""}
					{$translate_workorder_not_assigned}
				{else}
					<i class="bi bi-info-circle-fill text-primary me-1 fs-5"
					   aria-label="Contact Info"
					   onMouseOver="ddrivetip('<center><b>Contact</b></center><hr><b>Work: </b>{$single_workorder_array[i].EMPLOYEE_WORK_PHONE}<br><b>Mobile: </b>{$single_workorder_array[i].EMPLOYEE_MOBILE_PHONE}<br><b>Home: </b>{$single_workorder_array[i].EMPLOYEE_HOME_PHONE}')"
					   onMouseOut="hideddrivetip()"></i>
					<a class="link-primary" href="?page=employees:employee_details&employee_id={$single_workorder_array[i].EMPLOYEE_ID}&page_title={$single_workorder_array[i].EMPLOYEE_DISPLAY_NAME}">
						{$single_workorder_array[i].EMPLOYEE_DISPLAY_NAME}
					</a>
				{/if}
			</td>
			<td>{$single_workorder_array[i].LAST_ACTIVE|date_format:"%m-%d-%Y"}</td>
		</tr>
		</tbody>
	</table>
</div>
