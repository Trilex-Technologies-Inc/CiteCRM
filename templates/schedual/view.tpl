<!-- Schedule -->
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
<table width="100%" border="0" cellpadding="20" cellspacing="5">
	<tr>
		<td>
			{section name=a loop=$arr}
			<table width="700" cellpadding="4" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;{$arr[a].SCHEDUAL_START|date_format:"%m-%d-%y %r"} to {$arr[a].SCHEDUAL_END|date_format:"%m-%d-%y %r"}
					</td>
				</tr><tr>
					<td class="menutd2">
						<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td class="menutd">
									<table width="100%" celpadding="5" cellspacing="5">
										<tr>
											<td >
											<br>
											<b>{$translate_schedul_start} </b>{$arr[a].SCHEDUAL_START|date_format:"%m-%d-%y %r"} <b>{$translate_schedule_end} </b>{$arr[a].SCHEDUAL_END|date_format:"%m-%d-%y %r"}<br>
											{$arr[a].SCHEDUAL_NOTES}<br><br>
											<b>{$translate_schedule_tech}</b> {$arr[a].EMPLOYEE_DISPLAY_NAME}
											<br>
											<a href="?page=schedual:edit&sch_id={$arr[a].SCHEDUAL_ID}&y={$y}&m={$m}&d={$d}">{$translate_schedule_edit}</a> <a href="?page=schedual:delete&sch_id={$arr[a].SCHEDUAL_ID}&y={$y}&m={$m}&d={$d}">{$translate_schedule_delete}</a>
											{/section}
											
											</td>
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