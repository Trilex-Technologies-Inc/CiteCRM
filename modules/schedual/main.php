<?php
#########################################################
#           Cite CRM Customer Relations Management      #
#       Modern Google Calendar Style Schedule           #
#########################################################

require('include.php');

if(!xml2php("schedual")) {
    $smarty->assign('error_msg',"Error in language file");
}

if(!function_exists('sanitize_schedual_notes')) {
	function sanitize_schedual_notes($html) {
		if($html === null) {
			return '';
		}

		$html = trim((string)$html);
		if($html === '') {
			return '';
		}

		/* remove script/style blocks completely */
		$html = preg_replace('~<(script|style)\b[^>]*>.*?</\1>~is', '', $html);

		/* allow basic formatting tags but strip everything else */
		$html = strip_tags($html, '<br><b><strong><i><em><u><p><div><span><ul><ol><li><a>');

		/* basic hardening: strip event handlers and inline styles */
		$html = preg_replace('~\s+on[a-z]+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)~i', '', $html);
		$html = preg_replace('~\s+style\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)~i', '', $html);

		/* block javascript: URLs in href */
		$html = preg_replace('~href\s*=\s*(["\'])\s*javascript:.*?\1~i', 'href="#"', $html);

		return $html;
	}
}

/* work order */
$wo_id = isset($_GET['wo_id']) && !empty($_GET['wo_id'])
    ? $_GET['wo_id']
    : '0';

/* check closed work order */
if(isset($wo_id) && !empty($wo_id)) {

    $q = "SELECT WORK_ORDER_CURENT_STATUS
          FROM ".PRFX."TABLE_WORK_ORDER
          WHERE WORK_ORDER_ID=".$db->qstr($wo_id);

    if(!$rs = $db->execute($q)) {

        force_page(
            'core',
            'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database'
        );
        exit;

    } else {

        $status = $rs->fields['WORK_ORDER_CURENT_STATUS'];
    }

    if(in_array($status, array('6','7','8','9'))) {

        force_page(
            'workorder',
            'view&wo_id='.$wo_id.
            '&error_msg=Can not set a schedual for closed work order'.
            '&page_title=Work Order ID '.$wo_id.
            '&type=warning'
        );

        exit;
    }
}

/* date */
$y = isset($VAR['y']) ? $VAR['y'] : date('Y');
$m = isset($VAR['m']) ? $VAR['m'] : date('m');
$d = isset($VAR['d']) ? $VAR['d'] : date('d');

$cur_date = $m."/".$d."/".$y;

$date_array = array(
    'y'=>$y,
    'm'=>$m,
    'd'=>$d,
    'wo_id'=>$wo_id
);

$smarty->assign('date_array',$date_array);

/* office hours */
$q = "SELECT OFFICE_HOUR_START, OFFICE_HOUR_END
      FROM ".PRFX."SETUP";

if(!$rs = $db->execute($q)) {

    force_page(
        'core',
        'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database'
    );

    exit;
}

$H = $rs->fields['OFFICE_HOUR_START'];
$E = $rs->fields['OFFICE_HOUR_END'];

if(empty($H) || empty($E)) {

    force_page(
        'core',
        'error&error_msg=You must first set start and stop times in control center'
    );

    exit;
}

/* technician */
if(!isset($VAR['tech'])) {
    $tech = $_SESSION['login_id'];
} else {
    $tech = $VAR['tech'];
}

/* tech dropdown */
$tech_array = display_tech($db);

$smarty->assign('selected', $tech);
$smarty->assign('tech', $tech_array);
$smarty->assign('y', $y);
$smarty->assign('m', $m);
$smarty->assign('d', $d);

/* business hours */
$business_start = mktime($H,0,0,$m,$d,$y);
$business_end   = mktime($E,0,0,$m,$d,$y);

/* get schedule */
$q = "
SELECT *
FROM ".PRFX."TABLE_SCHEDUAL
WHERE SCHEDUAL_START >= ".$business_start."
AND SCHEDUAL_START <= ".$business_end."
AND EMPLOYEE_ID = '".$tech."'
ORDER BY SCHEDUAL_START ASC
";

if(!$rs = $db->Execute($q)) {

    force_page(
        'core',
        'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database'
    );

    exit;
}

/* build array */
$sch = array();

while(!$rs->EOF) {

    array_push($sch, array(

        "SCHEDUAL_ID"      => $rs->fields["SCHEDUAL_ID"],
        "SCHEDUAL_START"   => $rs->fields["SCHEDUAL_START"],
        "SCHEDUAL_END"     => $rs->fields["SCHEDUAL_END"],
        "SCHEDUAL_NOTES"   => $rs->fields["SCHEDUAL_NOTES"],
        "WORK_ORDER_ID"    => $rs->fields["WORK_ORDER_ID"]

    ));

    $rs->MoveNext();
}

/* =========================
   GOOGLE CALENDAR STYLE
========================= */

$calendar = '

<style>

.google-calendar {
    width: 100%;
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #dadce0;
    box-shadow: 0 4px 18px rgba(0,0,0,0.08);
    font-family: Arial, sans-serif;
}

/* top header */
.gc-top {
    padding: 18px 20px;
    background: #ffffff;
    border-bottom: 1px solid #e8eaed;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.gc-title {
    font-size: 22px;
    font-weight: bold;
    color: #202124;
}

.gc-date {
    color: #5f6368;
    font-size: 14px;
}

/* grid */
.gc-header,
.gc-row {
    display: grid;
    grid-template-columns: 90px repeat(4, 1fr);
}

/* header */
.gc-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e8eaed;
}

