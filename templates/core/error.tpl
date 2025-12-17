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

<table width="700" border="0" cellpadding="4" cellspacing="4">
	<tr>
		<td>
			<table class="olotablered" width="100%" border="0" cellpadding="5" cellspacing="5">
				<tr>
					<td valign="middle">
						<span class="error_font">{$type}</span> {$error_msg}
						<br>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

