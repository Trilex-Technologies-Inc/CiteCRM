<div class="card mb-3">
	<div class="card-header">
		{$translate_workorder_parts}
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
