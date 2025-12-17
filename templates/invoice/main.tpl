<!-- Invoice main.tpl -->
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
{if $error_msg != ""}
	{include file="core/error.tpl"}
{/if}
<table width="100%" border="0" cellpadding="20" cellspacing="0">
	<tr>
		<td class="olotd">
		
			<table width="100%" cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;Open Work Order: #{$single_workorder_array[i].WORK_ORDER_ID}
					<td class="menuhead2" width="20%" align="right" valign="middle">
						<img src="images/icons/16x16/help.gif" border="0"
						onMouseOver="ddrivetip('<b>Invoice</b><hr><p></p>')" onMouseOut="hideddrivetip()"></td>
					</td>
				</tr><tr>
					<td class="olotd5" colspan="2">
					</td>
				</tr>
			</table>
			
		</td>
	</tr>
</table>