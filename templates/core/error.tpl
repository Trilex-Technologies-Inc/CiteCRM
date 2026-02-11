<!-- Core Error -->
{if $menu == '1'}
	<table  class="toolbar" border="0" cellpadding="0" cellspacing="0" width="700">
    	<tr>
	        <td >
				<table  cellpadding="2" cellspacing="2">
					<tr>
						{include file="core/tool_bar.tpl"}
					</tr>
				</table>
			</td>
		</tr>
	</table>
{/if}

<div class="alert alert-danger" role="alert">
	<strong>{$type}</strong>
	{$error_msg}
</div>

