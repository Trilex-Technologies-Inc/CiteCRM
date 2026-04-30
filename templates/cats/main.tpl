<!-- Main Cats TPL -->
{literal}
<script language="JavaScript">
    function go() {
        box = document.forms[1].page_no;
        destination = box.options[box.selectedIndex].value;
        if (destination) location.href = destination;
    }
</script>
{/literal}

<!-- Toolbar -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        {include file="core/admin_tool_bar.tpl"}
    </div>
</div>

<!-- Main Content Container -->
<div class="container-fluid p-3">
	    <!-- Cat Search Card -->
	    <div class="card mb-4">
	        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
	            <h5 class="mb-0">Cat Search</h5>
	            <i class="bi bi-question-circle-fill fs-5 text-white"
	               aria-hidden="true"
	               onMouseOver="ddrivetip('<b>Cat Search</b><hr><p>Search for cats by description.</p>')"
	               onMouseOut="hideddrivetip()"></i>
	        </div>
        
        <div class="card-body">
            {if $error_msg != ""}
                <div class="alert alert-danger">
                    {include file="core/error.tpl"}
                </div>
            {/if}
            
            <!-- Search Form and Pagination Row -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="POST" action="?page=cats:main" class="form-inline">
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="description" class="col-form-label"><strong>Description</strong></label>
                            </div>
                            <div class="col-auto">
                                <input class="form-control" id="description" name="description" type="text" />
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary" name="submit" value="Search" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-items-center">
                        <a href="?page=cats%3Amain&description={$description}&submit=submit&page_no=1" class="me-2">
                            <img src="images/rewnd_24.gif" border="0" alt="First">
                        </a>
                        {if $previous != ''}
                            <a href="?page=cats%3Amain&description={$description}&submit=submit&page_no={$previous}" class="me-2">
                                <img src="images/back_24.gif" border="0" alt="Previous">
                            </a>
                        {/if}
                        
                        <select name="page_no" onChange="go()" class="form-select w-auto mx-2">
                            {section name=page loop=$total_pages start=1}
                                <option value="?page=cats%3Amain&description={$description}&submit=submit&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index} Selected {/if}>
                                    Page {$smarty.section.page.index} of {$total_pages}
                                </option>
                            {/section}
                        </select>
                        
                        {if $next != ''}
                            <a href="?page=cats%3Amain&description={$description}&submit=submit&page_no={$next}" class="me-2 ms-2">
                                <img src="images/forwd_24.gif" border="0" alt="Next">
                            </a>
                        {/if}
                        
                        <a href="?page=cats%3Amain&description={$description}&submit=submit&page_no={$total_pages}">
                            <img src="images/fastf_24.gif" border="0" alt="Last">
                        </a>
                    </div>
                    <div class="text-end mt-2">
                        <span class="badge bg-info">{$total_results} records found</span>
                    </div>
                </div>
            </div>
            
            <!-- Alphabet Filter -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="btn-group flex-wrap" role="group">
                        {foreach from=$alpha item=alpha}
                            <a href="?page=cats%3Amain&description={$alpha}&submit=submit" class="btn btn-outline-secondary btn-sm">{$alpha}</a>
                        {/foreach}
                    </div>
                </div>
            </div>
            
            <!-- Cats Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>ID</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {section name=i loop=$cat_search_result}
                        <tr ondblclick="window.location='index.php?page=cats:cat_details&id={$cat_search_result[i].ID}';" style="cursor: pointer;">
                            <td>
                                <a href="?page=cats:cat_details&id={$cat_search_result[i].ID}">
                                    {$cat_search_result[i].ID}
                                </a>
	                            </td>
	                            <td>
	                                <i class="bi bi-info-circle-fill text-primary me-1 fs-5"
	                                   aria-hidden="true"
	                                   onMouseOver="ddrivetip('{$cat_search_result[i].DESCRIPTION}')"
	                                   onMouseOut="hideddrivetip()"></i>
	                                {$cat_search_result[i].DESCRIPTION}
	                            </td>
	                            <td class="text-center">
	                                <a href="?page=cats:cat_details&id={$cat_search_result[i].ID}" class="text-decoration-none me-2">
	                                    <i class="bi bi-eye-fill text-secondary  fs-5"
	                                       aria-hidden="true"
	                                       onMouseOver="ddrivetip('View Details')"
	                                       onMouseOut="hideddrivetip()"></i>
	                                </a>
	                                <a href="?page=cats:edit&id={$cat_search_result[i].ID}" class="text-decoration-none me-2">
	                                    <i class="bi bi-pencil-square text-secondary fs-5"
	                                       aria-hidden="true"
	                                       onMouseOver="ddrivetip('Edit')"
	                                       onMouseOut="hideddrivetip()"></i>
	                                </a>
	                                <a href="?page=cats:subcat_new&cat_id={$cat_search_result[i].ID}" class="text-decoration-none me-2">
	                                    <i class="bi bi-plus-circle-fill text-success"
	                                       aria-hidden="true"
	                                       onMouseOver="ddrivetip('Add Subcategory')"
	                                       onMouseOut="hideddrivetip()"></i>
	                                </a>
	                                <a href="?page=cats:delete&id={$cat_search_result[i].ID}" onclick="return confirm('Are you sure you want to delete this cat?');" class="text-decoration-none">
	                                    <i class="bi bi-trash-fill text-danger"
	                                       aria-hidden="true"
	                                       onMouseOver="ddrivetip('Delete')"
	                                       onMouseOut="hideddrivetip()"></i>
	                                </a>
	                            </td>
                        </tr>
                        <!-- Nested subcategories -->
                        {section name=j loop=$cat_search_result[i].subcats}
                        <tr class="table-secondary">
                            <td>&nbsp;&nbsp;&mdash; {$cat_search_result[i].subcats[j].SUB_CATEGORY}</td>
                            <td>&nbsp;&nbsp;&mdash; {$cat_search_result[i].subcats[j].DESCRIPTION}</td>
	                            <td class="text-center">
	                                <a href="?page=cats:subcat_edit&id={$cat_search_result[i].subcats[j].ID}" class="text-decoration-none me-2">
	                                    <i class="bi bi-pencil-square text-secondary fs-5"
	                                       aria-hidden="true"
	                                       onMouseOver="ddrivetip('Edit Subcat')"
	                                       onMouseOut="hideddrivetip()"></i>
	                                </a>
	                                <a href="?page=cats:subcat_delete&id={$cat_search_result[i].subcats[j].ID}" onclick="return confirm('Are you sure?');" class="text-decoration-none">
	                                    <i class="bi bi-trash-fill text-danger"
	                                       aria-hidden="true"
	                                       onMouseOver="ddrivetip('Delete Subcat')"
	                                       onMouseOut="hideddrivetip()"></i>
	                                </a>
	                            </td>
                        </tr>
                        {/section}
                        {/section}
                    </tbody>
                </table>
            </div>
            
            <!-- Add New Cat Button -->
            <div class="mt-3">
                <a href="?page=cats:new" class="btn btn-success">Add New Cat</a>
            </div>
        </div>
    </div>
</div>
