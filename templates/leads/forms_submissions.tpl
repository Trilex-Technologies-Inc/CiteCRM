<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Submissions for: {$form.FORM_NAME|escape}</h2>

        <a href="index.php?page=leads:forms_list" class="btn btn-secondary">Back to Forms</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            {if $submissions|@count > 0}
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Submitted At</th>
                                <th>Source IP</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                        {foreach from=$submissions item=s}
                            <tr>
                                <td>{$s.SUBMISSION_ID}</td>
                                <td>{$s.SUBMITTED_AT}</td>
                                <td>{$s.SOURCE_IP}</td>
                                <td><pre style="max-width:600px;white-space:pre-wrap;">{$s.DATA|escape}</pre></td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>
            {else}
                <div class="alert alert-info">No submissions yet for this form.</div>
            {/if}

        </div>
    </div>

</div>
