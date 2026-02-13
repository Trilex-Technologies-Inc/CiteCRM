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
				<img src="images/icons/16x16/help.gif" border="0"
					 onMouseOver="ddrivetip('<b>Help Menu</b><hr><p></p>')"
					 onMouseOut="hideddrivetip()">
			</div>
		</div>

		<div class="card-body">

			{if $error_msg != ""}
				<div class="alert alert-danger">
					{include file="core/error.tpl"}
				</div>
			{/if}

			<div class="table-responsive">
				<table class="table table-striped table-hover align-middle">
					<thead class="table-secondary">
					<tr>
						<th>Time</th>
						<th>Page</th>
						<th>User Agent</th>
						<th>Referer</th>
					</tr>
					</thead>
					<tbody>
					{section name=i loop=$hits}
						<tr style="cursor:pointer;"
							ondblclick="window.location='index.php?page=stats:hit_stats_view&ip={$hits[i].ip}&page_title=Hits For {$hits[i].ip}';">
							<td>{$hits[i].date|date_format:" %H:%M:%S"}</td>

							<td class="text-truncate" style="max-width: 250px;">
								{$hits[i].full_page}
							</td>

							<td class="text-truncate" style="max-width: 300px;">
								{$hits[i].uagent}
							</td>

							<td class="text-truncate" style="max-width: 250px;">
								{$hits[i].referer}
							</td>
						</tr>
					{/section}
					</tbody>
				</table>
			</div>

		</div>
	</div>

</div>
