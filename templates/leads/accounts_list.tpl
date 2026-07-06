<h2>Accounts</h2>
<p>
  <a class="btn btn-sm btn-primary" href="index.php?page=leads:accounts_edit">Create Account</a>
  <a class="btn btn-sm btn-secondary" href="index.php?page=leads:list">Back to Leads</a>
</p>
{if $accounts|@count == 0}
    <p>No accounts found.</p>
{else}
    <table class="table table-striped">
        <thead><tr><th>Name</th><th>Phone</th><th>Website</th><th>Actions</th></tr></thead>
        <tbody>
        {foreach from=$accounts item=a}
        <tr>
            <td>{$a.ACCOUNT_NAME|escape}</td>
            <td>{$a.ACCOUNT_PHONE|escape}</td>
            <td>{$a.ACCOUNT_WEBSITE|escape}</td>
            <td><a class="btn btn-sm btn-outline-secondary" href="index.php?page=leads:accounts_edit&account_id={$a.ACCOUNT_ID}">Edit</a></td>
        </tr>
        {/foreach}
        </tbody>
    </table>
{/if}
