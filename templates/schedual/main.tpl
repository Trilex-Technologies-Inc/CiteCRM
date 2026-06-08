<!-- main.tpl -->

{literal}
<script>

function go() {
    box = document.forms[0].page_no;
    destination = box.options[box.selectedIndex].value;

    if(destination) {
        location.href = destination;
    }
}

</script>

<style>

/* PAGE */

.schedule-page {
    padding: 20px;
}

/* HEADER */

.schedule-header {
    background: #ffffff;
    border-radius: 18px;
    padding: 22px 28px;
    margin-bottom: 20px;

    display: flex;
    justify-content: space-between;
    align-items: center;

    box-shadow: 0 2px 14px rgba(0,0,0,0.06);
    border: 1px solid #eaeaea;
}

.schedule-title {
    font-size: 26px;
    font-weight: 700;
    color: #202124;
}

.schedule-subtitle {
    color: #5f6368;
    font-size: 14px;
    margin-top: 4px;
}

/* ACTION BAR */

.schedule-toolbar {
    background: #ffffff;
    border-radius: 16px;
    padding: 18px 24px;
    margin-bottom: 20px;

    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;

    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    border: 1px solid #ececec;
}

/* BUTTON */

.gc-btn {
    background: #1a73e8;
    color: #fff !important;
    border: none;
    padding: 10px 18px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.gc-btn:hover {
    background: #1557b0;
    transform: translateY(-1px);
}

/* SELECT */

.gc-select {
    border: 1px solid #d0d7de;
    border-radius: 10px;
    padding: 10px 14px;
    min-width: 220px;
    background: #fff;
    font-size: 14px;
    outline: none;
    transition: 0.2s ease;
}

.gc-select:focus {
    border-color: #1a73e8;
    box-shadow: 0 0 0 3px rgba(26,115,232,0.15);
}

/* ALERT */

.schedule-alert {
    background: #fff4e5;
    border: 1px solid #ffd8a8;
    color: #8a5300;
    padding: 16px 18px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-size: 14px;
}

/* CALENDAR WRAPPER */

.calendar-wrapper {
    background: #ffffff;
    border-radius: 18px;
    padding: 20px;

    box-shadow: 0 2px 14px rgba(0,0,0,0.05);
    border: 1px solid #ececec;

    overflow-x: auto;
}

/* HELP BUTTON */

.help-btn img {
    opacity: 0.7;
    transition: 0.2s ease;
}

.help-btn img:hover {
    opacity: 1;
    transform: scale(1.1);
}

/* RESPONSIVE */

@media (max-width: 768px) {

    .schedule-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .schedule-toolbar {
        flex-direction: column;
        align-items: stretch;
    }

    .gc-select {
        width: 100%;
    }

    .schedule-title {
        font-size: 22px;
    }
}

</style>
{/literal}

<!-- TOOLBAR -->
<div class="container-fluid mb-3">
    {include file="core/tool_bar.tpl"}
</div>

<div class="container-fluid schedule-page">

    <!-- HEADER -->
    <div class="schedule-header">

        <div>

            <div class="schedule-title">
                📅 {$translate_schedule_view}
            </div>

            <div class="schedule-subtitle">
                {$cur_date}
            </div>

        </div>

        <div>

            <a
                class="help-btn"
                href="http://www.citecrm.com/docs/#schedual"
                target="new"
            >

                <i class="bi bi-question-circle-fill fs-5 text-secondary"
                   aria-hidden="true"
                   onMouseOver="ddrivetip('<b>Schedule Help</b><hr><p>Click an empty slot to create a new schedule. Click an existing event to view details. You can also create schedules directly from Work Orders.</p>')"
                   onMouseOut="hideddrivetip()"></i>

            </a>

        </div>

    </div>

    <!-- ERROR -->
    {if $error_msg != ""}

        {include file="core/error.tpl"}

    {/if}

    <!-- WORK ORDER ALERT -->
    {if $wo_id != '0'}

        <div class="schedule-alert">

            <strong>{$translate_schedule_info}</strong><br>

            {$translate_schedule_msg_1}
            <strong>#{$wo_id}</strong>
            {$translate_schedule_msg_2}

        </div>

    {/if}

    <!-- ACTIONS -->
    <div class="schedule-toolbar">

        <!-- LEFT -->
        <div>

            <a
                class="gc-btn"
                href="?page=schedual:print&tech={$selected}&y={$y}&m={$m}&d={$d}&escape=1"
                target="new"
            >
                🖨 Print Schedule
            </a>

        </div>

        <!-- RIGHT -->
        <div>

            <form class="d-flex align-items-center gap-2">

                <span>
                    {$translate_schedule_msg_3}
                </span>

                <select
                    name="page_no"
                    class="gc-select"
                    onChange="go()"
                >

                    {section name=i loop=$tech}

                        <option
                            value="?page=schedual:main&tech={$tech[i].EMPLOYEE_ID}
                            {foreach from=$date_array key=key item=item}
                            &{$key}={$item}
                            {/foreach}
                            &page_title=Schedual"

                            {if $selected == $tech[i].EMPLOYEE_ID}
                                selected
                            {/if}
                        >

                            {$tech[i].EMPLOYEE_LOGIN}

                        </option>

                    {/section}

                </select>

            </form>

        </div>

    </div>

    <!-- CALENDAR -->
    <div class="calendar-wrapper">

        {$calendar}

    </div>

</div>
