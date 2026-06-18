<?php

/**
 * SMTP Settings Diagnostic - Portable Version
 * Upload to your CiteCRM root and run via: http://yoursite.com/check_smtp_settings.php
 */

echo "<pre>";
echo "=== CiteCRM SMTP Settings Diagnostic ===\n\n";

// Try to connect to database
if (!file_exists('conf.php')) {
    die("ERROR: conf.php not found in current directory\n");
}

// Extract database credentials from conf.php
$conf_content = file_get_contents('conf.php');

preg_match("/define\('DB_HOST',\s*'([^']+)'\)/", $conf_content, $m_host);
preg_match("/define\('DB_USER',\s*'([^']+)'\)/", $conf_content, $m_user);
preg_match("/define\('DB_PASS',\s*'([^']+)'\)/", $conf_content, $m_pass);
preg_match("/define\('DB_NAME',\s*'([^']+)'\)/", $conf_content, $m_name);
preg_match("/define\('PRFX',\s*'([^']+)'\)/", $conf_content, $m_prfx);

$db_host = isset($m_host[1]) ? $m_host[1] : 'localhost';
$db_user = isset($m_user[1]) ? $m_user[1] : '';
$db_pass = isset($m_pass[1]) ? $m_pass[1] : '';
$db_name = isset($m_name[1]) ? $m_name[1] : '';
$prfx = isset($m_prfx[1]) ? $m_prfx[1] : 'CRM_';

echo "Database: $db_name @ $db_host\n";
echo "Prefix: $prfx\n\n";

// Try different connection methods
$conn = null;

// Try MySQLi
if (!$conn && function_exists('mysqli_connect')) {
    $conn = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if ($conn) {
        echo "✓ Connected via MySQLi\n\n";
    } else {
        echo "✗ MySQLi connection failed: " . @mysqli_connect_error() . "\n";
    }
}

// Try MySQL (old extension, likely deprecated)
if (!$conn && function_exists('mysql_connect')) {
    $conn = @mysql_connect($db_host, $db_user, $db_pass);
    if ($conn) {
        @mysql_select_db($db_name);
        echo "✓ Connected via MySQL (old extension)\n\n";
    }
}

if (!$conn) {
    echo "✗ Could not connect to database via any method\n";
    echo "\nPossible reasons:\n";
    echo "1. Database host '$db_host' is unreachable\n";
    echo "2. Database credentials are wrong\n";
    echo "3. MySQLi/MySQL extensions not loaded\n";
    echo "4. Database server is not running\n";
    die();
}

// Query SETUP table for SMTP settings
$smtp_fields = array('SMTP_HOST', 'SMTP_PORT', 'SMTP_USER', 'SMTP_PASS', 'SMTP_SECURE', 'SMTP_AUTH');
$q = "SELECT * FROM " . $prfx . "SETUP LIMIT 1";

if (is_object($conn) && get_class($conn) === 'mysqli') {
    $result = mysqli_query($conn, $q);
    if (!$result) {
        die("ERROR: " . mysqli_error($conn) . "\n");
    }
    $row = mysqli_fetch_assoc($result);
} else {
    $result = mysql_query($q, $conn);
    if (!$result) {
        die("ERROR: " . mysql_error() . "\n");
    }
    $row = mysql_fetch_assoc($result);
}

if (!$row) {
    die("No SETUP record found in database\n");
}

echo "=== Current SMTP Settings ===\n\n";

foreach ($smtp_fields as $field) {
    $value = isset($row[$field]) ? $row[$field] : '(not set)';

    // Mask password for security
    if ($field === 'SMTP_PASS') {
        if (empty($value)) {
            $value = '(empty)';
        } else if (substr($value, 0, 4) === 'ENC:') {
            $value = '(encrypted) [length: ' . strlen($value) . ']';
        } else {
            $value = str_repeat('*', strlen($value)) . ' (length: ' . strlen($value) . ')';
        }
    }

    printf("%-15s: %s\n", $field, $value);
}

// Check company email
echo "\n=== Company Info ===\n\n";
$q_company = "SELECT COMPANY_EMAIL, COMPANY_NAME FROM " . $prfx . "TABLE_COMPANY LIMIT 1";

