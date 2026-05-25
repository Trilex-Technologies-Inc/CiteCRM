<?php
require_once("include.php");

if (!xml2php("billing")) {
	$smarty->assign('error_msg', "Error in language file");
}

function citecrm_public_page($title, $message)
{
	$title = (string)$title;
	$message = (string)$message;
	$esc_title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
	$esc_msg = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

	echo '<!doctype html><html><head><meta charset="utf-8">';
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
	echo '<title>' . $esc_title . '</title>';
	echo '<style>body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;margin:24px;max-width:720px}';
	echo '.card{border:1px solid #e5e7eb;border-radius:12px;padding:16px}';
	echo '.muted{color:#6b7280}';
	echo '</style></head><body>';
	echo '<div class="card">';
	echo '<h2 style="margin:0 0 8px 0">' . $esc_title . '</h2>';
	echo '<p style="margin:0 0 12px 0">' . $esc_msg . '</p>';
	echo '<p class="muted" style="margin:0">You can close this tab.</p>';
	echo '</div></body></html>';
	exit;
}

$invoice_id = isset($VAR['invoice_id']) ? (int)$VAR['invoice_id'] : 0;
$workorder_id = isset($VAR['wo_id']) ? (int)$VAR['wo_id'] : 0;

$msg = 'Your Stripe payment was canceled.';
if ($invoice_id > 0) {
	$msg .= ' Invoice #' . $invoice_id . '.';
}
if ($workorder_id > 0) {
	$msg .= ' Work Order #' . $workorder_id . '.';
}

citecrm_public_page('Payment canceled', $msg);
?>

