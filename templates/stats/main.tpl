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

<table width="100%" border="0" cellpadding="20" cellspacing="5">
	<tr>
		<td class="olotd">

			<table width="100%" cellpadding="4" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;Stats for {$smarty.now|date_format:"%m-%d-%Y"}</td>
					<td class="menuhead2" width="20%" align="right" valign="middle">
						<img src="images/icons/16x16/help.gif" border="0"
						onMouseOver="ddrivetip('<b>Monthly stats</b><hr><p></p>')" 
						onMouseOut="hideddrivetip()">
					</td>
				</tr><tr>
					<td class="olotd5" colspan="2">
					<table class="olotable"  border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td class="olohead">Work Orders</td>
								<td class="olohead">Customers</td>
								<td class="olohead">Invoices</td>
								<td class="olohead">Revenue</td>
							</tr><tr>
								<td class="olotd4" valign="top">
									<table >
										<tr>
											<td ><b>Open Work Orders: </b></td>
											<td >{$month_open}</td>
										</tr>
											<td><b>Closed Work Orders:</b></td>
											<td></td>
									</table>
								</td>
								<td class="olotd4" valign="top">
									<table >
										<tr>
											<td ><b>New Customers:</b></td>
											<td></td>
										</tr><tr>
											<td><b>Total Customers:</b></td>
										</tr>
									</table>
								
								</td>
								<td class="olotd4" valign="top">
									<table >
										<tr>
											<td><b>Open Invoices:</b></td>
											<td></td>
										</tr><tr>
											<td><b>Closed Invoices:</b></td>
											<td></td>
										</tr>
									</table>
								</td>
								<td class="olotd4" valign="top">
									<table >
										<tr>
											<td ><b>Total Revenue:</b></td>
											<td></td>
										</tr><tr>
											<td><b>Losses:</b></td>
											<td></td>
										</tr>
									</table>
								</td>
								
							</tr>
						</table>
					
					</td>
				</tr>
			</table>
		
		</td>
	</tr>
</table>