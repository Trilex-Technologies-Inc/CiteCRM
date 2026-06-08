<div class="row g-3">

	<!-- Work Orders -->
	<div class="col-md-6 col-lg-3">
		<div class="card dash-stat-card dash-accent-primary h-100">
			<div class="card-body">
				<a class="d-flex align-items-center justify-content-between mb-1 text-decoration-none text-reset"
				   href="?page=workorder:main&page_title={$translate_menu_work_orders|default:"Work Orders"}"
				   aria-label="Work Orders">
					<div class="dash-stat-title mb-0">Work Orders</div>
					<div class="dash-stat-icon"><i class="bi bi-clipboard-check" aria-hidden="true"></i></div>
				</a>
				<a class="stat-kv text-decoration-none text-reset" href="?page=workorder:main&page_title={$translate_menu_work_orders|default:"Work Orders"}" aria-label="Open Work Orders">
					<span>Open</span>
					<span class="stat-kv-value">{$month_open|default:0}</span>
				</a>
				<a class="stat-kv text-decoration-none text-reset" href="?page=workorder:view_closed&page_title={$translate_menu_closed_work_orders|default:"Closed Work Orders"}" aria-label="Closed Work Orders">
					<span>Closed</span>
					<span class="stat-kv-value">{$month_closed|default:0}</span>
				</a>
			</div>
		</div>
	</div>

	<!-- Customers -->
	<div class="col-md-6 col-lg-3">
		<div class="card dash-stat-card dash-accent-success h-100">
			<div class="card-body">
				<a class="d-flex align-items-center justify-content-between mb-1 text-decoration-none text-reset"
				   href="?page=customer:view&page_title={$translate_menu_customer_search|default:$translate_menu_customers|default:"Customers"}"
				   aria-label="Customers">
					<div class="dash-stat-title mb-0">Customers</div>
					<div class="dash-stat-icon"><i class="bi bi-people" aria-hidden="true"></i></div>
				</a>
				<a class="stat-kv text-decoration-none text-reset" href="?page=customer:view&page_title={$translate_menu_customer_search|default:$translate_menu_customers|default:"Customers"}" aria-label="New Customers">
					<span>New</span>
					<span class="stat-kv-value">{$new_customers|default:0}</span>
				</a>
				<a class="stat-kv text-decoration-none text-reset" href="?page=customer:view&page_title={$translate_menu_customer_search|default:$translate_menu_customers|default:"Customers"}" aria-label="Total Customers">
					<span>Total</span>
					<span class="stat-kv-value">{$total_customers|default:0}</span>
				</a>
			</div>
		</div>
	</div>

	<!-- Invoices -->
	<div class="col-md-6 col-lg-3">
		<div class="card dash-stat-card dash-accent-info h-100">
			<div class="card-body">
				<a class="d-flex align-items-center justify-content-between mb-1 text-decoration-none text-reset"
				   href="?page=invoice:view_unpaid&page_title={$translate_menu_un_paid_2|default:"Unpaid Invoices"}"
				   aria-label="Invoices">
					<div class="dash-stat-title mb-0">Invoices</div>
					<div class="dash-stat-icon"><i class="bi bi-receipt" aria-hidden="true"></i></div>
				</a>
				<a class="stat-kv text-decoration-none text-reset" href="?page=invoice:view_unpaid&page_title={$translate_menu_un_paid_2|default:"Unpaid Invoices"}" aria-label="Open Invoices">
					<span>Open</span>
					<span class="stat-kv-value">{$open_invoices|default:0}</span>
				</a>
				<a class="stat-kv text-decoration-none text-reset" href="?page=invoice:view_paid&page_title={$translate_menu_paid_2|default:"Paid Invoices"}" aria-label="Closed Invoices">
					<span>Closed</span>
					<span class="stat-kv-value">{$closed_invoices|default:0}</span>
				</a>
			</div>
		</div>
	</div>

	<!-- Revenue -->
	<div class="col-md-6 col-lg-3">
		<div class="card dash-stat-card dash-accent-warning h-100">
			<div class="card-body">
				<a class="d-flex align-items-center justify-content-between mb-1 text-decoration-none text-reset"
				   href="#"
				   aria-label="Revenue">
					<div class="dash-stat-title mb-0">Revenue</div>
					<div class="dash-stat-icon"><i class="bi bi-cash-stack" aria-hidden="true"></i></div>
				</a>
				<a class="stat-kv text-decoration-none text-reset" href="#" aria-label="Total Revenue">
					<span>Total</span>
					<span class="stat-kv-value">$ {$total_revenue|default:"0.00"}</span>
				</a>
				<a class="stat-kv text-decoration-none text-reset" href="#" aria-label="Total Losses">
					<span>Losses</span>
					<span class="stat-kv-value">$ {$total_losses|default:"0.00"}</span>
				</a>
			</div>
		</div>
	</div>

</div>
