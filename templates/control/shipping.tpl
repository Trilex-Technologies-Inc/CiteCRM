<div class="container my-4">
	<div class="mb-3">
		{include file="core/admin_tool_bar.tpl"}
	</div>

	{if $msg != ""}
		{include file="core/msg.tpl"}
	{/if}
	{if $error_msg != ""}
		{include file="core/error.tpl"}
	{/if}

	<div class="card shadow-sm">
		<div class="card-header bg-white fw-semibold">
			<i class="bi bi-truck me-2 text-secondary"></i> Shipping Management
		</div>
		<div class="card-body">
			<form method="post" action="">
				{section name=w loop=$setup}

					{if !$has_shipping_columns}
						<div class="alert alert-warning">
							Your database does not have the optional shipping provider columns yet. UPS credentials can still be saved, but selecting a provider (UPS/FedEx/DHL) requires a database upgrade.
						</div>
					{else}
						<h6 class="mb-3">Provider</h6>
						<div class="row g-3">
							<div class="col-12 col-md-6">
								<label class="form-label" for="shipping_provider">Shipping provider</label>
								<select class="form-select" id="shipping_provider" name="shipping_provider">
									<option value="ups" {if $setup[w].SHIPPING_PROVIDER == 'ups' || $setup[w].SHIPPING_PROVIDER == ''}selected{/if}>UPS</option>
									<option value="fedex" {if $setup[w].SHIPPING_PROVIDER == 'fedex'}selected{/if}>FedEx</option>
									<option value="dhl" {if $setup[w].SHIPPING_PROVIDER == 'dhl'}selected{/if}>DHL</option>
								</select>
								<div class="form-text">Select which provider to use for shipping.</div>
							</div>
							<div class="col-12 col-md-6"></div>
						</div>
					{/if}

					<hr class="my-4">

					<div id="shipping-ups-fields" class="col-12">
						<h6 class="mb-3">UPS Credentials</h6>
						<div class="row g-3">
							<div class="col-md-6">
								<label class="form-label">UPS Login</label>
								<input type="text" class="form-control" name="ups_login" value="{$setup[w].UPS_LOGIN|default:''|escape}">
							</div>

							<div class="col-md-6">
								<label class="form-label">UPS Password</label>
								<input type="password" class="form-control" name="ups_password">
								<div class="form-text">Leave blank to keep current password.</div>
							</div>

							<div class="col-md-6">
								<label class="form-label">UPS Access Key</label>
								<input type="text" class="form-control" name="ups_access_key" value="{$setup[w].UPS_ACCESS_KEY|default:''|escape}">
								<div class="form-text">For UPS REST/OAuth, set this to your Client ID.</div>
							</div>
						</div>
					</div>

					<div id="shipping-fedex-fields" class="col-12" {if !$has_shipping_columns}style="display:none"{/if}>
						<h6 class="mb-3">FedEx Credentials</h6>
						<div class="row g-3">
							<div class="col-md-6">
								<label class="form-label">FedEx API Key</label>
								<input type="text" class="form-control" name="fedex_key" value="{$setup[w].FEDEX_KEY|default:''|escape}">
							</div>

							<div class="col-md-6">
								<label class="form-label">FedEx API Password</label>
								<input type="password" class="form-control" name="fedex_password">
								<div class="form-text">Leave blank to keep current password.</div>
							</div>

							<div class="col-md-6">
								<label class="form-label">FedEx Account Number</label>
								<input type="text" class="form-control" name="fedex_account" value="{$setup[w].FEDEX_ACCOUNT|default:''|escape}">
							</div>

							<div class="col-md-6">
								<label class="form-label">FedEx Meter Number</label>
								<input type="text" class="form-control" name="fedex_meter" value="{$setup[w].FEDEX_METER|default:''|escape}">
							</div>
						</div>
					</div>

					<div id="shipping-dhl-fields" class="col-12" {if !$has_shipping_columns}style="display:none"{/if}>
						<h6 class="mb-3">DHL Express (MyDHL API)</h6>

						{if !$has_dhl_columns}
							<div class="alert alert-warning mb-0">
								Your database is missing DHL credential columns (DHL_KEY/DHL_SECRET/DHL_ACCOUNT). Please run the DHL shipping upgrade SQL, then refresh this page.
							</div>
						{else}
							<div class="row g-3">
								<div class="col-md-6">
									<label class="form-label">DHL API Key</label>
									<input type="text" class="form-control" name="dhl_key" value="{$setup[w].DHL_KEY|default:''|escape}">
									<div class="form-text">MyDHL API Key (used as Basic Auth username).</div>
								</div>

								<div class="col-md-6">
									<label class="form-label">DHL API Secret</label>
									<input type="password" class="form-control" name="dhl_secret">
									<div class="form-text">Leave blank to keep current secret.</div>
								</div>

								<div class="col-md-6">
									<label class="form-label">DHL Account Number</label>
									<input type="text" class="form-control" name="dhl_account" value="{$setup[w].DHL_ACCOUNT|default:''|escape}">
									<div class="form-text">Your DHL Express account number.</div>
								</div>
							</div>
						{/if}
					</div>

				{/section}

				<div class="mt-4 d-flex gap-2">
					<input value="Save Shipping Settings" name="submit" type="submit" class="btn btn-primary">
					<a class="btn btn-outline-secondary" href="?page=control:main&page_title={$translate_core_control|default:"Control Center"}">Back to Control Center</a>
				</div>
			</form>
		</div>
	</div>
</div>

{literal}
<script>
	(function () {
		function toggleShippingProviderFields() {
			var providerEl = document.getElementById('shipping_provider');
			var ups = document.getElementById('shipping-ups-fields');
			var fedex = document.getElementById('shipping-fedex-fields');
			var dhl = document.getElementById('shipping-dhl-fields');
			if (!providerEl || !ups || !fedex || !dhl) return;

			var provider = (providerEl.value || 'ups').toLowerCase();
			ups.style.display = provider === 'ups' ? '' : 'none';
			fedex.style.display = provider === 'fedex' ? '' : 'none';
			dhl.style.display = provider === 'dhl' ? '' : 'none';
		}

		document.addEventListener('DOMContentLoaded', function () {
			var providerEl = document.getElementById('shipping_provider');
			if (providerEl) {
				providerEl.addEventListener('change', toggleShippingProviderFields);
			}
			toggleShippingProviderFields();
		});
	})();
</script>
{/literal}
