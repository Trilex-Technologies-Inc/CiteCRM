<!-- Parts Orders Status -->
{literal}
<script type="text/javascript">
	function partsStatusGo(sel) {
		if (sel && sel.value) window.location = sel.value;
	}
</script>
{/literal}

<div class="container-fluid my-3">
	<div class="mb-3">
		{include file="core/tool_bar.tpl"}
	</div>

	<div class="card shadow-sm">
		<div class="card-header d-flex justify-content-between align-items-center">
			<div class="fw-bold">{$or_status} Orders</div>
			<div class="text-muted small">
				{if $total_results != ""}{$total_results} {$translate_parts_records_found}{/if}
			</div>
		</div>

		<div class="card-body">
			{if $error_msg != ""}
				<div class="alert alert-danger mb-3">
					{include file="core/error.tpl"}
				</div>
			{/if}

			<div class="row g-3 align-items-end mb-3">
				<div class="col-12 col-md-5 col-lg-4">
					<form method="post" action="?page=parts:view" class="d-flex gap-2 align-items-end">
						<div class="flex-grow-1">
							<label class="form-label mb-1">{$translate_parts_inc_inv}</label>
							<input class="form-control form-control-sm" name="ORDER_ID" type="text"/>
						</div>
						<button class="btn btn-sm btn-primary" name="submit" value="Search" type="submit">Search</button>
					</form>
				</div>

				<div class="col-12 col-md-7 col-lg-8 text-md-end">
					<div class="d-inline-flex flex-wrap gap-2 align-items-center">
						<a class="btn btn-sm btn-outline-secondary"
						   href="?page=parts:status&status={$status}&submit=submit&page_no=1&page_title={$smarty.request.page_title|default:''|escape:'url'}">
							First
						</a>

						{if $previous != ''}
							<a class="btn btn-sm btn-outline-secondary"
							   href="?page=parts:status&status={$status}&submit=submit&page_no={$previous}&page_title={$smarty.request.page_title|default:''|escape:'url'}">
								Prev
							</a>
						{/if}

						<select class="form-select form-select-sm" style="width: 210px;"
								name="page_no"
								onchange="partsStatusGo(this)">
							{section name=page loop=$total_pages start=1}
								<option value="?page=parts:status&status={$status}&submit=submit&page_no={$smarty.section.page.index}&page_title={$smarty.request.page_title|default:''|escape:'url'}"
										{if $page_no == $smarty.section.page.index } selected{/if}>
									{$translate_parts_page} {$smarty.section.page.index} {$translate_parts_of} {$total_pages}
								</option>
							{/section}
							<option value="?page=parts:status&status={$status}&submit=submit&page_no={$total_pages}&page_title={$smarty.request.page_title|default:''|escape:'url'}"
									{if $page_no == $total_pages} selected{/if}>
								{$translate_parts_page} {$total_pages} {$translate_parts_of} {$total_pages}
							</option>
						</select>

						{if $next != ''}
							<a class="btn btn-sm btn-outline-secondary"
							   href="?page=parts:status&status={$status}&submit=submit&page_no={$next}&page_title={$smarty.request.page_title|default:''|escape:'url'}">
								Next
							</a>
						{/if}

						<a class="btn btn-sm btn-outline-secondary"
						   href="?page=parts:status&status={$status}&submit=submit&page_no={$total_pages}&page_title={$smarty.request.page_title|default:''|escape:'url'}">
							Last
						</a>
					</div>
				</div>
			</div>

			<div class="table-responsive">
				<table class="table table-sm table-striped table-hover align-middle mb-0">
					<thead class="table-secondary">
					<tr>
						<th>{$translate_parts_id}</th>
						<th>{$translate_parts_created}</th>
						<th>{$translate_parts_invoice}</th>
						<th>{$translate_parts_wo}</th>
						<th class="text-end">{$translate_parts_sub_total}</th>
						<th class="text-end">{$translate_parts_shipping}</th>
						<th class="text-end">{$translate_parts_total}</th>
						<th>{$translate_parts_update}</th>
						<th>{$translate_parts_tracking}</th>
						<th>{$translate_parts_status}</th>
					</tr>
					</thead>
					<tbody>
					{if $order}
						{section name=i loop=$order}
							<tr style="cursor:pointer;"
								ondblclick="window.location='index.php?page=parts:view&ORDER_ID={$order[i].ORDER_ID}&page_title={$translate_parts_order_details|escape:'url'}%20{$order[i].ORDER_ID}';">
								<td>
									<a href="index.php?page=parts:view&ORDER_ID={$order[i].ORDER_ID}&page_title={$translate_parts_order_details|escape:'url'}%20{$order[i].ORDER_ID}">
										{$order[i].ORDER_ID}
									</a>
								</td>
								<td>{$order[i].DATE_CREATE|date_format:"%m-%d-%Y"}</td>
								<td>{$order[i].INVOICE_ID}</td>
								<td>
									<a href="?page=workorder:view&wo_id={$order[i].WO_ID}&page_title={$translate_parts_wo_id|escape:'url'}%20{$order[i].WO_ID}">
										{$order[i].WO_ID}
									</a>
								</td>
								<td class="text-end">${$order[i].SUB_TOTAL|string_format:"%.2f"}</td>
								<td class="text-end">${$order[i].SHIPPING|string_format:"%.2f"}</td>
								<td class="text-end fw-semibold">${$order[i].TOTAL|string_format:"%.2f"}</td>
								<td>{$order[i].DATE_LAST|date_format:"%m-%d-%Y"}</td>
								<td>
									
										<a href="?page=parts:tracking&invoice_id={$order[i].INVOICE_ID}&order_id={$order[i].ORDER_ID}">Get Tracking</a>
									
								</td>
								<td>
									{if $order[i].STATUS == 1}
										<a href="?page=parts:update&wo_id={$order[i].WO_ID}">{$translate_parts_set_recv}</a>
									{else}
										{$translate_parts_rcv}
									{/if}
								</td>
							</tr>
						{/section}
					{else}
						<tr>
							<td colspan="10" class="text-center text-muted py-4">No orders found.</td>
						</tr>
					{/if}
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
