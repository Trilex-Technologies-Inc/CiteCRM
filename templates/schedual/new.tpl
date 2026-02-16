<!-- New Schedule tpl -->

{literal}
	<script src="include/tinymce/js/tinymce/tinymce.min.js"></script>

	<script>
		tinymce.init({
			selector: 'textarea[name="schedaul_notes"]',
			license_key: 'gpl',
			height: 500,
			menubar: true,
			plugins: 'lists link image table code preview fullscreen',
			toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image table | code preview fullscreen',
			toolbar_mode: 'sliding'
		});
	</script>

	<style>
		/* Fix TinyMCE dialogs */
		.tox-tinymce-aux,
		.tox-dialog,
		.tox-menu {
			z-index: 10000 !important;
		}

		/* Prevent clipping */
		.container,
		.card,
		.card-body {
			overflow: visible !important;
		}
	</style>
{/literal}

<div class="container mt-4">

	<div class="mb-3">
		{include file="core/tool_bar.tpl"}
	</div>

	{if $error_msg != ""}
		<div class="alert alert-danger">
			{include file="core/error.tpl"}
		</div>
	{/if}

	<div class="card shadow-sm">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h5 class="mb-0">{$translate_schedule_new}</h5>
			<a href="http://www.citecrm.com/docs/#schedual" target="_blank">Help</a>
		</div>

		<div class="card-body">

			{if $wo_id != '0'}
				<div class="alert alert-warning">
					<strong>{$translate_schedule_info}:</strong>
					{$translate_schedule_wo} {$wo_id}
				</div>
			{/if}

			<form method="POST" action="?page=schedual:new">

				<input type="hidden" name="page" value="schedual:new">
				<input type="hidden" name="tech" value="{$tech}">
				<input type="hidden" name="wo_id" value="{$wo_id}">

				<h6 class="fw-bold mb-3">{$translate_schedule_set}</h6>

				<div class="row mb-4">

					<!-- START -->
					<div class="col-md-6">
						<label class="form-label fw-bold">
							{$translate_schedul_start}
						</label>

						<div class="input-group mb-2">
							<input class="form-control"
								   name="start[schedual_date]"
								   type="date"
								   value="{$start_day|date_format:"%Y-%m-%d"}">
						</div>

						<div class="mt-2">
							{html_select_time use_24_hours=false display_seconds=false minute_interval=15 field_array=start time=$start_time}
						</div>
					</div>

					<!-- END -->
					<div class="col-md-6">
						<label class="form-label fw-bold">
							{$translate_schedule_end}
						</label>

						<div class="input-group mb-2">
							<input class="form-control"
								   name="end[schedual_date]"
								   type="date"
								   value="{$start_day|date_format:"%Y-%m-%d"}">
						</div>

						<div class="mt-2">
							{html_select_time use_24_hours=false display_seconds=false minute_interval=15 field_array=end time=$start_time}
						</div>
					</div>
				</div>

				<!-- NOTES -->
				<div class="mb-4">
					<label class="form-label fw-bold">
						{$translate_schedule_notes}
					</label>

					<textarea class="form-control"
							  name="schedaul_notes"
							  rows="10">{$schedaul_notes}</textarea>
				</div>

				<!-- SUBMIT -->
				<div class="text-end">
					<input type="submit"
						   name="submit"
						   value="{$translate_schedule_submit}"
						   class="btn btn-primary">
				</div>

			</form>

		</div>
	</div>
</div>
