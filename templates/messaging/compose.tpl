<div class="container-fluid">

    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h2 class="mb-0">Compose Message</h2>
            <div>
                <a href="index.php?page=messaging:list" class="btn btn-sm btn-outline-secondary me-2">List</a>
                <a href="index.php?page=messaging:logs" class="btn btn-sm btn-outline-secondary">Logs</a>
            </div>
        </div>

        <div class="card-body">

            <div class="alert alert-info">
                Mass Email Campaigns can create personalized, branded HTML emails to up to 500 prospects at once.
            </div>

            <form method="post" action="index.php?page=messaging:send">

                <input type="hidden" name="customer_id" value="{$customer_id}">

                <div class="mb-3">
                    <label class="form-label">To</label>
                    <input type="text"
                           name="to"
                           class="form-control"
                           value="{if $to_name}{$to_name} &lt;{$to_email}&gt;{else}{$to_email}{/if}">
                </div>

                <div class="mb-3">
                    <label class="form-label">BCC (comma-separated)</label>
                    <input type="text"
                           name="bcc"
                           class="form-control"
                           value="">
                </div>

                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <input type="text"
                           name="subject"
                           class="form-control"
                           value="{if $template_subject}{$template_subject|escape}{/if}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Template</label>
                        <select class="form-select"
                            name="template"
                            onchange="onTemplateChange(this.value)">
                        <option value="">-- None --</option>
                        {foreach from=$templates item=t}
                            <option value="{$t.slug|escape}" {if $selected_template == $t.slug}selected{/if}>
                                {$t.title|escape}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea id="message" name="message"
                              class="form-control"
                              rows="12">{if $template_body}{$template_body}{/if}</textarea>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input"
                           type="checkbox"
                           name="is_html"
                           value="1"
                           id="is_html">

                    <label class="form-check-label" for="is_html">
                        Send as HTML email
                    </label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Send
                    </button>

                    <a href="index.php?page=messaging:list"
                       class="btn btn-outline-secondary">
                        Back
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

        {literal}
        <script src="include/tinymce/js/tinymce/tinymce.min.js"></script>
        <script>
        tinymce.init({
            selector: '#message',
            license_key: 'gpl',
            height: 400,
            menubar: true,
            plugins: " preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help quickbars emoticons",
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            paste_as_text: false,
            valid_elements: '*[*]',
            extended_valid_elements: '*[*]',
            verify_html: false,
            cleanup: false,
            toolbar_mode: 'sliding'
        });
        </script>
        {/literal}

        <script>
        // JS helper to navigate when template selected
        function onTemplateChange(val) {
            if (!val) return;
            var url = 'index.php?page=messaging:compose&template=' + encodeURIComponent(val);
            var cidElem = document.querySelector('input[name="customer_id"]');
            var cid = cidElem ? cidElem.value : 0;
            if (cid && cid != 0) url += '&customer_id=' + encodeURIComponent(cid);
            window.location = url;
        }
        </script>