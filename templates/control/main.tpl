<!-- Admin main --><!-- template name -->
<table  class="toolbar" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
		<td >
			<table  cellpadding="2" cellspacing="2">
				<tr>
				{include file="core/admin_tool_bar.tpl"}
				</tr>
			</table>
		</td>
	</tr>
</table>

<div class="card shadow-sm mb-3">
	<div class="card-header">
		Control Center
	</div>
	<div class="card-body">
		{if $error_msg != ""}
			{include file="core/error.tpl"}
		{/if}
		<p class="mb-1">
			Welcome to the Admin Section.
		</p>
		<p class="mb-0">
			Select an option from the Drop Down Menu.
		</p>
	</div>
</div>