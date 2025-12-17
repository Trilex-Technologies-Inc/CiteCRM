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
<table width="100%" border="0" cellpadding="20" cellspacing="0">
	<tr>
		<td><!-- Begin Page -->

			<table width="700" 	cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2">&nbsp;Control Center</td>
					
				</tr><tr>
					<td class="menutd2">
					{if $error_msg != ""}
						{include file="core/error.tpl"}
					{/if}
					<table width="700" class="olotable" cellpadding="5" cellspacing="0" border="0" >
						<tr>
							<td class="menutd" valign="top" >
								<!-- Content Here -->
								Welcome to the Admin Section.<br>
								Select an option from the Drop Down Menu.
								<!-- End Content -->
							</td>
						</tr>
					</table>
				</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
	