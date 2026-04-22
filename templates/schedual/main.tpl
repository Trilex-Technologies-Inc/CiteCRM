<!-- main.tpl -->
{literal}
<script language="JavaScript">
        function go()
        {
                box = document.forms[0].page_no;
                destination = box.options[box.selectedIndex].value;
                if (destination) location.href = destination;
        }
        </script>
{/literal}

<!-- Toolbar -->
<div class="container-fluid mb-3">
	{include file="core/tool_bar.tpl"}
</div>
<table width="100%" border="0" cellpadding="20" cellspacing="5">
	<tr>
		<td>

			<table width="700" cellpadding="4" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;{$translate_schedule_view} {$cur_date}</td>
					<td class="menuhead2" width="20%" align="right" valign="middle">
						<a href="http://www.citecrm.com/docs/#schedual" target="new"><img src="images/icons/16x16/help.gif" border="0"
						onMouseOver="ddrivetip('<b>Schedule</b><hr><p>To set a schedule click on the table row with time in the right column. To view a schedule event click on the schedule title. <br>If you whish to set a schedule for a work order go to the work order details then click on the day you whish to set the schedule.<br>If you do not want to set a schedule for a work order and the info box is showing you a work order. Click on the Home option in the menu and then click on the day you whish to set the schedule. This will unset the Work Order ID.</p>')" 
						onMouseOut="hideddrivetip()"></a>
					</td>
				</tr><tr>
					<td class="menutd2" colspan="2">
						<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td class="menutd">
						{if $error_msg != ""}
							<br>
							{include file="core/error.tpl"}
							<br>
						{/if}
						{if $wo_id != '0'}
						<table class="olotablered" width="100%" border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td>
									<span class="error_font">{$translate_schedule_info} </span> {$translate_schedule_msg_1} {$wo_id} {$translate_schedule_msg_2}
								</td>
							</tr>
						</table>
					<br>
					{/if}
						<!-- Content -->
						<table width="100%" cellpadding="4" cellspacing="0" border="0">
							<tr>
								<td valign="top" align="left" valign="middle"><a href="?page=schedual:print&tech={$selected}&y={$y}&m={$m}&d={$d}&escape=1" target=new">{$translate_schedule_print}</a></td>
								<td valign="top" align="right" valign="middle"><form>
								{$translate_schedule_msg_3}&nbsp;
								
								<select name="page_no" onChange="go()">
								{section name=i loop=$tech}
									<option value="?page=schedual:main&tech={$tech[i].EMPLOYEE_ID}
										{foreach from=$date_array key=key item=item}
											&{$key}={$item}
										{/foreach}
										&page_title=Schedual" {if $selected == $tech[i].EMPLOYEE_ID} Selected {/if}>
										{$tech[i].EMPLOYEE_LOGIN}</option>
								{/section}
								</select>
								</form>
								</td>
							</tr>
						</table>
						{$calendar}
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
