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
							{section name=q loop=$arr}
								<div class="form-check mb-2">
									<input class="form-check-input" type="checkbox" name="{$arr[q].BILLING_OPTION}" value="1" id="billing{$q}" {if $arr[q].ACTIVE == 1} checked {/if}>
									<label class="form-check-label" for="billing{$q}">
										<b>{$arr[q].BILLING_NAME}</b>
									</label>
								</div>
							{/section}
						</div>

						<!-- Authorize.Net Info -->
						<div class="mb-4">
							<h6>Authorize.Net Information</h6>
							<p>
								If you are enabling credit card billing you must have an Authorize.Net account set up and enabled.
								To set up an Authorize.Net account click here. Your account information will be encrypted before being stored.
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
						<div class="mb-4">
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
