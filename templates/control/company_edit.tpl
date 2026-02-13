<div class="container my-4">

	<!-- Admin Toolbar -->
	<div class="mb-3">
		{include file="core/admin_tool_bar.tpl"}
	</div>

	<div class="card shadow-sm">
		<div class="card-header">
			<h5 class="mb-0">Edit The Company Information</h5>
		</div>

		<div class="card-body">

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

			{section name=q loop=$company}
				<form method="POST" action="?page=control:company_edit">

					<div class="row g-3">

						<!-- Company Info -->
						<div class="col-md-6">
							<label class="form-label">Company Display Name</label>
							<input type="text" class="form-control"
								   name="company_name"
								   value="{$company[q].COMPANY_NAME}">
						</div>

						<div class="col-md-6">
							<label class="form-label">Phone</label>
							<input type="text" class="form-control"
								   name="phone"
								   value="{$company[q].COMPNAY_PHONE}">
						</div>

						<div class="col-md-6">
							<label class="form-label">Mobile Phone</label>
							<input type="text" class="form-control"
								   name="mobile_phone"
								   value="{$company[q].COMPNAY_MOBILE}">
						</div>

						<div class="col-md-6">
							<label class="form-label">Toll Free</label>
							<input type="text" class="form-control"
								   name="toll_free"
								   value="{$company[q].COMPANY_TOLL_FREE}">
						</div>

						<div class="col-12">
							<label class="form-label">Address</label>
							<input type="text" class="form-control"
								   name="address"
								   value="{$company[q].COMPANY_ADDRESS}">
						</div>

						<div class="col-md-4">
							<label class="form-label">City</label>
							<input type="text" class="form-control"
								   name="city"
								   value="{$company[q].COMPANY_CITY}">
						</div>

						<div class="col-md-4">
							<label class="form-label">State</label>
							<input type="text" class="form-control"
								   name="state"
								   value="{$company[q].COMPANY_STATE}">
						</div>

						<div class="col-md-4">
							<label class="form-label">Zip</label>
							<input type="text" class="form-control"
								   name="zip"
								   value="{$company[q].COMPANY_ZIP}">
						</div>

						<div class="col-md-6">
							<label class="form-label">Country</label>
							<select name="country" class="form-select">
								{section name=c loop=$country}
									<option value="{$country[c].code}"
											{if $company[q].COMPANY_COUNTRY == $country[c].code}selected{/if}>
										{$country[c].name}
									</option>
								{/section}
							</select>
						</div>

					</div>

					<hr class="my-4">

					{section name=w loop=$setup}

						<!-- Invoice Settings -->
						<h6>Invoice Settings</h6>

						<div class="form-check mb-3">
							<input class="form-check-input" type="checkbox"
								   name="pdf_print" value="1"
								   {if $setup[w].PDF_PRINT == 1}checked{/if}>
							<label class="form-check-label">Enable PDF Printing</label>
						</div>

						<div class="mb-3">
							<label class="form-label">Tax Amount (%)</label>
							<input type="text" name="inv_tax"
								   class="form-control w-25"
								   value="{$setup[w].INVOCIE_TAX}">
						</div>

						<div class="mb-3">
							<label class="form-label">Invoice Thank You Note</label>
							<textarea class="form-control"
									  rows="3"
									  maxlength="255"
									  name="inv_thank_you">{$setup[w].INV_THANK_YOU}</textarea>
						</div>

						<div class="mb-3">
							<label class="form-label">Company Welcome Note</label>
							<textarea class="form-control"
									  rows="3"
									  name="welcome">{$setup[w].WELCOME_NOTE}</textarea>
						</div>

						<hr class="my-4">

						<!-- Parts Settings -->
						<h6>Parts Ordering Settings</h6>

						<div class="row g-3">

							<div class="col-md-6">
								<label class="form-label">Parts Login</label>
								<input type="text" class="form-control"
									   name="parts_login"
									   value="{$setup[w].PARTS_LOGIN}">
							</div>

							<div class="col-md-6">
								<label class="form-label">Parts Password</label>
								<input type="password" class="form-control"
									   name="parts_password">
							</div>

							<div class="col-md-6">
								<label class="form-label">Parts Markup (%)</label>
								<input type="text" class="form-control"
									   name="parts_markup"
									   value="{$setup[w].PARTS_MARKUP}">
							</div>

						</div>

						<hr class="my-4">

						<!-- UPS Settings -->
						<h6>UPS Shipping Settings</h6>

						<div class="row g-3">

							<div class="col-md-6">
								<label class="form-label">UPS Login</label>
								<input type="text" class="form-control"
									   name="ups_login"
									   value="{$setup[w].UPS_LOGIN}">
							</div>

							<div class="col-md-6">
								<label class="form-label">UPS Password</label>
								<input type="password" class="form-control"
									   name="ups_password">
							</div>

							<div class="col-md-6">
								<label class="form-label">UPS Access Key</label>
								<input type="text" class="form-control"
									   name="ups_access_key"
									   value="{$setup[w].UPS_ACCESS_KEY}">
							</div>

						</div>

					{/section}

					<div class="mt-4">
						<input value="Update Company Information" name="submit" type="submit" class="btn btn-primary">

					</div>

				</form>
			{/section}

		</div>
	</div>

</div>
