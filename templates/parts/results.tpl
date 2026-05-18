<!-- invoice -->
<!-- Toolbar -->
<div class="container-fluid mb-3">
	{include file="core/tool_bar.tpl"}
</div>
<div class="container-fluid">
	<div class="card shadow-sm mb-3">
		<div class="card-header d-flex align-items-center justify-content-between">
			<div class="fw-semibold">{$translate_parts_order_complete}</div>
			<a class="btn btn-outline-secondary btn-sm"
			   href="?page=parts:print_results&wo_id={$invoice_details.WORKORDER}&escape=1"
			   target="new">
				{$translate_parts_print}
			</a>
		</div>
		<div class="card-body">
			{if $error_msg != ""}
				<div class="mb-3">
					{include file="core/error.tpl"}
				</div>
			{/if}

			<div class="row g-3">
				<div class="col-12 col-lg-7">
					<div class="p-3 border rounded bg-body-tertiary">
						<div class="h5 mb-2">Cite CRM</div>
						<div class="text-muted small">
							<div>323 SE Riverside AV</div>
							<div>Grants Pass, Oregon 97526</div>
							<div>1-866-471-1343</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-lg-5">
					<div class="border rounded">
						<div class="list-group list-group-flush">
							<div class="list-group-item d-flex justify-content-between">
								<span class="fw-semibold">{$translate_parts_crm_order_id}</span>
								<span>{$invoice_details.ORDER_ID}</span>
							</div>
							<div class="list-group-item d-flex justify-content-between">
								<span class="fw-semibold">{$translate_parts_date}</span>
								<span>{$invoice_details.DATE|date_format:"%m/%d/%y"}</span>
							</div>
							<div class="list-group-item d-flex justify-content-between">
								<span class="fw-semibold">{$translate_parts_total}</span>
								<span>${$invoice_details.TOTAL|string_format:"%.2f"}</span>
							</div>
							<div class="list-group-item d-flex justify-content-between">
								<span class="fw-semibold">{$translate_parts_total_items}</span>
								<span>{$invoice_details.TOTAL_ITEMS}</span>
							</div>
							<div class="list-group-item d-flex justify-content-between">
								<span class="fw-semibold">{$translate_parts_weight}</span>
								<span>{$invoice_details.WEIGHT} lbs</span>
							</div>
						</div>
					</div>
				</div>
			</div>

			<hr class="my-4">

			<div class="row g-3">
				<div class="col-12 col-lg-7">
					<div class="card">
						<div class="card-header fw-semibold">{$translate_parts_ship_to}</div>
						<div class="card-body">
							{foreach item=item from=$customer}
								<div class="h5 mb-2">{$item.COMPANY_NAME}</div>
								<div class="text-muted small">
									<div>{$item.COMPANY_ADDRESS}</div>
									<div>{$item.COMPANY_CITY}, {$item.COMPANY_STATE} {$item.COMPANY_ZIP}</div>
									<div>{$item.COMPNAY_PHONE}</div>
								</div>
							{/foreach}
						</div>
					</div>
				</div>
				<div class="col-12 col-lg-5">
					<div class="card">
						<div class="card-header fw-semibold">{$translate_parts_cap_invoice}</div>
						<div class="card-body">
							<div class="d-flex justify-content-between mb-2">
								<span class="fw-semibold">{$translate_parts_wo_id}</span>
								<span>{$invoice_details.WORKORDER}</span>
							</div>
							<div class="d-flex justify-content-between">
								<span class="fw-semibold">{$translate_parts_tech}</span>
								<span>{$display_login}</span>
							</div>
						</div>
					</div>
				</div>
			</div>

			<hr class="my-4">

			<div class="row g-3">
				<div class="col-12 col-lg-7">
					<div class="card">
						<div class="card-header fw-semibold">{$translate_parts_msg_10}</div>
						<div class="list-group list-group-flush">
							{section name=w loop=$details}
								<div class="list-group-item">
									<div class="d-flex justify-content-between align-items-start gap-2">
										<div class="flex-grow-1">
											<div class="fw-semibold">{$details[w].DESCRIPTION}</div>
											<div class="text-muted small">
												<span class="me-3"><span class="fw-semibold">{$translate_parts_sku}:</span> {$details[w].SKU}</span>
												<span class="me-3"><span class="fw-semibold">{$translate_parts_vendor}:</span> {$details[w].VENDOR}</span>
												<span><span class="fw-semibold">{$translate_parts_count}:</span> {$details[w].COUNT}</span>
											</div>
										</div>
										<div class="text-end">
											<div class="small text-muted">${$details[w].PRICE|string_format:"%.2f"} each</div>
											<div class="fw-semibold">${$details[w].SUB_TOTAL|string_format:"%.2f"}</div>
										</div>
									</div>
								</div>
							{/section}
						</div>
					</div>
				</div>
				<div class="col-12 col-lg-5">
					<div class="card">
						<div class="card-header fw-semibold">{$translate_parts_total}</div>
						<div class="list-group list-group-flush">
							<div class="list-group-item d-flex justify-content-between">
								<span class="fw-semibold">{$translate_parts_sub_total}</span>
								<span>${$invoice_details.CART_TOTAL|string_format:"%.2f"}</span>
							</div>
							<div class="list-group-item d-flex justify-content-between">
								<span class="fw-semibold">{$translate_parts_shipping}</span>
								<span>${$invoice_details.SHIPPING|string_format:"%.2f"}</span>
							</div>
							<div class="list-group-item d-flex justify-content-between">
								<span class="fw-semibold">{$translate_parts_tax}</span>
								<span>${$invoice_details.TAX|string_format:"%.2f"}</span>
							</div>
							<div class="list-group-item d-flex justify-content-between">
								<span class="fw-semibold">{$translate_parts_total}</span>
								<span class="fw-semibold">${$invoice_details.TOTAL|string_format:"%.2f"}</span>
							</div>
						</div>
					</div>

					<div class="alert alert-info mt-3 mb-0 small">
						{$translate_parts_msg_11}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
