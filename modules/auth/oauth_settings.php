<?php
require '../../conf.php';
require_once(INCLUDE_URL . 'session.php');

$s = new Session();
if (!$s->get('login_id')) {
    header('Location: ../../login.php');
    exit;
}

$msg = '';
// ensure SETUP row exists
$rs = $db->Execute("SELECT * FROM " . PRFX . "SETUP LIMIT 1");
if (!$rs) {
    $msg = 'Database error';
} else if ($rs->EOF) {
    // insert an empty setup row
    $db->Execute("INSERT INTO " . PRFX . "SETUP (ID) VALUES (1)");
}

// helper to add column if missing
function ensure_column($db, $col, $type = "VARCHAR(255) NOT NULL DEFAULT '')") {
    $q = "SHOW COLUMNS FROM " . PRFX . "SETUP LIKE '" . $col . "'";
    $r = @$db->Execute($q);
    if ($r && $r->EOF) {
        $db->Execute("ALTER TABLE " . PRFX . "SETUP ADD " . $col . " " . $type);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $google_id = isset($_POST['OAUTH_GOOGLE_CLIENT_ID']) ? trim($_POST['OAUTH_GOOGLE_CLIENT_ID']) : '';
    $google_secret = isset($_POST['OAUTH_GOOGLE_CLIENT_SECRET']) ? trim($_POST['OAUTH_GOOGLE_CLIENT_SECRET']) : '';
    $ms_id = isset($_POST['OAUTH_MS_CLIENT_ID']) ? trim($_POST['OAUTH_MS_CLIENT_ID']) : '';
    $ms_secret = isset($_POST['OAUTH_MS_CLIENT_SECRET']) ? trim($_POST['OAUTH_MS_CLIENT_SECRET']) : '';

    // ensure columns
    ensure_column($db, 'OAUTH_GOOGLE_CLIENT_ID', "VARCHAR(255) NOT NULL DEFAULT ''");
    ensure_column($db, 'OAUTH_GOOGLE_CLIENT_SECRET', "VARCHAR(255) NOT NULL DEFAULT ''");
    ensure_column($db, 'OAUTH_MS_CLIENT_ID', "VARCHAR(255) NOT NULL DEFAULT ''");
    ensure_column($db, 'OAUTH_MS_CLIENT_SECRET', "VARCHAR(255) NOT NULL DEFAULT ''");

    // update single-row setup
    $sql = "UPDATE " . PRFX . "SETUP SET OAUTH_GOOGLE_CLIENT_ID=" . $db->qstr($google_id) . ", OAUTH_GOOGLE_CLIENT_SECRET=" . $db->qstr($google_secret) . ", OAUTH_MS_CLIENT_ID=" . $db->qstr($ms_id) . ", OAUTH_MS_CLIENT_SECRET=" . $db->qstr($ms_secret) . " LIMIT 1";
    $db->Execute($sql);
    $msg = 'Saved.';
}

$cfg = $db->GetRow("SELECT * FROM " . PRFX . "SETUP LIMIT 1");
$g_id = isset($cfg['OAUTH_GOOGLE_CLIENT_ID']) ? $cfg['OAUTH_GOOGLE_CLIENT_ID'] : '';
$g_secret = isset($cfg['OAUTH_GOOGLE_CLIENT_SECRET']) ? $cfg['OAUTH_GOOGLE_CLIENT_SECRET'] : '';
$ms_id = isset($cfg['OAUTH_MS_CLIENT_ID']) ? $cfg['OAUTH_MS_CLIENT_ID'] : '';
$ms_secret = isset($cfg['OAUTH_MS_CLIENT_SECRET']) ? $cfg['OAUTH_MS_CLIENT_SECRET'] : '';

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OAuth Settings</title>
  <link rel="stylesheet" href="/css/default.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
  <div class="container">
    <h1>OAuth Settings</h1>
    <?php if ($msg != ''): ?>
      <div class="alert alert-info"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Google Client ID</label>
        <input name="OAUTH_GOOGLE_CLIENT_ID" class="form-control" value="<?php echo htmlspecialchars($g_id); ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Google Client Secret</label>
        <input name="OAUTH_GOOGLE_CLIENT_SECRET" class="form-control" value="<?php echo htmlspecialchars($g_secret); ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Microsoft Client ID</label>
        <input name="OAUTH_MS_CLIENT_ID" class="form-control" value="<?php echo htmlspecialchars($ms_id); ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Microsoft Client Secret</label>
        <input name="OAUTH_MS_CLIENT_SECRET" class="form-control" value="<?php echo htmlspecialchars($ms_secret); ?>">
      </div>

      <div class="mb-3">
        <button class="btn btn-primary" type="submit">Save</button>
        <a class="btn btn-secondary ms-2" href="../../index.php">Back</a>
      </div>
    </form>

    <p class="text-muted small">After saving, ensure these redirect URIs are registered with providers:<br>
    <code>https://your-host/modules/auth/google_callback.php</code><br>
    <code>https://your-host/modules/auth/microsoft_callback.php</code></p>
  </div>
</body>
</html>
