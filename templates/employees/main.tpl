<!-- Main Employees TPL -->
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
        {include file="core/tool_bar.tpl"}
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a class="btn btn-success btn-sm" href="?page=employees:new&page_title=New%20Employee">
            <i class="bi bi-person-plus-fill me-1" aria-hidden="true"></i>
            {$translate_menu_add_new_employee|default:"New Employee"}
        </a>
    </div>
</div>

<!-- Main Content Container -->
<div class="container-fluid p-3">
    <!-- Employee Search Card -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{$translate_employee_search}</h5>
            <img src="images/icons/16x16/help.gif" border="0" alt="Help" 
                onMouseOver="ddrivetip('<b>Employee Search</b><hr><p>You can search by the employees full display name or just their first name. If you wish to see all the employees for just one letter like A enter the letter a only.</p> <p>To find employees whos name starts with Ja enter just ja. The system will intelegently look for the corect employee that matches.</p>')" 
                onMouseOut="hideddrivetip()">
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
                    <form method="POST" action="?page=employees:main" class="form-inline">
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="name" class="col-form-label"><strong>{$translate_employee_display_name}</strong></label>
                            </div>
                            <div class="col-auto">
                                <input class="form-control" id="name" name="name" type="text" />
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary" name="submit" value="Search" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-items-center">
                        <a href="?page=employees%3Amain&name={$name}&submit=submit&page_no=1" class="me-2">
                            <img src="images/rewnd_24.gif" border="0" alt="First">
                        </a>
                        {if $previous != ''}
                            <a href="?page=employees%3Amain&name={$name}&submit=submit&page_no={$previous}" class="me-2">
                                <img src="images/back_24.gif" border="0" alt="Previous">
                            </a>
                        {/if}
                        
                        <select name="page_no" onChange="go()" class="form-select w-auto mx-2">
                            {section name=page loop=$total_pages start=1}
                                <option value="?page=employees%3Amain&name={$name}&submit=submit&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index} Selected {/if}>
                                    {$translate_employee_page} {$smarty.section.page.index} {$translate_employee_of} {$total_pages}
                                </option>
                            {/section}
                            <option value="?page=employees%3Amain&name={$name}&submit=submit&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
                                {$translate_employee_page} {$total_pages} {$translate_employee_of} {$total_pages}
                            </option>
                        </select>
                        
                        {if $next != ''}
                            <a href="?page=employees%3Amain&name={$name}&submit=submit&page_no={$next}" class="me-2 ms-2">
                                <img src="images/forwd_24.gif" border="0" alt="Next">
                            </a>
                        {/if}
                        
                        <a href="?page=employees%3Amain&name={$name}&submit=submit&page_no={$total_pages}">
                            <img src="images/fastf_24.gif" border="0" alt="Last">
                        </a>
                    </div>
                    <div class="text-end mt-2">
                        <span class="badge bg-info">{$total_results} {$translate_employee_records_found}</span>
                    </div>
                </div>
            </div>
            
            <!-- Alphabet Filter -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="btn-group flex-wrap" role="group">
                        {foreach from=$alpha item=alpha}
                            <a href="?page=employees%3Amain&name={$alpha}&submit=submit" class="btn btn-outline-secondary btn-sm">{$alpha}</a>
                        {/foreach}
                    </div>
                </div>
            </div>
            
            <!-- Employees Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>{$translate_employee_id}</th>
                            <th>{$translate_employee_display}</th>
                            <th>{$translate_employee_first}</th>
                            <th>{$translate_employee_last}</th>
                            <th>{$translate_employee_work_phone}</th>
                            <th>{$translate_employee_type}</th>
                            <th>{$translate_employee_email}</th>
                            <th>{$translate_employee_action}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {section name=i loop=$employee_search_result}
                        <tr ondblclick="window.location='index.php?page=employees:employee_details&employee_id={$employee_search_result[i].EMPLOYEE_ID}&page_title={$translate_employee_details_for} {$employee_search_result[i].EMPLOYEE_DISPLAY_NAME}';" style="cursor: pointer;">
                            <td>
                                <a href="?page=employees:employee_details&employee_id={$employee_search_result[i].EMPLOYEE_ID}&page_title={$translate_employee_details_for} {$employee_search_result[i].EMPLOYEE_DISPLAY_NAME}">
                                    {$employee_search_result[i].EMPLOYEE_ID}
                                </a>
                            </td>
                            <td>
                                <img src="images/icons/16x16/view+.gif" border="0" class="me-1" 
                                    onMouseOver="ddrivetip('{$employee_search_result[i].EMPLOYEE_ADDRESS}<br>{$employee_search_result[i].EMPLOYEE_CITY}, {$employee_search_result[i].EMPLOYEE_SATE} {$employee_search_result[i].EMPLOYEE_ZIP}')" 
                                    onMouseOut="hideddrivetip()">
                                {$employee_search_result[i].EMPLOYEE_DISPLAY_NAME}
                            </td>
                            <td>{$employee_search_result[i].EMPLOYEE_FIRST_NAME}</td>
                            <td>{$employee_search_result[i].EMPLOYEE_LAST_NAME}</td>
                            <td>
                                <img src="images/icons/16x16/view+.gif" border="0" class="me-1"
                                    onMouseOver="ddrivetip('<b>{$translate_employee_home} </b>{$employee_search_result[i].EMPLOYEE_HOME_PHONE}<br><b>{$translate_employee_mobile} </b>{$employee_search_result[i].EMPLOYEE_MOBILE_PHONE}')" 
                                    onMouseOut="hideddrivetip()">
                                {$employee_search_result[i].EMPLOYEE_WORK_PHONE}
                            </td>
                            <td>{$employee_search_result[i].TYPE_NAME}</td>
                            <td>
                                <a href="mailto:{$employee_search_result[i].EMPLOYEE_EMAIL}" class="text-primary">
                                    {$employee_search_result[i].EMPLOYEE_EMAIL}
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="?page=employees:employee_details&employee_id={$employee_search_result[i].EMPLOYEE_ID}&page_title={$translate_employee_details_for} {$employee_search_result[i].EMPLOYEE_DISPLAY_NAME}" class="text-decoration-none me-2">
                                    <img src="images/icons/16x16/viewmag.gif" border="0" onMouseOver="ddrivetip('View Employees Details')" onMouseOut="hideddrivetip()" alt="View">
                                </a>
                                <a href="?page=employees:edit&employee_id={$employee_search_result[i].EMPLOYEE_ID}&page_title={$translate_employee_edit} {$employee_search_result[i].EMPLOYEE_DISPLAY_NAME}" class="text-decoration-none">
                                    <img src="images/icons/16x16/small_edit_employees.gif" border="0" onMouseOver="ddrivetip('Edit')" onMouseOut="hideddrivetip()" alt="Edit">
                                </a>
                            </td>
                        </tr>
                        {/section}
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>
