<div class="container my-4">

	<!-- Admin Toolbar -->
	<div class="mb-3">
		{include file="core/admin_tool_bar.tpl"}
	</div>

	<div class="row justify-content-center">
		<div class="col-lg-12">

			<div class="card shadow-sm">
				<div class="card-header">
					<h5 class="mb-0">Payment Options</h5>
				</div>

				<div class="card-body">

					{if $error_msg != ""}
						<div class="alert alert-danger">
							{include file="core/error.tpl"}
						</div>
					{/if}

					{if $msg != ""}
						<div class="alert alert-success">
							{include file="core/msg.tpl"}
						</div>
					{/if}

					<!-- Payment Form -->
					<form method="POST" action="?page=control:payment_options">

						<!-- Billing Options -->
						<div class="mb-4">
							<h6 class="mb-3">Payment Method</h6>
							<div class="row g-3 mb-4">
								<div class="col-12 col-md-6">
									<label class="form-label" for="gateway">Payment gateway</label>
									<select class="form-select" id="gateway" name="gateway">
										<option value="" {if $gateway == ''}selected{/if}>None</option>
										<option value="cc_billing" {if $gateway == 'cc_billing'}selected{/if}>Credit Card (Authorize.Net)</option>
										<option value="stripe_billing" {if $gateway == 'stripe_billing'}selected{/if}>Stripe</option>
										<option value="paypal_billing" {if $gateway == 'paypal_billing'}selected{/if}>PayPal</option>
									</select>
									<div class="form-text">Select one gateway; its settings will appear below.</div>
								</div>
							</div>

							<h6 class="mb-3">Other Billing Options</h6>
							{section name=q loop=$arr}
								{if $arr[q].BILLING_OPTION != 'cc_billing' && $arr[q].BILLING_OPTION != 'stripe_billing' && $arr[q].BILLING_OPTION != 'paypal_billing'}
									<div class="form-check mb-2">
										<input class="form-check-input" type="checkbox" name="{$arr[q].BILLING_OPTION}" value="1" id="billing{$q}" {if $arr[q].ACTIVE == 1} checked {/if}>
										<label class="form-check-label" for="billing{$q}">
											<b>{$arr[q].BILLING_NAME}</b>
										</label>
									</div>
								{/if}
							{/section}
						</div>

						<!-- Authorize.Net Info -->
						<div class="mb-4" id="gateway-cc">
							<h6>Authorize.Net Information</h6>
								<p>
									If you are enabling credit card billing you must have an Authorize.Net account set up and enabled.
									To set up an Authorize.Net account <a href="https://www.authorize.net/sign-up/pricing.html" target="_blank" rel="noopener noreferrer">click here</a>. Your account information will be encrypted before being stored.
									No credit card information is stored in the Cite CRM system. For more information on billing profiles and setup please contact Authorize.Net.
								</p>

							{section name=w loop=$opts}
								<div class="mb-3">
									<label for="an_login" class="form-label"><b>Login:</b></label>
									<input type="text" class="form-control" id="an_login" name="an_login" value="{$opts[w].AN_LOGIN_ID}">
								</div>
								<div class="mb-3">
									<label for="an_password" class="form-label"><b>Password:</b></label>
									<input type="password" class="form-control" id="an_password" name="AN_PASSWORD">
								</div>
								<div class="mb-3">
									<label for="an_trans_key" class="form-label"><b>Transaction Key:</b></label>
									<input type="text" class="form-control" id="an_trans_key" name="AN_TRANS_KEY" value="{$opts[w].AN_TRANS_KEY}">
								</div>
							{/section}
						</div>

						<!-- Paypal Info -->
						<div class="mb-4" id="gateway-paypal">
							<h6>Paypal Information</h6>
							<p>
								You must have a Paypal Merchant account set and working. Please see
								<a href="https://www.paypal.com/" target="_blank">Paypal.com</a> for more information.
							</p>

							{section name=w loop=$opts}
								<div class="mb-3">
									<label for="pp_id" class="form-label"><b>Paypal Email:</b></label>
									<input type="text" class="form-control" id="pp_id" name="PP_ID" value="{$opts[w].PP_ID}">
								</div>
								<div class="form-check mb-3">
									<input class="form-check-input" type="checkbox" id="pp_sandbox" name="PP_SANDBOX" value="1" {if $opts[w].PP_SANDBOX == 1}checked{/if}>
									<label class="form-check-label" for="pp_sandbox">
										<b>Use PayPal Sandbox</b>
									</label>
								</div>
							{/section}
						</div>

						<!-- Stripe Info -->
						<div class="mb-4" id="gateway-stripe">
							<h6>Stripe Information</h6>
							<p>
								Enable Stripe to collect payment via Stripe Checkout. Keys are stored encrypted in the database.
							</p>

							{section name=w loop=$opts}
								<div class="mb-3">
									<label for="stripe_publishable" class="form-label"><b>Publishable Key:</b></label>
									<input type="text" class="form-control" id="stripe_publishable" name="STRIPE_PUBLISHABLE_KEY" value="{$opts[w].STRIPE_PUBLISHABLE_KEY|escape}">
								</div>
								<div class="mb-3">
									<label for="stripe_secret" class="form-label"><b>Secret Key:</b></label>
									<input type="password" class="form-control" id="stripe_secret" name="STRIPE_SECRET_KEY" value="{$opts[w].STRIPE_SECRET_KEY|escape}">
								</div>
								<div class="form-check mb-3">
									<input class="form-check-input" type="checkbox" id="stripe_test_mode" name="STRIPE_TEST_MODE" value="1" {if $opts[w].STRIPE_TEST_MODE == 1}checked{/if}>
									<label class="form-check-label" for="stripe_test_mode">
										<b>Use Stripe Test Mode</b>
									</label>
								</div>
							{/section}
						</div>

						<input name="submit"  value="Submit" type="submit" class="btn btn-primary">

					</form>
					<!-- End Payment Form -->

				</div>
			</div>

		</div>
	</div>

</div>

{literal}
<script>
	(function () {
		function toggleGatewaySections() {
			var sel = document.getElementById('gateway');
			var cc = document.getElementById('gateway-cc');
			var paypal = document.getElementById('gateway-paypal');
			var stripe = document.getElementById('gateway-stripe');
			if (!sel || !cc || !paypal || !stripe) return;

			var v = (sel.value || '').toLowerCase();
			cc.style.display = (v === 'cc_billing') ? '' : 'none';
			paypal.style.display = (v === 'paypal_billing') ? '' : 'none';
			stripe.style.display = (v === 'stripe_billing') ? '' : 'none';
		}

		document.addEventListener('DOMContentLoaded', function () {
			var sel = document.getElementById('gateway');
			if (sel) sel.addEventListener('change', toggleGatewaySections);
			toggleGatewaySections();
		});
	})();
</script>
{/literal}
