<!-- Tool Bar -->

<td>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-3 p-0">
        <div class="container-fluid px-0">
            <ul class="navbar-nav me-auto mb-0 flex-wrap">
                <!-- Home -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="index.php" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        {$translate_menu_home}
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="index.php">
                                {$translate_menu_home}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="index.php?action=logout">
                                {$translate_menu_log_out}
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Customers -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"
                       href="?page=customer:view&page_title={$translate_menu_customers}"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {$translate_menu_customers}
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item"
                               href="?page=customer:view&page_title={$translate_menu_customer_search}">
                                {$translate_menu_search}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="?page=customer:new&page_title={$translate_menu_add_new_customer}">
                                {$translate_menu_new}
                            </a>
                        </li>

	{if $customer_details[i].CUSTOMER_ID != ''}
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item"
                                   href="?page=customer:edit&customer_id={$customer_details[i].CUSTOMER_ID}&page_title={$translate_menu_edit_customer}">
                                    {$translate_menu_edit}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="?page=billing:new_gift&customer_id={$customer_details[i].CUSTOMER_ID}&page_title={$translate_menu_new_gift}&customer_name={$customer_details[i].CUSTOMER_DISPLAY_NAME}">
                                    {$translate_menu_gift_cert}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger"
                                   href="?page=customer:delete&customer_id={$customer_details[i].CUSTOMER_ID}&page_title={$translate_menu_delete_customer}">
                                    {$translate_menu_delete}
                                </a>
                            </li>
	{/if}
                    </ul>
                </li>

                <!-- Work Orders -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"
                       href="?page=workorder:main&page_title={$translate_menu_work_orders}"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {$translate_menu_work_orders}
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item"
                               href="?page=workorder:main&page_title={$translate_menu_work_orders}">
                                {$translate_menu_open_work_orders}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="?page=workorder:view_closed&page_title={$translate_menu_closed_work_orders}">
                                {$translate_menu_closed_work_orders}
                            </a>
                        </li>

	{if $customer_details[i].CUSTOMER_ID != ''}
                            <li>
                                <a class="dropdown-item"
                                   href="?page=workorder:new&customer_id={$customer_details[i].CUSTOMER_ID}&page_title=Create New Work Order">
                                    New Work Order
                                </a>
                            </li>
	{else}
                            <li>
                                <a class="dropdown-item"
                                   href="index.php?page=customer:view&page_title={$translate_menu_customer_search}">
                                    {$translate_menu_new}
                                </a>
                            </li>
	{/if}
	
	{if $single_workorder_array[i].WORK_ORDER_ID != ''}
                            <li><hr class="dropdown-divider"></li>

                            {if $single_workorder_array[i].WORK_ORDER_STATUS != "6"}
                                <li>
                                    <a class="dropdown-item"
                                       href="?page=workorder:new_note&wo_id={$single_workorder_array[i].WORK_ORDER_ID}&page_title={$translate_menu_new_note}">
                                        {$translate_menu_new_note}
                                    </a>
                                </li>

                                {if $single_workorder_array[i].WORK_ORDER_STATUS == "10"}
				{if $part == "1"}
                                        {if $single_workorder_array[i].WORK_ORDER_CURENT_STATUS == "3"}
                                            <li>
                                                <a class="dropdown-item"
                                                   href="?page=parts:update&wo_id={$single_workorder_array[i].WORK_ORDER_ID}&page_title={$translate_menu_recieved_parts}">
                                                    {$translate_menu_recieved_parts}
                                                </a>
                                            </li>
					{/if}
				{else}
                                        <li>
                                            <a class="dropdown-item"
                                               href="?page=parts:main&wo_id={$single_workorder_array[i].WORK_ORDER_ID}&page_title={$translate_menu_order_parts}">
                                                {$translate_menu_order_parts}
                                            </a>
                                        </li>
                                    {/if}
				{/if}
			{/if}
			
                            {if $single_workorder_array[i].EMPLOYEE_DISPLAY_NAME != ""}
                                {if $single_workorder_array[i].WORK_ORDER_STATUS == "10"}
                                    <li>
                                        <a class="dropdown-item"
                                           href="?page=workorder:close&wo_id={$single_workorder_array[i].WORK_ORDER_ID}&page_title={$translate_menu_close_work_order} {$single_workorder_array[i].WORK_ORDER_ID}">
                                            {$translate_menu_close}
                                        </a>
                                    </li>
			{/if}
		{/if}
		
                            <li>
                                <a class="dropdown-item"
                                   href="?page=workorder:print&wo_id={$single_workorder_array[i].WORK_ORDER_ID}&page_title={$translate_menu_print}&escape=1">
                                    {$translate_menu_print}
                                </a>
                            </li>

                            {if $single_workorder_array[i].EMPLOYEE_DISPLAY_NAME != "" && $single_workorder_array[i].WORK_ORDER_STATUS != "6"}
                                <li>
                                    <a class="dropdown-item"
                                       href="?page=invoice:new&wo_id={$single_workorder_array[i].WORK_ORDER_ID}&page_title={$translate_menu_invoice}&customer_id={$single_workorder_array[i].CUSTOMER_ID}">
                                        {$translate_menu_invoice}
                                    </a>
                                </li>
			{/if}
		{/if}
                    </ul>
                </li>

                <!-- Employees -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"
                       href="?page=employees:main&page_title={$translate_menu_employees}"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {$translate_menu_employees}
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item"
                               href="?page=employees:main&page_title={$translate_menu_search}">
                                {$translate_menu_search}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="?page=employees:new&page_title={$translate_menu_new}">
                                {$translate_menu_new}
                            </a>
                        </li>

	{if $employee_details[i].EMPLOYEE_ID != ''}
                            <li>
                                <a class="dropdown-item"
                                   href="?page=employees:edit&employee_id={$employee_details[i].EMPLOYEE_ID}&page_title={$translate_menu_edit}">
                                    {$translate_menu_edit}
                                </a>
                            </li>
	{/if}
                    </ul>
                </li>

                <!-- Invoices -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"
                       href="?page=invoice:view_paid&page_title={$translate_menu_invoice}"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {$translate_menu_invoice}
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item"
                               href="?page=invoice:view_paid&page_title={$translate_menu_paid_2}">
                                {$translate_menu_paid}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="?page=invoice:view_unpaid&page_title={$translate_menu_un_paid_2}">
                                {$translate_menu_un_paid}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="?page=parts:status&status=1&page_title={$translate_menu_open_orders}">
                                {$translate_menu_open_orders}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="?page=parts:status&status=0&page_title={$translate_menu_closed_orders}">
                                {$translate_menu_closed_orders}
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Help -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        Help
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item"
                               href="http://www.citecrm.com/docs/" target="new">
                                {$translate_core_documentation}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="http://www.citecrm.com/bugs" target="new">
                                {$translate_core_report_bug}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="?page=control:main&page_title={$translate_core_control}">
                                {$translate_core_control}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="http://www.citecrm.com" target="new">
                                Cite CRM
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="http://forums.citecrm.com/" target="new">
                                Forums
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</td>


