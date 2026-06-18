<div class="container-fluid">

    <div class="card shadow-sm">
        <div class="card-header">
            <h2 class="mb-0">
                {if $form.FORM_ID}
                    Edit Form
                {else}
                    Create Form
                {/if}
            </h2>
        </div>

        <div class="card-body">

            <form method="post" action="index.php?page=leads:forms_save">

                <input type="hidden"
                       name="form_id"
                       value="{$form.FORM_ID}" />

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="form_name" class="form-label">
                            Form Name
                        </label>

                        <input type="text"
                               id="form_name"
                               name="form_name"
                               class="form-control"
                               value="{$form.FORM_NAME|escape}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="form_slug" class="form-label">
                            Slug
                        </label>

                        <input type="text"
                               id="form_slug"
                               name="form_slug"
                               class="form-control"
                               value="{$form.FORM_SLUG|escape}">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="form_html" class="form-label">
                        HTML (Embed Form)
                    </label>

                    <textarea id="form_html"
                              name="form_html"
                              rows="10"
                              class="form-control font-monospace">{$form.FORM_HTML|escape}</textarea>

                    <div class="form-text">
                        Paste or edit the HTML used for this lead capture form.
                    </div>
                </div>

                <div class="mb-4">
                    <label for="form_mapping" class="form-label">
                        Mapping (JSON)
                    </label>

                    <textarea id="form_mapping"
                              name="form_mapping"
                              rows="6"
                              class="form-control font-monospace">{$form.FORM_MAPPING|escape}</textarea>

                    <div class="form-text">
                        Define how submitted fields map to lead fields.
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit"
                            class="btn btn-primary">
                        Save Form
                    </button>

                    <a href="index.php?page=leads:forms"
                       class="btn btn-secondary">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>