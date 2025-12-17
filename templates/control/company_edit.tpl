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
		<td>
			<!-- Begin Page -->
			<table width="700" cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;Edit The Company Information</td>
				</tr><tr>
					<td class="menutd2">
					<table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
						<tr>
							<td width="100%" valign="top" >
							{if $error_msg != ""}
								{include file="core/error.tpl"}
							{/if}
							{if $msg !=""}
								{include file="core/msg.tpl"}
							{/if}
								<!-- Content Here -->
								{section name=q loop=$company}
									<form method="POST" action="?page=control:company_edit">
										<table  cellpadding="5" cellspacing="0">
											<tr>
												<td><b>Company Display Name:</b></td>
												<td><input class="olotd5" type="text" name="company_name" value="{$company[q].COMPANY_NAME}"></td>
											</tr><tr>
												<td><b>Address:</b></td>
												<td><input class="olotd5" type="text" name="address" value="{$company[q].COMPANY_ADDRESS}"></td>
											</tr><tr>
												<td><b>City:</b></td>
												<td><input class="olotd5" type="text" name="city" value="{$company[q].COMPANY_CITY}"></td>
											</tr><tr>
												<td><b>State:</b></td>
												<td><input class="olotd5" type="text" name="state" value="{$company[q].COMPANY_STATE}">
        										</td>
											</tr><tr>
												<td><b>Zip:</b></td>
												<td><input class="olotd5" type="text" name="zip" value="{$company[q].COMPANY_ZIP}"></td>
											</tr><tr>
													<td><b>Country</b></td>
												<td>
													<select name="country" class="olotd5">
													{section name=c loop=$country}
  														<option value="{$country[c].code}" {if $company[q].COMPANY_COUNTRY == $country[c].code} selected {/if} >{$country[c].name}</option>
														
													{/section}
													</select>
												</td>
											</tr><tr>
												<td><b>Phone:</b></td>
												<td><input class="olotd5" type="text" name="phone" value="{$company[q].COMPNAY_PHONE}"></td>
											</tr><tr>
												<td><b>Mobile Phone:</b></td>
												<td><input class="olotd5" type="text" name="mobile_phone" value="{$company[q].COMPNAY_MOBILE}"></td>
											</tr><tr>
												<td><b>Toll Free:</b></td>
												<td><input class="olotd5" type="text" name="toll_free" value="{$company[q].COMPANY_TOLL_FREE}"></td>
											</tr>
											{section name=w loop=$setup}
											<tr>
												<td><b>PDF Printing:</b></td>
												<td><input class="olotd5" type="checkbox" name="pdf_print" value="1" { if $setup[w].PDF_PRINT == 1 } checked {/if}></td>
											</tr>
											
											<tr>
												<td><b>Tax Amount:</b></td>
												<td><input type="text" size="6" name="inv_tax" value="{$setup[w].INVOCIE_TAX}" class="olotd5">%</td>
											</tr><tr>
												<td colspan="2"><b>Invoice Thank You Note:</b> 255 max characters. Displays at the bottom of each invoice.</td>
											</tr><tr>
												<td colspan="2"><textarea class="olotd5" cols="80" rows="5" name="inv_thank_you">{$setup[w].INV_THANK_YOU}</textarea></td>
											</tr><tr>
												<td><b>Company Welcome Note</b> (home page)</td>
											</tr><tr>
												<td colspan="2"><textarea class="olotd5" cols="80" rows="5" name="welcome">{$setup[w].WELCOME_NOTE}</textarea></td>	
											
											<tr><tr>
												<td colspan="2">Cite CRM Parts Ordering Settings. Please fill in the user name and password you used to sign up at www.incitecrm.com. You will need to select the nearest city from the dropdown box. If you have not signed up for Parts Ordering you can create a free account here. The Password field is hidden after it has been set. <a href="https://www.incitecrm.com/?page=sign_up:main&page_title=Sign%20Up" target="new">Registar</a>.</td>
											</tr><tr>
												<td><b>Login:</b></td>
												<td><input class="olotd5" type="text" name="parts_login" value="{$setup[w].PARTS_LOGIN}"></td>
											</tr><tr>
												<td><b>Password:</b></td>
												<td><input class="olotd5" type="password" name="parts_password"></td>
											</tr><tr>
												<td><b>Location:</b></td>
												<td><select class="olotd5" name="parts_lo">
															<option value="AT" {if $setup[w].PARTS_LO == "AT"} selected {/if}>Atlanta</option>
															<option value="CH" {if $setup[w].PARTS_LO == "CH"} selected {/if}>Chicago</option>
															<option value="DA" {if $setup[w].PARTS_LO == "DA"} selected {/if}>Dallas</option>
															<option value="FR" {if $setup[w].PARTS_LO == "FR"} selected {/if}>Fremont</option>
															<option value="HO" {if $setup[w].PARTS_LO == "HO"} selected {/if}>Houston</option>
															<option value="KA" {if $setup[w].PARTS_LO == "KA"} selected {/if}>Kansas</option>
															<option value="LR" {if $setup[w].PARTS_LO == "LR"} selected {/if}>Laredo</option>
															<option value="LA" {if $setup[w].PARTS_LO == "LA"} selected {/if}>Los Angeles</option>
															<option value="MI" {if $setup[w].PARTS_LO == "MI"} selected {/if}>Miami</option>
															<option value="NJ" {if $setup[w].PARTS_LO == "NJ"} selected {/if}>New Jersey</option>
															<option value="PO" {if $setup[w].PARTS_LO == "PO"} selected {/if}>Portland</option>
															<option value="TA" {if $setup[w].PARTS_LO == "TA"} selected {/if}>Tampa</option>
													 </select>
												</td>
											</tr><tr>
												<td><b>Parts Markup:</b></td>
												<td><input class="olotd5" type="text" name="parts_markup" size="6" value="{$setup[w].PARTS_MARKUP}">% Percent</td>
											</tr><tr>
												<td colspan="2">UPS Account Information. To be able to use live shipping quotes you need to have an UPS account. This will enable you to see shipping costs before you submit an order to In-Cite CRM. This feature is not necessary and can be disabled by leaving the UPS Login, UPS Password, and UPS Access KEY fields blank. In-Cite CRM will still calculate the shipping on our side and return the shipping costs to you.  For more information on UPS live shipping please see <a href="http://www.ups.com" target="new">UPS</a>.
												</td>
											<tr></tr>
												<td><b>UPS Shipping Preferance:</b></td>
												<td><select class="olotd5" name="service_code">
															<option value="03" {if $setup[w].SERVICE_CODE == "03"} selected {/if}>UPS Ground</option>
															<option value="02" {if $setup[w].SERVICE_CODE == "02"} selected {/if}>UPS 2nd Day Air</option> 
															<option value="01" {if $setup[w].SERVICE_CODE == "01"} selected {/if}>UPS Next Day Air</option>
															<option value="07" {if $setup[w].SERVICE_CODE == "07"} selected {/if}>UPS Worldwide Express</option>
															<option value="08" {if $setup[w].SERVICE_CODE == "08"} selected {/if}>UPS Worldwide Expedited</option>
															<option value="11" {if $setup[w].SERVICE_CODE == "11"} selected {/if}>UPS Standard</option>
															<option value="12" {if $setup[w].SERVICE_CODE == "12"} selected {/if}>UPS 3 Day Select</option>
															<option value="13" {if $setup[w].SERVICE_CODE == "13"} selected {/if}>UPS Next Day Air Saver</option>
															<option value="14" {if $setup[w].SERVICE_CODE == "14"} selected {/if}>UPS Next Day Air Early A.M.</option> 
															<option value="54" {if $setup[w].SERVICE_CODE == "54"} selected {/if}>UPS Worldwide Express Plus</option>
															<option value="59" {if $setup[w].SERVICE_CODE == "59"} selected {/if}>UPS 2nd Day Air A.M.</option>
															<option value="65" {if $setup[w].SERVICE_CODE == "65"} selected {/if}>UPS Express Saver</option>
														</select>
												</td>
											</tr><tr>
												<td><b>UPS Login:</b></td>
												<td><input class="olotd5" type="text" name="ups_login" size="25" value="{$setup[w].UPS_LOGIN}"></td>
											</tr><tr>
												<td><b>UPS Password</b></td>
												<td><input class="olotd5" type="password" name="ups_password" size="25"></td>
											</tr><tr>
												<td><b>UPS Access KEY</b></td>
												<td><input class="olotd5" type="text" name="ups_access_key" value="{$setup[w].UPS_ACCESS_KEY}" size="25"></td>
											</tr><tr>
												
												<td colspan="2"> <input class="olotd5" type="submit" name="submit"  value="Update"></td>
											{/section}
											</tr>
										</table>
									</form>
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
	