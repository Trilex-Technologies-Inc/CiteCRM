<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">API Keys</h2>

        <a href="index.php?page=leads:keys_create"
           class="btn btn-primary">
            Create New Key
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            {if $keys|@count > 0}

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>API Key</th>
                                <th>Form</th>
                                <th>Description</th>
                                <th>Created</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                        {foreach from=$keys item=key}
                            <tr>
                                <td>
                                    <code>{$key.API_KEY|escape}</code>
                                </td>

                                <td>
                                    {if $key.FORM_NAME}
                                        {$key.FORM_NAME|escape}
                                    {else}
                                        <span class="badge bg-secondary">Global</span>
                                    {/if}
                                </td>

                                <td>{$key.DESCRIPTION|escape}</td>

                                <td>{$key.CREATED_AT|escape}</td>

                                <td>
                                                                        <a href="index.php?page=leads:keys_delete&key_id={$key.KEY_ID}"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Revoke this API key?')">
                                        Revoke
                                    </a>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>

            {else}

                <div class="alert alert-info mb-0">
                    No API keys found.
                </div>

            {/if}

        </div>
    </div>

</div>