.gc-header div {
    padding: 14px;
    text-align: center;
    font-weight: 600;
    color: #5f6368;
    border-right: 1px solid #eee;
}

/* rows */
.gc-row {
    min-height: 90px;
    border-bottom: 1px solid #f1f3f4;
}

/* time */
.gc-time {
    background: #fafafa;
    border-right: 1px solid #eee;
    text-align: center;
    padding-top: 12px;
    color: #70757a;
    font-size: 13px;
    font-weight: bold;
}

/* slots */
.gc-slot {
    position: relative;
    border-right: 1px solid #f1f3f4;
    transition: background 0.2s ease;
}

.gc-slot:hover {
    background: #f8fbff;
}

/* available */
.gc-free {
    position: absolute;
    top: 6px;
    left: 6px;
    right: 6px;
    bottom: 6px;

    background: #e6f4ea;
    border: 1px dashed #34a853;

    border-radius: 10px;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    cursor: pointer;

    transition: all 0.2s ease;
}

.gc-free:hover {
    transform: scale(1.02);
    background: #d7f0dd;
}

/* booked */
.gc-booked {
    position: absolute;
    top: 6px;
    left: 6px;
    right: 6px;
    bottom: 6px;

    background: #4285f4;
    border-radius: 10px;

    color: white;
    padding: 6px 8px;
    text-align: center;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    cursor: pointer;

    transition: all 0.2s ease;
}

.gc-booked:hover {
    transform: scale(1.02);
    background: #3367d6;
}

/* circles */
.gc-circle {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-bottom: 8px;
}

.gc-green {
    background: #34a853;
}

.gc-white {
    background: #ffffff;
}

/* text */
.gc-time-label {
    font-size: 12px;
    font-weight: 600;
}

.gc-wo {
    margin-top: 4px;
    font-size: 11px;
    opacity: 0.9;
}

.gc-notes {
    margin-top: 4px;
    font-size: 11px;
    opacity: 0.95;
    line-height: 1.25;
    max-height: 38px;
    overflow: hidden;
    word-break: break-word;
}

/* responsive */
@media (max-width: 768px) {

    .gc-header,
    .gc-row {
        grid-template-columns: 70px repeat(4, 1fr);
    }

    .gc-row {
        min-height: 70px;
    }

    .gc-title {
        font-size: 18px;
    }
}

</style>
';

/* container */
$calendar .= '<div class="google-calendar">';

/* top */
$calendar .= '
<div class="gc-top">
    <div class="gc-title">Schedule Calendar</div>
    <div class="gc-date">'.date("F d, Y", strtotime($cur_date)).'</div>
</div>
';

/* header */
$calendar .= '
<div class="gc-header">
    <div>Time</div>
    <div>00</div>
    <div>15</div>
    <div>30</div>
    <div>45</div>
</div>
';

/* rows */
$start = mktime($H,0,0,$m,$d,$y);

while($start < $business_end) {

    $calendar .= '<div class="gc-row">';

    /* time column */
    $calendar .= '
    <div class="gc-time">
        '.date("h:i A", $start).'
    </div>
    ';

    /* 15 minute slots */
    for($slot = 0; $slot < 4; $slot++) {

        $slot_time = mktime(
            date("H", $start),
            $slot * 15,
            0,
            $m,
            $d,
            $y
        );

        $is_available = true;
        $scheduled_info = null;

        foreach($sch as $scheduled) {

            if(
                $slot_time >= $scheduled['SCHEDUAL_START']
                &&
                $slot_time < $scheduled['SCHEDUAL_END']
            ) {

                $is_available = false;
                $scheduled_info = $scheduled;
                break;
            }
        }

        /* AVAILABLE */
        if($is_available) {

            $calendar .= '

            <div class="gc-slot">

                <div
                    class="gc-free"
                    onclick="window.location=\'?page=schedual:new
                    &starttime='.date("h:i a", $slot_time).'
                    &day='.$cur_date.'
                    &wo_id='.$wo_id.'
                    &tech='.$tech.'\'"
                >

                    <div class="gc-circle gc-green"></div>

                    <div class="gc-time-label">
                        '.date("h:i A", $slot_time).'
                    </div>

                </div>

            </div>
            ';

        } else {

            $onclick = '';
            $notes_block = '';

            if($slot_time == $scheduled_info['SCHEDUAL_START']) {

                $onclick = '
                onclick="window.location=\'?page=schedual:view
                &sch_id='.$scheduled_info['SCHEDUAL_ID'].'
                &y='.$y.'
                &m='.$m.'
                &d='.$d.'\'"
                ';

                $notes_html = sanitize_schedual_notes($scheduled_info['SCHEDUAL_NOTES']);
                if($notes_html !== '') {
                    $notes_block = '

                    <div class="gc-notes">
                        '.$notes_html.'
                    </div>
                    ';
                }
            }

            $calendar .= '

            <div class="gc-slot">

                <div class="gc-booked" '.$onclick.'>

                    <div class="gc-circle gc-white"></div>

                    <div class="gc-time-label">
                        '.date("h:i A", $slot_time).'
                    </div>

                    <div class="gc-wo">
                        WO #'.$scheduled_info['WORK_ORDER_ID'].'
                    </div>

                    '.$notes_block.'

                </div>

            </div>
            ';
        }
    }

    $calendar .= '</div>';

    $start = mktime(
        date("H", $start) + 1,
        0,
        0,
        $m,
        $d,
        $y
    );
}

$calendar .= '</div>';

/* send to smarty */
$smarty->assign('calendar', $calendar);
$smarty->assign('cur_date', $cur_date);

/* display */
$smarty->display('schedual'.SEP.'main.tpl');

?>
