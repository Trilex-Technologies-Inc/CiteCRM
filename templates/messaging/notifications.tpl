<div class="card">
  <div class="card-header">Notifications</div>
  <div class="card-body">
    {if $notifications|@count == 0}
      <p>No notifications.</p>
    {else}
      <ul class="list-group">
      {foreach from=$notifications item=n}
        <li class="list-group-item{if $n.IS_READ == 0} list-group-item-warning{/if}">
          <strong>{$n.EVENT}</strong> — {$n.EMAIL} <br/>
          <small>{$n.CREATED_AT}</small>
          <a href="?page=messaging:notifications&mark_read={$n.NOTIFICATION_ID}" class="btn btn-sm btn-link">Mark read</a>
        </li>
      {/foreach}
      </ul>
    {/if}
  </div>
</div>
