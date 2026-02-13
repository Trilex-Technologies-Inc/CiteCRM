<div class="container my-4">

	<!-- Admin Toolbar -->
	<div class="mb-3">
		{include file="core/admin_tool_bar.tpl"}
	</div>

	<div class="row justify-content-center">
		<div class="col-lg-8">

			<div class="card shadow-sm">
				<div class="card-header">
					<h5 class="mb-0">Update Status</h5>
				</div>

				<div class="card-body">

					{if $error_msg != ""}
						<div class="alert alert-danger">
							{include file="core/error.tpl"}
						</div>
					{/if}

					{if $status == 1}

						<div class="alert alert-warning">
							<strong>Updates are available.</strong>
						</div>

						<p>
							Please download
							<a href="{$file}" class="fw-semibold">
								{$file}
							</a>
							and place it in the top directory of your Cite CRM install.
						</p>

						<p>
							Once you unpack the file read the <strong>README</strong>
							and <strong>INSTALL</strong> files for further instructions.
						</p>

						<hr>

						<h6>Additional Information</h6>

						<ul class="list-group mb-3">
							<li class="list-group-item">
								<strong>Update Version:</strong> {$cur_version}
							</li>
							<li class="list-group-item">
								<strong>Date:</strong> {$date}
							</li>
							<li class="list-group-item">
								<strong>File:</strong> {$file}
							</li>
						</ul>

						<div>
							{$message}
						</div>

					{else}

						<div class="alert alert-success text-center">
							<strong>No Updates Available</strong>
						</div>

					{/if}

				</div>
			</div>

		</div>
	</div>

</div>
