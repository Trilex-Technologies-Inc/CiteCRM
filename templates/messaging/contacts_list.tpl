<h2>{$translate_messaging_contacts}</h2>
<p>
    <a class="btn btn-sm btn-primary" href="index.php?page=messaging:contacts&action=new">{$translate_messaging_add_new_contact}</a>
    <a class="btn btn-sm btn-secondary" href="index.php?page=messaging:contacts&action=business_new">{$translate_messaging_add_business}</a>
</p>

{if $contacts|@count > 0}
<table class="table table-striped">
    <thead><tr><th>{$translate_messaging_name}</th><th>{$translate_messaging_email}</th><th>{$translate_messaging_business}</th><th>Action</th></tr></thead>
    <tbody>
    {foreach from=$contacts item=c}
    <tr>
        <td>{$c.FIRST_NAME|escape} {$c.LAST_NAME|escape}</td>
        <td>{$c.EMAIL|escape}</td>
        <td>{$c.BUSINESS_NAME|escape}</td>
        <td>
            <a class="btn btn-sm btn-outline-primary" href="index.php?page=messaging:contacts&action=view&contact_id={$c.CONTACT_ID}">View</a>
            <a class="btn btn-sm btn-outline-secondary" href="index.php?page=messaging:contacts&action=edit&contact_id={$c.CONTACT_ID}">Edit</a>
            <a class="btn btn-sm btn-outline-danger" href="index.php?page=messaging:contacts&action=delete&contact_id={$c.CONTACT_ID}" onclick="return confirm('{$translate_messaging_delete_confirm}');">Delete</a>
        </td>
    </tr>
    {/foreach}
    </tbody>
</table>
{else}
<p>{$translate_messaging_no_contacts}</p>
{/if}
