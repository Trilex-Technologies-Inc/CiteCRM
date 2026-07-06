<?php
require '../conf.php';
require_once(INCLUDE_URL . 'session.php');

$s = new Session();
if (!$s->get('login_id')) {
    header('Location: ../login.php');
    exit;
}

$msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['install_leads'])) {
    // Run leads module install
    define('PRFX', 'CRM_');
    
    $sqls = array();
    $sqls[] = "CREATE TABLE IF NOT EXISTS " . PRFX . "LEADS (
        LEAD_ID INT AUTO_INCREMENT PRIMARY KEY,
        LEAD_TITLE VARCHAR(255) NOT NULL,
        LEAD_DESCRIPTION TEXT,
        LEAD_STATUS VARCHAR(50) DEFAULT 'New',
        LEAD_PRIORITY VARCHAR(20) DEFAULT 'Normal',
        ASSIGNED_TO INT DEFAULT NULL,
        ACCOUNT_ID INT DEFAULT NULL,
        CONTACT_ID INT DEFAULT NULL,
        CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UPDATED_AT TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

    $sqls[] = "CREATE TABLE IF NOT EXISTS " . PRFX . "LEAD_CONTACTS (
        CONTACT_ID INT AUTO_INCREMENT PRIMARY KEY,
        CONTACT_NAME VARCHAR(255) NOT NULL,
        CONTACT_EMAIL VARCHAR(255),
        CONTACT_PHONE VARCHAR(50),
        COMPANY VARCHAR(255),
        CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

    $sqls[] = "CREATE TABLE IF NOT EXISTS " . PRFX . "LEAD_ACCOUNTS (
        ACCOUNT_ID INT AUTO_INCREMENT PRIMARY KEY,
        ACCOUNT_NAME VARCHAR(255) NOT NULL,
        ACCOUNT_PHONE VARCHAR(50),
        ACCOUNT_WEBSITE VARCHAR(255),
        CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

    $sqls[] = "CREATE TABLE IF NOT EXISTS " . PRFX . "LEAD_BOARDS (
        BOARD_ID INT AUTO_INCREMENT PRIMARY KEY,
        BOARD_NAME VARCHAR(255) NOT NULL,
        BOARD_DESC TEXT,
        CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

    $sqls[] = "CREATE TABLE IF NOT EXISTS " . PRFX . "LEAD_BOARD_ITEMS (
        ITEM_ID INT AUTO_INCREMENT PRIMARY KEY,
        BOARD_ID INT NOT NULL,
        LEAD_ID INT NOT NULL,
        COLUMN_NAME VARCHAR(255),
        POSITION INT DEFAULT 0,
        CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

    $sqls[] = "CREATE TABLE IF NOT EXISTS " . PRFX . "LEAD_FORMS (
        FORM_ID INT AUTO_INCREMENT PRIMARY KEY,
        FORM_NAME VARCHAR(255) NOT NULL,
        FORM_SLUG VARCHAR(255) NOT NULL,
        FORM_HTML TEXT,
        FORM_MAPPING TEXT,
        PUBLIC_TOKEN VARCHAR(64),
        CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

    $sqls[] = "CREATE TABLE IF NOT EXISTS " . PRFX . "LEAD_FORM_FIELDS (
        FIELD_ID INT AUTO_INCREMENT PRIMARY KEY,
        FORM_ID INT NOT NULL,
        FIELD_NAME VARCHAR(255) NOT NULL,
        FIELD_KEY VARCHAR(255) NOT NULL,
        FIELD_TYPE VARCHAR(50) DEFAULT 'text',
        POSITION INT DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

    $sqls[] = "CREATE TABLE IF NOT EXISTS " . PRFX . "LEAD_FORM_SUBMISSIONS (
        SUBMISSION_ID INT AUTO_INCREMENT PRIMARY KEY,
        FORM_ID INT NOT NULL,
        SUBMITTED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        DATA TEXT,
        SOURCE_IP VARCHAR(45)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

    $sqls[] = "CREATE TABLE IF NOT EXISTS " . PRFX . "LEAD_FORM_KEYS (
        KEY_ID INT AUTO_INCREMENT PRIMARY KEY,
        FORM_ID INT NULL,
        API_KEY VARCHAR(128) NOT NULL,
        DESCRIPTION VARCHAR(255),
        CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

    $sqls[] = "CREATE TABLE IF NOT EXISTS " . PRFX . "LEAD_IMPORT_PRESETS (
        PRESET_ID INT AUTO_INCREMENT PRIMARY KEY,
        NAME VARCHAR(255) NOT NULL,
        MAPPING TEXT,
        CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

    $sqls[] = "CREATE TABLE IF NOT EXISTS " . PRFX . "LEAD_IMPORT_SCHEDULES (
        SCHEDULE_ID INT AUTO_INCREMENT PRIMARY KEY,
        PRESET_ID INT NOT NULL,
        SOURCE_PATH VARCHAR(1024) NOT NULL,
        LAST_RUN TIMESTAMP NULL DEFAULT NULL,
        CRON_EXPRESSION VARCHAR(128) DEFAULT NULL,
        ENABLED TINYINT(1) DEFAULT 1,
        CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

    $errors = array();
    foreach ($sqls as $s) {
        if (!$db->Execute($s)) {
            $errors[] = $db->ErrorMsg();
        }
    }

    if (!empty($errors)) {
        $error_msg = 'Leads install errors: ' . implode('; ', $errors);
    } else {
        $msg = 'Leads module tables created successfully.';
    }
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Install Leads Module</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
  <div class="container">
    <h1>Install Leads Module</h1>
    
    <?php if ($msg != ''): ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>
    
    <?php if ($error_msg != ''): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error_msg); ?></div>
    <?php endif; ?>

    <p>Click the button below to create the required Leads module tables:</p>
    
    <form method="POST">
      <button type="submit" name="install_leads" class="btn btn-primary">Create Leads Tables</button>
      <a href="../index.php" class="btn btn-secondary ms-2">Back</a>
    </form>

    <p class="text-muted small mt-3">This will create the following tables if they don't exist:<br>
    CRM_LEADS, CRM_LEAD_CONTACTS, CRM_LEAD_ACCOUNTS, CRM_LEAD_BOARDS, CRM_LEAD_BOARD_ITEMS, CRM_LEAD_FORMS, CRM_LEAD_FORM_FIELDS, CRM_LEAD_FORM_SUBMISSIONS, CRM_LEAD_FORM_KEYS, CRM_LEAD_IMPORT_PRESETS, CRM_LEAD_IMPORT_SCHEDULES</p>
  </div>
</body>
</html>
