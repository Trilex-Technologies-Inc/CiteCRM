<div class="offcanvas-lg offcanvas-start app-sidebar sb-sidebar" tabindex="-1"
     id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
    <div class="offcanvas-header d-lg-none text-white">
        <h5 class="offcanvas-title" id="sidebarOffcanvasLabel">
            {$company_name|default:"Cite CRM"}
        </h5>
        <button type="button" class="btn-close btn-close-white"
                data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-0 d-flex flex-column">
        <div class="app-sidebar-brand">
            <a class="sb-sidebar-brand d-flex align-items-center justify-content-center"
               href="index.php" aria-label="Home">
                <span class="sb-sidebar-brand-icon" aria-hidden="true">
                    <i class="bi bi-hexagon-fill"></i>
                </span>
                <span class="sb-sidebar-brand-text">
                    {$company_name|default:"Cite CRM"}
                </span>
            </a>
        </div>

	        <div class="app-sidebar-menu px-2 py-2 border-bottom border-white border-opacity-10">
	            <div class="text-uppercase small text-white-50 px-3 mb-1">
	                Menu
	            </div>
	            <nav class="app-sidebar-nav">
	                {assign var="nav_customer_id" value=$customer_details.0.CUSTOMER_ID|default:$single_workorder_array.0.CUSTOMER_ID|default:$smarty.request.customer_id|default:""}
	                {assign var="nav_customer_name" value=$customer_details.0.CUSTOMER_DISPLAY_NAME|default:$single_workorder_array.0.CUSTOMER_DISPLAY_NAME|default:$smarty.request.customer_name|default:$smarty.request.page_title|default:""}
	                {assign var="nav_employee_id" value=$employee_details.0.EMPLOYEE_ID|default:$smarty.request.employee_id|default:""}
	                {assign var="nav_wo_id" value=$single_workorder_array.0.WORK_ORDER_ID|default:$smarty.request.wo_id|default:""}
	                {assign var="nav_wo_status" value=$single_workorder_array.0.WORK_ORDER_STATUS|default:""}
	                {assign var="nav_wo_current_status" value=$single_workorder_array.0.WORK_ORDER_CURENT_STATUS|default:""}
	                {assign var="nav_wo_employee" value=$single_workorder_array.0.EMPLOYEE_DISPLAY_NAME|default:""}
	                {assign var="nav_part" value=$part|default:""}

	                <a class="app-nav-item {if $current_module == 'core' && $current_page == 'main'}active{/if}"
	                   href="index.php">
	                    <span class="app-nav-icon" aria-hidden="true">
	                        <i class="bi bi-house-door-fill"></i>
	                    </span>
	                    <span class="app-nav-label">{$translate_menu_home|default:"Dashboard"}</span>
	                </a>

	                <details class="app-nav-group" {if $current_module == 'customer'}open{/if}>
	                    <summary class="app-nav-item {if $current_module == 'customer'}active{/if}">
	                        <span class="app-nav-icon" aria-hidden="true">
	                            <i class="bi bi-people-fill"></i>
	                        </span>
	                        <span class="app-nav-label">{$translate_menu_customers|default:"Customers"}</span>
	                    </summary>
	                    <div class="app-nav-sub">
	                        <a class="app-nav-subitem {if $current_module == 'customer' && $current_page == 'view'}active{/if}"
	                           href="?page=customer:view&page_title={$translate_menu_customer_search|default:$translate_menu_customers|default:"Customers"}">
	                            {$translate_menu_search|default:"Search"}
	                        </a>
	                        <a class="app-nav-subitem {if $current_module == 'customer' && $current_page == 'new'}active{/if}"
	                           href="?page=customer:new&page_title={$translate_menu_add_new_customer|default:"New Customer"}">
	                            {$translate_menu_new|default:"New"}
	                        </a>
	                        {if $nav_customer_id != ""}
	                            <hr class="border-light opacity-25 my-2">
	                            <a class="app-nav-subitem"
	                               href="?page=customer:edit&customer_id={$nav_customer_id}&page_title={$translate_menu_edit_customer|default:$translate_menu_edit|default:"Edit"}">
	                                {$translate_menu_edit|default:"Edit"}
	                            </a>
	                            <a class="app-nav-subitem"
	                               href="?page=billing:new_gift&customer_id={$nav_customer_id}&page_title={$translate_menu_new_gift|default:"New Gift"}&customer_name={$nav_customer_name|escape:'url'}">
	                                {$translate_menu_gift_cert|default:"Gift Certificate"}
	                            </a>
	                            <a class="app-nav-subitem text-danger"
	                               href="?page=customer:delete&customer_id={$nav_customer_id}&page_title={$translate_menu_delete_customer|default:$translate_menu_delete|default:"Delete"}">
	                                {$translate_menu_delete|default:"Delete"}
	                            </a>
	                        {/if}
	                    </div>
	                </details>

	                <details class="app-nav-group" {if $current_module == 'workorder'}open{/if}>
	                    <summary class="app-nav-item {if $current_module == 'workorder'}active{/if}">
	                        <span class="app-nav-icon" aria-hidden="true">
	                            <i class="bi bi-clipboard-check-fill"></i>
	                        </span>
	                        <span class="app-nav-label">{$translate_menu_work_orders|default:"Work Orders"}</span>
	                    </summary>
	                    <div class="app-nav-sub">
	                        <a class="app-nav-subitem {if $current_module == 'workorder' && $current_page == 'main'}active{/if}"
	                           href="?page=workorder:main&page_title={$translate_menu_work_orders|default:"Work Orders"}">
	                            {$translate_menu_open_work_orders|default:"Open Work Orders"}
	                        </a>
	                        <a class="app-nav-subitem {if $current_module == 'workorder' && $current_page == 'view_closed'}active{/if}"
	                           href="?page=workorder:view_closed&page_title={$translate_menu_closed_work_orders|default:"Closed Work Orders"}">
	                            {$translate_menu_closed_work_orders|default:"Closed Work Orders"}
	                        </a>
	                        {if $nav_customer_id != ""}
	                            <a class="app-nav-subitem {if $current_module == 'workorder' && $current_page == 'new'}active{/if}"
	                               href="?page=workorder:new&customer_id={$nav_customer_id}&page_title=Create%20New%20Work%20Order">
	                                New Work Order
	                            </a>
	                        {else}
	                            <a class="app-nav-subitem"
	                               href="index.php?page=customer:view&page_title={$translate_menu_customer_search|default:$translate_menu_customers|default:"Customers"}">
	                                {$translate_menu_new|default:"New"}
	                            </a>
	                        {/if}

	                        {if $nav_wo_id != ""}
	                            <hr class="border-light opacity-25 my-2">

	                            {if $nav_wo_status != "6"}
	                                <a class="app-nav-subitem"
	                                   href="?page=workorder:new_note&wo_id={$nav_wo_id}&page_title={$translate_menu_new_note|default:"New Note"}">
	                                    {$translate_menu_new_note|default:"New Note"}
	                                </a>

	                                {if $nav_wo_status == "10"}
	                                    {if $nav_part == "1"}
	                                        {if $nav_wo_current_status == "3"}
	                                            <a class="app-nav-subitem"
	                                               href="?page=parts:update&wo_id={$nav_wo_id}&page_title={$translate_menu_recieved_parts|default:"Received Parts"}">
	                                                {$translate_menu_recieved_parts|default:"Received Parts"}
	                                            </a>
	                                        {/if}
	                                    {else}
	                                        <a class="app-nav-subitem"
	                                           href="?page=parts:main&wo_id={$nav_wo_id}&page_title={$translate_menu_order_parts|default:"Order Parts"}">
	                                            {$translate_menu_order_parts|default:"Order Parts"}
	                                        </a>
	                                    {/if}
	                                {/if}

	                                {if $nav_wo_employee != "" && $nav_wo_status == "10"}
	                                    <a class="app-nav-subitem"
	                                       href="?page=workorder:close&wo_id={$nav_wo_id}&page_title={$translate_menu_close_work_order|default:$translate_menu_close|default:"Close"}%20{$nav_wo_id}">
	                                        {$translate_menu_close|default:"Close"}
	                                    </a>
	                                {/if}
	                            {/if}

	                            <a class="app-nav-subitem"
	                               href="?page=workorder:print&wo_id={$nav_wo_id}&page_title={$translate_menu_print|default:"Print"}&escape=1">
	                                {$translate_menu_print|default:"Print"}
	                            </a>

	                            {if ($nav_wo_employee != "" && $nav_wo_status != "6") || $nav_customer_id != ""}
	                                <a class="app-nav-subitem"
	                                   href="?page=invoice:new&wo_id={$nav_wo_id}&page_title={$translate_menu_invoice|default:"Invoice"}&customer_id={$nav_customer_id}">
	                                    {$translate_menu_invoice|default:"Invoice"}
	                                </a>
	                            {/if}
	                        {/if}
	                    </div>
	                </details>

	                <details class="app-nav-group" {if $current_module == 'employees'}open{/if}>
	                    <summary class="app-nav-item {if $current_module == 'employees'}active{/if}">
	                        <span class="app-nav-icon" aria-hidden="true">
	                            <i class="bi bi-person-badge-fill"></i>
	                        </span>
	                        <span class="app-nav-label">{$translate_menu_employees|default:"Employees"}</span>
	                    </summary>
	                    <div class="app-nav-sub">
	                        <a class="app-nav-subitem {if $current_module == 'employees' && $current_page == 'main'}active{/if}"
	                           href="?page=employees:main&page_title={$translate_menu_employees|default:"Employees"}">
	                            {$translate_menu_search|default:"Search"}
	                        </a>
	                        <a class="app-nav-subitem {if $current_module == 'employees' && $current_page == 'new'}active{/if}"
	                           href="?page=employees:new&page_title=New%20Employee">
	                            {$translate_menu_new|default:"New"}
	                        </a>
	                        {if $nav_employee_id != ""}
	                            <a class="app-nav-subitem"
	                               href="?page=employees:edit&employee_id={$nav_employee_id}&page_title={$translate_menu_edit|default:"Edit"}">
	                                {$translate_menu_edit|default:"Edit"}
	                            </a>
	                        {/if}
	                    </div>
	                </details>

	                <details class="app-nav-group" {if $current_module == 'invoice'}open{/if}>
	                    <summary class="app-nav-item {if $current_module == 'invoice'}active{/if}">
	                        <span class="app-nav-icon" aria-hidden="true">
	                            <i class="bi bi-receipt-cutoff"></i>
	                        </span>
	                        <span class="app-nav-label">{$translate_menu_invoice|default:"Invoices"}</span>
	                    </summary>
	                    <div class="app-nav-sub">
	                        <a class="app-nav-subitem {if $current_module == 'invoice' && $current_page == 'view_paid'}active{/if}"
	                           href="?page=invoice:view_paid&page_title={$translate_menu_paid_2|default:"Paid Invoices"}">
	                            {$translate_menu_paid|default:"Paid"}
	                        </a>
	                        <a class="app-nav-subitem {if $current_module == 'invoice' && $current_page == 'view_unpaid'}active{/if}"
	                           href="?page=invoice:view_unpaid&page_title={$translate_menu_un_paid_2|default:"Unpaid Invoices"}">
	                            {$translate_menu_un_paid|default:"Unpaid"}
	                        </a>
	                        <a class="app-nav-subitem {if $current_module == 'parts' && $current_page == 'status' && $smarty.request.status == '1'}active{/if}"
	                           href="?page=parts:status&status=1&page_title={$translate_menu_open_orders|default:"Open Orders"}">
	                            {$translate_menu_open_orders|default:"Open Orders"}
	                        </a>
	                        <a class="app-nav-subitem {if $current_module == 'parts' && $current_page == 'status' && $smarty.request.status == '0'}active{/if}"
	                           href="?page=parts:status&status=0&page_title={$translate_menu_closed_orders|default:"Closed Orders"}">
	                            {$translate_menu_closed_orders|default:"Closed Orders"}
	                        </a>
	                    </div>
	                </details>

	                <details class="app-nav-group" {if $current_module == 'stats'}open{/if}>
	                    <summary class="app-nav-item {if $current_module == 'stats'}active{/if}">
	                        <span class="app-nav-icon" aria-hidden="true">
	                            <i class="bi bi-bar-chart-fill"></i>
	                        </span>
	                        <span class="app-nav-label">{$translate_menu_stats|default:"Reports"}</span>
	                    </summary>
	                    <div class="app-nav-sub">
	                        <a class="app-nav-subitem {if $current_module == 'stats' && $current_page == 'main'}active{/if}"
	                           href="?page=stats:main&page_title=Stats">
	                            Office Stats
	                        </a>
	                        <a class="app-nav-subitem {if $current_module == 'stats' && $current_page == 'hit_stats'}active{/if}"
	                           href="?page=stats:hit_stats&page_title=Traffic">
	                            Web Traffic
	                        </a>
	                    </div>
	                </details>

	               
	                <a class="app-nav-item {if $current_module == 'schedual'}active{/if}"
	                   href="?page=schedual:main&page_title={$translate_core_schedule|default:"Schedule"}">
	                    <span class="app-nav-icon" aria-hidden="true">
	                        <i class="bi bi-calendar-event-fill"></i>
	                    </span>
	                    <span class="app-nav-label">{$translate_core_schedule|default:"Schedule"}</span>
	                </a>

	                <details class="app-nav-group">
	                    <summary class="app-nav-item">
	                        <span class="app-nav-icon" aria-hidden="true">
	                            <i class="bi bi-question-circle-fill"></i>
	                        </span>
	                        <span class="app-nav-label">Help</span>
	                    </summary>
	                    <div class="app-nav-sub">
	                        <a class="app-nav-subitem" href="http://www.citecrm.com/docs/" target="new">
	                            {$translate_core_documentation|default:"Documentation"}
	                        </a>
	                        <a class="app-nav-subitem" href="http://www.citecrm.com/bugs" target="new">
	                            {$translate_core_report_bug|default:"Report Bug"}
	                        </a>
	                        <a class="app-nav-subitem"
	                           href="?page=control:main&page_title={$translate_core_control|default:"Control Center"}">
	                            {$translate_core_control|default:"Control Center"}
	                        </a>
	                        <a class="app-nav-subitem" href="http://www.citecrm.com" target="new">
	                            Cite CRM
	                        </a>
	                        <a class="app-nav-subitem" href="http://forums.citecrm.com/" target="new">
	                            Forums
	                        </a>
	                    </div>
	                </details>
	                <a class="app-nav-item" href="index.php?action=logout">
	                    <span class="app-nav-icon" aria-hidden="true">
	                        <i class="bi bi-box-arrow-right"></i>
	                    </span>
	                    <span class="app-nav-label">{$translate_menu_log_out|default:$translate_core_log_off|default:"Sign out"}</span>
	                </a>

	            </nav>
	        </div>

        <div class="app-sidebar-widgets px-3 py-3">
            <div class="card shadow-sm mb-3 sidebar-card">
                <div class="card-header bg-body-secondary py-2">
                    <span class="fw-semibold small text-uppercase">
                        {$translate_core_schedule}
                    </span>
                </div>
                <div class="card-body">
                    <div id="calendar-container" class="mb-2"></div>
                    <p class="small text-muted mb-0 text-center">
                        {$translate_core_click_scheudle}
                    </p>
                </div>
            </div>

            {literal}
            <style>
                #calendar-container .fc { font-family: inherit; font-size: 12px; }

                #calendar-container .fc-toolbar { margin: 0 0 .35rem 0 !important; }
                #calendar-container .fc-toolbar-title { font-size: .9rem; font-weight: 600; }
                #calendar-container .fc-button {
                    background: transparent !important;
                    border: 0 !important;
                    box-shadow: none !important;
                    padding: .1rem .35rem !important;
                    color: var(--bs-secondary-color, #6c757d) !important;
                }
                #calendar-container .fc-button:hover { background: rgba(0,0,0,.06) !important; }
                #calendar-container .fc-button:focus { box-shadow: 0 0 0 .15rem rgba(13,110,253,.2) !important; }
                #calendar-container .fc-icon { font-size: 1.05rem; line-height: 1; }
                #calendar-container .fc-icon-chevron-left:before { content: "‹"; font-family: inherit; font-weight: 700; }
                #calendar-container .fc-icon-chevron-right:before { content: "›"; font-family: inherit; font-weight: 700; }

                #calendar-container .fc-scrollgrid,
                #calendar-container .fc-scrollgrid td,
                #calendar-container .fc-scrollgrid th,
                #calendar-container .fc-theme-standard td,
                #calendar-container .fc-theme-standard th { border: 0 !important; }

                #calendar-container .fc-col-header-cell-cushion {
                    padding: .15rem 0 !important;
                    font-size: .7rem;
                    font-weight: 600;
                    color: var(--bs-secondary-color, #6c757d);
                }

                #calendar-container .fc-daygrid-day { cursor: pointer; }
                #calendar-container .fc-daygrid-day-frame {
                    aspect-ratio: 1 / 1;
                    display: flex;
                    border-radius: 0;
                    align-items: center;
                    justify-content: center;
                    margin: 2px;
                }
                #calendar-container .fc-daygrid-day:hover .fc-daygrid-day-frame { background: rgba(0,0,0,.06); }

                #calendar-container .fc-daygrid-day-top { width: 100%; justify-content: center; }
                #calendar-container .fc-daygrid-day-number {
                    float: none !important;
                    padding: 0 !important;
                    width: 100%;
                    text-align: center;
                    color: #111 !important;
                }

                #calendar-container .fc-day-other .fc-daygrid-day-number {
                    color: rgba(0,0,0,.35) !important;
                }

                #calendar-container .fc-day-today { background: transparent !important; }
                #calendar-container .fc-day-today .fc-daygrid-day-frame { background: #0d6efd !important; }
                #calendar-container .fc-day-today .fc-daygrid-day-number { color: #fff !important; }

                #calendar-container .fc-daygrid-day-events,
                #calendar-container .fc-daygrid-day-bottom { display: none !important; }

                #calendar-container .fc-scroller { overflow: hidden !important; }
                #calendar-container .fc-view-harness { height: auto !important; }
            </style>
            <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function() {
                    var calendarEl = document.getElementById('calendar-container');
                    if (!calendarEl || typeof FullCalendar === 'undefined') return;

                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        headerToolbar: { left: 'prev', center: 'title', right: 'next' },
                        height: 'auto',
                        contentHeight: 'auto',
                        aspectRatio: 0.85,
                        fixedWeekCount: false,
                        dayHeaderContent: function(arg) {
                            return String(arg.text || '').substring(0, 2);
                        },
                        titleFormat: { year: 'numeric', month: 'long' },
                        dateClick: function(info) {
                            var date = info.date;
                            var y = date.getFullYear();
                            var m = date.getMonth() + 1;
                            var d = date.getDate();

                            window.location =
                                "?page=schedual:main&y=" + y +
                                "&m=" + m +
                                "&d=" + d +
                                "&wo_id={/literal}{$wo_id}{literal}" +
                                "&page_title={/literal}{$translate_core_schedule}{literal}";
                        }
                    });

                    calendar.render();
                });
            </script>
            {/literal}
