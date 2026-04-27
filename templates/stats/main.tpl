<div class="container my-3">

	<!-- Admin Toolbar -->
	<div class="mb-3">
		{include file="core/admin_tool_bar.tpl"}
	</div>

	<div class="card shadow-sm">
		<div class="card-header d-flex justify-content-between align-items-center">
			<div>
				<strong>Stats for {$smarty.now|date_format:"%m-%d-%Y"}</strong>
			</div>
			<div>
				<img src="images/icons/16x16/help.gif" border="0"
					 onMouseOver="ddrivetip('<b>Monthly stats</b><hr><p></p>')"
					 onMouseOut="hideddrivetip()">
			</div>
		</div>

		<div class="card-body">
			{include file="stats/summary_cards.tpl"}
		</div>
	</div>

</div>
