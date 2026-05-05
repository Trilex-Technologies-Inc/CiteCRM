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
				<i class="bi bi-box-seam me-2 text-secondary"></i> Products
			</div>
			<a class="btn btn-sm btn-outline-secondary" href="?page=inventory:manufacturers&page_title=Manufacturers">
				<i class="bi bi-building"></i> Manufacturers
			</a>
		</div>
		<div class="card-body">
			<form method="get" action="index.php" class="row g-2 align-items-end mb-3">
				<input type="hidden" name="page" value="inventory:products">
				<input type="hidden" name="page_title" value="Products">
				<div class="col-sm-8 col-lg-6">
					<label class="form-label">Search</label>
					<input type="text" name="q" value="{$q|escape}" class="form-control" placeholder="Product name / SKU / manufacturer">
				</div>
				<div class="col-auto">
					<button class="btn btn-outline-secondary" type="submit">
						<i class="bi bi-search"></i> Search
					</button>
				</div>
			</form>

			<form method="post" action="?page=inventory:products" class="row g-2 align-items-end">
				<div class="col-sm-6 col-lg-3">
					<label class="form-label">Manufacturer</label>
					<select name="manufacturer_id" class="form-select" required>
						<option value="">Select…</option>
						{foreach from=$manufacturer_options item=o}
							<option value="{$o.MANUFACTURER_ID}">{$o.MANUFACTURER_NAME|escape}</option>
						{/foreach}
					</select>
				</div>
				<div class="col-sm-6 col-lg-2">
					<label class="form-label">Category</label>
					<select name="cat_id" id="new_cat_id" class="form-select" required>
						<option value="">Select…</option>
						{foreach from=$category_options item=c}
							<option value="{$c.ID|escape}">{$c.DESCRIPTION|escape}</option>
						{/foreach}
					</select>
				</div>
				<div class="col-sm-6 col-lg-2">
					<label class="form-label">Subcategory</label>
					<select name="subcat_id" id="new_subcat_id" class="form-select" required disabled>
						<option value="">Select category first…</option>
					</select>
				</div>
				<div class="col-sm-6 col-lg-2">
					<label class="form-label">SKU</label>
					<input type="text" name="product_sku" class="form-control" placeholder="Optional">
				</div>
				<div class="col-sm-6 col-lg-3">
					<label class="form-label">Name</label>
					<input type="text" name="product_name" class="form-control" required maxlength="120">
				</div>
				<div class="col-sm-6 col-lg-2">
					<label class="form-label">Price</label>
					<input type="text" name="product_price" class="form-control" placeholder="0.00">
				</div>
				<div class="col-sm-6 col-lg-1">
					<label class="form-label">Active</label>
					<select name="product_active" class="form-select">
						<option value="1" selected>Yes</option>
						<option value="0">No</option>
					</select>
				</div>
				<div class="col-sm-12 col-lg-7">
					<label class="form-label">Description</label>
					<input type="text" name="product_description" class="form-control" placeholder="Optional">
				</div>
				<div class="col-auto">
					<input type="submit" name="submit" value="New" class="btn btn-primary">
				</div>
			</form>
		</div>
	</div>

	<div class="card shadow-sm">
		<div class="card-header bg-white fw-semibold">
			Existing products
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover align-middle">
					<thead class="table-light">
						<tr>
							<th style="width: 90px;">ID</th>
							<th style="min-width: 180px;">Name</th>
							<th style="min-width: 160px;">Manufacturer</th>
							<th style="min-width: 180px;">Category</th>
							<th style="min-width: 200px;">Subcategory</th>
							<th style="width: 140px;">SKU</th>
							<th style="width: 120px;">Price</th>
							<th class="text-center" style="width: 120px;">Active</th>
							<th style="min-width: 240px;">Description</th>
							<th class="text-center" style="width: 220px;">Actions</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$products item=p}
							<tr>
								<td class="text-muted">{$p.PRODUCT_ID}</td>
								<td>
									<form method="post" action="?page=inventory:products" class="d-flex gap-2">
										<input type="hidden" name="product_id" value="{$p.PRODUCT_ID}">
										<input type="text" name="product_name" class="form-control form-control-sm" value="{$p.PRODUCT_NAME|escape}" maxlength="120" required>
								</td>
								<td>
										<select name="manufacturer_id" class="form-select form-select-sm" required>
											<option value="">Select…</option>
											{foreach from=$manufacturer_options item=o}
												<option value="{$o.MANUFACTURER_ID}" {if $o.MANUFACTURER_ID == $p.MANUFACTURER_ID}selected{/if}>{$o.MANUFACTURER_NAME|escape}</option>
											{/foreach}
										</select>
								</td>
								<td>
										<select name="cat_id" class="form-select form-select-sm js-cat" data-subcat-target="subcat_{$p.PRODUCT_ID}" required>
											<option value="">Select…</option>
											{foreach from=$category_options item=c}
												<option value="{$c.ID|escape}" {if $c.ID == $p.CAT_ID}selected{/if}>{$c.DESCRIPTION|escape}</option>
											{/foreach}
										</select>
								</td>
								<td>
										<select name="subcat_id" class="form-select form-select-sm js-subcat" id="subcat_{$p.PRODUCT_ID}" data-selected="{$p.SUBCAT_ID}" required disabled>
											{if $p.SUBCAT_ID > 0}
												<option value="{$p.SUBCAT_ID}" selected>{$p.SUBCAT_DESCRIPTION|escape}</option>
											{else}
												<option value="">Select category first…</option>
											{/if}
										</select>
								</td>
								<td>
										<input type="text" name="product_sku" class="form-control form-control-sm" value="{$p.PRODUCT_SKU|escape}">
								</td>
								<td>
										<input type="text" name="product_price" class="form-control form-control-sm" value="{$p.PRODUCT_PRICE|escape}">
								</td>
								<td class="text-center">
										<select name="product_active" class="form-select form-select-sm" style="width: 100px; margin: 0 auto;">
											<option value="1" {if $p.PRODUCT_ACTIVE == 1}selected{/if}>Yes</option>
											<option value="0" {if $p.PRODUCT_ACTIVE != 1}selected{/if}>No</option>
										</select>
								</td>
								<td>
										<input type="text" name="product_description" class="form-control form-control-sm" value="{$p.PRODUCT_DESCRIPTION|escape}">
								</td>
								<td class="text-center">
										<button type="submit" name="submit" value="Edit" class="btn btn-sm btn-outline-primary">
											<i class="bi bi-save"></i> Save
										</button>
									</form>

									<form method="post" action="?page=inventory:products" class="d-inline">
										<input type="hidden" name="product_id" value="{$p.PRODUCT_ID}">
										<button type="submit"
												name="submit"
												value="Delete"
												class="btn btn-sm btn-outline-danger"
												onclick="return confirm('Delete product {$p.PRODUCT_NAME|escape:'javascript'}?');">
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

