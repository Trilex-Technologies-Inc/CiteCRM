						<!-- begin comapany.tpl -->
			<table width="220" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="81"   class="bg1">
						<div id="calendar-container"></div>
							<link rel="stylesheet" type="text/css" media="all" href="include/jscalendar/calendar-blue.css" title="win2k-1" />
								<script type="text/javascript" src="include/jscalendar/calendar_stripped.js"></script>
								<script type="text/javascript" src="include/jscalendar/lang/calendar-english.js"></script>
								<script type="text/javascript" src="include/jscalendar/calendar-setup_stripped.js"></script>
						{literal}
    							<script type="text/javascript">
								function dateChanged(calendar) {
    									// Beware that this function is called even if the end-user only
   										 // changed the month/year.  In order to determine if a date was
										// clicked you can use the dateClicked property of the calendar:
											if (calendar.dateClicked) {
											// OK, a date was clicked, redirect to /yyyy/mm/dd/index.php
											var y = calendar.date.getFullYear();
											var M = calendar.date.getMonth();  
											var m = M + 1;   // integer, 0..11
											var d = calendar.date.getDate();      // integer, 1..31
											// redirect...
											window.location =  "?page=schedual:main&y="+y+"&m="+m+"&d="+ d+"&wo_id={/literal}{$wo_id}{literal}&page_title={/literal}{$translate_core_schedule}{literal}";
											}
								};
				 				Calendar.setup(
										{
												 flat         : "calendar-container",
					 						 	flatCallback : dateChanged
											}
				 			 		);
							</script>
						{/literal}
						<center>{$translate_core_click_scheudle}</center>
					</td>
				</tr>
			</table>
			<br>
						<!-- End company.tpl -->
		