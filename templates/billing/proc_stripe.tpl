<!-- Toolbar -->
<div class="container-fluid mb-3">
	{include file="core/tool_bar.tpl"}
</div>
{if $error_msg != ""}
	{include file="core/error.tpl"}
{/if}

<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-12 col-xxl-10">
			{if $email_to != ''}
				<div class="alert {if $email_sent == 1}alert-success{else}alert-warning{/if} mb-4" role="alert">
					{if $email_sent == 1}
						Stripe payment link emailed to <strong>{$email_to|escape}</strong>.
					{else}
						Could not email Stripe payment link to <strong>{$email_to|escape}</strong>. Please copy the link below.
					{/if}
					{if $stripe_url != ''}
						<div class="mt-2">
							<input type="text" class="form-control" value="{$stripe_url|escape}" readonly onclick="this.select();">
						</div>
					{/if}
				</div>
			{/if}

			<div class="card shadow-sm">
				<div class="card-header d-flex justify-content-between align-items-center">
					<strong>{$translate_billing_stripe}</strong>
					<a href="http://www.citecrm.com/docs/#billing" target="new" class="text-decoration-none">
						<i class="bi bi-question-circle-fill text-secondary" aria-hidden="true"></i>
					</a>
				</div>
				<div class="card-body">
					{if $stripe_url != ''}
						<a class="btn btn-primary" href="{$stripe_url|escape}" target="_blank" rel="noopener">Open Stripe payment page</a>
					{/if}
					<p class="mt-3 mb-0 text-muted">After payment completes, the invoice will be updated when the customer returns via the Stripe redirect.</p>
				</div>
			</div>
		</div>
	</div>
</div>

