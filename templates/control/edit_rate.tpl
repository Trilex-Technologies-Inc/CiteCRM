<!-- edit rates -->
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
		<td>
			<table width="700" cellpadding="4" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;Edit Billing Rates</td>
				</tr><tr>
					<td class="menutd2">
						<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td class="menutd">
									<table width="100%" celpadding="5" cellspacing="5">
										<tr>
											<td>
												Labor Rates are Per Hour.
												<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
													<tr>
														<td class="olohead">ID</td>
														<td class="olohead">Display</td>
														<td class="olohead">Amount</td>
														<td class="olohead">Active</td>
														<td class="olohead">Action</td>
													</tr>
													{section name=q loop=$rate}
													<form method="POST" action="?page=control:edit_rate">
													<tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" class="row1">
														<td class="olotd4" nowrap>{$rate[q].LABOR_RATE_ID}</td>
														<td class="olotd4" nowrap><input class="olotd5" type="text" name="display" value="{$rate[q].LOABOR_RATE_NAME}" size="50"></td>
														<td class="olotd4" nowrap>$<input class="olotd5" type="text" name="amount" value="{$rate[q].LABOR_RATE_AMOUT}" size="6"></td>
														<td class="olotd4" nowrap><select class="olotd5" name="active">
																<option value="0" {if $rate[q].LABOR_RATE_ACTIVE == 0} selected{/if}>No</option>
																<option value="1" {if $rate[q].LABOR_RATE_ACTIVE == 1} selected{/if}>Yes</option>
															</select>
														</td>
														<td class="olotd4" nowrap>
															<input type="hidden" name="id" value="{$rate[q].LABOR_RATE_ID}">
															<input type="submit" name="submit" value="Edit">&nbsp;
															<input type="submit" name="submit" value="Delete">
														</td>
													</tr>
													</form>
													{/section}
												</table>
												<br>
												<b>Add New<br>
												<form method="POST" action="?page=control:edit_rate">
												<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
													<tr>
														<td class="olohead">Display</td>
														<td class="olohead">Amount</td>
													</tr><tr>
														<td class="olotd4"><input class="olotd5" type="text" name="display" size="60"></td>
														<td class="olotd4">$<input class="olotd5" type="text" name="amount" size="6"></td>
													<tr>
														<td class="olotd4" colspan="2"><input type="submit" name="submit" value="New"></td>
													</tr>
												</table>
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