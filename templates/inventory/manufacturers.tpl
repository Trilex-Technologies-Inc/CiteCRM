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
				<i class="bi bi-building me-2 text-secondary"></i> Manufacturers
			</div>
			<a class="btn btn-sm btn-outline-secondary" href="?page=inventory:products&page_title=Products">
				<i class="bi bi-box-seam"></i> Products
			</a>
		</div>
		<div class="card-body">
			<form method="post" action="?page=inventory:manufacturers" class="row g-2 align-items-end">
				<div class="col-sm-6 col-lg-4">
					<label class="form-label">Name</label>
					<input type="text" name="manufacturer_name" class="form-control" placeholder="e.g. HP" required maxlength="120">
				</div>
				<div class="col-sm-6 col-lg-4">
					<label class="form-label">Website</label>
					<input type="text" name="manufacturer_website" class="form-control" placeholder="https://example.com">
				</div>
				<div class="col-sm-6 col-lg-2">
					<label class="form-label">Active</label>
					<select name="manufacturer_active" class="form-select">
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
			Existing manufacturers
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover align-middle">
					<thead class="table-light">
						<tr>
							<th style="width: 90px;">ID</th>
							<th>Name</th>
							<th>Website</th>
							<th class="text-center" style="width: 120px;">Active</th>
							<th class="text-center" style="width: 140px;">Products</th>
							<th class="text-center" style="width: 240px;">Actions</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$manufacturers item=m}
							<tr>
								<td class="text-muted">{$m.MANUFACTURER_ID}</td>
								<td>
									<form method="post" action="?page=inventory:manufacturers" class="d-flex gap-2">
										<input type="hidden" name="manufacturer_id" value="{$m.MANUFACTURER_ID}">
										<input type="text" name="manufacturer_name" class="form-control form-control-sm" value="{$m.MANUFACTURER_NAME|escape}" maxlength="120" required>
								</td>
								<td>
										<input type="text" name="manufacturer_website" class="form-control form-control-sm" value="{$m.MANUFACTURER_WEBSITE|escape}">
								</td>
								<td class="text-center">
										<select name="manufacturer_active" class="form-select form-select-sm" style="width: 100px; margin: 0 auto;">
											<option value="1" {if $m.MANUFACTURER_ACTIVE == 1}selected{/if}>Yes</option>
											<option value="0" {if $m.MANUFACTURER_ACTIVE != 1}selected{/if}>No</option>
										</select>
								</td>
								<td class="text-center">
									<span class="badge text-bg-secondary">{$m.PRODUCT_COUNT}</span>
								</td>
								<td class="text-center">
										<button type="submit" name="submit" value="Edit" class="btn btn-sm btn-outline-primary">
											<i class="bi bi-save"></i> Save
										</button>
									</form>

									<form method="post" action="?page=inventory:manufacturers" class="d-inline">
										<input type="hidden" name="manufacturer_id" value="{$m.MANUFACTURER_ID}">
										<button type="submit"
												name="submit"
												value="Delete"
												class="btn btn-sm btn-outline-danger"
												onclick="return confirm('Delete manufacturer {$m.MANUFACTURER_NAME|escape:'javascript'}?');"
												{if $m.PRODUCT_COUNT > 0}disabled{/if}>
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

