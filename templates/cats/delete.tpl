<div class="container my-4">

    <!-- Toolbar -->
    <div class="mb-3">
        {include file="core/admin_tool_bar.tpl"}
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Delete Cat</h5>
        </div>

        <div class="card-body">

            {if $error_msg != ""}
                <div class="alert alert-danger">
                    {include file="core/error.tpl"}
                </div>
            {/if}

            <div class="alert alert-warning">
                <h5 class="alert-heading">Are you sure?</h5>
                <p>You are about to delete the following cat:</p>
                <hr>
                <p><strong>ID:</strong> {$cat_details.ID}</p>
                <p><strong>Description:</strong> {$cat_details.DESCRIPTION}</p>
                <hr>
                <p class="mb-0">This action cannot be undone.</p>
            </div>

            <form action="?page=cats:delete" method="POST">
                <input type="hidden" name="cat_id" value="{$cat_details.ID}">
                <input type="hidden" name="confirm" value="yes">
                
                <div class="text-end">
                    <button type="submit" name="submit" class="btn btn-danger px-4">
                        Yes, Delete Cat
                    </button>
                    <a href="?page=cats:main" class="btn btn-secondary px-4">
                        Cancel
                    </a>
                </div>
            </form>

        </div>
    </div>

</div>