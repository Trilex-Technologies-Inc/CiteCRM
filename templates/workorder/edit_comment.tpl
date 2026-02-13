{literal}
	<script src="include/tinymce/js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
		tinyMCE.init({
			selector: 'textarea.tinymce-editor',
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

<div class="container my-4">
	<!-- Toolbar Section -->
	<div class="mb-3">
		<div class="d-flex justify-content-start">
			{include file="core/tool_bar.tpl"}
		</div>
	</div>

	<!-- Work Order Comment Form -->
	<div class="card">
		<div class="card-header">
			{$translate_workorder_edit_comments}
		</div>
		<div class="card-body">
			{if $error_msg != ""}
				{include file="core/error.tpl"}
			{/if}

			<form action="?page=workorder:edit_comment" method="POST">
				<div class="mb-3">
					<label for="comment" class="form-label"><b>{$translate_workorder_comments_title}</b></label>
					<textarea id="comment" class="form-control tinymce-editor" rows="15" name="comment">{$comment}</textarea>
				</div>
				<input type="hidden" name="wo_id" value="{$wo_id}">
				<input name="submit" value="{$translate_workorder_submit}" type="submit" class="btn btn-primary">
			</form>
		</div>
	</div>
</div>
