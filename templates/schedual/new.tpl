<!-- New Schedual tpl -->
{literal}
<script language="javascript" type="text/javascript" src="include/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "specific_textareas",
		theme : "advanced",
		plugins : "advlink,iespell,insertdatetime,preview",
		theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,separator,forecolor,backcolor",
		theme_advanced_buttons2_add_before: "cut,copy,paste",
		theme_advanced_toolbar_location : "bottom",
		theme_advanced_toolbar_align : "center", 
		content_css : "themes/default/style.css",
	    plugin_insertdate_dateFormat : "%Y-%m-%d",
	    plugin_insertdate_timeFormat : "%H:%M:%S",
		extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
		external_link_list_url : "example_link_list.js",
		external_image_list_url : "example_image_list.js",
		flash_external_list_url : "example_flash_list.js",
		file_browser_callback : "fileBrowserCallBack",
		width : "100%"
	}); 

</script>
<link rel="stylesheet" type="text/css" media="all" href="include/jscalendar/calendar-blue.css" title="win2k-1" />
<script type="text/javascript" src="include/jscalendar/calendar_stripped.js"></script>
<script type="text/javascript" src="include/jscalendar/lang/calendar-english.js"></script>
<script type="text/javascript" src="include/jscalendar/calendar-setup_stripped.js"></script>
{/literal}

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

			
<table width="100%" border="0" cellpadding="20" cellspacing="5">
	<tr>
		<td>

			<table width="700" cellpadding="4" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;{$translate_schedule_new}
					
					</td>
					<td class="menuhead2" size="10%" align="right"><a href="http://www.citecrm.com/docs/#schedual" target="new"><img src="images/icons/16x16/help.gif" border="0"
						onMouseOver="ddrivetip('<b>Set Schedule</b>')" onMouseOut="hideddrivetip()"></a>
					</td>
			</tr><tr>
					<td class="menutd2" colspan="2">
						<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td class="menutd">
						{if $wo_id != '0'}
							<table class="olotablered" width="100%" border="0" cellpadding="5" cellspacing="0">
								<tr>
									<td>
										<span class="error_font">{$translate_schedule_info}: </span> {$translate_schedule_wo} {$wo_id}
									</td>
								</tr>
							</table>
							<br>
						{/if}
						<!-- Content -->
						<table class="menutable" width="100%" border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td>
								
									<form method = "POST" action="?page=schedual:new">
									
									<input type="hidden" name="page" value="schedual:new">
									<input type="hidden" name="tech" value="{$tech}">
									<input type="hidden" name="wo_id" value="0">
									<table class="olotable" width="100%" border="0" summary="Work order display">
										<tr>
											<td class="olohead">{$translate_schedule_set}</td>
										</tr><tr>
											<td class="olotd">
												<table width="100%" cellpadding="5" cellspacing="5">
													<tr>
														<td><b>{$translate_schedul_start}</b></td>
														<td><b>{$translate_schedule_end}</b></td>
													</tr><tr>
															<td>
	
															<input size="10" name="start[schedual_date]" type="text" id="schedual_date" value="{$start_day}"/>
															<input type="button" id="trigger_schedual_date" value="+">
															{literal}
															<script type="text/javascript">
																Calendar.setup(
																	{
																		inputField  : "schedual_date",
																		ifFormat    : "%m/%d/%Y",
																		button      : "trigger_schedual_date"
																	}
																);
															</script>
															{/literal}
															{html_select_time use_24_hours=false display_seconds=false minute_interval=15 field_array=start time=$start_time}
															</td>
															<td>
														
															 
															
															<input size="10" name="end[schedual_date]" type="text" id="end_schedual_date" value="{$start_day}">
															<input type="button" id="trigger_end_schedual_date" value="+">
															{literal}
															<script type="text/javascript">
																Calendar.setup(
																	{
																		inputField  : "end_schedual_date",
																		ifFormat    : "%m/%d/%Y",
																		button      : "trigger_end_schedual_date"
																	}
																);
															</script>
															{/literal}
															{html_select_time use_24_hours=false display_seconds=false minute_interval=15 field_array=end time=$start_time}
															
														</td>
													</tr><tr>
														<td colspan="2">
															<b>{$translate_schedule_notes}</b><br>
															<textarea name="schedaul_notes" rows="15" cols="70" mce_editable="true">{$schedaul_notes}</textarea>
															
														</td>
													</tr><tr>
														<td colspan="2">
															<input type="hidden" name="wo_id" value="{$wo_id}">
															<input type="submit" name="submit" value="{$translate_schedule_submit}">	
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<br>
									
									</form>
									<br>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>