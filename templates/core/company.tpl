<!-- Office stats + app info (closes sidebar and opens main content) -->
        <div class="card shadow-sm mb-3 sidebar-card">
            <div class="card-header bg-body-secondary py-2">
                <span class="fw-semibold small text-uppercase">
                    {$translate_core_office_stats}
                </span>
            </div>
            <div class="card-body small">
                <p class="mb-1 fw-semibold">{$translate_core_work_order_stats}</p>
                <ul class="list-unstyled mb-0">
                    <li>{$open_count} {$translate_core_open_workorders}</li>
                    <li>{$assigned} {$translate_core_assigned_workorders}</li>
                    <li>{$awaiting} {$translate_core_waiting_payment}</li>
                </ul>
            </div>
        </div>

        <div class="card shadow-sm mb-3 sidebar-card">
            <div class="card-header bg-body-secondary py-2">
                <span class="fw-semibold small text-uppercase">
                    {$translate_core_cite_crm}
                </span>
            </div>
            <div class="card-body small">
                <p class="mb-1">
                    <strong>{$translate_core_version}</strong> {$VERSION}<br>
                    <strong>{$translate_core_update_status}</strong> None Available
                </p>
                <p class="mb-1 fw-semibold">{$translate_core_links}</p>
                <ul class="list-unstyled mb-0">
                    <li>
                        <a href="?page=control:main&page_title={$translate_core_control}">
                            {$translate_core_control}
                        </a>
                    </li>
                    <li>
                        <a href="http://www.citecrm.com/docs/" target="new">
                            {$translate_core_documentation}
                        </a>
                    </li>
                    <li>
                        <a href="http://www.citecrm.com" target="new">
                            www.citecrm.com
                        </a>
                    </li>
                    <li>
                        <a href="https://github.com/Trilex-Technologies-Inc/CiteCRM" target="new">
                            {$translate_core_report_bug}
                        </a>
                    </li>
                    <li>
                        <a href="https://www.incitecrm.com/module.php?modname=forum" target="new">
                            Support Forum
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="text-center d-none d-lg-block pt-2">
            <button type="button" class="sidebar-toggle" data-sidebar-collapse aria-label="Toggle sidebar">
                <i class="bi bi-chevron-left" aria-hidden="true"></i>
            </button>
        </div>

        </div><!-- /.app-sidebar-widgets -->
    </div><!-- /.offcanvas-body -->
</div><!-- /.app-sidebar -->

<div class="app-content flex-grow-1 d-flex flex-column">
    <!-- Top bar (right column) -->
    <nav class="navbar navbar-expand navbar-light app-topbar bg-white shadow-sm">
        <div class="container-fluid">
            <button class="btn btn-link text-secondary d-lg-none me-2" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas"
                    aria-controls="sidebarOffcanvas" aria-label="Toggle navigation">
                <i class="bi bi-list fs-3 lh-1" aria-hidden="true"></i>
            </button>

            <button class="btn btn-link text-secondary d-none d-lg-inline-flex me-2"
                    type="button" data-sidebar-collapse aria-label="Toggle sidebar">
                <i class="bi bi-chevron-left fs-5 lh-1" aria-hidden="true"></i>
            </button>

            <a class="navbar-brand fw-semibold d-lg-none" href="index.php">
                {$company_name|default:"Cite CRM"}
            </a>

            <form class="app-topbar-search d-none d-sm-flex ms-2 me-auto" action="index.php" method="GET" role="search">
                <div class="input-group input-group-sm">
                    <input class="form-control border-0 bg-light" type="search" name="q" placeholder="Search..." aria-label="Search">
                    <button class="btn btn-primary" type="submit" aria-label="Search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                    </button>
                </div>
            </form>

            <div class="ms-auto d-flex align-items-center gap-2">
                {if $login != ""}
                    <div class="dropdown">
                        <button class="btn btn-link text-secondary app-topbar-icon-btn {if $current_module == 'control' || $current_module == 'cats'}active{/if}"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Settings">
                            <i class="bi bi-gear-fill" aria-hidden="true"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <a class="dropdown-item {if $current_module == 'control' && $current_page == 'main'}active{/if}"
                                   href="?page=control:main&page_title={$translate_core_control|default:"Control Center"}">
                                    <i class="bi bi-sliders me-2" aria-hidden="true"></i>
                                    {$translate_core_control|default:"Control Center"}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {if $current_module == 'control' && $current_page == 'company_edit'}active{/if}"
                                   href="?page=control:company_edit&page_title=Company">
                                    <i class="bi bi-building me-2" aria-hidden="true"></i>
                                    Company
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {if $current_module == 'control' && $current_page == 'acl'}active{/if}"
                                   href="?page=control:acl&page_title=Permissions">
                                    <i class="bi bi-shield-lock me-2" aria-hidden="true"></i>
                                    Permissions
                                </a>
                            </li>

                            {if $show_admin_menu|default:false}
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item {if $current_module == 'control' && $current_page == 'hours_edit'}active{/if}"
                                       href="?page=control:hours_edit&page_title=Office%20Hours">
                                        <i class="bi bi-clock me-2" aria-hidden="true"></i>
                                        Office Hours
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {if $current_module == 'control' && $current_page == 'payment_options'}active{/if}"
                                       href="?page=control:payment_options&page_title=Payment%20Methods">
                                        <i class="bi bi-credit-card me-2" aria-hidden="true"></i>
                                        Payment Methods
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {if $current_module == 'control' && $current_page == 'edit_rate'}active{/if}"
                                       href="?page=control:edit_rate&page_title=Billing%20Rates">
                                        <i class="bi bi-cash-coin me-2" aria-hidden="true"></i>
                                        Billing Rates
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {if $current_module == 'control' && $current_page == 'check_updates'}active{/if}"
                                       href="?page=control:check_updates&page_title=Check%20For%20Updates">
                                        <i class="bi bi-arrow-repeat me-2" aria-hidden="true"></i>
                                        Check for Updates
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {if $current_module == 'cats'}active{/if}"
                                       href="?page=cats:main&page_title=Category">
                                        <i class="bi bi-tags me-2" aria-hidden="true"></i>
                                        Categories
                                    </a>
                                </li>
                            {/if}
                        </ul>
                    </div>
                    <div class="vr text-secondary opacity-25 d-none d-sm-block"></div>
                    <div class="dropdown">
                        <button class="btn btn-link text-secondary dropdown-toggle app-topbar-user-btn" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="d-none d-sm-inline me-2">{$login}</span>
                            <span class="app-topbar-avatar" aria-hidden="true">
                                <i class="bi bi-person-circle"></i>
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <a class="dropdown-item"
                                   href="?page=employees:employee_details&employee_id={$login_id}">
                                    <i class="bi bi-person me-2" aria-hidden="true"></i>
                                    Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="index.php?action=logout">
                                    <i class="bi bi-box-arrow-right me-2" aria-hidden="true"></i>
                                    {$translate_core_log_off}
                                </a>
                            </li>
                        </ul>
                    </div>
                {else}
                    <a class="btn btn-outline-primary btn-sm" href="login.php">Login</a>
                {/if}
            </div>
        </div>
    </nav>

    <main class="app-main flex-grow-1 p-3">
