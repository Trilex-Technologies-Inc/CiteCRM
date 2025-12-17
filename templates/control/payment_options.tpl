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
					<td class="menuhead2" width="80%">&nbsp;Payment Options	</td>
				</tr><tr>
					<td class="menutd2">
					
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
								<form method="POST" action="?page=control:payment_options">
								
									<table>
										{section name=q loop=$arr}
										<tr>
											<td><b>{$arr[q].BILLING_NAME}</b></td>
											<td>Active: <input type="checkbox" name="{$arr[q].BILLING_OPTION}" {if $arr[q].ACTIVE == 1} checked {/if} value=1 	 class="olotd5"></td>
										</tr>
										{/section}
									</table>
									<br>
									<br>
									<b>Authorize.Net information</b><br>
									If you are enabling credit card billing you must have an Authorize.Net account set up and enbaled. To set up an Authorize.Net account click here. You account information will encrypted before being stored in the database. No credit Card information is stored in the Cite CRM system. For more information on billing profiles and setup please contact Authorize.Net. If you re-install Cite CRM you will need to enter your Authorize.Net account settings as a random encyption key is generated at install time. 
									{section name=w loop=$opts}
									<table >
										<tr>
											<td><b>Login:</b></td>
											<td><input type="text" name="an_login" value="{$opts[w].AN_LOGIN_ID}" class="olotd5"></td>
										</tr><tr>
											<td><b>Password:</b></td>
											<td><input type="password" name="AN_PASSWORD" class="olotd5"> </td>
										</tr><tr>
											<td><b>Transaction Key:</b></td>
											<td><input type="text" name="AN_TRANS_KEY" value="{$opts[w].AN_TRANS_KEY}" size="50" class="olotd5"></td>
										</tr>
									</table>
									<br>
									<br>
									<b>Paypal Information</b><br>
									You must have a Paypal Merchant account set and working. Please see https://www.paypal.com/ for more information.
									<table>
										<tr>
											<td><b>Paypal Email</b></td>
											<td><input type="text" name="PP_ID" value="{$opts[w].PP_ID}" size="50" class="olotd5"></td>
										</tr>
									</table>
									<input type="submit" name="submit" value="Submit">
								{/section}
								</form>
								<!-- End Content -->
							</td>
						</tr>
					</table>
				</tr>
			</table>
		</td>
	</tr>
</table>
	