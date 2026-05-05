<div class="container my-4">

	<div class="mb-3">
		{include file="core/admin_tool_bar.tpl"}
	</div>

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

	<div class="card shadow-sm mb-4">
		<div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
			<div>
				<i class="bi bi-people-fill me-2 text-secondary"></i> Roles
			</div>
			<a class="btn btn-sm btn-outline-secondary" href="?page=control:acl&page_title=Permissions">
				<i class="bi bi-shield-lock"></i> Permissions
			</a>
		</div>
		<div class="card-body">
			<form method="post" action="?page=control:roles" class="row g-2 align-items-end">
				<div class="col-sm-6 col-lg-4">
					<label class="form-label">New role name</label>
					<input type="text" name="type_name" class="form-control" placeholder="e.g. Sales" required>
					
				</div>
				<div class="col-auto">
					<input type="submit" name="submit" value="New" class="btn btn-primary">
				</div>
			</form>
		</div>
	</div>

	<div class="card shadow-sm">
		<div class="card-header bg-white fw-semibold">
			Existing roles
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover align-middle">
					<thead class="table-light">
						<tr>
							<th style="width: 90px;">ID</th>
							<th>Role Name</th>
                            <!--
							<th class="text-center" style="width: 160px;">Employees</th>
                            -->
							<th class="text-center" style="width: 260px;">Actions</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$roles item=r}
							<tr>
								<td class="text-muted">{$r.TYPE_ID}</td>
								<td>
									<form method="post" action="?page=control:roles" class="d-flex gap-2">
										<input type="hidden" name="type_id" value="{$r.TYPE_ID}">
										<input type="text"
											   name="type_name"
											   class="form-control form-control-sm"
											   value="{$r.TYPE_NAME|escape}"
											   {if $r.TYPE_NAME == 'Admin'}readonly{/if}>
                                               <!--
										<button type="submit"
												name="submit"
												value="Edit"
												class="btn btn-sm btn-outline-primary"
												{if $r.TYPE_NAME == 'Admin'}disabled{/if}>
											<i class="bi bi-save"></i>
										</button>
                                        -->
									</form>
								</td>
                                <!--
								<td class="text-center">
									<span class="badge text-bg-secondary">{$r.EMPLOYEE_COUNT}</span>
								</td>
                                -->
								<td class="text-center">
									<a class="btn btn-sm btn-outline-secondary"
									   href="?page=control:acl&page_title=Permissions&role={$r.TYPE_NAME|escape:'url'}">
										<i class="bi bi-shield-lock"></i> Permissions
									</a>

									<form method="post"
										  action="?page=control:roles"
										  class="d-inline">
										<input type="hidden" name="type_id" value="{$r.TYPE_ID}">
										<button type="submit"
												name="submit"
												value="Delete"
												class="btn btn-sm btn-outline-danger"
												onclick="return confirm('Delete role {$r.TYPE_NAME|escape:'javascript'}?');"
												{if $r.TYPE_NAME == 'Admin' || $r.EMPLOYEE_COUNT > 0}disabled{/if}>
											<i class="bi bi-trash"></i> Delete
										</button>
									</form>
								</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
			
		</div>
	</div>
</div>