{literal}
<script>
(function () {
	function loadSubcats(catId, subcatSelect, selectedId) {
		if (!catId) {
			subcatSelect.innerHTML = '<option value="">Select category first…</option>';
			subcatSelect.disabled = true;
			return;
		}

		subcatSelect.disabled = true;
		subcatSelect.innerHTML = '<option value="">Loading…</option>';

		fetch('index.php?page=inventory:products&ajax=subcats&escape=1&cat_id=' + encodeURIComponent(catId), {
			credentials: 'same-origin'
		})
			.then(function (r) { return r.json(); })
			.then(function (items) {
				var html = '<option value="">Select…</option>';
				if (Array.isArray(items)) {
					for (var i = 0; i < items.length; i++) {
						var it = items[i];
						var id = String(it.ID || '');
						var label = String(it.DESCRIPTION || it.SUB_CATEGORY || id);
						var sel = (selectedId && String(selectedId) === id) ? ' selected' : '';
						html += '<option value="' + id.replace(/"/g, '&quot;') + '"' + sel + '>' +
							label.replace(/</g, '&lt;').replace(/>/g, '&gt;') +
							'</option>';
					}
				}
				subcatSelect.innerHTML = html;
				subcatSelect.disabled = false;
			})
			.catch(function () {
				subcatSelect.innerHTML = '<option value="">Failed to load</option>';
				subcatSelect.disabled = true;
			});
	}

	document.addEventListener('DOMContentLoaded', function () {
		var newCat = document.getElementById('new_cat_id');
		var newSub = document.getElementById('new_subcat_id');
		if (newCat && newSub) {
			newCat.addEventListener('change', function () {
				loadSubcats(newCat.value, newSub, null);
			});
		}

		var cats = document.querySelectorAll('select.js-cat');
		for (var i = 0; i < cats.length; i++) {
			(function (catSelect) {
				var targetId = catSelect.getAttribute('data-subcat-target');
				if (!targetId) return;
				var subSelect = document.getElementById(targetId);
				if (!subSelect) return;

				catSelect.addEventListener('change', function () {
					loadSubcats(catSelect.value, subSelect, null);
				});

				// Initialize existing rows
				var selected = subSelect.getAttribute('data-selected');
				if (catSelect.value) {
					loadSubcats(catSelect.value, subSelect, selected);
				}
			})(cats[i]);
		}
	});
})();
</script>
{/literal}
