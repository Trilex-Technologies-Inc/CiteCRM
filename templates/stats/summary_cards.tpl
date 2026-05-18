<div class="row g-3">

	<!-- Work Orders -->
	<div class="col-md-6 col-lg-3">
		<div class="card dash-stat-card dash-accent-primary h-100">
			<div class="card-body">
				<div class="d-flex align-items-center justify-content-between mb-1">
					<div class="dash-stat-title mb-0">Work Orders</div>
					<div class="dash-stat-icon"><i class="bi bi-clipboard-check" aria-hidden="true"></i></div>
				</div>
				<div class="stat-kv">
					<span>Open</span>
					<span class="stat-kv-value">{$month_open|default:0}</span>
				</div>
				<div class="stat-kv">
					<span>Closed</span>
					<span class="stat-kv-value">{$month_closed|default:0}</span>
				</div>
			</div>
		</div>
	</div>

	<!-- Customers -->
	<div class="col-md-6 col-lg-3">
		<div class="card dash-stat-card dash-accent-success h-100">
			<div class="card-body">
				<div class="d-flex align-items-center justify-content-between mb-1">
					<div class="dash-stat-title mb-0">Customers</div>
					<div class="dash-stat-icon"><i class="bi bi-people" aria-hidden="true"></i></div>
				</div>
				<div class="stat-kv">
					<span>New</span>
					<span class="stat-kv-value">{$new_customers|default:0}</span>
				</div>
				<div class="stat-kv">
					<span>Total</span>
					<span class="stat-kv-value">{$total_customers|default:0}</span>
				</div>
			</div>
		</div>
	</div>

	<!-- Invoices -->
	<div class="col-md-6 col-lg-3">
		<div class="card dash-stat-card dash-accent-info h-100">
			<div class="card-body">
				<div class="d-flex align-items-center justify-content-between mb-1">
					<div class="dash-stat-title mb-0">Invoices</div>
					<div class="dash-stat-icon"><i class="bi bi-receipt" aria-hidden="true"></i></div>
				</div>
				<div class="stat-kv">
					<span>Open</span>
					<span class="stat-kv-value">{$open_invoices|default:0}</span>
				</div>
				<div class="stat-kv">
					<span>Closed</span>
					<span class="stat-kv-value">{$closed_invoices|default:0}</span>
				</div>
			</div>
		</div>
	</div>

	<!-- Revenue -->
	<div class="col-md-6 col-lg-3">
		<div class="card dash-stat-card dash-accent-warning h-100">
			<div class="card-body">
				<div class="d-flex align-items-center justify-content-between mb-1">
					<div class="dash-stat-title mb-0">Revenue</div>
					<div class="dash-stat-icon"><i class="bi bi-cash-stack" aria-hidden="true"></i></div>
				</div>
				<div class="stat-kv">
					<span>Total</span>
					<span class="stat-kv-value">$ {$total_revenue|default:"0.00"}</span>
				</div>
				<div class="stat-kv">
					<span>Losses</span>
					<span class="stat-kv-value">$ {$total_losses|default:"0.00"}</span>
				</div>
			</div>
		</div>
	</div>

</div>
