<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Contacts</h2>

        <div>
            <a class="btn btn-primary"
               href="index.php?page=leads:contacts_edit">
                Create Contact
            </a>

            <a class="btn btn-secondary"
               href="index.php?page=leads:list">
                Back to Leads
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            {if $contacts|@count == 0}

                <div class="alert alert-info mb-0">
                    No contacts found.
                </div>

            {else}

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Company</th>
                                <th width="100">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                        {foreach from=$contacts item=c}
                            <tr>
                                <td>
                                    <strong>{$c.CONTACT_NAME|escape}</strong>
                                </td>

                                <td>
                                    <a href="mailto:{$c.CONTACT_EMAIL|escape}">
                                        {$c.CONTACT_EMAIL|escape}
                                    </a>
                                </td>

                                <td>
                                    {$c.CONTACT_PHONE|escape}
                                </td>

                                <td>
                                    {$c.COMPANY|escape}
                                </td>

                                <td>
                                    <a class="btn btn-sm btn-outline-primary"
                                       href="index.php?page=leads:contacts_edit&contact_id={$c.CONTACT_ID}">
                                        Edit
                                    </a>
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