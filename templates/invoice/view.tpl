<!-- invoice view -->
<div class="container my-4">

	<!-- Toolbar -->
	<div class="mb-3">
		{include file="core/tool_bar.tpl"}
	</div>

	<div class="card shadow-sm">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h5 class="mb-0">{$translate_invoice_for} {$wo_id}</h5>
			<a href="http://www.citecrm.com/docs/#billing" target="new" class="text-decoration-none">
				<i class="bi bi-question-circle-fill fs-5 text-secondary" aria-hidden="true"></i>
			</a>
		</div>

		<div class="card-body">
			{if $error_msg != ""}
				<div class="alert alert-danger">
					{include file="core/error.tpl"}
				</div>
			{/if}

			<!-- Invoice summary -->
			<div class="table-responsive mb-4">
				<table class="table table-sm table-bordered align-middle mb-0">
					<thead class="table-light">
					<tr>
						<th>{$translate_invoice_id}</th>
						<th>{$translate_invoice_date}</th>
						<th>{$translate_invoice_due}</th>
						<th>{$translate_invoice_amount}</th>
						<th>{$translate_invoice_tech}</th>
						<th>{$translate_invoice_work_order}</th>
						<th>{$translate_invoice_date_paid}</th>
						<th>{$translate_invoice_amount_paid}</th>
						<th>{$translate_invoice_balance}</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td>{$invoice.INVOICE_ID}</td>
						<td>{$invoice.INVOICE_DATE|date_format:"%m/%d/%y"}</td>
						<td>{$invoice.INVOICE_DUE|date_format:"%m/%d/%y"}</td>
						<td>${$invoice.INVOICE_AMOUNT|string_format:"%.2f"}</td>
						<td>{$invoice.EMPLOYEE_DISPLAY_NAME}</td>
						<td>
							<a href="?page=workorder:view&wo_id={$invoice.WORKORDER_ID}&page_title={$translate_invoice_wo_id} {$invoice.WORKORDER_ID}">
								{$invoice.WORKORDER_ID}
							</a>
						</td>
						<td>{$invoice.PAID_DATE|date_format:"%m/%d/%y"}</td>
						<td>${$invoice.PAID_AMOUNT|string_format:"%.2f"}</td>
						<td>
							{if $invoice.BALLANCE > 0}
								<span class="text-danger">${$invoice.BALLANCE|string_format:"%.2f"}</span>
							{else}
								${$invoice.BALLANCE|string_format:"%.2f"}
							{/if}
						</td>
					</tr>
					</tbody>
				</table>
			</div>

			<div class="row mb-4">
				<div class="col-md-6">
					<h6>{$translate_invoice_bill}</h6>
					{foreach item=item from=$customer_details}
						<div class="mb-2">
							<a href="?page=customer:customer_details&customer_id={$item.CUSTOMER_ID}&page_title={$item.CUSTOMER_DISPLAY_NAME}">
								{$item.CUSTOMER_DISPLAY_NAME}
							</a><br>
							{$item.CUSTOMER_PHONE}<br>
							{$item.CUSTOMER_ADDRESS}<br>
							{$item.CUSTOMER_CITY}, {$item.CUSTOMER_STATE} {$item.CUSTOMER_ZIP}<br>
							{$item.CUSTOMER_EMAIL}
						</div>
					{/foreach}
				</div>
				<div class="col-md-6">
					<h6>{$translate_invoice_pay}</h6>
					{section name=x loop=$company}
						<div class="mb-2">
							{$company[x].COMPANY_NAME}<br>
							{$company[x].COMPANY_ADDRESS}<br>
							{$company[x].COMPANY_CITY}, {$company[x].COMPANY_STATE} {$company[x].COMPANY_ZIP}<br>
							{if $company[x].COMPANY_TAX_ID != ''}Tax ID: {$company[x].COMPANY_TAX_ID}<br>{/if}
							{$company[x].COMPNAY_PHONE}<br>
						</div>
					{/section}
				</div>
			</div>

			{if $invoice.INVOICE_AMOUNT > 0 }
				<div class="mb-4">
					<a class="btn btn-outline-secondary btn-sm"
					   href="?page=invoice:print&wo_id={$invoice.WORKORDER_ID}&customer_id={$invoice.CUSTOMER_ID}&invoice_id={$invoice.INVOICE_ID}&escape=1"
					   target="new">
						{$translate_invoice_print}
					</a>
				</div>
				{/if}

				<!-- Labor -->
				<div class="card mb-4">
					<div class="card-header fw-bold">{$translate_invoice_labor}</div>
					<div class="card-body p-0">
						{if $labor != '0'}
							<div class="table-responsive">
								<table class="table table-sm table-bordered mb-0 align-middle">
									<thead class="table-light">
									<tr>
										<th>{$translate_invoice_no}</th>
										<th>{$translate_invoice_hours}</th>
										<th>{$translate_invoice_description}</th>
										<th>{$translate_invoice_rate}</th>
										<th>{$translate_invoice_total}</th>
									</tr>
									</thead>
									<tbody>
									{section name=q loop=$labor}
										<tr>
											<td>{$smarty.section.q.index+1}</td>
											<td>{$labor[q].INVOICE_LABOR_UNIT}</td>
											<td>{$labor[q].INVOICE_LABOR_DESCRIPTION}</td>
											<td>${$labor[q].INVOICE_LABOR_RATE|string_format:"%.2f"}</td>
											<td>${$labor[q].INVOICE_LABOR_SUBTOTAL|string_format:"%.2f"}</td>
										</tr>
									{/section}
									</tbody>
								</table>
							</div>
						{else}
							<div class="p-3 text-muted">No labor items.</div>
						{/if}
					</div>
				</div>

				<!-- Parts -->
				<div class="card mb-4">
					<div class="card-header fw-bold">{$translate_invoice_parts}</div>
					<div class="card-body p-0">
						{if $parts != '0'}
							<div class="table-responsive">
								<table class="table table-sm table-bordered mb-0 align-middle">
									<thead class="table-light">
									<tr>
										<th>{$translate_invoice_no}</th>
										<th>{$translate_invoice_count}</th>
										<th>{$translate_invoice_description}</th>
										<th>{$translate_invoice_man}</th>
										<th>{$translate_invoice_price}</th>
										<th>{$translate_invoice_total}</th>
									</tr>
									</thead>
									<tbody>
									{section name=w loop=$parts}
										<tr>
											<td>{$smarty.section.w.index+1}</td>
											<td>{$parts[w].INVOICE_PARTS_COUNT}</td>
											<td>{$parts[w].INVOICE_PARTS_DESCRIPTION}</td>
											<td>{$parts[w].INVOICE_PARTS_MANUF}</td>
											<td>${$parts[w].INVOICE_PARTS_AMOUNT|string_format:"%.2f"}</td>
											<td>${$parts[w].INVOICE_PARTS_SUBTOTA|string_format:"%.2f"}</td>
										</tr>
									{/section}
									</tbody>
								</table>
							</div>
						{else}
							<div class="p-3 text-muted">No parts items.</div>
						{/if}
					</div>
				</div>

				<!-- Totals -->
				<div class="card mb-4">
					<div class="card-header fw-bold">{$translate_invoice_total}</div>
					<div class="card-body p-0">
						<div class="table-responsive">
							<table class="table table-sm table-bordered mb-0 align-middle">
								<tbody>
								<tr>
									<td class="text-end fw-bold">{$translate_invoice_sub_total}</td>
									<td class="text-end">${$invoice.SUB_TOTAL}</td>
								</tr>
								<tr>
									<td class="text-end fw-bold">{$translate_invoice_shipping}</td>
									<td class="text-end">${$invoice.SHIPPING}</td>
								</tr>
								<tr>
									<td class="text-end fw-bold">{$translate_invoice_tax}</td>
									<td class="text-end">${$invoice.TAX}</td>
								</tr>
								<tr>
									<td class="text-end fw-bold">{$translate_invoice_discount}</td>
									<td class="text-end">- ${$invoice.DISCOUNT|default:"0.00"}</td>
								</tr>
								<tr>
									<td class="text-end fw-bold">{$translate_invoice_total}</td>
									<td class="text-end">${$invoice.INVOICE_AMOUNT}</td>
								</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				{if $invoice.BALLANCE > 0}
					<!-- Transaction log -->
					<div class="card mb-0">
						<div class="card-header fw-bold">{$translate_invoice_trans_log}</div>
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table table-sm table-bordered mb-0 align-middle">
									<thead class="table-light">
									<tr>
										<th>{$translate_invoice_trans_id}</th>
										<th>{$translate_invoice_date}</th>
										<th>{$translate_invoice_amount}</th>
										<th>{$translate_invoice_type}</th>
									</tr>
									</thead>
									<tbody>
									{section name=r loop=$trans}
										<tr>
											<td>{$trans[r].TRANSACTION_ID}</td>
											<td>{$trans[r].DATE|date_format:"%m/%d/%y %r"}</td>
											<td><b>$</b>{$trans[r].AMOUNT}</td>
											<td>
												{if $trans[r].TYPE == 1}
													{$translate_invoice_cc}
												{elseif $trans[r].TYPE == 2}
													{$translate_invoice_check}
												{elseif $trans[r].TYPE == 3}
													{$translate_invoice_cash}
												{elseif $trans[r].TYPE == 4}
													{$translate_invoice_gift}
												{elseif $trans[r].TYPE == 5}
													{$translate_invoice_paypal}
												{elseif $trans[r].TYPE == 6}
													{$translate_invoice_stripe}
												{/if}
											</td>
										</tr>
										<tr>
											<td class="fw-bold">{$translate_invoice_memo}</td>
											<td colspan="3">{$trans[r].MEMO}</td>
										</tr>
									{/section}
									</tbody>
								</table>
							</div>
						</div>
					</div>
				{/if}

			</div>
		</div>
	</div>
