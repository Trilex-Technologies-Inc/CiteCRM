<!-- parts -->
{literal}
<script>
function setOptions(chosen) {
    const selbox = document.form1.CAT2;
    selbox.options.length = 0;
    if (chosen === " ") {
        selbox.options[selbox.options.length] = new Option('Please select one of the options above first', ' ');
    }
    {/literal}
    {section name=q loop=$CAT}
    {literal}
    if (chosen === "{/literal}{$CAT[q].ID}{literal}") {
        {/literal}
        {section name=w loop=$SUB_CAT}
        {if $SUB_CAT[w].CAT == $CAT[q].ID}
        {literal}
        selbox.options[selbox.options.length] = new Option('{/literal}{$SUB_CAT[w].DESCRIPTION}{literal}', '{/literal}{$SUB_CAT[w].SUB_CATEGORY}{literal}');
        {/literal}
        {/if}
        {/section}
        {literal}
    }
    {/literal}
    {/section}
    {literal}
}
</script>
{/literal}
<div class="toolbar container-fluid mb-3">
	<div class="row">
		<div class="col-12">
			{include file="core/tool_bar.tpl"}
		</div>
	</div>
</div>
{if $error_msg != ""}
	{include file="core/error.tpl"}
{/if}
<div class="container-fluid py-3">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header bg-primary text-white">
					<h4 class="mb-0">{$translate_parts_order}</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<!-- Left Side -->
						<div class="col-md-9">
							<div class="mb-4">
								<p >{$translate_parts_msg_1}</p>
								<p>
									{$translate_parts_msg_2}<br>
									{$translate_parts_msg_3}<br>
									{$translate_parts_msg_4}
								</p>
							</div>
							{if $crm_msg != ''}
								<div class="alert alert-danger mb-3" role="alert">
									<span>{$crm_msg}</span>
								</div>
							{/if}
							{if $parts != ''}
								<div class="table-responsive mb-4">
									<table class="table table-striped table-hover">
										<thead class="table-dark">
											<tr>
												<th class="text-center">{$translate_parts_amount}</th>
												<th class="text-center">{$translate_parts_sku}</th>
												<th class="text-center">{$translate_parts_item_id}</th>
												<th>{$translate_parts_description}</th>
												<th>{$translate_parts_vendor}</th>
												<th class="text-center">{$translate_parts_weight}</th>
												<th class="text-center">{$translate_parts_price}</th>
												<th class="text-center">{$translate_parts_add}</th>
											</tr>
										</thead>
										<tbody>
											{section name=p loop=$parts}
												<tr>
													<td class="text-center align-middle">
														<form method="post" action="?page=parts:main" class="d-inline">
															<input type="text" class="form-control form-control-sm d-inline-block text-center" name="AMOUNT" size="2" maxlength="4" style="width: 60px;">
															<input type="hidden" name="SKU" value="{$parts[p].SKU}">
															<input type="hidden" name="DESCRIPTION" value="{$parts[p].DESCRIPTION}">
															<input type="hidden" name="VENDOR" value="{$parts[p].VENDOR}">
															<input type="hidden" name="ITEMID" value="{$parts[p].ITEMID}">
															<input type="hidden" name="Weight" value="{$parts[p].Weight}">
															<input type="hidden" name="PRICE" value="{$parts[p].PRICE|string_format:"%.2f"}">
															<input type="hidden" name="CAT2" value="{$CAT2}">
															<input type="hidden" name="add_part" value="1">
															<input type="hidden" name="wo_id" value="{$wo_id}">
															<input type="hidden" name="from_zip" value="{$from_zip}">
													</td>
													<td class="text-center align-middle">{$parts[p].SKU}</td>
													<td class="text-center align-middle">{$parts[p].ITEMID}</td>
													<td class="align-middle">{$parts[p].DESCRIPTION}</td>
													<td class="align-middle">{$parts[p].VENDOR}</td>
													<td class="text-center align-middle">{$parts[p].Weight} {$parts[p].UNIT}</td>
													<td class="text-center align-middle">${$parts[p].PRICE}</td>
													<td class="text-center align-middle">
														<input type="submit" class="btn btn-sm btn-primary" name="submit" value="Add">
														</form>
													</td>
												</tr>
											{/section}
										</tbody>
									</table>
								</div>
							{/if}
							{if $inventory_products|@count > 0}
								<div class="table-responsive mb-4">
									<h5 class="mb-3">Products</h5>
									<table class="table table-striped table-hover">
										<thead class="table-dark">
											<tr>
												<th style="width: 90px;">ID</th>
												<th style="min-width: 220px;">Name</th>
												<th style="min-width: 160px;">Manufacturer</th>
												<th style="width: 140px;">SKU</th>
												<th style="width: 120px;">Price</th>
												<th>Description</th>
												<th style="width: 160px;">Add</th>
											</tr>
										</thead>
										<tbody>
											{section name=ip loop=$inventory_products}
												<tr>
													<td class="text-muted">{$inventory_products[ip].PRODUCT_ID}</td>
													<td>{$inventory_products[ip].PRODUCT_NAME|escape}</td>
													<td>{if $inventory_products[ip].MANUFACTURER_NAME != ''}{$inventory_products[ip].MANUFACTURER_NAME|escape}{else}-{/if}</td>
													<td>{$inventory_products[ip].PRODUCT_SKU|escape}</td>
													<td class="text-end">${$inventory_products[ip].PRODUCT_PRICE|string_format:"%.2f"}</td>
													<td>{$inventory_products[ip].PRODUCT_DESCRIPTION|escape}</td>
													<td class="text-center">
														<form method="post" action="?page=parts:main" class="d-inline-flex gap-2 align-items-center">
															<input type="text" class="form-control form-control-sm text-center" name="AMOUNT" value="1" style="width: 70px;" maxlength="4">
															<input type="hidden" name="SKU" value="{$inventory_products[ip].PRODUCT_SKU|escape}">
															<input type="hidden" name="DESCRIPTION" value="{$inventory_products[ip].PRODUCT_NAME|escape}">
															<input type="hidden" name="VENDOR" value="{$inventory_products[ip].MANUFACTURER_NAME|escape}">
															<input type="hidden" name="ITEMID" value="LOCAL-{$inventory_products[ip].PRODUCT_ID}">
															<input type="hidden" name="Weight" value="0">
															<input type="hidden" name="PRICE" value="{$inventory_products[ip].PRODUCT_PRICE|string_format:"%.2f"}">
															<input type="hidden" name="CAT2" value="{$CAT2}">
															<input type="hidden" name="add_part" value="1">
															<input type="hidden" name="wo_id" value="{$wo_id}">
															<input type="hidden" name="from_zip" value="{$from_zip}">
															<input type="submit" class="btn btn-sm btn-primary" name="submit" value="Add">
														</form>
													</td>
												</tr>
											{/section}
										</tbody>
									</table>
								</div>
							{/if}
							{if $cart_contents != ''}
								<div class="mt-4">
									<h4 class="mb-3">Check Out</h4>
									<div class="table-responsive">
										<table class="table table-striped table-hover">
											<thead class="table-dark">
												<tr>
													<th class="text-center">{$translate_parts_amount}</th>
													<th class="text-center">{$translate_parts_sku}</th>
													<th class="text-center">{$translate_parts_item_id}</th>
													<th>{$translate_parts_description}</th>
													<th>{$translate_parts_vendor}</th>
													<th class="text-center">{$translate_parts_weight}</th>
													<th class="text-end">{$translate_parts_each}</th>
													<th class="text-end">{$translate_parts_total}</th>
												</tr>
											</thead>
											<tbody>
												{section name=a loop=$cart_contents}
													<tr>
														<td class="text-center align-middle">{$cart_contents[a].AMOUNT}</td>
														<td class="text-center align-middle">{$cart_contents[a].SKU}</td>
														<td class="text-center align-middle">{$cart_contents[a].ITEMID}</td>
														<td class="align-middle">{$cart_contents[a].DESCRIPTION}</td>
														<td class="align-middle">{$cart_contents[a].VENDOR}</td>
														<td class="text-center align-middle">{$cart_contents[a].Weight} {$cart_contents[a].UNIT}</td>
														<td class="text-end align-middle">${$cart_contents[a].PRICE|string_format:"%.2f"}</td>
														<td class="text-end align-middle">${$cart_contents[a].SUB_TOTAL|string_format:"%.2f"}</td>
														<input type="hidden" name="SKU" value="{$cart_contents[a].SKU}">
														<input type="hidden" name="PRICE" value="{$cart_contents[a].PRICE|string_format:"%.2f"}">
													</tr>
												{/section}
												<tr class="table-light">
													<td colspan="6" class="align-middle">
														{$translate_parts_msg_5} ${$total_charges|string_format:"%.2f"}. {$translate_parts_msg_6} {$service_code} {$translate_parts_msg_7} {$location}. {$translate_parts_msg_8}
													</td>
													<td class="text-end fw-bold">{$translate_parts_sub_total}</td>
													<td class="text-end fw-bold">${$sub_total}</td>
												</tr>
												<tr class="table-light">
													<td colspan="6" class="align-middle">{$translate_parts_msg_9}</td>
													<td class="text-end fw-bold">{$translate_parts_shipping}</td>
													<td class="text-end fw-bold">${$shipping_charges|string_format:"%.2f"}</td>
												</tr>
												<tr class="table-light">
													<td colspan="6" class="align-middle">
														{if $ResponseStatusCode == 0}
															<span class="text-danger">{$ErrorDescription}</span>
														{else}
															<form method="post" action="?page=parts:checkout" class="d-inline">
																<input type="hidden" name="wo_id" value="{$wo_id}">
																<input type="submit" class="btn btn-success btn-sm" name="submit" value="check out">
															</form>
														{/if}
													</td>
													<td class="text-end fw-bold">{$translate_parts_total_charges}</td>
													<td class="text-end align-middle fw-bold">${$total_charges|string_format:"%.2f"}</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							{/if}
						</div>
						<!-- Right Side -->
						<div class="col-md-3">
							<div class="card mb-3">
								<div class="card-header">
									<strong>{$translate_parts_select}</strong>
								</div>
								<div class="card-body">
									<form action="?page=parts:main" method="post" id="form1" name="form1">
										<div class="mb-3">
											<label for="CAT" class="form-label">Category</label>
											<select name="CAT" id="CAT" class="form-select" onchange="setOptions(document.form1.CAT.options[document.form1.CAT.selectedIndex].value);">
												<option value="" selected="selected">{$translate_parts_select_cat}</option>
												{section name=q loop=$CAT}
													<option value="{$CAT[q].ID}">{$CAT[q].DESCRIPTION}</option>
												{/section}
											</select>
										</div>
										<div class="mb-3">
											<label for="CAT2" class="form-label">Subcategory</label>
											<select name="CAT2" id="CAT2" class="form-select">
												<option value=" " selected="selected">&nbsp;</option>
											</select>
										</div>
										<input type="hidden" name="wo_id" value="{$wo_id}">
										<button type="submit" class="btn btn-primary w-100" name="submit" value="{$translate_parts_search}">{$translate_parts_search}</button>
									</form>
								</div>
							</div>
							<div class="card mb-3">
								<div class="card-header">
									<strong>{$translate_parts_cart}</strong>
								</div>
								<div class="card-body">
									<p class="mb-2">{$translate_parts_total_items} {$cart_count}</p>
									{if $cart|@count > 0}
										<div class="table-responsive mb-3">
											<table class="table table-sm table-striped">
												<thead>
													<tr>
														<th>{$translate_parts_remove}</th>
														<th>{$translate_parts_sku}</th>
														<th class="text-center">{$translate_parts_amount}</th>
														<th class="text-end">{$translate_parts_sub_total}</th>
													</tr>
												</thead>
												<tbody>
													{section name=c loop=$cart}
														<tr>
															<td><input type="checkbox" name="remove[{$smarty.section.c.index}]" value="{$cart[c].SKU}" class="form-check-input"></td>
															<td>{$cart[c].SKU}</td>
															<td class="text-center">{$cart[c].AMOUNT}</td>
															<td class="text-end">${$cart[c].PRICE|string_format:"%.2f"}</td>
														</tr>
													{/section}
												</tbody>
											</table>
										</div>
										<form method="post" action="?page=parts:main">
											<input type="hidden" name="wo_id" value="{$wo_id}">
											<input type="hidden" name="CAT2" value="{$CAT2}">
											<input type="hidden" name="update_cart" value="1">
											{section name=c loop=$cart}
												<input type="hidden" name="remove[{$smarty.section.c.index}]" value="">
											{/section}
											<button type="submit" class="btn btn-secondary btn-sm w-100 mb-2" name="submit" value="{$translate_parts_update}">{$translate_parts_update}</button>
										</form>
										<hr>
										<div class="d-flex justify-content-between">
											<strong>{$translate_parts_cart_total}</strong>
											<strong>${$cart_total|string_format:"%.2f"}</strong>
										</div>
									{else}
										<p class="text-muted">Your cart is empty.</p>
									{/if}
								</div>
							</div>
							<div class="card mb-3">
								<div class="card-header">
									<strong>Actions</strong>
								</div>
								<div class="card-body">
									<div class="d-grid gap-2">
										<form method="POST" action="?page=parts:main" class="d-inline">
											<input type="hidden" name="wo_id" value="{$wo_id}">
											<input type="hidden" name="check_out" value="1">
											<button type="submit" class="btn btn-success w-100" name="submit" value="{$translate_parts_checkout}">{$translate_parts_checkout}</button>
										</form>
										<form method="POST" action="?page=parts:main" class="d-inline">
											<input type="hidden" name="wo_id" value="{$wo_id}">
											<input type="hidden" name="check_out" value="1">
											<button type="submit" class="btn btn-outline-primary w-100" name="submit" value="{$translate_parts_view}">{$translate_parts_view}</button>
										</form>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<strong>Work Order Info</strong>
								</div>
								<div class="card-body small">
									<div class="mb-1"><strong>{$translate_parts_wo_id}</strong> {$wo_id}</div>
									<div class="mb-1"><strong>{$translate_parts_shipping_method}</strong> {$service_code}</div>
									<div><strong>{$translate_parts_ware}</strong> {$location}</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
