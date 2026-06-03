<div class="container my-4">

	<div class="mb-3">
		{include file="core/admin_tool_bar.tpl"}
	</div>

	{if $error_msg != ""}
		<div class="alert alert-danger">
			{include file="core/error.tpl"}
		</div>
	{/if}

	{if $msg !=""}
		<div class="alert alert-success">
			{include file="core/msg.tpl"}
		</div>
	{/if}

	<div class="card shadow-sm mb-4">
		<div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
			<div>
				<i class="bi bi-houses me-2 text-secondary"></i> Warehouses
			</div>
			<div class="d-flex gap-2">
				<a class="btn btn-sm btn-outline-secondary" href="?page=inventory:manufacturers&page_title=Manufacturers">
					<i class="bi bi-building"></i> Manufacturers
				</a>
				<a class="btn btn-sm btn-outline-secondary" href="?page=inventory:products&page_title=Products">
					<i class="bi bi-box-seam"></i> Products
				</a>
			</div>
		</div>
		<div class="card-body">
			<form method="post" action="?page=inventory:warehouses" class="row g-2 align-items-end">
				<div class="col-sm-6 col-lg-3">
					<label class="form-label">Name</label>
					<input type="text" name="warehouse_name" class="form-control" placeholder="Main Warehouse" required maxlength="120">
				</div>
				<div class="col-sm-6 col-lg-2">
					<label class="form-label">Code</label>
					<input type="text" name="warehouse_code" class="form-control" placeholder="MAIN">
				</div>
				<div class="col-sm-6 col-lg-3">
					<label class="form-label">Address</label>
					<input type="text" name="warehouse_address" class="form-control">
				</div>
				<div class="col-sm-6 col-lg-2">
					<label class="form-label">City</label>
					<input type="text" name="warehouse_city" class="form-control">
				</div>
				<div class="col-sm-6 col-lg-2">
					<label class="form-label">State</label>
					<input type="text" name="warehouse_state" class="form-control">
				</div>
				<div class="col-sm-6 col-lg-2">
					<label class="form-label">Zip</label>
					<input type="text" name="warehouse_zip" class="form-control">
				</div>
				<div class="col-sm-6 col-lg-2">
					<label class="form-label">Country</label>
					<select name="warehouse_country" class="form-select">
						<option value="">Select country</option>
						{section name=c loop=$country}
							<option value="{$country[c].code}"
								{if $selected_country == $country[c].code}selected{/if}>
								{$country[c].name}
							</option>
						{/section}
					</select>
				</div>
				<div class="col-sm-6 col-lg-1">
					<label class="form-label">Active</label>
					<select name="warehouse_active" class="form-select">
						<option value="1" selected>Yes</option>
						<option value="0">No</option>
					</select>
				</div>
				<div class="col-auto">
					<input type="submit" name="submit" value="New" class="btn btn-primary">
				</div>
			</form>
		</div>
	</div>

	<div class="card shadow-sm">
		<div class="card-header bg-white fw-semibold">
			Existing warehouses
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover align-middle">
					<thead class="table-light">
						<tr>
							<th style="width: 80px;">ID</th>
							<th style="min-width: 180px;">Name</th>
							<th style="width: 120px;">Code</th>
							<th style="min-width: 220px;">Address</th>
							<th style="width: 140px;">City</th>
							<th style="width: 120px;">State</th>
							<th style="width: 110px;">Zip</th>
							<th style="width: 130px;">Country</th>
							<th class="text-center" style="width: 110px;">Active</th>
							<th class="text-center" style="width: 120px;">Products</th>
							<th class="text-center" style="width: 220px;">Actions</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$warehouses item=w}
							<tr>
								<td class="text-muted">{$w.WAREHOUSE_ID}</td>
								<td>
									<form method="post" action="?page=inventory:warehouses" class="d-flex gap-2">
										<input type="hidden" name="warehouse_id" value="{$w.WAREHOUSE_ID}">
										<input type="text" name="warehouse_name" class="form-control form-control-sm" value="{$w.WAREHOUSE_NAME|escape}" maxlength="120" required>
								</td>
								<td>
										<input type="text" name="warehouse_code" class="form-control form-control-sm" value="{$w.WAREHOUSE_CODE|escape}">
								</td>
								<td>
										<input type="text" name="warehouse_address" class="form-control form-control-sm" value="{$w.WAREHOUSE_ADDRESS|escape}">
								</td>
								<td>
										<input type="text" name="warehouse_city" class="form-control form-control-sm" value="{$w.WAREHOUSE_CITY|escape}">
								</td>
								<td>
										<input type="text" name="warehouse_state" class="form-control form-control-sm" value="{$w.WAREHOUSE_STATE|escape}">
								</td>
								<td>
										<input type="text" name="warehouse_zip" class="form-control form-control-sm" value="{$w.WAREHOUSE_ZIP|escape}">
								</td>
								<td>
										<select name="warehouse_country" class="form-select form-select-sm">
											<option value="">Select country</option>
											{section name=c loop=$country}
												<option value="{$country[c].code}"
													{if $w.WAREHOUSE_COUNTRY == $country[c].code}selected{/if}>
													{$country[c].name}
												</option>
											{/section}
										</select>
								</td>
								<td class="text-center">
										<select name="warehouse_active" class="form-select form-select-sm" style="width: 90px; margin: 0 auto;">
											<option value="1" {if $w.WAREHOUSE_ACTIVE == 1}selected{/if}>Yes</option>
											<option value="0" {if $w.WAREHOUSE_ACTIVE != 1}selected{/if}>No</option>
										</select>
								</td>
								<td class="text-center">
									<span class="badge text-bg-secondary">{$w.PRODUCT_COUNT}</span>
								</td>
								<td class="text-center">
										<button type="submit" name="submit" value="Edit" class="btn btn-sm btn-outline-primary">
											<i class="bi bi-save"></i> Save
										</button>
									</form>

									<form method="post" action="?page=inventory:warehouses" class="d-inline">
										<input type="hidden" name="warehouse_id" value="{$w.WAREHOUSE_ID}">
										<button type="submit"
												name="submit"
												value="Delete"
												class="btn btn-sm btn-outline-danger"
												onclick="return confirm('Delete warehouse {$w.WAREHOUSE_NAME|escape:'javascript'}?');"
												{if $w.PRODUCT_COUNT > 0}disabled{/if}>
											<i class="bi bi-trash"></i> Delete
										</button>
									</form>
								</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
