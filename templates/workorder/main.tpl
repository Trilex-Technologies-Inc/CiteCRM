<!-- Work order main TPL -->
<!-- Toolbar -->
<div class="mb-3">
	{include file="core/tool_bar.tpl"}
</div>

<!-- Work Orders Card -->
<div class="card shadow-sm mb-3">
	<div class="card-header d-flex justify-content-between align-items-center">
		<div class="fs-5 fw-bold">{$translate_workorder_title}</div>
		<div>
			<a href="http://www.citecrm.com/docs/#work_orders" target="_blank"
			   class="btn btn-sm btn-outline-secondary"
			   onMouseOver="ddrivetip('<b>Navigation</b><hr><p>Double Click on an empty space in each row to go directly to the work order. <br><br>Hover over the magnifying glass under Customer to view the Quick Contact Information. Click on the Customers name to view the customers details.<br><br>Click on the status of each work order listed to update the current work order status.<br><br>Hover over the Magnifying Glass under the Employee to view the Quick Contact Information for the assigned employee. Click on the employees name to view the details.<br><br>Under Action click the Printer Icon to print the work order. Click the Magnifying Glass to view the work order. Click the Red Stop sign to close the work order and start the invoicing.</p>')"
			   onMouseOut="hideddrivetip()">
				<i class="bi bi-question-circle-fill" aria-hidden="true"></i>
			</a>
		</div>
	</div>

	<div class="card-body">
		<!-- New Work Orders -->
		<div class="mb-4" id="new">
			{include file="workorder/blocks/new_work_order.tpl"}
		</div>

		<!-- Assigned Work Orders -->
		<div class="mb-4" id="assigned">
			{include file="workorder/blocks/assigned_work_order.tpl"}
		</div>

		<!-- Awaiting Parts/Status -->
		<div class="mb-4" id="awaiting">
			{include file="workorder/blocks/awaiting_work_order.tpl"}
		</div>

		<!-- Payment Pending Work Orders -->
		<div class="mb-2" id="payment">
			{include file="workorder/blocks/payment_work_order.tpl"}
		</div>
	</div>
</div>
