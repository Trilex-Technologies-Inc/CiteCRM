<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header">
            <h2 class="mb-0">Message Result</h2>
        </div>

        <div class="card-body">

            {if isset($result.error)}
                <div class="alert alert-danger">
                    {$result.error|escape}
                </div>
            {/if}

            {if $result.invalid|@count}
                <div class="alert alert-warning">
                    <strong>Invalid email addresses:</strong>
                    {$result.invalid|@implode:', '}
                </div>
            {/if}

            {if $result.sent|@count}
                <div class="alert alert-success">
                    <strong>Sent to:</strong>
                    {$result.sent|@implode:', '}
                </div>
            {/if}

            {if $result.failed|@count}
                <div class="alert alert-danger">
                    <strong>Failed to send to:</strong>
                    {$result.failed|@implode:', '}
                </div>
            {/if}

        </div>

        <div class="card-footer">
            <div class="d-flex gap-2">
                <a href="index.php?page=messaging:list"
                   class="btn btn-outline-secondary">
                    Back to list
                </a>

                <a href="index.php?page=messaging:compose"
                   class="btn btn-primary">
                    Compose another
                </a>
            </div>
        </div>

    </div>

</div>