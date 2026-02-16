<!-- Edit Schedule Template -->

{literal}
	<script src="include/tinymce/js/tinymce/tinymce.min.js"></script>
	<script>
		document.addEventListener("DOMContentLoaded", function () {
			tinymce.init({
				selector: 'textarea[name="schedual_notes"]',
				license_key: 'gpl',
				height: 400,
				menubar: true,
				plugins: 'lists link image table code preview fullscreen',
				toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image table | code preview fullscreen',
				toolbar_mode: 'sliding'
			});
		});
	</script>
{/literal}

<div class="container mt-4">

	<!-- Toolbar -->
	<div class="mb-3">
		{include file="core/tool_bar.tpl"}
	</div>

	<div class="card shadow-sm">
		<div class="card-header">
			<h5 class="mb-0">Edit Schedule</h5>
		</div>

		<div class="card-body">
			<form method="POST" action="?page=schedual:edit&y={$y}&m={$m}&d={$d}">

				<!-- Notes -->
				<div class="mb-3">
					<label for="schedual_notes" class="form-label fw-bold">Notes</label>
					<textarea id="schedual_notes"
							  name="schedual_notes"
							  class="form-control"
							  rows="15">{$schedual_notes}</textarea>
				</div>

				<input type="hidden" name="sch_id" value="{$sch_id}">

				<!-- Submit -->
				<div class="text-end">
					<input type="submit"
						   name="submit"
						   value="Submit"
						   class="btn btn-primary">
				</div>

			</form>
		</div>
	</div>
</div>
