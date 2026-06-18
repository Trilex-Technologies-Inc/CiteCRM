<div class="container-fluid">

    <div class="card shadow-sm">
        <div class="card-header">
            <h2 class="mb-0">Create API Key</h2>
        </div>

        <div class="card-body">

            <form method="post" action="index.php?page=leads:keys_save">

                <div class="mb-3">
                    <label for="form_id" class="form-label">
                        Form
                    </label>

                    <select name="form_id"
                            id="form_id"
                            class="form-select">
                        <option value="">(Global API Key)</option>

                        {foreach from=$forms item=form}
                            <option value="{$form.FORM_ID}">
                                {$form.FORM_NAME|escape}
                            </option>
                        {/foreach}
                    </select>

                    <div class="form-text">
                        Select a specific form or leave empty to create a global API key.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">
                        Description
                    </label>

                    <input type="text"
                           name="description"
                           id="description"
                           class="form-control"
                           placeholder="e.g. Website Integration, Zapier Connection">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Create API Key
                    </button>

                    <a href="index.php?page=leads:list"
                       class="btn btn-secondary">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>