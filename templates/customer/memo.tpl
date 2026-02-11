{literal}
<script src="include/tinymce/js/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: 'textarea[name="memo"]', 
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


<!-- Toolbar -->
<div class="container-fluid mb-3">
    {include file="core/tool_bar.tpl"}
</div>

<!-- Page Content -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">New Memo</h5>
                </div>

                <div class="card-body">

                    {if $error_msg != ""}
                        {include file="core/error.tpl"}
                    {/if}

                    <form action="index.php?page=customer:memo" method="POST">
                        <input type="hidden" name="customer_id" value="{$customer_id}">

                        <div class="mb-3">
                            <textarea 
                                class="form-control" 
                                rows="12" 
                                name="memo" 
                                mce_editable="true">
                            </textarea>
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