if (is_object($conn) && get_class($conn) === 'mysqli') {
    $result_company = mysqli_query($conn, $q_company);
    $company = mysqli_fetch_assoc($result_company);
} else {
    $result_company = mysql_query($q_company, $conn);
    $company = mysql_fetch_assoc($result_company);
}

if ($company) {
    echo "Company Email (From address): " . htmlspecialchars($company['COMPANY_EMAIL']) . "\n";
    echo "Company Name: " . htmlspecialchars($company['COMPANY_NAME']) . "\n";
}

echo "\n=== Analysis ===\n\n";

// Check configuration
$smtp_host = isset($row['SMTP_HOST']) ? $row['SMTP_HOST'] : '';
$smtp_port = isset($row['SMTP_PORT']) ? $row['SMTP_PORT'] : '25';
$smtp_secure = isset($row['SMTP_SECURE']) ? $row['SMTP_SECURE'] : '';
$smtp_auth = isset($row['SMTP_AUTH']) ? $row['SMTP_AUTH'] : 0;
$smtp_user = isset($row['SMTP_USER']) ? $row['SMTP_USER'] : '';
$smtp_pass = isset($row['SMTP_PASS']) ? $row['SMTP_PASS'] : '';

if (empty($smtp_host)) {
    echo "✗ SMTP_HOST is NOT configured\n";
} else {
    echo "✓ SMTP_HOST: $smtp_host\n";
}

echo "  SMTP_PORT: $smtp_port\n";

if (empty($smtp_secure)) {
    echo "  SMTP_SECURE: (empty/none)\n";
} else {
    echo "  SMTP_SECURE: $smtp_secure\n";
}

if ($smtp_port == '587' && empty($smtp_secure)) {
    echo "  ⚠ WARNING: Port 587 usually requires SMTP_SECURE = 'tls'\n";
}
if ($smtp_port == '465' && $smtp_secure !== 'ssl') {
    echo "  ⚠ WARNING: Port 465 usually requires SMTP_SECURE = 'ssl'\n";
}

if (empty($smtp_auth)) {
    echo "\n✗ SMTP_AUTH is DISABLED\n";
    echo "  ⚠ PROBLEM: Your server likely requires authentication to send external mail\n";
} else {
    echo "\n✓ SMTP_AUTH is ENABLED\n";
    echo "  SMTP_USER: " . htmlspecialchars($smtp_user) . "\n";
    echo "  SMTP_PASS: " . (empty($smtp_pass) ? "(empty)" : (substr($smtp_pass, 0, 4) === 'ENC:' ? "(encrypted)" : "(plain text)")) . "\n";
}

echo "\n=== Why 'Relay access denied'? ===\n\n";
echo "Your issue is: SMTP server rejects external mail delivery.\n\n";
echo "Fix checklist:\n";
echo "1. [ ] Enable SMTP_AUTH (set to 1)\n";
echo "2. [ ] Verify SMTP_USER and SMTP_PASS are correct\n";
echo "3. [ ] Check SMTP_PORT matches provider:\n";
echo "       Port 587 → SMTP_SECURE = 'tls'\n";
echo "       Port 465 → SMTP_SECURE = 'ssl'\n";
echo "       Port 25  → SMTP_SECURE = (empty) or 'tls'\n";
echo "4. [ ] Verify COMPANY_EMAIL is a valid sender address\n";
echo "5. [ ] Test by editing SMTP settings via admin panel\n";

echo "\n=== Common Provider Settings ===\n\n";
echo "Gmail SMTP:\n";
echo "  HOST: smtp.gmail.com\n";
echo "  PORT: 587\n";
echo "  SECURE: tls\n";
echo "  AUTH: 1\n";
echo "  USER: your-email@gmail.com\n";
echo "  PASS: Your app-specific password (NOT your regular password)\n";
echo "  Note: Enable 'Less secure app access' or use app passwords\n\n";

echo "Office 365:\n";
echo "  HOST: smtp.office365.com\n";
echo "  PORT: 587\n";
echo "  SECURE: tls\n";
echo "  AUTH: 1\n";
echo "  USER: your-email@yourdomain.com\n";
echo "  PASS: Your Office 365 password\n\n";

echo "</pre>";

if (is_object($conn) && get_class($conn) === 'mysqli') {
    mysqli_close($conn);
} else if (is_resource($conn)) {
    @mysql_close($conn);
}
