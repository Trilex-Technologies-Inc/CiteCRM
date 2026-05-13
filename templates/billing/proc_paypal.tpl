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
			<div class="card mb-4 shadow-sm">
				<div class="card-header d-flex justify-content-between align-items-center">
					<strong>{$translate_billing_paypal}</strong>
					<a href="http://www.citecrm.com/docs/#billing" target="new" class="text-decoration-none">
						<i class="bi bi-question-circle-fill text-secondary" aria-hidden="true"></i>
					</a>
				</div>
				<div class="card-body">
					<div class="ratio ratio-16x9" style="min-height: 600px;">
						<iframe src="tmp.html" name="myspot" title="PayPal" class="border-0"></iframe>
					</div>
				</div>
			</div>

			<div class="card shadow-sm">
				<div class="card-header">
					<strong>{$translate_billing_results}</strong>
				</div>
				<div class="card-body">
					<p class="mb-3">{$translate_billing_paypal_note}</p>
					<form method="POST" action="?page=billing:pp_complete" class="row g-2 align-items-end">
						<div class="col-12 col-md-4">
							<label for="pp_invoice" class="form-label mb-1"><strong>{$translate_billing_invoice_id}</strong></label>
							<input type="text" id="pp_invoice" name="pp_invoice" class="form-control">
						</div>
						<input type="hidden" name="invoice_id" value="{$invoice_id}">
						<input type="hidden" name="wo_id" value="{$wo_id}">
						<div class="col-12 col-md-auto">
							<button type="submit" name="submit" value="Sucesses" class="btn btn-success">Success</button>
						</div>
						<div class="col-12 col-md-auto">
							<button type="submit" name="submit" value="Failed" class="btn btn-outline-danger">Failed</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
