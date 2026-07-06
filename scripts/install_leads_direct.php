<?php
// Direct installer: parse DB credentials from conf.php then create leads tables
chdir(__DIR__ . '/..');
$conf = @file_get_contents('conf.php');
if ($conf === false) { echo "conf.php not found\n"; exit(1); }

function extract_const($name, $conf) {
    if (preg_match('/define\(\'"?'.preg_quote($name,'/').'\'"?\s*,\s*\'"?([^\'"\)]+)\'"?\)/i', $conf, $m)) return $m[1];
    if (preg_match('/@define\(\'"?'.preg_quote($name,'/').'\'"?\s*,\s*\'"?([^\'"\)]+)\'"?\)/i', $conf, $m)) return $m[1];
    return null;
}

$dbhost = extract_const('DB_HOST', $conf);
$dbuser = extract_const('DB_USER', $conf);
$dbpass = extract_const('DB_PASS', $conf);
$dbname = extract_const('DB_NAME', $conf);
$prfx = extract_const('PRFX', $conf);
if ($prfx === null) $prfx = '';
if (!$dbhost || !$dbuser || !$dbname) { echo "DB credentials not found in conf.php\n"; exit(1); }

$pdo = null;
$mysqli = null;
echo "Connecting to DB $dbhost / $dbname...\n";
if (class_exists('mysqli')) {
    $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    if ($mysqli->connect_errno) { echo "DB connect failed: " . $mysqli->connect_error . "\n"; exit(1); }
} else {
    try {
        $dsn = "mysql:host={$dbhost};dbname={$dbname};charset=utf8";
        $pdo = new PDO($dsn, $dbuser, $dbpass, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    } catch (Exception $e) {
        echo "No mysqli and PDO connection failed: " . $e->getMessage() . "\n";
        exit(1);
    }
}

$sqls = array();
$sqls[] = "CREATE TABLE IF NOT EXISTS {$prfx}LEADS (
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
$sqls[] = "CREATE TABLE IF NOT EXISTS {$prfx}LEAD_CONTACTS (
    CONTACT_ID INT AUTO_INCREMENT PRIMARY KEY,
    CONTACT_NAME VARCHAR(255) NOT NULL,
    CONTACT_EMAIL VARCHAR(255),
    CONTACT_PHONE VARCHAR(50),
    COMPANY VARCHAR(255),
    CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
$sqls[] = "CREATE TABLE IF NOT EXISTS {$prfx}LEAD_ACCOUNTS (
    ACCOUNT_ID INT AUTO_INCREMENT PRIMARY KEY,
    ACCOUNT_NAME VARCHAR(255) NOT NULL,
    ACCOUNT_PHONE VARCHAR(50),
    ACCOUNT_WEBSITE VARCHAR(255),
    CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
$sqls[] = "CREATE TABLE IF NOT EXISTS {$prfx}LEAD_BOARDS (
    BOARD_ID INT AUTO_INCREMENT PRIMARY KEY,
    BOARD_NAME VARCHAR(255) NOT NULL,
    BOARD_DESC TEXT,
    CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
$sqls[] = "CREATE TABLE IF NOT EXISTS {$prfx}LEAD_BOARD_ITEMS (
    ITEM_ID INT AUTO_INCREMENT PRIMARY KEY,
    BOARD_ID INT NOT NULL,
    LEAD_ID INT NOT NULL,
    COLUMN_NAME VARCHAR(255),
    POSITION INT DEFAULT 0,
    CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

$sqls[] = "CREATE TABLE IF NOT EXISTS {$prfx}LEAD_FORMS (
    FORM_ID INT AUTO_INCREMENT PRIMARY KEY,
    FORM_NAME VARCHAR(255) NOT NULL,
    FORM_SLUG VARCHAR(255) NOT NULL,
    FORM_HTML TEXT,
    FORM_MAPPING TEXT,
    PUBLIC_TOKEN VARCHAR(64),
    CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

$sqls[] = "CREATE TABLE IF NOT EXISTS {$prfx}LEAD_FORM_FIELDS (
    FIELD_ID INT AUTO_INCREMENT PRIMARY KEY,
    FORM_ID INT NOT NULL,
    FIELD_NAME VARCHAR(255) NOT NULL,
    FIELD_KEY VARCHAR(255) NOT NULL,
    FIELD_TYPE VARCHAR(50) DEFAULT 'text',
    POSITION INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

$sqls[] = "CREATE TABLE IF NOT EXISTS {$prfx}LEAD_FORM_SUBMISSIONS (
    SUBMISSION_ID INT AUTO_INCREMENT PRIMARY KEY,
    FORM_ID INT NOT NULL,
    SUBMITTED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    DATA TEXT,
    SOURCE_IP VARCHAR(45)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

$sqls[] = "CREATE TABLE IF NOT EXISTS {$prfx}LEAD_FORM_KEYS (
    KEY_ID INT AUTO_INCREMENT PRIMARY KEY,
    FORM_ID INT NULL,
    API_KEY VARCHAR(128) NOT NULL,
    DESCRIPTION VARCHAR(255),
    CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

$sqls[] = "CREATE TABLE IF NOT EXISTS {$prfx}LEAD_IMPORT_PRESETS (
    PRESET_ID INT AUTO_INCREMENT PRIMARY KEY,
    NAME VARCHAR(255) NOT NULL,
    MAPPING TEXT,
    CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

$sqls[] = "CREATE TABLE IF NOT EXISTS {$prfx}LEAD_IMPORT_SCHEDULES (
    SCHEDULE_ID INT AUTO_INCREMENT PRIMARY KEY,
    PRESET_ID INT NOT NULL,
    SOURCE_PATH VARCHAR(1024) NOT NULL,
    LAST_RUN TIMESTAMP NULL DEFAULT NULL,
    CRON_EXPRESSION VARCHAR(128) DEFAULT NULL,
    ENABLED TINYINT(1) DEFAULT 1,
    CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

try {
    if ($mysqli) {
        foreach ($sqls as $s) {
            if (!$mysqli->query($s)) throw new Exception($mysqli->error);
        }
    } else {
        foreach ($sqls as $s) {
            $pdo->exec($s);
        }
    }
} catch (Exception $e) {
    echo "SQL error: " . $e->getMessage() . "\n";
    echo "Install failed.\n";
    exit(1);
}
echo "Leads tables created successfully.\n";
exit(0);
