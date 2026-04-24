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

    <!-- Sidebar calendar (used in left sidebar widget) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">

    <!-- App theme overrides -->
    <link href="css/default.css" rel="stylesheet" type="text/css">
</head>
<body class="app-body">

<a name="top"></a>
<div id="dhtmltooltip"></div>
<script src="js/dhtml.js"></script>

<!-- Top bar -->
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
