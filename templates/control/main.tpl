<div class="container my-4">

<div class="mb-3">
	{include file="core/admin_tool_bar.tpl"}
</div>

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
</div>