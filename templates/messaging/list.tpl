<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header d-flex align-items-center justify-content-between">
            <h2 class="mb-0">Messaging - Customers</h2>
            <div>
                <a href="index.php?page=messaging:compose" class="btn btn-sm btn-primary me-2">Compose</a>
                <a href="index.php?page=messaging:compose&mass=subscribed" class="btn btn-sm btn-success me-2">Email Subscribed Customers</a>
                <a href="index.php?page=messaging:logs" class="btn btn-sm btn-outline-secondary">Logs</a>
            </div>
        </div>

        <div class="card-body">

            <p class="mb-3">
                Select a customer to compose an email.
            </p>

            <div class="alert alert-info">
                Mass Email Campaigns can create personalized, branded HTML emails to up to 500 prospects at once.
            </div>

            {if $customers|@count > 0}

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th width="150">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            {foreach from=$customers item=c}
                            <tr>
                                <td>{$c.CUSTOMER_DISPLAY_NAME|escape}</td>
                                <td>{$c.CUSTOMER_EMAIL|escape}</td>
                                <td>
                                    <a href="index.php?page=messaging:compose&customer_id={$c.CUSTOMER_ID}"
                                       class="btn btn-sm btn-primary">
                                        Compose
                                    </a>
                                </td>
                            </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>

            {else}

                <div class="alert alert-warning mb-0">
                    No customers found.
                </div>

            {/if}

        </div>

    </div>

</div>