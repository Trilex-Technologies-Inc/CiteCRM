<div class="card mb-3">
	<div class="card-header d-flex justify-content-between align-items-center">
		<span>{$translate_workorder_parts}</span>
		{if $single_workorder_array[i].WORK_ORDER_STATUS != 6}
			{if $part|default:0 == 0}
				<a class="btn btn-sm btn-primary"
				   href="?page=parts:main&wo_id={$single_workorder_array[i].WORK_ORDER_ID}&page_title=New%20Parts%20Order"
				   data-bs-toggle="tooltip"
				   title="New Part Order">
					<i class="bi bi-cart-plus me-1" aria-hidden="true"></i>
					New Part Order
				</a>
			{/if}
		{/if}
	</div>

	<div class="card-body p-0">
		{section name=p loop=$order}
			<div class="table-responsive">
				<table class="table table-bordered table-striped mb-3">
					<thead class="table-dark">
					<tr>
						<th>ID</th>
						<th>Invoice</th>
						<th>Created</th>
						<th>Updated</th>
						<th>Sub Total</th>
						<th>Shipping</th>
						<th>Total</th>
						<th>Tracking</th>
						<th>Status</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td>
							<a href="?page=parts:view&ORDER_ID={$order[p].ORDER_ID}&page_title=Order%20Details%20for%20{$order[p].ORDER_ID}">
								{$order[p].ORDER_ID}
							</a>
						</td>
						<td>{$order[p].INVOICE_ID}</td>
						<td>{$order[p].DATE_CREATE|date_format:"%m-%d-%Y"}</td>
						<td>{$order[p].DATE_LAST|date_format:"%m-%d-%Y"}</td>
						<td>${$order[p].SUB_TOTAL}</td>
						<td>${$order[p].SHIPPING}</td>
						<td>${$order[p].TOTAL}</td>
						<td>
							{if $order[p].TRACKING_NO == 0}
								<a href="">Get Tracking</a>
							{else}
								{$order[p].TRACKING_NO}
							{/if}
						</td>
						<td>
							{if $order[p].STATUS == '1'}Open{/if}
							{if $order[p].STATUS == '0'}Closed{/if}
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			{sectionelse}
			<div class="p-3">No Parts On Order</div>
		{/section}
	</div>
</div>
