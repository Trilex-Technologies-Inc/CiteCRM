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
                        <a href="http://www.citecrm.com/bugs" target="new">
                            {$translate_core_report_bug}
                        </a>
                    </li>
                    <li>
                        <a href="http://forums.citecrm.com" target="new">
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

<main class="app-main flex-grow-1 p-3">
