<!-- Core Error -->
{if $menu == '1'}
	<table  class="toolbar" border="0" cellpadding="0" cellspacing="0" width="100%">
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

<div class="alert alert-success" role="alert">
	{$msg}
</div>

