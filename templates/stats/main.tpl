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
					<i class="bi bi-question-circle-fill fs-5 text-secondary"
					   aria-hidden="true"
					   onMouseOver="ddrivetip('<b>Monthly stats</b><hr><p></p>')"
					   onMouseOut="hideddrivetip()"></i>
				</div>
		</div>

		<div class="card-body">
			{include file="stats/summary_cards.tpl"}
		</div>
	</div>

</div>
