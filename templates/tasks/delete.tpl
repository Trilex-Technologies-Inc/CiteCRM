<div class="container-fluid p-3">
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Delete Task</h5>
        </div>

        <div class="card-body">
            {if $error_msg|default:'' != ''}
                <div class="alert alert-danger">{$error_msg|escape}</div>
            {/if}

            <div class="alert alert-warning">
                <h5 class="alert-heading">Are you sure?</h5>
                <p>You are about to permanently delete this task:</p>
                <p class="mb-1"><strong>{$task.TITLE|escape}</strong></p>
                {if $task.DESCRIPTION != ''}
                    <p class="mb-0">{$task.DESCRIPTION|escape}</p>
                {/if}
            </div>

            <form method="post" action="index.php?page=tasks:delete">
                <input type="hidden" name="id" value="{$task_id|escape}">
                <input type="hidden" name="confirm" value="yes">
                <button class="btn btn-danger" type="submit">Delete Task</button>
                <a class="btn btn-secondary" href="index.php?page=tasks:main">Cancel</a>
            </form>
        </div>
    </div>
</div>
