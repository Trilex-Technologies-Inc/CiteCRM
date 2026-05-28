<!-- Parts Tracking -->
<div class="container-fluid my-3">
	<div class="mb-3">
		{include file="core/tool_bar.tpl"}
	</div>

	<div class="card shadow-sm">
		<div class="card-header d-flex justify-content-between align-items-center">
			<div class="fw-bold">
				Tracking for Order {$order.ORDER_ID}
			</div>
			<div class="d-flex gap-2">
				<a class="btn btn-sm btn-outline-secondary"
				   href="?page=parts:view&ORDER_ID={$order.ORDER_ID}&page_title=Order%20Parts">
					Back to Order
				</a>
			</div>
		</div>

		<div class="card-body">
			{if $tracking_error != ""}
				<div class="alert alert-warning" role="alert">
					{$tracking_error|escape}
				</div>
			{/if}

			<div class="row g-3">
				<div class="col-md-6">
					<div class="border rounded p-3">
						<div class="text-muted">CRM Order ID</div>
						<div class="fw-semibold">{$order.INVOICE_ID}</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="border rounded p-3">
						<div class="text-muted">Tracking Number</div>
						{if $tracking_available}
							<div class="fw-semibold">{$tracking_no}</div>
						{else}
							<div class="text-muted">Tracking not yet available.</div>
						{/if}
					</div>
				</div>

				{if $tracking_available}
					<div class="col-md-6">
						<div class="border rounded p-3 h-100">
							<div class="text-muted">Carrier</div>
							<div class="fw-semibold">{$tracking_provider|escape}</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="border rounded p-3 h-100">
							<div class="text-muted">Current Status</div>
							{if $tracking_result.status != ""}
								<div class="fw-semibold">{$tracking_result.status|escape}</div>
							{else}
								<div class="text-muted">No status returned.</div>
							{/if}
						</div>
					</div>

					<div class="col-md-6">
						<div class="border rounded p-3 h-100">
							<div class="text-muted">Estimated Delivery</div>
							{if $tracking_result.estimated_delivery != ""}
								<div class="fw-semibold">{$tracking_result.estimated_delivery|escape}</div>
							{else}
								<div class="text-muted">Not provided.</div>
							{/if}
						</div>
					</div>

					<div class="col-md-6">
						<div class="border rounded p-3 h-100">
							<div class="text-muted">Latest Location</div>
							{if $tracking_result.latest_location != ""}
								<div class="fw-semibold">{$tracking_result.latest_location|escape}</div>
							{else}
								<div class="text-muted">Not provided.</div>
							{/if}
						</div>
					</div>

					<div class="col-12">
						<div class="border rounded p-3">
							<div class="text-muted">Latest Event</div>
							{if $tracking_result.latest_event != ""}
								<div class="fw-semibold">{$tracking_result.latest_event|escape}</div>
								{if $tracking_result.latest_event_time != ""}
									<div class="small text-muted">{$tracking_result.latest_event_time|escape}</div>
								{/if}
							{else}
								<div class="text-muted">No tracking events returned.</div>
							{/if}
						</div>
					</div>

					{if $tracking_result.events|@count > 0}
						<div class="col-12">
							<div class="table-responsive">
								<table class="table table-sm table-striped table-hover align-middle mb-0">
									<thead class="table-secondary">
									<tr>
										<th style="width: 190px;">Date / Time</th>
										<th>Status</th>
										<th style="width: 260px;">Location</th>
									</tr>
									</thead>
									<tbody>
									{section name=e loop=$tracking_result.events}
										<tr>
											<td>{$tracking_result.events[e].time|escape}</td>
											<td>{$tracking_result.events[e].description|escape}</td>
											<td>{$tracking_result.events[e].location|escape}</td>
										</tr>
									{/section}
									</tbody>
								</table>
							</div>
						</div>
					{/if}
				{/if}
			</div>
		</div>
	</div>
</div>
