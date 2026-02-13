<div class="container my-4">

	<!-- Toolbar -->
	<div class="mb-3">
		{include file="core/tool_bar.tpl"}
	</div>

	<!-- Error Message -->
	{if $error_msg != ""}
		<div class="alert alert-danger">
			{include file="core/error.tpl"}
		</div>
	{/if}

	<!-- Work Order Card -->
	<div class="card shadow-sm">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h5 class="mb-0">Open Work Order: #{$single_workorder_array[i].WORK_ORDER_ID}</h5>
			<img src="images/icons/16x16/help.gif" alt="Help"
				 onMouseOver="ddrivetip('<b>Invoice</b><hr><p></p>')"
				 onMouseOut="hideddrivetip()">
		</div>
		<div class="card-body">
			<!-- Content area (was empty in original) -->
		</div>
	</div>

</div>
