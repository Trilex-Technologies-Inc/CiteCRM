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
<body class="bg-light">

<a name="top"></a>
<div id="dhtmltooltip"></div>
<script src="js/dhtml.js"></script>

<!-- Top bar -->
<nav class="navbar navbar-expand navbar-dark bg-primary app-topbar shadow-sm">
	    <div class="container-fluid">
	        <button class="btn btn-primary d-lg-none me-2" type="button"
	                data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas"
	                aria-controls="sidebarOffcanvas" aria-label="Toggle navigation">
	            <span class="navbar-toggler-icon"></span>
	        </button>

	        <button class="navbar-toggler d-none d-lg-inline-flex me-2" type="button"
	                id="sidebarCollapseBtn" aria-label="Toggle sidebar">
	            <span class="navbar-toggler-icon"></span>
	        </button>

	        <a class="navbar-brand fw-semibold" href="index.php">
	            {$company_name|default:"Cite CRM"}
	        </a>

        <div class="ms-auto d-flex align-items-center gap-2">
            {if $login != ""}
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#"
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
