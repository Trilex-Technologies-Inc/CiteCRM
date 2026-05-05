<div class="container my-4">

    <!-- Toolbar -->
    <div class="mb-3">
        {include file="core/admin_tool_bar.tpl"}
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Category Details</h5>
        </div>

        <div class="card-body">

            {if $error_msg != ""}
                <div class="alert alert-danger">
                    {$error_msg}
                </div>
            {/if}

            <!-- Category Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <strong>Category ID:</strong> {$cat_info.ID}
                </div>
                <div class="col-md-6">
                    <strong>Description:</strong> {$cat_info.DESCRIPTION}
                </div>
            </div>

            <!-- Subcategories -->
            <h6>Subcategories:</h6>
            {if $subcats|@count > 0}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>ID</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {section name=i loop=$subcats}
                            <tr>
                                <td>{$subcats[i].SUB_CATEGORY}</td>
                                <td>{$subcats[i].DESCRIPTION}</td>
                                <td>
                                    <a href="?page=cats:subcat_edit&id={$subcats[i].ID}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <a href="?page=cats:subcat_delete&id={$subcats[i].ID}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?');">Delete</a>
                                </td>
                            </tr>
                            {/section}
                        </tbody>
                    </table>
                </div>
            {else}
                <p class="text-muted">No subcategories found.</p>
            {/if}

            <!-- Actions -->
            <div class="mt-3">
                <a href="?page=cats:subcat_new&cat_id={$cat_info.ID}" class="btn btn-success">Add Subcategory</a>
                <a href="?page=cats:edit&id={$cat_info.ID}" class="btn btn-primary">Edit Category</a>
                <a href="?page=cats:main" class="btn btn-secondary">Back to List</a>
            </div>

        </div>
    </div>

</div>