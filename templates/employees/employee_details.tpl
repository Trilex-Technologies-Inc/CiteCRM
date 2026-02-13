<!-- Employee Details TPL -->
{section name=i loop=$employee_details}

<!-- Toolbar -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        {include file="core/tool_bar.tpl"}
    </div>
</div>

<!-- Main Content Container -->
<div class="container-fluid p-3">
    <!-- Page Header -->
    <div class="card mb-3">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{$translate_employee_details_for} {$employee_details[i].EMPLOYEE_DISPLAY_NAME}</h5>
            <img src="images/icons/16x16/help.gif" border="0" alt="Help">
        </div>
        
        <div class="card-body">
            {if $error_msg != ""}
                {include file="core/error.tpl"}
            {/if}
            {include file="employees/edit.js"}
            
            <!-- Employee Contact Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">{$translate_employee_contact_information}</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>{$translate_employee_display_name}</strong>
                        </div>
                        <div class="col-md-3">
                            {$employee_details[i].EMPLOYEE_FIRST_NAME} {$employee_details[i].EMPLOYEE_LAST_NAME}
                        </div>
                        <div class="col-md-3">
                            <strong>{$translate_employee_email}</strong>
                        </div>
                        <div class="col-md-3">
                            {$employee_details[i].EMPLOYEE_EMAIL}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>{$translate_employee_first_name}</strong>
                        </div>
                        <div class="col-md-3">
                            {$employee_details[i].EMPLOYEE_FIRST_NAME}
                        </div>
                        <div class="col-md-3">
                            <strong>{$translate_employee_last_name}</strong>
                        </div>
                        <div class="col-md-3">
                            {$employee_details[i].EMPLOYEE_LAST_NAME}
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>{$translate_employee_address}</strong>
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                            <strong>{$translate_employee_home}</strong>
                        </div>
                        <div class="col-md-3">
                            {$employee_details[i].EMPLOYEE_HOME_PHONE}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {$employee_details[i].EMPLOYEE_ADDRESS}
                        </div>
                        <div class="col-md-3">
                            <strong>{$translate_employee_work_phone}</strong>
                        </div>
                        <div class="col-md-3">
                            {$employee_details[i].EMPLOYEE_WORK_PHONE}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {$employee_details[i].EMPLOYEE_CITY}, {$employee_details[i].EMPLOYEE_STATE} {$employee_details[i].EMPLOYEE_ZIP}
                        </div>
                        <div class="col-md-3">
                            <strong>{$translate_employee_mobile}</strong>
                        </div>
                        <div class="col-md-3">
                            {$employee_details[i].EMPLOYEE_MOBILE_PHONE}
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>{$translate_employee_type}</strong>
                        </div>
                        <div class="col-md-3">
                            {$employee_details[i].TYPE_NAME}
                        </div>
                        <div class="col-md-3">
                            <strong>{$translate_employee_login}</strong>
                        </div>
                        <div class="col-md-3">
                            {$employee_details[i].EMPLOYEE_LOGIN}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Open Work Orders Section -->
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">{$employee_details[i].EMPLOYEE_DISPLAY_NAME}'s Assigned Work Orders</h6>
                </div>
            </div>

{/section} <!-- Close the section loop here -->

            <!-- Work Orders Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>{$translate_employee_wo_id}</th>
                            <th>{$translate_employee_date_open}</th>
                            <th>{$translate_employee_customer}</th>
                            <th>{$translate_employee_scope}</th>
                            <th>{$translate_employee_status}</th>
                            <th>{$translate_employee_manager}</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {section name=a loop=$open_work_orders}
                        <tr ondblclick="window.location='?page=workorder:view&wo_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}&page_title={$translate_employee_work_order_id}{$open_work_orders[a].WORK_ORDER_ID},';" style="cursor: pointer;">
                            <td>{$open_work_orders[a].WORK_ORDER_ID}</td>
                            <td>{$open_work_orders[a].WORK_ORDER_OPEN_DATE|date_format:"%m/%d/%Y"}</td>
                            <td>
                                <img src="images/icons/16x16/view+.gif" border="0" class="me-1" onMouseOver="ddrivetip('<center><b>Customer Contact</b><hr></center><b>Home: </b>{$open_work_orders[a].CUSTOMER_PHONE}<br><b>Work: </b>{$open_work_orders[a].CUSTOMER_WORK_PHONE}<br><b>Mobile: </b>{$open_work_orders[a].CUSTOMER_MOBILE_PHONE}')"
                                    onMouseOut="hideddrivetip()">
                                {$open_work_orders[a].CUSTOMER_DISPLAY_NAME}
                            </td>
                            <td>
                                <img src="images/icons/16x16/view+.gif" border="0" class="me-1" onMouseOver="ddrivetip('<center><b>Description</b><hr></center>{$open_work_orders[a].WORK_ORDER_DESCRIPTION}')" onMouseOut="hideddrivetip()">
                                {$open_work_orders[a].WORK_ORDER_SCOPE}
                            </td>
                            <td>{$open_work_orders[a].CONFIG_WORK_ORDER_STATUS}</td>
                            <td>{$open_work_orders[a].EMPLOYEE_DISPLAY_NAME}</td>
                            <td class="text-center">
                                <a href="?page=workorder:print&wo_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}&escape=1" target="new" class="text-decoration-none me-2">
                                    <img src="images/icons/16x16/fileprint.gif" border="0" onMouseOver="ddrivetip('Print The Work Order')" onMouseOut="hideddrivetip()">
                                </a>
                                <a href="?page=workorder:view&wo_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}" class="text-decoration-none">
                                    <img src="images/icons/16x16/viewmag.gif" border="0" onMouseOver="ddrivetip('View The Work Order')" onMouseOut="hideddrivetip()">
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