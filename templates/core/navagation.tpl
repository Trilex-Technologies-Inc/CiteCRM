<!-- Sidebar: calendar & shortcuts (opens left column; main column opens in company.tpl) -->
<aside class="col-md-3 col-lg-3">
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-body-secondary py-2">
            <span class="fw-semibold small text-uppercase">
                {$translate_core_schedule}
            </span>
        </div>
        <div class="card-body">
            <!-- FullCalendar container -->
            <div id="calendar-container" styleclass="mb-2"></div>
            <p class="small text-muted mb-0 text-center">
                {$translate_core_click_scheudle}
            </p>
        </div>
    </div>

   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

    {literal}
 <style>
    .fc-day-today {
        background-color: #ffc107 !important;
    }
</style>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar-container');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
           
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            height: 'auto',
            aspectRatio: 1.2,
            
            // Theme customization
            buttonText: {
                today: 'Today',
                month: 'Month'
            },
            
            // Formatting
            titleFormat: { year: 'numeric', month: 'long' },
            
            // Date click handler
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
            },
            
            // Styling
            dayCellClassNames: function(arg) {
                if (arg.isToday) {
                    return ['fc-day-today-custom'];
                }
                return [];
            },
            
            // Disable other views to keep it simple
            views: {
                dayGridMonth: {
                    titleFormat: { year: 'numeric', month: 'short' }
                }
            },
            
            // Make weekends stand out slightly
            dayCellDidMount: function(info) {
                if (info.date.getDay() === 0 || info.date.getDay() === 6) {
                    info.el.style.backgroundColor = '#f8f9fa';
                }
            }
        });
        
        calendar.render();
    });
</script>
{/literal}

