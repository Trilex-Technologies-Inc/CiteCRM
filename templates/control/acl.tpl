<div class="container my-4">

	<!-- Admin Toolbar -->
	<div class="mb-3">
		{include file="core/admin_tool_bar.tpl"}
	</div>

	<div class="card shadow-sm">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h5 class="mb-0">Welcome to your Online Office</h5>

				<i class="bi bi-question-circle-fill fs-5 text-secondary ms-2"
				   style="cursor:pointer;"
				   aria-hidden="true"
				   onMouseOver="ddrivetip('<b>Help Menu</b><hr><p></p>')"
				   onMouseOut="hideddrivetip()"></i>
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

			<form method="post" action="?page=control:acl">

				<div class="table-responsive">
					<table class="table table-bordered table-hover align-middle">
						<thead class="table-light">
						<tr>
							<th>Module : Page</th>
							<th class="text-center">Manager</th>
							<th class="text-center">Supervisor</th>
							<th class="text-center">Technician</th>
						</tr>
						</thead>
						<tbody>

						{section name=q loop=$acl}
							<tr>
								<td><strong>{$acl[q].page}</strong></td>

								<td class="text-center">
									<select name="{$acl[q].page}[Manager]"
											class="form-select form-select-sm">
										<option value="1" {if $acl[q].Manager == '1'}selected{/if}>Yes</option>
										<option value="0" {if $acl[q].Manager == '0'}selected{/if}>No</option>
									</select>
								</td>

								<td class="text-center">
									<select name="{$acl[q].page}[Supervisor]"
											class="form-select form-select-sm">
										<option value="1" {if $acl[q].Supervisor == '1'}selected{/if}>Yes</option>
										<option value="0" {if $acl[q].Supervisor == '0'}selected{/if}>No</option>
									</select>
								</td>

								<td class="text-center">
									<select name="{$acl[q].page}[Technician]"
											class="form-select form-select-sm">
										<option value="1" {if $acl[q].Technician == '1'}selected{/if}>Yes</option>
										<option value="0" {if $acl[q].Technician == '0'}selected{/if}>No</option>
									</select>
								</td>
							</tr>
						{/section}

						</tbody>
					</table>
				</div>

				<div class="mt-3">
					<input type="submit"
						   name="submit"
						   value="Save Permissions"
						   class="btn btn-primary">

				</div>

			</form>

		</div>
	</div>

</div>
