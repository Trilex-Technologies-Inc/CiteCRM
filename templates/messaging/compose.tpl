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
                           class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Template</label>
                    <select class="form-select"
                            name="template"
                            onchange="if(this.value) window.location='index.php?page=messaging:compose&template='+encodeURIComponent(this.value){if $customer_id}&customer_id={$customer_id}{/if}">
                        <option value="">-- None --</option>
                        {foreach from=$templates item=t}
                            <option value="{$t}" {if $selected_template == $t}selected{/if}>
                                {$t|escape}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea name="message"
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