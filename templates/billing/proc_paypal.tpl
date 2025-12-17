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
			<table width="825" cellpadding="4" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;{$translate_billing_paypal}</td>
					<td class="menuhead2" width="20%" align="right" valign="middle">
						<a href="http://www.citecrm.com/docs/#billing" target="new"><img src="images/icons/16x16/help.gif" border="0"></a>
				</td>
					</tr><tr>
					<td class="menutd2" colspan="2">
						<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td class="menutd">
									<iframe src="tmp.html" name="myspot" width="100%" height="600">
					
									</iframe>
								</td>
							</tr><tr>
								<td>
									<table width="100%" cellpadding="4" cellspacing="0" border="0" >
										<tr>
											<td class="menuhead2">&nbsp;{$translate_billing_results}</td>
										</tr><tr>
											<td class="menutd2">
												<table class="olotable" width="100%" cellpadding="3" cellspacing="0">
													<tr>
														<td class="menutd">
															{$translate_billing_paypal_note}<br>
															<form method="POST" action="?page=billing:pp_complete">
																<b>{$translate_billing_invoice_id}</b><input type="text" name="pp_invoice" class="olotd4">&nbsp;
																<input type="hidden" name="invoice_id" value="{$invoice_id}">
																<input type="hidden" name="wo_id" value="{$wo_id}">
																<input type="submit" name="submit" value="Sucesses">&nbsp;
																<input type="submit" name="submit" value="Failed">
															</form>
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
		</td>
	</tr>
</table>
	

				
			