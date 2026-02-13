<div class="container my-4">

	<!-- Admin Toolbar -->
	<div class="mb-3">
		{include file="core/admin_tool_bar.tpl"}
	</div>

	<div class="row justify-content-center">
		<div class="col-lg-12">

			<div class="card shadow-sm">
				<div class="card-header">
					<h5 class="mb-0">Office Hours</h5>
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

					<form method="POST" action="?page=control:hours_edit">

						{section name=a loop=$arr}

							<div class="mb-3">
								<label class="form-label fw-bold">Start Hour</label>
								<div>
									{html_select_time
									use_24_hours=true
									display_minutes=false
									display_seconds=false
									all_extra='class="form-select d-inline w-auto"'
									prefix=start}
								</div>
							</div>

							<div class="mb-3">
								<label class="form-label fw-bold">End Hour</label>
								<div>
									{html_select_time
									use_24_hours=true
									display_minutes=false
									display_seconds=false
									all_extra='class="form-select d-inline w-auto"'
									prefix=end}
								</div>
							</div>

							<div class="mb-3">
								<input type="submit"
										name="submit"
										class="btn btn-primary"
									value="Submit" >

							</div>

							<div class="alert alert-info mt-4">
								These settings are used to display the start and stop times of the schedule.
								<br><br>
								<strong>Current Start Hour:</strong> {$arr[a].OFFICE_HOUR_START}
								<br>
								<strong>Current End Hour:</strong> {$arr[a].OFFICE_HOUR_END}
							</div>

						{/section}

					</form>

				</div>
			</div>

		</div>
	</div>

</div>
