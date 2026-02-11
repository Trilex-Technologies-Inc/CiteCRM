<!-- Work order main TPL-->
<table  class="toolbar" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td >
			<table  cellpadding="0" cellspacing="0">
				<tr>
					{include file="core/tool_bar.tpl"}
				</tr>
			</table>
		</td>
	</tr>
</table>

<div class="card shadow-sm mb-3">
	<div class="card-header d-flex justify-content-between align-items-center">
		<div>{$translate_workorder_title}</div>
		<div>
			<a href="http://www.citecrm.com/docs/#work_orders" target="new"
			   class="btn btn-sm btn-outline-light"
			   onMouseOver="ddrivetip('<b>Navagation</b><hr><p>Double Click on an empty space in each row to go directly to the work order. <br><br>Hover over the magnifying glass under Customer to view the Quick Contact Information. Click on the Customers name to view the customers details.<br><br>Click on the status of each work order listed to update the curent work order status.<br><br>Hover over the Magnifying Glass under the Employee to view the Quick Contact Information for the assigned employee. Click on the employees name to view the details.<br><br>Under Action click the Printer Icon to print the work order. Click the mMagnifying Glass to view the work order. Click the Red Stop sign to close the work order and start the invoicing.</p>')"
			   onMouseOut="hideddrivetip()">
				<img src="images/icons/16x16/help.gif" border="0" alt="Help">
			</a>
		</div>
	</div>
	<div class="card-body">
		<div class="mb-4" id="new">
			{include file="workorder/blocks/new_work_order.tpl"}
		</div>
		<div class="mb-4" id="assigned">
			{include file="workorder/blocks/assigned_work_order.tpl"}
		</div>
		<div class="mb-4" id="awaiting">
			{include file="workorder/blocks/awaiting_work_order.tpl"}
		</div>
		<div class="mb-2" id="payment">
			{include  file="workorder/blocks/payment_work_order.tpl"}
		</div>
	</div>
</div>