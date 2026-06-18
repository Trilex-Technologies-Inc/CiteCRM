<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Lead Capture Forms</h2>

        <a href="index.php?page=leads:forms_edit"
           class="btn btn-primary">
            Create New Form
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            {if $forms|@count > 0}

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Form Name</th>
                                <th>Slug</th>
                                <th>Public Token</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                        {foreach from=$forms item=form}
                            <tr>
                                <td>
                                    <strong>{$form.FORM_NAME|escape}</strong>
                                </td>

                                <td>
                                    <code>{$form.FORM_SLUG|escape}</code>
                                </td>

                                <td>
                                    <code>{$form.PUBLIC_TOKEN|escape}</code>
                                </td>

                                <td>
                                    <a href="index.php?page=leads:forms_edit&form_id={$form.FORM_ID}"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>

            {else}

                <div class="alert alert-info mb-0">
                    No lead capture forms have been created yet.
                </div>

            {/if}

        </div>
    </div>

</div>