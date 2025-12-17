<!-- template name -->
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
			<table width="700" cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;Office Hours</td>
				</tr><tr>
					<td class="menutd2" >
						<table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
						<tr>
							<td width="100%" valign="top" class="menutd">
							{if $error_msg != ""}
								{include file="core/error.tpl"}
							{/if}
							{if $msg !=""}
								{include file="core/msg.tpl"}
							{/if}
							
								<!-- Content Here -->
								<form method="POST" action="?page=control:hours_edit">
								{section name=a loop=$arr}
								<table >
									<tr>
										<td><b>Start Hour</b></td>
										<td align="left">
											{html_select_time use_24_hours=true display_minutes=false display_seconds=false prefix=start}
										</td>
									</tr><tr>
										<td><b>End Hour</b></td>
										<td>
											{html_select_time use_24_hours=true display_minutes=false display_seconds=false prefix=end}
										</td>
									</tr><tr>
										<td>
											<input type="submit" name="submit" value="Submit">
										</td>
									</tr>	
								</table>
								These settings are used to dispplay the start and stop times of the Schedual.
								Curent Start Hour = 	{$arr[a].OFFICE_HOUR_START} and current end hour = {$arr[a].OFFICE_HOUR_END}.
								{/section}	
								<!-- End Content -->
							</td>
						</tr>
					</table>
				</tr>
			</table>
		</td>
	</tr>
</table>
	