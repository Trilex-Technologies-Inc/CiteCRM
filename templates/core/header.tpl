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

    <!-- App theme overrides -->
    <link href="css/default.css" rel="stylesheet" type="text/css">
</head>
<body class="bg-light">

<a name="top"></a>
<div id="dhtmltooltip"></div>
<script src="js/dhtml.js"></script>

<!-- Top brand bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            
            <span>Cite CRM</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar"
                aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="topNavbar">
            <ul class="navbar-nav mb-2 mb-lg-0 align-items-lg-center">
                <li class="nav-item me-2 text-white small d-none d-lg-block">
                    {$translate_core_loged_in}
                    <a class="link-light fw-semibold"
                       href="?page=employees:employee_details&employee_id={$login_id}">
                        {$login}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm"
                       href="index.php?action=logout">
                        {$translate_core_log_off}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Page header -->
<header class="bg-white border-bottom shadow-sm">
    <div class="container-fluid py-2">
        <div class="row align-items-center g-2">
            <div class="col-md-4">
                <div class="small text-muted text-uppercase fw-semibold">
                    {$translate_core_schedule}
                </div>
            </div>
            <div class="col-md-8 text-md-end">
                <h1 class="h5 mb-0 page-title-text">
                    {$page_title}
                </h1>
            </div>
        </div>
    </div>
</header>

<!-- Main layout -->
<div class="container-fluid my-3">
    <div class="row g-3">