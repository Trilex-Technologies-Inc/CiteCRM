{literal}
	<!-- TinyMCE 6 Modern Editor -->
	<script language="javascript" type="text/javascript" src="include/tinymce/js/tinymce/tinymce.min.js"></script>

	<script>
		tinymce.init({
			selector: 'textarea[name="resolution"]',
			height: 400,
			menubar: false,
			plugins: 'lists link image table code preview fullscreen',
			toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link table | preview code fullscreen',
			branding: false,
			content_style: "body { font-family: Arial, Helvetica, sans-serif; font-size:14px }"
		});
	</script>
{/literal}

<!-- Toolbar -->
<div class="container-fluid mb-3">
	<div class="d-flex align-items-center">
		{include file="core/tool_bar.tpl"}
	</div>
</div>

<div class="container my-4">

	<div class="card shadow-sm">

		<div class="card-header bg-primary text-white fw-bold">
			{$translate_work_order_close_title} #{$wo_id}
		</div>

		<div class="card-body">

			{if $error_msg != ""}
				{include file="core/error.tpl"}
			{/if}

			<form action="index.php?page=workorder:close"
				  method="POST"
				  name="close_work_order"
				  id="close_work_order">

				<div class="mb-3">
					<label class="form-label fw-bold">
						{$translate_workorder_resolution}
					</label>

					<textarea name="resolution"
							  class="form-control"
							  rows="10"></textarea>
				</div>

				<!-- Hidden Fields -->
				<input type="hidden" name="page" value="workorder:close">
				<input type="hidden" name="create_by" value="{$display_login}">
				<input type="hidden" name="wo_id" value="{$wo_id}">

				<!-- Submit -->
				<div class="text-end">
					<input type="submit"
						   name="submit"
						   value="{$translate_workorder_submit}"
						   class="btn btn-success px-4">
				</div>

			</form>

		</div>
	</div>

</div>
