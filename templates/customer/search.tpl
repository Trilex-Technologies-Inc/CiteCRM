<!-- Customer Search TPL -->
{literal}
<script type="text/javascript">
//<![CDATA[
function validate_customer_search(frm) {
    var value = frm.elements['name'] ? frm.elements['name'].value : '';
    var msg = '';

    if (value !== '' && value.length > 50) {
        msg = msg + '\n - Customers Name cannot be more than 50 characters';
    }

    if (msg !== '') {
        msg = 'Invalid information entered.' + msg;
        msg = msg + '\nPlease correct these fields.';
        alert(msg);
        return false;
    }
    return true;
}
//]]>
</script>
{/literal}

<!-- Toolbar -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        {include file="core/tool_bar.tpl"}
    </div>
</div>

<!-- Main Content Container -->
<div class="container-fluid p-3">
    <!-- Customer Search Card -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{$translate_main_title}</h5>
            <a href="http://www.incitecrm.com/doc/#Customers" target="_blank" rel="noopener noreferrer" aria-label="Help: Customer Search">
                <i class="bi bi-question-circle-fill fs-5 text-white"
                   aria-hidden="true"
                   onMouseOver="ddrivetip('<b>Customer Search</b><hr><p>You can search by the customers full display name or just their first name. If you wish to see all the customers for just one letter like A Click the letter A.</p> <p>To find customers whos name starts with Ja enter just ja. The system will intelegently look for the corect customers that match. To view all customers leave the name field blank and click view.</p>')"
                   onMouseOut="hideddrivetip()"></i>
            </a>
        </div>

        <div class="card-body">
            {if $error_msg != ""}
                <div class="alert alert-danger">
                    {include file="core/error.tpl"}
                </div>
            {/if}

            <!-- Search Form and Pagination Row -->
            <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <form action="index.php" method="get" name="customer_search" id="customer_search" onsubmit="return validate_customer_search(this);">
                        <input name="page" type="hidden" value="customer:view" />
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="name" class="col-form-label"><strong>{$translate_display}</strong></label>
                            </div>
                            <div class="col-auto">
                                <input class="form-control" id="name" name="name" type="text" value="{$name|escape:'html'}" maxlength="50" placeholder="Search…" />
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary" name="submit" value="Search" type="submit">Search</button>
                                <a class="btn btn-outline-secondary ms-1" href="?page=customer%3Aview&submit=submit">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-items-center flex-wrap gap-2">
                        <a href="?page=customer%3Aview&name={$name|escape:'url'}&submit=submit&page_no=1" class="btn btn-outline-secondary btn-sm" aria-label="First page" title="First">
                            <img src="images/rewnd_24.gif" alt="First" />
                        </a>

                        {if $previous != ''}
                            <a href="?page=customer%3Aview&name={$name|escape:'url'}&submit=submit&page_no={$previous}" class="btn btn-outline-secondary btn-sm" aria-label="Previous page" title="Previous">
                                <img src="images/back_24.gif" alt="Previous" />
                            </a>
                        {else}
                            <span class="btn btn-outline-secondary btn-sm disabled" aria-disabled="true" title="Previous">
                                <img src="images/back_24.gif" alt="Previous" />
                            </span>
                        {/if}

                        <select name="page_no" onchange="if (this.value) window.location.href = this.value;" class="form-select form-select-sm w-auto" aria-label="Select page">
                            {section name=page start=1 loop=$total_pages+1}
                                <option value="?page=customer%3Aview&name={$name|escape:'url'}&submit=submit&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index} selected {/if}>
                                    {$translate_page} {$smarty.section.page.index} {$translate_of} {$total_pages}
                                </option>
                            {/section}
                        </select>

                        {if $next != ''}
                            <a href="?page=customer%3Aview&name={$name|escape:'url'}&submit=submit&page_no={$next}" class="btn btn-outline-secondary btn-sm" aria-label="Next page" title="Next">
                                <img src="images/forwd_24.gif" alt="Next" />
                            </a>
                        {else}
                            <span class="btn btn-outline-secondary btn-sm disabled" aria-disabled="true" title="Next">
                                <img src="images/forwd_24.gif" alt="Next" />
                            </span>
                        {/if}

                        <a href="?page=customer%3Aview&name={$name|escape:'url'}&submit=submit&page_no={$total_pages}" class="btn btn-outline-secondary btn-sm" aria-label="Last page" title="Last">
                            <img src="images/fastf_24.gif" alt="Last" />
                        </a>
                    </div>
                    <div class="text-end mt-2">
                        <span class="badge bg-info">{$total_results} {$translate_records_found}</span>
                    </div>
                </div>
            </div>

            <!-- Alphabet Filter -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="btn-group flex-wrap" role="group" aria-label="Filter by first letter">
                        {foreach from=$alpha item=alpha}
                            <a href="?page=customer%3Aview&name={$alpha|escape:'url'}&submit=submit" class="btn btn-outline-secondary btn-sm">{$alpha}</a>
                        {/foreach}
                    </div>
                </div>
            </div>

            <!-- Customers Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">{$translate_display}</th>
                            <th scope="col">{$translate_first}</th>
                            <th scope="col">{$translate_last}</th>
                            <th scope="col">{$translate_phone}</th>
                            <th scope="col">{$translate_type}</th>
                            <th scope="col">{$translate_email}</th>
                            <th scope="col">{$translate_action}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {section name=i loop=$customer_search_result}
                            <tr ondblclick="window.location.href='index.php?page=customer:customer_details&customer_id={$customer_search_result[i].CUSTOMER_ID}&page_title={$customer_search_result[i].CUSTOMER_DISPLAY_NAME|escape:'url'}';" style="cursor: pointer;">
                                <td class="text-nowrap">
                                    <a href="index.php?page=customer:customer_details&customer_id={$customer_search_result[i].CUSTOMER_ID}&page_title={$customer_search_result[i].CUSTOMER_DISPLAY_NAME|escape:'url'}">
                                        {$customer_search_result[i].CUSTOMER_ID}
                                    </a>
                                </td>
                                <td class="text-nowrap">
                                    <i class="bi bi-info-circle-fill text-primary me-1 fs-5"
                                       aria-hidden="true"
                                       onMouseOver="ddrivetip('{$customer_search_result[i].CUSTOMER_ADDRESS}<br>{$customer_search_result[i].CUSTOMER_CITY}, {$customer_search_result[i].CUSTOMER_STATE} {$customer_search_result[i].CUSTOMER_ZIP}')"
                                       onMouseOut="hideddrivetip()"></i>
                                    {$customer_search_result[i].CUSTOMER_DISPLAY_NAME}
                                </td>
                                <td class="text-nowrap">{$customer_search_result[i].CUSTOMER_FIRST_NAME}</td>
                                <td class="text-nowrap">{$customer_search_result[i].CUSTOMER_LAST_NAME}</td>
                                <td class="text-nowrap">
                                    <i class="bi bi-info-circle-fill text-primary me-1 fs-5"
                                       aria-hidden="true"
                                       onMouseOver="ddrivetip('<b>Work: </b>{$customer_search_result[i].CUSTOMER_WORK_PHONE}<br><b>Mobile:</b>{$customer_search_result[i].CUSTOMER_MOBILE_PHONE}')"
                                       onMouseOut="hideddrivetip()"></i>
                                    {$customer_search_result[i].CUSTOMER_PHONE}
                                </td>
                                <td class="text-nowrap">
                                    {if $customer_search_result[i].CUSTOMER_TYPE ==1}
                                        {$translate_customer_type_1}
                                    {/if}
                                    {if $customer_search_result[i].CUSTOMER_TYPE ==2}
                                        {$translate_customer_type_2}
                                    {/if}
                                    {if $customer_search_result[i].CUSTOMER_TYPE ==3}
                                        {$translate_customer_type_3}
                                    {/if}
                                    {if $customer_search_result[i].CUSTOMER_TYPE ==4}
                                        {$translate_customer_type_4}
                                    {/if}
                                </td>
                                <td class="text-nowrap">
                                    <a href="mailto:{$customer_search_result[i].CUSTOMER_EMAIL}" class="text-primary">
                                        {$customer_search_result[i].CUSTOMER_EMAIL}
                                    </a>
                                </td>
                                <td class="text-nowrap text-center">
                                    <a href="?page=customer:customer_details&customer_id={$customer_search_result[i].CUSTOMER_ID}&page_title={$customer_search_result[i].CUSTOMER_DISPLAY_NAME|escape:'url'}" class="text-decoration-none me-2" aria-label="View customer details">
                                        <i class="bi bi-eye-fill text-secondary  fs-5"
                                           aria-hidden="true"
                                           onMouseOver="ddrivetip('View Customer Details')"
                                           onMouseOut="hideddrivetip()"></i>
                                    </a>
                                    <a href="?page=workorder:new&customer_id={$customer_search_result[i].CUSTOMER_ID}&page_title=New Work Order" class="text-decoration-none" aria-label="Create new work order">
                                        <i class="bi bi-clipboard-plus-fill text-primary fs-5"
                                           aria-hidden="true"
                                           onMouseOver="ddrivetip('New Work Order')"
                                           onMouseOut="hideddrivetip()"></i>
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
