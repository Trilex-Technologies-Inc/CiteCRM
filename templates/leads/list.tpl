<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Leads</h2>

        <div class="d-flex gap-2">
            <a class="btn btn-primary"
               href="index.php?page=leads:edit">
                Create New Lead
            </a>

            <a class="btn btn-secondary"
               href="index.php?page=leads:boards">
                Boards
            </a>
        </div>
    </div>

    {if isset($admin_links)}
        <div class="mb-3">
            <div class="card">
                <div class="card-body py-2">
                    {foreach from=$admin_links item=link name=adminlinks}
                        <a href="{$link.url|escape}" class="me-2">
                            {$link.label|escape}
                        </a>
                        {if !$smarty.foreach.adminlinks.last}
                            <span class="text-muted">|</span>
                        {/if}
                    {/foreach}
                </div>
            </div>
        </div>
    {/if}

    <div class="card shadow-sm">

        <div class="card-body">

            {if $leads|count == 0}

                <div class="alert alert-info mb-0">
                    No leads yet.
                </div>

            {else}

                <div class="table-responsive">

                    <table class="table table-striped table-hover align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th>Title</th>
                                <th>Account</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th width="140">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                        {foreach from=$leads item=l}
                            <tr>
                                <td>
                                    <strong>{$l.LEAD_TITLE|escape}</strong>
                                </td>

                                <td>
                                    {$l.ACCOUNT_NAME|default:''|escape}
                                </td>

                                <td>
                                    {$l.CONTACT_NAME|default:''|escape}
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {$l.LEAD_STATUS|escape}
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex gap-1">
                                        <a class="btn btn-sm btn-outline-secondary"
                                           href="index.php?page=leads:edit&lead_id={$l.LEAD_ID}">
                                            Edit
                                        </a>

                                        <a class="btn btn-sm btn-outline-primary"
                                           href="index.php?page=leads:view&lead_id={$l.LEAD_ID}">
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>

                    </table>

                </div>

            {/if}

        </div>
    </div>

</div>