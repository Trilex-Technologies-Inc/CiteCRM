<!-- Customer Search TPL -->
{literal}
<script language="JavaScript">
    function go() {
        box = document.forms[1].page_no;
        destination = box.options[box.selectedIndex].value;
        if (destination) location.href = destination;
    }
</script>

<script type="text/javascript">
//<![CDATA[
function validate_customer_search(frm) {
    var value = '';
    var errFlag = new Array();
    var _qfGroups = {};
    _qfMsg = '';
    
    value = frm.elements['name'].value;
    if (value != '' && value.length > 50 && !errFlag['name']) {
        errFlag['name'] = true;
        _qfMsg = _qfMsg + '\n - Customers Name cannot be more than 50 characters';
    }
    
    if (_qfMsg != '') {
        _qfMsg = 'Invalid information entered.' + _qfMsg;
        _qfMsg = _qfMsg + '\nPlease correct these fields.';
        alert(_qfMsg);
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
            <a href="http://www.citecrm.com/docs/#Customers" target="new">
                <img src="images/icons/16x16/help.gif" border="0" alt="Help"
                    onMouseOver="ddrivetip('<b>Customer Search</b><hr><p>You can search by the customers full display name or just their first name. If you wish to see all the customers for just one letter like A Click the letter A.</p> <p>To find customers whos name starts with Ja enter just ja. The system will intelegently look for the corect customers that match. To view all customers leave the name field blank and click view.</p>')" 
                    onMouseOut="hideddrivetip()">
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
                <div class="col-md-6">
                    {literal}
                    <form action="index.php?page=customer:view" method="get" name="customer_search" id="customer_search" onsubmit="try { var myValidator = validate_customer_search; } catch(e) { return true; } return myValidator(this);">
                    {/literal}
                        <input name="page" type="hidden" value="customer:view" />
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="name" class="col-form-label"><strong>{$translate_display}</strong></label>
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
                        <a href="?page=customer%3Aview&name={$name}&submit=submit&page_no=1" class="me-2">
                            <img src="images/rewnd_24.gif" border="0" alt="First">
                        </a>
                        {if $previous != ''}
                            <a href="?page=customer%3Aview&name={$name}&submit=submit&page_no={$previous}" class="me-2">
                                <img src="images/back_24.gif" border="0" alt="Previous">
                            </a>
                        {/if}
                        
                        <select name="page_no" onChange="go()" class="form-select w-auto mx-2">
                            {section name=page loop=$total_pages start=1}
                                <option value="?page=customer%3Aview&name={$name}&submit=submit&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index} Selected {/if}>
                                    {$translate_page} {$smarty.section.page.index} {$translate_of} {$total_pages}
                                </option>
                            {/section}
                            <option value="?page=customer%3Aview&name={$name}&submit=submit&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
                                {$translate_page} {$total_pages} {$translate_of} {$total_pages}
                            </option>
                        </select>
                        
                        {if $next != ''}
                            <a href="?page=customer%3Aview&name={$name}&submit=submit&page_no={$next}" class="me-2 ms-2">
                                <img src="images/forwd_24.gif" border="0" alt="Next">
                            </a>
                        {/if}
                        
                        <a href="?page=customer%3Aview&name={$name}&submit=submit&page_no={$total_pages}">
                            <img src="images/fastf_24.gif" border="0" alt="Last">
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
                    <div class="btn-group flex-wrap" role="group">
                        {foreach from=$alpha item=alpha}
                            <a href="?page=customer%3Aview&name={$alpha}&submit=submit" class="btn btn-outline-secondary btn-sm">{$alpha}</a>
                        {/foreach}
                    </div>
                </div>
            </div>
            
            <!-- Customers Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>ID</th>
                            <th>{$translate_display}</th>
                            <th>{$translate_first}</th>
                            <th>{$translate_last}</th>
                            <th>{$translate_phone}</th>
                            <th>{$translate_type}</th>
                            <th>{$translate_email}</th>
                            <th>{$translate_action}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {section name=i loop=$customer_search_result}
                        <tr ondblclick="window.location='index.php?page=customer:customer_details&customer_id={$customer_search_result[i].CUSTOMER_ID}&page_title={$customer_search_result[i].CUSTOMER_DISPLAY_NAME}';" style="cursor: pointer;">
                            <td class="text-nowrap">
                                <a href="index.php?page=customer:customer_details&customer_id={$customer_search_result[i].CUSTOMER_ID}&page_title={$customer_search_result[i].CUSTOMER_DISPLAY_NAME}">
                                    {$customer_search_result[i].CUSTOMER_ID}
                                </a>
                            </td>
                            <td class="text-nowrap">
                                <img src="images/icons/16x16/view+.gif" border="0" class="me-1" 
                                    onMouseOver="ddrivetip('{$customer_search_result[i].CUSTOMER_ADDRESS}<br>{$customer_search_result[i].CUSTOMER_CITY}, {$customer_search_result[i].CUSTOMER_STATE} {$customer_search_result[i].CUSTOMER_ZIP}')" 
                                    onMouseOut="hideddrivetip()">
                                {$customer_search_result[i].CUSTOMER_DISPLAY_NAME}
                            </td>
                            <td class="text-nowrap">{$customer_search_result[i].CUSTOMER_FIRST_NAME}</td>
                            <td class="text-nowrap">{$customer_search_result[i].CUSTOMER_LAST_NAME}</td>
                            <td class="text-nowrap">
                                <img src="images/icons/16x16/view+.gif" border="0" class="me-1"
                                    onMouseOver="ddrivetip('<b>Work: </b>{$customer_search_result[i].CUSTOMER_WORK_PHONE}<br><b>Mobile:</b>{$customer_search_result[i].CUSTOMER_MOBILE_PHONE}')" 
                                    onMouseOut="hideddrivetip()">
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
                                <a href="?page=customer:customer_details&customer_id={$customer_search_result[i].CUSTOMER_ID}&page_title={$customer_search_result[i].CUSTOMER_DISPLAY_NAME}" class="text-decoration-none me-2">
                                    <img src="images/icons/16x16/viewmag.gif" border="0" onMouseOver="ddrivetip('View Customer Details')" onMouseOut="hideddrivetip()" alt="View">
                                </a>
                                <a href="?page=workorder:new&customer_id={$customer_search_result[i].CUSTOMER_ID}&page_title=New Work Order" class="text-decoration-none">
                                    <img src="images/icons/16x16/small_new_work_order.gif" border="0" onMouseOver="ddrivetip('New Work Order')" onMouseOut="hideddrivetip()" alt="New Work Order">
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
