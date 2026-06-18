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
                                    <div class="d-flex align-items-center gap-2 text-nowrap">
                                        <a href="index.php?page=leads:forms_edit&form_id={$form.FORM_ID}"
                                           class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <a href="index.php?page=leads:forms_submissions&form_id={$form.FORM_ID}"
                                           class="btn btn-sm btn-outline-primary d-flex align-items-center"
                                           title="View submissions for this form">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z" />
                                                <path d="M8 5.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5z" />
                                            </svg>
                                            <span class="d-none d-md-inline ms-2">Submissions</span>
                                            <span class="badge bg-primary ms-2">{$form.sub_count|default:0}</span>
                                        </a>
                                    </div>
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