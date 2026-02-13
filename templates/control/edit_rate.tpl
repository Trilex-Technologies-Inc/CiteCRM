<div class="container my-4">

	<!-- Admin Toolbar -->
	<div class="mb-3">
		{include file="core/admin_tool_bar.tpl"}
	</div>

	<div class="row justify-content-center">
		<div class="col-lg-12">

			<div class="card shadow-sm">
				<div class="card-header">
					<h5 class="mb-0">Edit Billing Rates</h5>
				</div>

				<div class="card-body">

					<p>Labor Rates are Per Hour.</p>

					<!-- Existing Rates Table -->
					<div class="table-responsive mb-4">
						<table class="table table-bordered table-hover align-middle">
							<thead class="table-light">
							<tr>
								<th>ID</th>
								<th>Display</th>
								<th>Amount</th>
								<th>Active</th>
								<th>Action</th>
							</tr>
							</thead>
							<tbody>
							{section name=q loop=$rate}
								<form method="POST" action="?page=control:edit_rate">
									<tr>
										<td>{$rate[q].LABOR_RATE_ID}</td>
										<td>
											<input type="text" class="form-control form-control-sm" name="display" value="{$rate[q].LOABOR_RATE_NAME}">
										</td>
										<td>
											$<input type="text" class="form-control form-control-sm d-inline w-auto" name="amount" value="{$rate[q].LABOR_RATE_AMOUT}">
										</td>
										<td>
											<select class="form-select form-select-sm" name="active">
												<option value="0" {if $rate[q].LABOR_RATE_ACTIVE == 0} selected{/if}>No</option>
												<option value="1" {if $rate[q].LABOR_RATE_ACTIVE == 1} selected{/if}>Yes</option>
											</select>
										</td>
										<td>
											<input type="hidden" name="id" value="{$rate[q].LABOR_RATE_ID}">
											<button type="submit" name="submit" value="Edit" class="btn btn-sm btn-primary mb-1">Edit</button>
											<button type="submit" name="submit" value="Delete" class="btn btn-sm btn-danger mb-1">Delete</button>
										</td>
									</tr>
								</form>
							{/section}
							</tbody>
						</table>
					</div>

					<!-- Add New Rate -->
					<div class="card border-secondary mb-3">
						<div class="card-header">
							<h6 class="mb-0">Add New Rate</h6>
						</div>
						<div class="card-body">
							<form method="POST" action="?page=control:edit_rate">
								<div class="row mb-2">
									<div class="col">
										<label class="form-label">Display</label>
										<input type="text" class="form-control" name="display" placeholder="Rate Name">
									</div>
									<div class="col-auto">
										<label class="form-label">Amount</label>
										$<input type="text" class="form-control" name="amount" placeholder="0.00">
									</div>
								</div>
								<input type="submit" name="submit" value="New" class="btn btn-success" value="New">
							</form>
						</div>
					</div>

				</div>
			</div>

		</div>
	</div>

</div>
