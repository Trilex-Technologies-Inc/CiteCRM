<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header">
            <h2 class="mb-0">Connected Mailboxes</h2>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Provider</th>
                            <th>Email</th>
                            <th>Employee</th>
                            <th>Enabled</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$accounts item=a}
                        <tr>
                            <td>{$a.ACCOUNT_ID}</td>
                            <td>{$a.PROVIDER}</td>
                            <td>{$a.EMAIL}</td>
                            <td>{$a.EMPLOYEE_ID}</td>
                            <td>
                                {if $a.ENABLED}
                                    <span class="badge bg-success">Enabled</span>
                                {else}
                                    <span class="badge bg-secondary">Disabled</span>
                                {/if}
                            </td>
                            <td>
                                <a href="modules/messaging/accounts.php?action=disable&id={$a.ACCOUNT_ID}"
                                   class="btn btn-sm btn-danger">
                                    Disable
                                </a>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>