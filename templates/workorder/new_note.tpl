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

	<!-- Work Order Note Form -->
	<div class="card">
		<div class="card-header">
			Add Work Order Note For Work Order ID#{$wo_id}
		</div>
		<div class="card-body">
			{if $error_msg != ""}
				{include file="core/error.tpl"}
			{/if}

			<form action="index.php?page=workorder:new_note" method="POST" name="new_workorder_note" id="new_workorder_note">
				<input type="hidden" name="page" value="workorder:new_note">
				<input type="hidden" name="wo_id" value="{$wo_id}">

				<div class="mb-3">
					<label for="work_order_notes" class="form-label">Work Order Notes</label>
					<textarea id="work_order_notes" class="form-control tinymce-editor" rows="15" name="work_order_notes"></textarea>
				</div>

				<input name="submit"  value="submit" type="submit" class="btn btn-primary">
			</form>
		</div>
	</div>
</div>
