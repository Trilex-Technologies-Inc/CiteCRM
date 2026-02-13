<!-- Sidebar: calendar & shortcuts (opens left column; main column opens in company.tpl) -->
<aside class="col-md-3 col-lg-3">
    <div class="card shadow-sm mb-3">
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

    <link rel="stylesheet" type="text/css" media="all"
          href="include/jscalendar/calendar-blue.css" title="win2k-1" />
    <script type="text/javascript" src="include/jscalendar/calendar_stripped.js"></script>
    <script type="text/javascript" src="include/jscalendar/lang/calendar-english.js"></script>
    <script type="text/javascript" src="include/jscalendar/calendar-setup_stripped.js"></script>

    {literal}
    <script type="text/javascript">
        function dateChanged(calendar) {
            if (calendar.dateClicked) {
                var y = calendar.date.getFullYear();
                var M = calendar.date.getMonth();
                var m = M + 1;
                var d = calendar.date.getDate();
                window.location =
                    "?page=schedual:main&y=" + y +
                    "&m=" + m +
                    "&d=" + d +
                    "&wo_id={/literal}{$wo_id}{literal}" +
                    "&page_title={/literal}{$translate_core_schedule}{literal}";
            }
        }

        Calendar.setup({
            flat: "calendar-container",
            flatCallback: dateChanged
        });
    </script>
    {/literal}