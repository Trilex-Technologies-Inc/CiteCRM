<!-- Ship Parts Order -->
<div class="container-fluid my-3">
	<div class="mb-3">
		{include file="core/tool_bar.tpl"}
	</div>

	<div class="card shadow-sm">
		<div class="card-header d-flex justify-content-between align-items-center">
			<div class="fw-bold">Send Product for Order {$order.ORDER_ID}</div>
			<a class="btn btn-sm btn-outline-secondary"
			   href="?page=parts:view&ORDER_ID={$order.ORDER_ID}&page_title=Order%20Parts">
				Back to Order
			</a>
		</div>

		<div class="card-body">
			{if $error_msg != ""}
				<div class="alert alert-danger" role="alert">{$error_msg|escape}</div>
			{/if}
			{if $info_msg != ""}
				<div class="alert alert-info" role="alert">{$info_msg|escape}</div>
			{/if}

			<div class="row g-3 mb-3">
				<div class="col-md-4">
					<div class="border rounded p-3 h-100">
						<div class="text-muted">CRM Order ID</div>
						<div class="fw-semibold">{$order.INVOICE_ID}</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="border rounded p-3 h-100">
						<div class="text-muted">Work Order</div>
						<div class="fw-semibold">{$order.WO_ID}</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="border rounded p-3 h-100">
						<div class="text-muted">Carrier</div>
						<div class="fw-semibold">{$shipping_provider|escape}</div>
					</div>
				</div>
			</div>

			{if $auto_ship_available}
				<form method="post" action="?page=parts:ship" class="mb-4">
					<input type="hidden" name="order_id" value="{$order.ORDER_ID}">
					<div class="d-flex flex-wrap gap-2 align-items-center">
						<button class="btn btn-primary" name="create_ups_shipment" value="1" type="submit">
							Create UPS Shipment
						</button>
						<span class="text-muted small">
							{if $ups_sandbox}UPS sandbox mode{else}UPS production mode{/if}
						</span>
					</div>
				</form>
			{/if}

			<div class="border-top pt-3">
				<form method="post" action="?page=parts:ship">
					<input type="hidden" name="order_id" value="{$order.ORDER_ID}">
					<div class="mb-3">
						<label class="form-label" for="tracking_no">Tracking Number</label>
						<input class="form-control"
							   id="tracking_no"
							   name="tracking_no"
							   type="text"
							   value="{$tracking_no|escape}"
							   maxlength="80"
							   autocomplete="off">
					</div>
					<div class="d-flex gap-2">
						<button class="btn btn-outline-primary" name="submit" value="1" type="submit">
							Save Tracking Number
						</button>
						<a class="btn btn-outline-secondary"
						   href="?page=parts:view&ORDER_ID={$order.ORDER_ID}&page_title=Order%20Parts">
							Cancel
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
