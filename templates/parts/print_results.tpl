<html>
<head>
	<title>{$translate_parts_order_complete}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link href="css/default.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container my-4">
	<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
		<div class="me-auto">
			<img src="images/cite_crm.jpg" alt="CiteCRM" class="img-fluid" style="max-width: 240px;">
		</div>

		<div class="card border-dark" style="min-width: 260px;">
			<div class="card-body p-3">
				<div><b>{$translate_parts_crm_order_id}</b> {$order.INVOICE_ID}</div>
				<div><b>{$translate_parts_date}</b> {$order.DATE_CREATE|date_format:"%m/%d/%y"}</div>
				<div><b>{$translate_parts_total}</b> ${$order.TOTAL|string_format:"%.2f"}</div>
				<div><b>{$translate_parts_total_items}</b> {$order.TOTAL_ITEMS}</div>
				<div><b>{$translate_parts_weight}</b> {$order.WEIGHT} lbs</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mt-1">
		<div class="col-12 col-md-8">
			<div class="card border-dark h-100">
				<div class="card-body p-3">
					<div class="fs-4 fw-bold">Cite CRM</div>
					
				</div>
			</div>
		</div>

		<div class="col-12 col-md-4">
			<div class="card border-dark h-100">
				<div class="card-body p-3">
					<div><b>{$translate_parts_wo_id}</b> {$order.WO_ID}</div>
					<div><b>{$translate_parts_tech}</b> {$display_login}</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mt-1">
		<div class="col-12">
			<div class="card border-dark">
				<div class="card-body p-3">
					<div class="fw-bold mb-2">{$translate_parts_ship_to}</div>
					<div class="fs-4 fw-bold">{$company_name}</div>
					<div>{$company_address}</div>
					<div>{$company_city}, {$company_state} {$company_zip}</div>
					<div>{$company_phone}</div>
				</div>
			</div>
		</div>
	</div>

	<h2 class="text-center my-4">{$translate_parts_cap_invoice}</h2>

	<div class="mb-2 fw-bold">Parts</div>
	<div class="table-responsive">
		<table class="table table-bordered align-middle">
			<thead class="table-light">
				<tr>
					<th style="width: 120px;">{$translate_parts_sku}</th>
					<th style="width: 90px;">{$translate_parts_count}</th>
					<th>{$translate_parts_description}</th>
					<th style="width: 140px;">{$translate_parts_vendor}</th>
					<th class="text-end" style="width: 120px;">{$translate_parts_amount}</th>
					<th class="text-end" style="width: 130px;">{$translate_parts_sub_total}</th>
				</tr>
			</thead>
			<tbody>
				{section name=q loop=$details}
				<tr>
					<td><b>{$details[q].SKU}</b></td>
					<td>{$details[q].COUNT}</td>
					<td>{$details[q].DESCRIPTION}</td>
					<td>{$details[q].VENDOR}</td>
					<td class="text-end">${$details[q].PRICE|string_format:"%.2f"}</td>
					<td class="text-end">${$details[q].SUB_TOTAL|string_format:"%.2f"}</td>
				</tr>
				{/section}
			</tbody>
		</table>
	</div>

	<div class="row justify-content-end">
		<div class="col-12 col-sm-8 col-md-5 col-lg-4">
			<div class="card border-dark">
				<div class="card-body p-3">
					<div class="d-flex justify-content-between"><div><b>{$translate_parts_sub_total}</b></div><div class="text-end">${$order.SUB_TOTAL|string_format:"%.2f"}</div></div>
					<div class="d-flex justify-content-between"><div><b>{$translate_parts_shipping}</b></div><div class="text-end">${$order.SHIPPING|string_format:"%.2f"}</div></div>
					<div class="d-flex justify-content-between"><div><b>{$translate_parts_tax}</b></div><div class="text-end">${$order.TAX|string_format:"%.2f"}</div></div>
					<hr class="my-2">
					<div class="d-flex justify-content-between fw-bold"><div>{$translate_parts_total}</div><div class="text-end">${$order.TOTAL|string_format:"%.2f"}</div></div>
				</div>
			</div>
		</div>
	</div>

	<div class="card border-dark mt-3">
		<div class="card-body p-3">
			<small>{$translate_parts_msg_11}</small>
		</div>
	</div>
</div>
</body>
</html>
