<!-- -->
<table class="olotable" width="100%" border="0" cellpadding="0" cellspacing="0" summary="Work order display">
	<tr>
		<td class="olohead">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="menuhead2" width="80%">&nbsp;{$translate_workorder_schedule_title}</td>
					<td class="menuhead2" width="20%" align="right">
						<table cellpadding="2" cellspacing="2" border="0">
							<tr>
								<td width="33%" align="center" class="button"><br>	</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>	
		</td>
	</tr><tr>
		<td class="menutd">
			<table width="100%" cellpadding="4" cellspacing="0">
				<tr>
					<td>
						{section name=e loop=$work_order_sched}
							<b>{$translate_workorder_start} </b>{$work_order_sched[e].SCHEDUAL_START|date_format:"%m-%d-%Y %I:%M  %p"} <b>- {$translate_workorder_end} </b> {$work_order_sched[e].SCHEDUAL_END|date_format:"%m-%d-%Y %I:%M  %p "} <br>
							<b>{$translate_workorder_notes}</b><br>
							{$work_order_sched[e].SCHEDUAL_NOTES}
						{sectionelse}
							<table class="olotablered" width="100%" border="0" cellpadding="5" cellspacing="0">
								<tr>
									<td>
										<span class="error_font">{$translate_workorder_warning}: </span> {$translate_workorder_msg_5}
									</td>
								</tr>
							</table>	
						{/section}
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
