<!-- Customer Details TPL -->
{section name=i loop=$customer_details}
{literal}
<script type="text/javascript">
function confirmSubmit(){
    <!--
    var answer = confirm ("Are you Sure you want to delete customer {/literal}{$customer_details[i].CUSTOMER_DISPLAY_NAME}{literal}? This will remove all work order history and invoices. You might want to just set customer to Inactive.")
    if (answer)
        window.location="?page=customer:delete&customer_id={/literal}{$customer_details[i].CUSTOMER_ID}{literal}"    
    }
// -->
</script>
{/literal}

<!-- Toolbar -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        {include file="core/tool_bar.tpl"}
    </div>
</div>

{if $error_msg != ""}
    <div class="alert alert-danger">
        {include file="core/error.tpl"}
    </div>
{/if}
{if $msg !=""}
    <div class="alert alert-success">
        {include file="core/msg.tpl"}
    </div>
{/if}

<!-- Main Content Container -->
<div class="container-fluid p-3">
    <!-- Customer Details Card -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{$translate_customer_details} {$customer_details[i].CUSTOMER_DISPLAY_NAME}</h5>
            <a href="http://www.citecrm.com/docs/#Customers" target="new">
                <i class="bi bi-question-circle-fill fs-5 text-white" aria-hidden="true"></i>
            </a>
        </div>
        
        <div class="card-body">
            <!-- Customer Contact Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">{$translate_customer_contact}</h6>
                </div>
                <div class="card-body">
                    <!-- Contact Name & Email -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>{$translate_customer_contact_2}</strong>
                        </div>
                        <div class="col-md-3">
                            {$customer_details[i].CUSTOMER_FIRST_NAME} {$customer_details[i].CUSTOMER_LAST_NAME}
                        </div>
                        <div class="col-md-3">
                            <strong>{$translate_email}</strong>
                        </div>
                        <div class="col-md-3">
                            {$customer_details[i].CUSTOMER_EMAIL}
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <!-- Address & Phone Section -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>{$translate_customer_address}</strong>
                        </div>
                        <div class="col-md-3">
                            {$customer_details[i].CUSTOMER_DISPLAY_NAME}
                        </div>
                        <div class="col-md-3">
                            <strong>{$translate_customer_home}</strong>
                        </div>
                        <div class="col-md-3">
                            {$customer_details[i].CUSTOMER_PHONE}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {$customer_details[i].CUSTOMER_ADDRESS}
                        </div>
                        <div class="col-md-3">
                            <strong>{$translate_customer_work}</strong>
                        </div>
                        <div class="col-md-3">
                            {$customer_details[i].CUSTOMER_WORK_PHONE}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {$customer_details[i].CUSTOMER_CITY}, {$customer_details[i].CUSTOMER_STATE} {$customer_details[i].CUSTOMER_ZIP}
                        </div>
                        <div class="col-md-3">
                            <strong>{$translate_customer_mobile}</strong>
                        </div>
                        <div class="col-md-3">
                            {$customer_details[i].CUSTOMER_MOBILE_PHONE}
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <!-- Customer Type & Discount -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>{$translate_customer_type}</strong>
                        </div>
                        <div class="col-md-3">
                            {if $customer_details[i].CUSTOMER_TYPE ==1}
                                {$translate_customer_type_1}
                            {/if}
                            {if $customer_details[i].CUSTOMER_TYPE ==2}
                                {$translate_customer_type_2}
                            {/if}
                            {if $customer_details[i].CUSTOMER_TYPE ==3}
                                {$translate_customer_type_3}
                            {/if}
                            {if $customer_details[i].CUSTOMER_TYPE ==4}
                                {$translate_customer_type_4}
                            {/if}
                        </div>
                        <div class="col-md-3">
                            <strong>{$translate_customer_discount}</strong>
                        </div>
                        <div class="col-md-3">
                            {$customer_details[i].DISCOUNT}%
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <!-- Created & Last Active -->
                    <div class="row">
                        <div class="col-md-3">
                            <strong>{$translate_customer_created}</strong>
                        </div>
                        <div class="col-md-3">
                            {$customer_details[i].CREATE_DATE|date_format:"%m-%d-%Y"}
                        </div>
                        <div class="col-md-3">
                            <strong>{$translate_customer_last}</strong>
                        </div>
                        <div class="col-md-3">
                            {$customer_details[i].LAST_ACTIVE|date_format:"%m-%d-%Y"}
                        </div>
                    </div>
                </div>
            </div>
            
            {assign var="customer_id" value=$customer_details[i].CUSTOMER_ID}
            {assign var="customer_name" value=$customer_details[i].CUSTOMER_DISPLAY_NAME}
{/section}

            <!-- Memos Card -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">Memo</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        {section name=m loop=$memo}
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <strong>Date</strong> {$memo[m].DATE|date_format:"%m-%d-%Y"}
                                <a href="?page=customer:memo&action=delete&note_id={$memo[m].ID}&customer_name={$customer_name}&customer_id={$customer_id}" class="text-danger">
                                    Delete
                                </a>
                            </div>
                            <div class="mt-2">
                                {$memo[m].NOTE}
                            </div>
                        </div>
                        {/section}
                        <div class="list-group-item">
                            <a href="?page=customer:memo&customer_id={$customer_id}&page_title=New Memo&customer_name={$customer_name}" class="btn btn-primary btn-sm">
                                New Memo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Open Work Orders Section -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">{$translate_customer_open_work_orders}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>{$translate_customer_wo_id}</th>
                                    <th>{$translate_customer_date_open}</th>
                                    <th>{$translate_customer}</th>
                                    <th>{$translate_customer_scope}</th>
                                    <th>{$translate_customer_status}</th>
                                    <th>{$translate_customer_tech}</th>
                                    <th>{$translate_customer_action}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {section name=a loop=$open_work_orders}
                                <tr ondblclick="window.location='?page=workorder:view&wo_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}&page_title={$translate_customer_work_order_id} {$open_work_orders[a].WORK_ORDER_ID}';" style="cursor: pointer;">
                                    <td>
                                        <a href="?page=workorder:view&wo_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}&page_title={$translate_customer_work_order_id} {$open_work_orders[a].WORK_ORDER_ID}">
                                            {$open_work_orders[a].WORK_ORDER_ID}
                                        </a>
                                    </td>
                                    <td>{$open_work_orders[a].WORK_ORDER_OPEN_DATE|date_format:"%m-%d-%Y"}</td>
                                    <td>{section name=i loop=$customer_details}{$customer_details[i].CUSTOMER_DISPLAY_NAME}{/section}</td>
                                    <td>{$open_work_orders[a].WORK_ORDER_SCOPE}</td>
                                    <td>{$open_work_orders[a].CONFIG_WORK_ORDER_STATUS}</td>
                                    <td>
                                        {if $open_work_orders[a].EMPLOYEE_ID != ''}
                                            <i class="bi bi-info-circle-fill text-primary me-1 fs-5"
                                               aria-hidden="true"
                                               onMouseOver="ddrivetip('<center><b>{$translate_contact}</b></center><hr><b>{$translate_work} </b>{$open_work_orders[a].EMPLOYEE_WORK_PHONE}<br><b>{$translate_mobile} </b>{$open_work_orders[a].EMPLOYEE_MOBILE_PHONE}<br><b>{$translate_home} </b>{$open_work_orders[a].EMPLOYEE_HOME_PHONE}')"
                                               onMouseOut="hideddrivetip()"></i>
                                            <a href="?page=employees:employee_details&employee_id={$open_work_orders[a].EMPLOYEE_ID}&page_title={$open_work_orders[a].EMPLOYEE_DISPLAY_NAME}" class="text-primary">
                                                {$open_work_orders[a].EMPLOYEE_DISPLAY_NAME}
                                            </a>
                                        {else}
                                            Not Assigned
                                        {/if}
                                    </td>
                                    <td class="text-center">
                                        <a href="?page=workorder:print&wo_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}&escape=1" target="new" class="text-decoration-none me-2">
                                            <i class="bi bi-printer-fill fs-5" aria-hidden="true" >l text-secondary"
                                               aria-hidden="true"
                                               onMouseOver="ddrivetip('{$translate_customer_print}')"
                                               onMouseOut="hideddrivetip()"></i>
                                        </a>
                                        <a href="?page=workorder:view&wo_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}" class="text-decoration-none">
                                            <i class="bi bi-eye-fill text-secondary  fs-5"
                                               aria-hidden="true"
                                               onMouseOver="ddrivetip('{$translate_customer_view_wo}')"
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
            
            <!-- Gift Certificates Card -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">{$translate_customer_gift_cert}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>{$translate_customer_id}</th>
                                    <th>{$translate_customer_created}</th>
                                    <th>{$translate_customer_expire}</th>
                                    <th>{$translate_customer_amount}</th>
                                    <th>{$translate_customer_active}</th>
                                    <th>{$translate_customer_redeemed}</th>
                                    <th>{$translate_customer_invoice}</th>
                                    <th>{$translate_customer_action}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {section name=g loop=$gift}
                                <tr>
                                    <td>{$gift[g].GIFT_ID}</td>
                                    <td>{$gift[g].DATE_CREATE|date_format:"%m/%d/%Y"}</td>
                                    <td>{$gift[g].EXPIRE|date_format:"%m/%d/%Y"}</td>
                                    <td>${$gift[g].AMOUNT}</td>
                                    <td>{if $gift[g].ACTIVE == 1} Yes {else} No{/if}</td>
                                    <td>{if $gift[g].DATE_REDEMED == 0}None {else} {$gift[g].DATE_REDEMED|date_format:"%m/%d/%Y"}{/if}</td>
                                    <td>{if $gift[g].INVOICE_ID == 0}None{else} <a href="">{$gift[g].INVOICE_ID}</a>{/if}</td>
                                    <td class="text-center">
                                        {if $gift[g].ACTIVE == 1}
                                            <a href="?page=billing:new_gift&gift_id={$gift[g].GIFT_ID}&customer_id={$gift[g].CUSTOMER_ID}&action=print&submit=1&escape=1" target="new" class="text-decoration-none me-2">
                                                <i class="bi bi-printer-fill text-secondary  fs-5"
                                                   aria-hidden="true"
                                                   onMouseOver="ddrivetip('{$translate_customer_print}')"
                                                   onMouseOut="hideddrivetip()"></i>
                                            </a>
                                            <a href="?page=billing:new_gift&gift_id={$gift[g].GIFT_ID}&customer_id={$gift[g].CUSTOMER_ID}&action=delete&submit=1" class="text-decoration-none">
                                                <i class="bi bi-trash-fill text-danger"
                                                   aria-hidden="true"
                                                   onMouseOver="ddrivetip('{$translate_customer_delete}')"
                                                   onMouseOut="hideddrivetip()"></i>
                                            </a>
                                        {else}
                                            Not Active
                                        {/if}
                                    </td>
                                </tr>
                                {/section}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Unpaid Invoices Card -->
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0">{$translate_customer_unpaid_invoice}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>{$translate_customer_inv_id}</th>
                                    <th>{$translate_customer_wo_id}</th>
                                    <th>{$translate_customer_date}</th>
                                    <th>{$translate_customer_amount}</th>
                                    <th>{$translate_customer_paid}</th>
                                    <th>{$translate_customer_balance}</th>
                                    <th>{$translate_customer_date_paid}</th>
                                    <th>{$translate_customer_employee}</th>
                                    <th>{$translate_customer_action}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {section name=w loop=$unpaid_invoices}
                                <tr ondblclick="window.location='?page=invoice:new&wo_id={$unpaid_invoices[w].WORKORDER_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&page_title={$translate_customer_invoice}';" style="cursor: pointer;">
                                    <td><a href="?page=invoice:new&wo_id={$unpaid_invoices[w].WORKORDER_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&page_title={$translate_customer_invoice}">{$unpaid_invoices[w].INVOICE_ID}</a></td>
                                    <td><a href="?page=workorder:view&wo_id={$unpaid_invoices[w].WORKORDER_ID}&page_title={$translate_customer_work_order_id} {$unpaid_invoices[w].WORKORDER_ID}">{$unpaid_invoices[w].WORKORDER_ID}</a></td>
                                    <td>{$unpaid_invoices[w].INVOICE_DATE|date_format:"%m-%d-%y"}</td>
                                    <td>${$unpaid_invoices[w].INVOICE_AMOUNT|string_format:"%.2f"}</td>
                                    <td>${$unpaid_invoices[w].PAID_AMOUNT|string_format:"%.2f"}</td>
                                    <td>${$unpaid_invoices[w].BALLANCE|string_format:"%.2f"}</td>
                                    <td>{$unpaid_invoices[w].PAID_DATE|date_format:"%m-%d-%y"}</td>
	                                    <td>{$unpaid_invoices[w].EMPLOYEE_DISPLAY_NAME}</td>
	                                    <td class="text-center">
	                                        <a href="?page=invoice:print&invoice_id={$unpaid_invoices[w].INVOICE_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&escape=1" target="new" class="text-decoration-none me-2">
	                                            <i class="bi bi-printer-fill text-secondary  fs-5"
	                                               aria-hidden="true"
	                                               onMouseOver="ddrivetip('{$translate_customer_print}')"
	                                               onMouseOut="hideddrivetip()"></i>
	                                        </a>
	                                        <a href="?page=workorder:view&wo_id={$unpaid_invoices[w].WORK_ORDER_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}" class="text-decoration-none">
	                                            <i class="bi bi-eye-fill text-secondary  fs-5"
	                                               aria-hidden="true"
	                                               onMouseOver="ddrivetip('{$translate_customer_view}')"
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
            
            <!-- Paid Invoices Card -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">{$translate_customer_paid_invoice}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>{$translate_customer_inv_id}</th>
                                    <th>{$translate_customer_wo_id}</th>
                                    <th>{$translate_customer_date}</th>
                                    <th>{$translate_customer_amount}</th>
                                    <th>{$translate_customer_paid}</th>
                                    <th>{$translate_customer_balance}</th>
                                    <th>{$translate_customer_paid}</th>
                                    <th>{$translate_customer_employee}</th>
                                    <th>{$translate_customer_action}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {section name=w loop=$paid_invoices}
                                <tr ondblclick="window.location='?page=invoice:view&customer_id={$paid_invoices[w].CUSTOMER_ID}&invoice_id={$paid_invoices[w].INVOICE_ID}&page_title={$translate_customer_invoice}';" style="cursor: pointer;">
                                    <td><a href="?page=invoice:view&customer_id={$paid_invoices[w].CUSTOMER_ID}&invoice_id={$paid_invoices[w].INVOICE_ID}&page_title={$translate_customer_invoice}">{$paid_invoices[w].INVOICE_ID}</a></td>
                                    <td><a href="?page=workorder:view&wo_id={$paid_invoices[w].WORKORDER_ID}&page_title={$translate_customer_work_order_id} {$paid_invoices[w].WORKORDER_ID}">{$paid_invoices[w].WORKORDER_ID}</a></td>
                                    <td>{$paid_invoices[w].INVOICE_DATE|date_format:"%m-%d-%y"}</td>
                                    <td>${$paid_invoices[w].INVOICE_AMOUNT|string_format:"%.2f"}</td>
                                    <td>${$paid_invoices[w].PAID_AMOUNT|string_format:"%.2f"}</td>
                                    <td>${$paid_invoices[w].BALLANCE|string_format:"%.2f"}</td>
                                    <td>{$paid_invoices[w].PAID_DATE|date_format:"%m-%d-%y"}</td>
	                                    <td>{$paid_invoices[w].EMPLOYEE_DISPLAY_NAME}</td>
	                                    <td class="text-center">
	                                        <a href="?page=invoice:print&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$paid_invoices[w].CUSTOMER_ID}&escape=1" target="new" class="text-decoration-none me-2">
	                                            <i class="bi bi-printer-fill text-secondary  fs-5"
	                                               aria-hidden="true"
	                                               onMouseOver="ddrivetip('{$translate_customer_print}')"
	                                               onMouseOut="hideddrivetip()"></i>
	                                        </a>
	                                        <a href="?page=invoice:view&customer_id={$paid_invoices[w].CUSTOMER_ID}&invoice_id={$paid_invoices[w].INVOICE_ID}&page_title={$translate_customer_invoice}" class="text-decoration-none">
	                                            <i class="bi bi-eye-fill text-secondary  fs-5"
	                                               aria-hidden="true"
	                                               onMouseOver="ddrivetip('{$translate_customer_view}')"
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
    </div>
</div>
