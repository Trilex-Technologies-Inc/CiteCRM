<!-- parts -->
{literal}

<SCRIPT LANGUAGE="JavaScript">

function setOptions(chosen) {
var selbox = document.form1.CAT2;

	selbox.options.length = 0;
	if (chosen == " ") {
	selbox.options[selbox.options.length] = new Option('Please select one of the options above first',' ');

	}
   {/literal}
   {section name=q loop=$CAT}
   {literal}if (chosen == "{/literal}{$CAT[q].ID}{literal}"){{/literal}
    {section name=w loop=$SUB_CAT}
     {if $SUB_CAT[w].CAT == $CAT[q].ID}
      {literal}
        selbox.options[selbox.options.length] = new Option({/literal}'{$SUB_CAT[w].DESCRIPTION}','{$SUB_CAT[w].SUB_CATEGORY}'{literal});
      {/literal}
      {/if}
     {/section}
    {literal}
     }
   {/literal}
   {/section}
   {literal}
}
</script>
{/literal}
<div class="toolbar container-fluid mb-3">
	<div class="row">
		<div class="col-12">
			{include file="core/tool_bar.tpl"}
		</div>
	</div>
</div>
{if $error_msg != ""}
	{include file="core/error.tpl"}
{/if}
<div class="container-fluid py-3">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
					&nbsp;{$translate_parts_order}
				</div>
				<div class="card-body menutd2">
					<div class="row">
						<!-- Left Side -->
						<div class="col-md-9">
							<p>{$translate_parts_msg_1}</p>
							<p>
								{$translate_parts_msg_2}
								<br>
								{$translate_parts_msg_3}
								<br>
								{$translate_parts_msg_4}
							</p>
							{if $crm_msg != ''}
								<div class="mb-3">
									<div class="error alert alert-danger mb-0" role="alert">
										<span>{$crm_msg}</span>
										<br>
									</div>
								</div>
							{/if}
							{if $parts != ''}
								<table class="olotable table table-striped table-bordered w-100" cellpadding="3" cellspacing="0" border="0">
									<tr>
										<td class="olohead">{$translate_parts_amount}</td>
										<td class="olohead">{$translate_parts_sku}</td>
										<td class="olohead">{$translate_parts_item_id}</td>
										<td class="olohead">{$translate_parts_description}</td>
										<td class="olohead">{$translate_parts_vendor}</td>
										<td class="olohead">{$translate_parts_weight}</td>
										<td class="olohead">{$translate_parts_price}</td>
										<td class="olohead">{$translate_parts_add}</td>
									</tr>
									{section name=p loop=$parts}
										<form method="post" action="?page=parts:main">
											<tr onmouseover="this.className='row2'" onmouseout="this.className='row1';" class="row1">
												<td class="olotd4 text-center align-middle">
													<input type="text" class="olotd5 form-control form-control-sm d-inline-block w-auto text-center" name="AMOUNT" size="2" maxlength="4">
												</td>
												<td class="olotd4 text-center align-middle">{$parts[p].SKU}</td>
												<td class="olotd4 text-center align-middle">{$parts[p].ITEMID}</td>
												<td class="olotd4">{$parts[p].DESCRIPTION}</td>
												<td class="olotd4">{$parts[p].VENDOR}</td>
												<td class="olotd4 text-center align-middle">{$parts[p].Weight} {$parts[p].UNIT}</td>
												<td class="olotd4 text-center align-middle">${$parts[p].PRICE}</td>
												<input type="hidden" name="SKU" value="{$parts[p].SKU}">
												<input type="hidden" name="DESCRIPTION" value="{$parts[p].DESCRIPTION}">
												<input type="hidden" name="VENDOR" value="{$parts[p].VENDOR}">
												<input type="hidden" name="ITEMID" value="{$parts[p].ITEMID}">
												<input type="hidden" name="Weight" value="{$parts[p].Weight}">
												<input type="hidden" name="PRICE" value="{$parts[p].PRICE|string_format:"%.2f"}">
												<input type="hidden" name="CAT2" value="{$CAT2}">
												<input type="hidden" name="add_part" value="1">
												<td class="olotd4 text-center align-middle">
													<input type="hidden" name="wo_id" value="{$wo_id}">
													<input type="hidden" name="from_zip" value="{$from_zip}">
													<input type="submit" class="btn btn-sm btn-primary" name="submit" value="Add">
												</td>
											</tr>
										</form>
									{/section}
								</table>
							{/if}
							<br>
							{if $cart_contents != ''}
								<b>Check Out</b>
								<table class="olotable table table-striped table-bordered w-100" cellpadding="3" cellspacing="0" border="0">
									<tr>
										<td class="olohead">{$translate_parts_amount}</td>
										<td class="olohead">{$translate_parts_sku}</td>
										<td class="olohead">{$translate_parts_item_id}</td>
										<td class="olohead">{$translate_parts_description}</td>
										<td class="olohead">{$translate_parts_vendor}</td>
										<td class="olohead">{$translate_parts_weight}</td>
										<td class="olohead">{$translate_parts_each}</td>
										<td class="olohead">{$translate_parts_total}</td>
									</tr>
									{section name=a loop=$cart_contents}
										<tr onmouseover="this.className='row2'" onmouseout="this.className='row1';" class="row1">
											<td class="olotd4 text-center align-middle">{$cart_contents[a].AMOUNT}</td>
											<td class="olotd4 text-center align-middle">{$cart_contents[a].SKU}</td>
											<td class="olotd4 text-center align-middle">{$cart_contents[a].ITEMID}</td>
											<td class="olotd4">{$cart_contents[a].DESCRIPTION}</td>
											<td class="olotd4">{$cart_contents[a].VENDOR}</td>
											<td class="olotd4 text-center align-middle">{$cart_contents[a].Weight} {$cart_contents[a].UNIT}</td>
											<td class="olotd4 text-right align-middle">${$cart_contents[a].PRICE|string_format:"%.2f"}</td>
											<td class="olotd4 text-right align-middle">${$cart_contents[a].SUB_TOTAL|string_format:"%.2f"}</td>
											<input type="hidden" name="SKU" value="{$cart_contents[a].SKU}">
											<input type="hidden" name="PRICE" value="{$cart_contents[a].PRICE|string_format:"%.2f"}">
										</tr>
									{/section}
									<tr>
										<td colspan="6" align="left">
											{$translate_parts_msg_5} ${$total_charges|string_format:"%.2f"}. {$translate_parts_msg_6} {$service_code} {$translate_parts_msg_7} {$location}. {$translate_parts_msg_8}
										</td>
										<td class="olotd4 text-right" nowrap><b>{$translate_parts_sub_total}</b></td>
										<td class="olotd4 text-right"><b>${$sub_total}</b></td>
									</tr>
									<tr>
										<td colspan="6" align="left">{$translate_parts_msg_9}</td>
										<td class="olotd4 text-right" nowrap><b>{$translate_parts_shipping}</b></td>
										<td class="olotd4 text-right"><b>${$shipping_charges|string_format:"%.2f"}</b></td>
									</tr>
									<tr>
										<td colspan="6" align="left">
											{if $ResponseStatusCode == 0}
												<span class="error">{$ErrorDescription}</span>
												<br>
											{else}
												<form method="post" action="?page=parts:checkout" class="mb-0">
													<input type="hidden" name="wo_id" value="{$wo_id}">
													<input type="submit" class="btn btn-sm btn-success" name="submit" value="check out">
												</form>
											{/if}
										</td>
										<td class="olotd4 text-right" nowrap><b>{$translate_parts_total_charges}</b></td>
										<td class="olotd4 text-right align-middle"><b>${$total_charges|string_format:"%.2f"}</b></td>
									</tr>
								</table>
							{/if}
						</div>
						<!-- Right Side -->
						<div class="col-md-3">
							<b>{$translate_parts_select}</b>
							<form action="?page=parts:main" method="post" id="form1" name="form1" class="mb-3">
								<select name="CAT" size="1" class="olotd5 form-control" onchange="setOptions(document.form1.CAT.options[document.form1.CAT.selectedIndex].value);">
									<option value="" selected="selected">{$translate_parts_select_cat}</option>
									{section name=q loop=$CAT}
										<option value="{$CAT[q].ID}">{$CAT[q].DESCRIPTION}</option>
									{/section}
								</select>
								<br>
								<select name="CAT2" size="1" class="olotd5 form-control mt-2">
									<option value=" " selected="selected">&nbsp;</option>
								</select>
								<br>
								<input type="hidden" name="wo_id" value="{$wo_id}">
								<input type="submit" class="btn btn-primary btn-sm mt-2" name="submit" value="{$translate_parts_search}">
							</form>
							<hr>
							<form action="?page=parts:main" method="post" class="mb-3 bg-white">
								<b>{$translate_parts_cart}</b><br>
								{$translate_parts_total_items} {$cart_count}<br>
								<table class="table table-sm mb-2" cellpadding="3" cellspacing="0" border="0">
									<tr>
										<td>{$translate_parts_remove}</td>
										<td>{$translate_parts_sku}</td>
										<td>{$translate_parts_amount}</td>
										<td>{$translate_parts_sub_total}</td>
									</tr>
									{section name=c loop=$cart}
										<tr>
											<td align="left"><input type="checkbox" name="remove[{$smarty.section.c.index}]" value="{$cart[c].SKU}"></td>
											<td>{$cart[c].SKU}</td>
											<td align="center">{$cart[c].AMOUNT}</td>
											<td align="right">${$cart[c].PRICE|string_format:"%.2f"}</td>
										</tr>
									{/section}
									<tr>
										<td colspan="4">
											<input type="hidden" name="wo_id" value="{$wo_id}">
											<input type="hidden" name="CAT2" value="{$CAT2}">
											<input type="hidden" name="update_cart" value="1">
											<input type="submit" class="btn btn-secondary btn-sm" name="submit" value="{$translate_parts_update}">
										</td>
									</tr>
									<tr>
										<td>{$translate_parts_cart_total}</td>
										<td colspan="3" align="right">${$cart_total|string_format:"%.2f"}</td>
									</tr>
								</table>
							</form>
							<div class="mb-3">
								<form method="POST" action="?page=parts:main" class="d-inline">
									<input type="hidden" name="wo_id" value="{$wo_id}">
									<input type="hidden" name="check_out" value="1">
									<input type="submit" class="btn btn-success btn-sm" name="submit" value="{$translate_parts_checkout}">
									<input type="submit" class="btn btn-outline-primary btn-sm" name="submit" value="{$translate_parts_view}">
								</form>
							</div>
							<div class="small">
								<b>{$translate_parts_wo_id}</b> {$wo_id}<br>
								<b>{$translate_parts_shipping_method}</b> {$service_code}<br>
								<b>{$translate_parts_ware}</b> {$location}<br>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>