<div class="container-fluid">

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">{$translate_messaging_businesses}</h2>

            <a class="btn btn-primary"
               href="index.php?page=messaging:contacts&action=business_new">
                {$translate_messaging_add_new_business}
            </a>
        </div>

        <div class="card-body">

            {if $businesses|@count > 0}

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{$translate_messaging_business_name}</th>
                                <th>{$translate_messaging_business_phone}</th>
                                <th width="220">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$businesses item=b}
                            <tr>
                                <td>{$b.BUSINESS_NAME|escape}</td>
                                <td>{$b.BUSINESS_PHONE|escape}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a class="btn btn-outline-primary"
                                           href="index.php?page=messaging:contacts&action=business_view&business_id={$b.BUSINESS_ID}">
                                            View
                                        </a>

                                        <a class="btn btn-outline-secondary"
                                           href="index.php?page=messaging:contacts&action=business_edit&business_id={$b.BUSINESS_ID}">
                                            Edit
                                        </a>

                                        <a class="btn btn-outline-danger"
                                           href="index.php?page=messaging:contacts&action=business_delete&business_id={$b.BUSINESS_ID}"
                                           onclick="return confirm('Delete business?');">
                                            Delete
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
                    No businesses found.
                </div>

            {/if}

        </div>
    </div>

</div>