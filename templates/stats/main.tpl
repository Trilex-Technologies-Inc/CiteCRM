<div class="container my-3">

	<!-- Admin Toolbar -->
	<div class="mb-3">
		{include file="core/admin_tool_bar.tpl"}
	</div>

	<div class="card shadow-sm">
		<div class="card-header d-flex justify-content-between align-items-center">
			<div>
				<strong>Stats for {$smarty.now|date_format:"%m-%d-%Y"}</strong>
			</div>
			<div>
				<img src="images/icons/16x16/help.gif" border="0"
					 onMouseOver="ddrivetip('<b>Monthly stats</b><hr><p></p>')"
					 onMouseOut="hideddrivetip()">
			</div>
		</div>

		<div class="card-body">
			<div class="row g-3">

				<!-- Work Orders -->
				<div class="col-md-6 col-lg-3">
					<div class="card h-100 border-0 bg-light">
						<div class="card-body">
							<h6 class="card-title fw-bold">Work Orders</h6>
							<div class="d-flex justify-content-between">
								<span>Open Work Orders:</span>
								<span>{$month_open}</span>
							</div>
							<div class="d-flex justify-content-between">
								<span>Closed Work Orders:</span>
								<span>{$month_closed}</span>
							</div>
						</div>
					</div>
				</div>

				<!-- Customers -->
				<div class="col-md-6 col-lg-3">
					<div class="card h-100 border-0 bg-light">
						<div class="card-body">
							<h6 class="card-title fw-bold">Customers</h6>
							<div class="d-flex justify-content-between">
								<span>New Customers:</span>
								<span>{$new_customers}</span>
							</div>
							<div class="d-flex justify-content-between">
								<span>Total Customers:</span>
								<span>{$total_customers}</span>
							</div>
						</div>
					</div>
				</div>

				<!-- Invoices -->
				<div class="col-md-6 col-lg-3">
					<div class="card h-100 border-0 bg-light">
						<div class="card-body">
							<h6 class="card-title fw-bold">Invoices</h6>
							<div class="d-flex justify-content-between">
								<span>Open Invoices:</span>
								<span>{$open_invoices}</span>
							</div>
							<div class="d-flex justify-content-between">
								<span>Closed Invoices:</span>
								<span>{$closed_invoices}</span>
							</div>
						</div>
					</div>
				</div>

				<!-- Revenue -->
				<div class="col-md-6 col-lg-3">
					<div class="card h-100 border-0 bg-light">
						<div class="card-body">
							<h6 class="card-title fw-bold">Revenue</h6>
							<div class="d-flex justify-content-between">
								<span>Total Revenue:</span>
								<span>${$total_revenue}</span>
							</div>
							<div class="d-flex justify-content-between">
								<span>Losses:</span>
								<span>${$total_losses}</span>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

</div>
