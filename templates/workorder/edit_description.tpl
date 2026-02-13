
<!-- Update Work Order Description -->
{literal}
	<script src="include/tinymce/js/tinymce/tinymce.min.js"></script>
	<script>
		tinymce.init({
			selector: 'textarea[name="description"]',
			license_key: 'gpl',
			height: 500,
			menubar: true,
			plugins: " preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons",
			toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
			paste_as_text: false, // allow full HTML
			valid_elements: '*[*]', // allow any tag and attribute
			extended_valid_elements: '*[*]',
			verify_html: false,
			cleanup: false,
			height: 400,
			code_dialog_height: 500,
			code_dialog_width: 800,
			toolbar_mode: 'sliding',
			setup: function (editor) {
				editor.on('PastePreProcess', function (e) {
					// allow raw HTML paste
					e.content = e.content;
				});
			},
			content_css: false
		});
	</script>
{/literal}

<div class="container mt-4">

	<!-- Toolbar -->
	<div class="row mb-3">
		<div class="col-12">
			{include file="core/tool_bar.tpl"}
		</div>
	</div>

	<!-- Card -->
	<div class="row justify-content-center">
		<div class="col-lg-12">

			<div class="card shadow-sm">
				<div class="card-header bg-primary text-white">
					{$translate_workorder_edit_descrp}
				</div>

				<div class="card-body">

					{if $error_msg != ""}
						<div class="mb-3">
							{include file="core/error.tpl"}
						</div>
					{/if}

					<form action="?page=workorder:edit_description" method="POST">

						<div class="mb-3">
							<label class="form-label fw-bold">
								{$translate_workorder_description_title}
							</label>

							<textarea
									class="form-control"
									name="description"
									rows="15">{$description}</textarea>
						</div>

						<input type="hidden" name="wo_id" value="{$wo_id}">

						<div class="d-flex justify-content-end">
							<button type="submit" class="btn btn-success">
								{$translate_workorder_submit}
							</button>
						</div>

					</form>

				</div>
			</div>

		</div>
	</div>

</div>
