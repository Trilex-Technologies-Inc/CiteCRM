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

			<div class="d-flex flex-wrap gap-2 align-items-center mb-3">
				<a class="btn btn-sm btn-outline-secondary" href="?page=control:roles&page_title=Roles">
					<i class="bi bi-people-fill"></i> Roles
				</a>

				<form class="d-flex gap-2 m-0" method="get" action="">
					<input type="hidden" name="page" value="control:acl">
					<input type="hidden" name="page_title" value="Permissions">
					<select name="role" class="form-select form-select-sm" style="max-width: 220px;" onchange="this.form.submit()">
						<option value="">All roles</option>
						{foreach from=$roles item=r}
							{if $r.TYPE_NAME != 'Admin'}
								<option value="{$r.TYPE_NAME|escape:'html'}" {if $role_filter == $r.TYPE_NAME}selected{/if}>
									{$r.TYPE_NAME}
								</option>
							{/if}
						{/foreach}
					</select>
				</form>

				{if $role_filter != ''}
					<div class="text-muted small">Showing permissions for <strong>{$role_filter|escape}</strong>.</div>
				{/if}
			</div>

			<form method="post" action="?page=control:acl">
				<input type="hidden" name="page_title" value="Permissions">
				{if $role_filter != ''}
					<input type="hidden" name="role" value="{$role_filter|escape:'html'}">
				{/if}

				<div class="table-responsive">
					<table class="table table-bordered table-hover align-middle">
						<thead class="table-light">
						<tr>
							<th>Module : Page</th>
							{foreach from=$roles item=r}
								{if $r.TYPE_NAME != 'Admin' && ($role_filter == '' || $role_filter == $r.TYPE_NAME)}
									<th class="text-center">{$r.TYPE_NAME}</th>
								{/if}
							{/foreach}
						</tr>
						</thead>
						<tbody>

						{section name=q loop=$acl}
							<tr>
								<td><strong>{$acl[q].page}</strong></td>

								{foreach from=$roles item=r}
									{if $r.TYPE_NAME != 'Admin' && ($role_filter == '' || $role_filter == $r.TYPE_NAME)}
										<td class="text-center">
											<select name="{$acl[q].page}[{$r.TYPE_NAME}]"
													class="form-select form-select-sm">
												<option value="1" {if $acl[q][$r.TYPE_NAME] == '1'}selected{/if}>Yes</option>
												<option value="0" {if $acl[q][$r.TYPE_NAME] == '0'}selected{/if}>No</option>
											</select>
										</td>
									{/if}
								{/foreach}
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
