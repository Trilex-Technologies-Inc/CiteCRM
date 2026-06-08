<div class="container my-3">

	<!-- Admin Toolbar -->
	<div class="mb-3">
		{include file="core/admin_tool_bar.tpl"}
	</div>

	<div class="card shadow-sm">

		<!-- Header -->
		<div class="card-header d-flex justify-content-between align-items-center">
			<div>
				<strong>Stats for {$smarty.now|date_format:"%m-%d-%Y"}</strong>
			</div>
				<div>
					<i class="bi bi-question-circle-fill fs-5 text-secondary"
					   aria-hidden="true"
					   onMouseOver="ddrivetip('<b>Help Menu</b><hr><p></p>')"
					   onMouseOut="hideddrivetip()"></i>
				</div>
		</div>

		<div class="card-body">

			{if $error_msg != ""}
				<div class="alert alert-danger">
					{include file="core/error.tpl"}
				</div>
			{/if}

			<!-- Summary Stats -->
			<div class="row mb-4 g-3">
				<div class="col-md-4">
					<div class="card bg-light border-0 h-100">
						<div class="card-body text-center">
							<h6 class="fw-bold">Daily Hits</h6>
							<h4>{$daily_total}</h4>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="card bg-light border-0 h-100">
						<div class="card-body text-center">
							<h6 class="fw-bold">Daily Visits</h6>
							<h4>{$daily_visits}</h4>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="card bg-light border-0 h-100">
						<div class="card-body text-center">
							<h6 class="fw-bold">Monthly Hits</h6>
							<h4>{$month_hit}</h4>
						</div>
					</div>
				</div>
			</div>

			<!-- Detailed Hit Table -->
			<div class="table-responsive">
				<table class="table table-striped table-hover align-middle">
					<thead class="table-secondary">
					<tr>
						<th>Time</th>
						<th>IP Address</th>
						<th>User Agent</th>
						<th>Hits</th>
					</tr>
					</thead>
					<tbody>
					{section name=i loop=$hit}
						<tr style="cursor:pointer;"
							ondblclick="window.location='index.php?page=stats:hit_stats_view&ip={$hit[i].ip}&page_title=Hits For {$hit[i].ip}';">
							<td>{$hit[i].date|date_format:" %H:%M:%S"}</td>
							<td>{$hit[i].ip}</td>
							<td class="text-truncate" style="max-width: 300px;">
								{$hit[i].uagent}
							</td>
							<td>{$hit[i].count}</td>
						</tr>
					{/section}
					</tbody>
				</table>
			</div>

		</div>
	</div>

</div>
