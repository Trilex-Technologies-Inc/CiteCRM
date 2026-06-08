<!-- Parts Order View -->
<div class="container-fluid my-3">
	<div class="mb-3">
		{include file="core/tool_bar.tpl"}
	</div>

	{section name=q loop=$order}
		<div class="card shadow-sm mb-3">
			<div class="card-header d-flex justify-content-between align-items-center">
				<div class="fw-bold">
					{$translate_part_order_num} {$order[q].ORDER_ID}
				</div>
				<div class="d-flex gap-2 align-items-center">
					<a class="btn btn-sm btn-primary"
					   href="?page=parts:ship&order_id={$order[q].ORDER_ID}&page_title=Send%20Product">
						Send Product
					</a>
					<a class="btn btn-sm btn-outline-secondary"
					   href="?page=parts:print_results&wo_id={$order[q].WO_ID}&escape=1"
					   target="new">
						{$translate_parts_print}
					</a>
				</div>
			</div>

			<div class="card-body">
				<div class="row g-3">
					<div class="col-lg-7">
						<div class="border rounded p-3 h-100">
							<div class="fs-5 fw-bold mb-2">{$company_name}</div>
							<div>{$company_address}</div>
							<div>{$company_city}, {$company_state} {$company_zip}</div>
							{if $company_phone != ''}<div>{$company_phone}</div>{/if}
							{if $company_toll_free != ''}<div>{$company_toll_free}</div>{/if}
							{if $company_email != ''}<div>{$company_email}</div>{/if}
							{if $company_tax_id != ''}<div>Tax ID: {$company_tax_id}</div>{/if}
						</div>
					</div>

					<div class="col-lg-5">
						<div class="border rounded p-3 h-100">
							<div class="row mb-1">
								<div class="col-6 text-muted">{$translate_parts_crm_order_id}</div>
								<div class="col-6 text-end fw-semibold">{$order[q].INVOICE_ID}</div>
							</div>
							<div class="row mb-1">
								<div class="col-6 text-muted">{$translate_parts_date}</div>
								<div class="col-6 text-end fw-semibold">{$order[q].DATE_CREATE|date_format:"%m-%d-%y"}</div>
							</div>
							<div class="row mb-1">
								<div class="col-6 text-muted">{$translate_parts_total_items}</div>
								<div class="col-6 text-end fw-semibold">{$order[q].ITEMS}</div>
							</div>
							<div class="row mb-1">
								<div class="col-6 text-muted">{$translate_parts_weight}</div>
								<div class="col-6 text-end fw-semibold">{$order[q].WEIGHT} lbs</div>
							</div>
							<div class="row mb-1">
								<div class="col-6 text-muted">{$translate_parts_tracking}</div>
								<div class="col-6 text-end">
									{if $order[q].TRACKING_NO == '0'}
										<a href="?page=parts:ship&order_id={$order[q].ORDER_ID}&page_title=Send%20Product">Send Product</a>
									{else}
										<a class="fw-semibold" href="?page=parts:tracking&invoice_id={$order[q].INVOICE_ID}&order_id={$order[q].ORDER_ID}">{$order[q].TRACKING_NO}</a>
									{/if}
								</div>
							</div>
							<hr class="my-2">
							<div class="row">
								<div class="col-6 text-muted">{$translate_parts_total}</div>
								<div class="col-6 text-end fw-bold">${$order[q].TOTAL|string_format:"%.2f"}</div>
							</div>
						</div>
					</div>

					<div class="col-12">
						<div class="border rounded p-3">
							<div class="row g-3 align-items-start">
								<div class="col-lg-8">
									<div class="text-muted fw-semibold mb-1">{$translate_parts_ship_to}</div>
									<div class="fs-5 fw-bold">{$company_name}</div>
									<div>{$company_address}</div>
									<div>{$company_city}, {$company_state} {$company_zip}</div>
									<div>{$company_phone}</div>
									{if $company_tax_id != ''}<div>Tax ID: {$company_tax_id}</div>{/if}
								</div>
								<div class="col-lg-4">
									<div class="text-muted fw-semibold mb-1">{$translate_parts_wo_id}</div>
									<div class="fw-semibold mb-2">{$order[q].WO_ID}</div>
									<div class="text-muted fw-semibold mb-1">{$translate_parts_tech}</div>
									<div class="fw-semibold">{$display_login}</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-12">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<div class="fw-bold">{$translate_parts_cap_invoice}</div>
						</div>

						<div class="table-responsive">
							<table class="table table-sm table-striped table-hover align-middle mb-0">
								<thead class="table-secondary">
								<tr>
									<th style="width: 90px;">{$translate_parts_sku}</th>
									<th style="width: 80px;">{$translate_parts_count}</th>
									<th>{$translate_parts_description}</th>
									<th style="width: 140px;">{$translate_parts_vendor}</th>
									<th class="text-end" style="width: 110px;">{$translate_parts_amount}</th>
									<th class="text-end" style="width: 130px;">{$translate_parts_sub_total}</th>
								</tr>
								</thead>
								<tbody>
								{section name=w loop=$order_details}
									<tr>
										<td class="fw-semibold">{$order_details[w].SKU}</td>
										<td>{$order_details[w].COUNT}</td>
										<td>{$order_details[w].INVOICE_PARTS_DESCRIPTION}</td>
										<td>{$order_details[w].INVOICE_PARTS_MANUF}</td>
										<td class="text-end">${$order_details[w].PRICE|string_format:"%.2f"}</td>
										<td class="text-end">${$order_details[w].SUB_TOTAL|string_format:"%.2f"}</td>
									</tr>
								{/section}
								</tbody>
							</table>
						</div>
					</div>

					<div class="col-12">
						<div class="row justify-content-end">
							<div class="col-12 col-md-6 col-lg-4">
								<div class="border rounded p-3">
									<div class="row mb-1">
										<div class="col-6 fw-semibold">{$translate_parts_sub_total}</div>
										<div class="col-6 text-end fw-semibold">$<span>{$order[q].SUB_TOTAL|string_format:"%.2f"}</span></div>
									</div>
									<div class="row mb-1">
										<div class="col-6 fw-semibold">{$translate_parts_shipping}</div>
										<div class="col-6 text-end fw-semibold">$<span>{$order[q].SHIPPING|string_format:"%.2f"}</span></div>
									</div>
									<div class="row mb-1">
										<div class="col-6 fw-semibold">{$translate_parts_tax}</div>
										<div class="col-6 text-end fw-semibold">${$invoice_details.TAX|string_format:"%.2f"}</div>
									</div>
									<hr class="my-2">
									<div class="row">
										<div class="col-6 fw-bold">{$translate_parts_total}</div>
										<div class="col-6 text-end fw-bold">$<span>{$order[q].TOTAL|string_format:"%.2f"}</span></div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-12">
						<div class="alert alert-secondary mb-0">
							{$translate_parts_msg_11}
						</div>
					</div>
				</div>
			</div>
		</div>
	{/section}
</div>
