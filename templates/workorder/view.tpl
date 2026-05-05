{section name=i loop=$single_workorder_array}

	<div class="container my-4">

		<!-- Toolbar Section -->
		<div class="mb-3">
			<div class="d-flex justify-content-start">
				{include file="core/tool_bar.tpl"}
			</div>
		</div>

		<!-- Error Message -->
		{if $error_msg != ""}
			{include file="core/error.tpl"}
		{/if}

		<!-- Work Order Card -->
		<div class="card mb-4">
			<div class="card-header d-flex justify-content-between align-items-center">
				<span>{$translate_workorder_page_title} {$single_workorder_array[i].WORK_ORDER_ID}</span>
				<a href="http://www.citecrm.com/docs/#work_orders" target="_blank">
					<i class="bi bi-question-circle-fill fs-5 text-secondary"
					   aria-label="Help"
					   onMouseOver="ddrivetip('<b>Work Orders</b><hr><p>You can edit the Work order commments, notes, and set schedule by clicking the icon on the right of each window.<br><br>You can navigate to the customer by clicking their name. Hover over the Magnifying glass by the customer name to view Quick Contact Information.<br><br>If you need to edit the Customers Contact Information click the Edit Icon by their contact details.<br><br>Click on the Employee name to go to the employees details. Hover over the Magnifying glass to view the employees Quick Contact Information.<br><br>Click the Print link on the Quick Bar to generate a printable PDF of this work order. Click the Close link on the Quick Bar to complete the order and start the invoicing processes</p>')"
					   onMouseOut="hideddrivetip()"></i>
				</a>
			</div>

			<div class="card-body">
				<!-- Work Order Header -->
				{include file="workorder/work_order_head.tpl"}
				<hr>

				<!-- Work Order Description -->
				{include file="workorder/work_order_description.tpl"}
				<hr>

				<!-- Work Order Comments -->
				{include file="workorder/work_order_comments.tpl"}
				<hr>

				<!-- Customer Contact Info -->
				{include file="workorder/work_order_customer_contact.tpl"}
				<hr>

				<!-- Schedule -->
				{include file="workorder/work_order_schedual.tpl"}
				<hr>

				<!-- Work Order Notes -->
				{include file="workorder/work_order_notes.tpl"}
				<hr>

				<!-- Parts -->
				{include file="workorder/work_order_parts.tpl"}
				<hr>

				<!-- Status -->
				{include file="workorder/work_order_status.tpl"}
				<hr>

				<!-- Resolution (if closed) -->
				{if $single_workorder_array[i].WORK_ORDER_CLOSE_BY != "" }
					{include file="workorder/resolution.tpl"}
				{/if}
			</div>
		</div>
	</div>

{/section}
