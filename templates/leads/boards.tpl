<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Boards</h2>

        <a href="index.php?page=leads:list"
           class="btn btn-secondary">
            Back to Leads
        </a>
    </div>

    {if $boards|@count == 0}

        <div class="alert alert-info">
            No boards created yet.
        </div>

    {else}

        <div class="row g-3 mb-4" id="boards">

            {foreach from=$boards item=b}
                <div class="col-md-4 col-lg-3">

                    <div class="card shadow-sm board h-100"
                         data-board-id="{$b.BOARD_ID}">

                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                {$b.BOARD_NAME|escape}
                            </h5>
                        </div>

                        <div class="card-body board-items"
                             style="min-height:250px;"
                             ondragover="event.preventDefault();"
                             ondrop="handleDrop(event, this, {$b.BOARD_ID})">

                            {foreach from=$b.items item=i}
                                <div class="card mb-2 board-item"
                                     draggable="true"
                                     data-item-id="{$i.ITEM_ID}"
                                     ondragstart="evt.dataTransfer.setData('text/plain', this.dataset.itemId);"
                                     style="cursor:move;">

                                    <div class="card-body p-2">
                                        {$i.LEAD_TITLE|escape}
                                    </div>

                                </div>
                            {/foreach}

                        </div>

                    </div>

                </div>
            {/foreach}

        </div>

    {/if}

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Board List</h5>

            <ul class="list-group">
                {foreach from=$boards item=b}
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{$b.BOARD_NAME|escape}</strong>
                            <div class="text-muted small">
                                {$b.BOARD_DESC|escape}
                            </div>
                        </div>
                    </li>
                {/foreach}
            </ul>

        </div>
    </div>

</div>

{literal}
<script>
function handleDrop(e, el, boardId) {
    e.preventDefault();

    var itemId = e.dataTransfer.getData('text/plain');

    fetch('index.php?page=leads:board_move', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'item_id=' + encodeURIComponent(itemId) +
              '&board_id=' + encodeURIComponent(boardId) +
              '&position=0'
    })
    .then(r => r.json())
    .then(j => {
        if (j.success) {
            location.reload();
        } else {
            alert('Move failed');
        }
    });
}
</script>
{/literal}