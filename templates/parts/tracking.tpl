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
			</div>
		</div>
	</div>
</div>

