<!-- Core Error -->
{if $menu == '1'}
	<div class="container-fluid mb-3">
		{include file="core/tool_bar.tpl"}
	</div>
{/if}

<div class="alert alert-danger" role="alert">
	<strong>{$type}</strong>
	{$error_msg}
</div>
