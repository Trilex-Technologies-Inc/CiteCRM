<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{$page_title}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    >
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <!-- App theme overrides -->
    <link href="css/default.css" rel="stylesheet" type="text/css">
</head>
<body class="bg-light ">

<a name="top"></a>
<div id="dhtmltooltip"></div>
<script src="js/dhtml.js"></script>

<!-- Top bar -->
<nav class="navbar navbar-expand navbar-dark app-topbar bg-primary shadow-sm">
	    <div class="container-fluid">
	        <button class="btn btn-primary d-lg-none me-2" type="button"
	                data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas"
	                aria-controls="sidebarOffcanvas" aria-label="Toggle navigation">
	            <span class="navbar-toggler-icon"></span>
	        </button>

	        <button class="btn btn-primary d-none d-lg-inline-flex me-2" type="button"
	                id="sidebarCollapseBtn" aria-label="Toggle sidebar">
	            <span class="navbar-toggler-icon"></span>
	        </button>

	        <a class="navbar-brand fw-semibold" href="index.php">
                <i class="bi bi-hexagon-fill app-brand-mark" aria-hidden="true"></i>
	            <span class="app-brand-name">{$company_name|default:"Cite CRM"}</span>
	        </a>

           
        <div class="ms-auto d-flex align-items-center gap-2">
            {if $login != ""}
                <div class="dropdown">
                    <a class="btn btn-sm text-white app-topbar-pill dropdown-toggle {if $current_module == 'control' || $current_module == 'cats'}active{/if}"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <i class="bi bi-gear-fill me-1" aria-hidden="true"></i>
                        {$translate_core_control|default:"Settings"}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item {if $current_module == 'control' && $current_page == 'main'}active{/if}"
                               href="?page=control:main&page_title={$translate_core_control|default:"Control Center"}">
                                {$translate_core_control|default:"Control Center"}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {if $current_module == 'control' && $current_page == 'company_edit'}active{/if}"
                               href="?page=control:company_edit&page_title=Company">
                                Company
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {if $current_module == 'control' && $current_page == 'acl'}active{/if}"
                               href="?page=control:acl&page_title=Permissions">
                                Permissions
                            </a>
                        </li>

                        {if $show_admin_menu|default:false}
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item {if $current_module == 'control' && $current_page == 'hours_edit'}active{/if}"
                                   href="?page=control:hours_edit&page_title=Office%20Hours">
                                    Office Hours
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {if $current_module == 'control' && $current_page == 'payment_options'}active{/if}"
                                   href="?page=control:payment_options&page_title=Payment%20Methods">
                                    Payment Methods
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {if $current_module == 'control' && $current_page == 'edit_rate'}active{/if}"
                                   href="?page=control:edit_rate&page_title=Billing%20Rates">
                                    Billing Rates
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {if $current_module == 'control' && $current_page == 'check_updates'}active{/if}"
                                   href="?page=control:check_updates&page_title=Check%20For%20Updates">
                                    Check for Updates
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {if $current_module == 'cats'}active{/if}"
                                   href="?page=cats:main&page_title=Category">
                                    Categories
                                </a>
                            </li>
                        {/if}
                    </ul>
                </div>
                <div class="dropdown">
                    <a class="btn btn-sm text-white app-topbar-pill dropdown-toggle" href="#"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {$login}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item"
                               href="?page=employees:employee_details&employee_id={$login_id}">
                                Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="index.php?action=logout">
                                {$translate_core_log_off}
                            </a>
                        </li>
                    </ul>
                </div>
            {else}
                <a class="btn btn-outline-light btn-sm" href="login.php">Login</a>
            {/if}
        </div>
    </div>
</nav>

<!-- App shell (sidebar + content) -->
<div class="app-shell d-flex">
