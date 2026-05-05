<?php
#############################################################
# Cite CRM	Customer Relations Management							#
# Copyright (C) 2003 - 2005 In-Cite CRM								#
# www.citecrm.com  dev@incitecrm.com									#
# This program is distributed under the terms and 				#
# conditions of the GPL	and is free to use or modify			#
# 																				#
# Installer																	#
# Version 0.0.1	Thu Oct 20 06:47:29 PDT 2005						#
#############################################################

###############################
#		Lock Check					#
###############################
if (check_lock_file()) {
    echo ("<div class='alert alert-danger'>Set up has already ran! Some clean up needs to happen before you can run it again!</div>");
    exit;
    /* add code to clean up inlude file and remove any database settings so we can do a clean install */
}

###############################
#		Switch 						#
###############################
$mode = isset($_POST['mode']) ? $_POST['mode'] : '';

switch ($mode) {

    ############################
    #		Install 					#
    ############################
    case "install":
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Cite CRM Installer</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
            <style>
                .install-header {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 2rem 0;
                }

                .step-indicator {
                    border-left: 4px solid #28a745;
                    padding-left: 1rem;
                    margin: 1rem 0;
                }

                .success-badge {
                    background-color: #28a745;
                    color: white;
                    padding: 0.25rem 0.75rem;
                    border-radius: 20px;
                    font-size: 0.875rem;
                }

                .warning-badge {
                    background-color: #ffc107;
                    color: #000;
                    padding: 0.25rem 0.75rem;
                    border-radius: 20px;
                    font-size: 0.875rem;
                }

                .danger-badge {
                    background-color: #dc3545;
                    color: white;
                    padding: 0.25rem 0.75rem;
                    border-radius: 20px;
                    font-size: 0.875rem;
                }
            </style>
        </head>

        <body class="bg-light">
            <!-- Header -->
            <div class="install-header mb-4">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col">
                            <h1 class="display-4">Cite CRM</h1>
                            <p class="lead">Customer Relations Management</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h4 class="mb-0">Cite CRM Installer</h4>
                            </div>
                            <div class="card-body">
                                <?php
                                $login    = strtolower($_POST["first_name"][0]) . strtolower($_POST["last_name"]);

                                /* validate submitted install data */
                                validate($_POST);

                                /* write the need configs */
                                set_path($_POST);

                                /* Load our new configs */
                                require("../include/ADODB/adodb.inc.php");

                                /* Create ADODB Connection */
                                $db = &ADONewConnection('mysqli');
                                $db->Connect($_POST['db_host'], $_POST['db_user'], $_POST['db_password']);

                                if ($db->errorMsg() != '') {
                                    echo "<div class='alert alert-danger'>There was an error connecting to the database: " . $db->errorMsg() . "</div>";
                                    die;
                                }
                                ?>

                                <div class="installation-progress">
                                    <!-- Database Creation -->
                                    <div class="step-indicator mb-4">
                                        <h5>Database Setup</h5>
                                    </div>

                                    <?php
                                    // Try to select the database to check if it exists
                                    if (!$db->SelectDB($_POST['db_name'])) {
                                        // Database doesn't exist, create it
                                        $q = "CREATE DATABASE " . $_POST['db_name'];
                                        if (!$rs = $db->Execute($q)) {
                                            echo "<div class='alert alert-danger mb-3'>
                                            <strong>Failed:</strong> Create Database " . $_POST['db_name'] . " - " . $db->ErrorMsg() . "
                                        </div>";
                                            die;
                                        } else {
                                            echo "<div class='alert alert-success mb-3'>
                                            <strong>Success:</strong> Database " . $_POST['db_name'] . " created successfully
                                        </div>";
                                        }
                                    } else {
                                        echo "<div class='alert alert-info mb-3'>
                                        <strong>Info:</strong> Database " . $_POST['db_name'] . " already exists
                                    </div>";
                                    }

                                    // First close the root connection
                                    $db->close();

                                    // Try to connect with the CRM user credentials
                                    $crm_db = &ADONewConnection('mysqli');
                                    if ($crm_db->Connect($_POST['db_host'], $_POST['crm_db_user'], $_POST['crm_db_password'], $_POST['db_name'])) {
                                        echo "<div class='alert alert-success mb-3'>
                                        <strong>Success:</strong> CRM Database User can connect
                                    </div>";
                                        $crm_db->close();
                                    } else {
                                        echo "<div class='alert alert-warning mb-3'>
                                        <strong>Warning:</strong> User '" . $_POST['crm_db_user'] . "' cannot connect. Tables will be created with root user.
                                    </div>";
                                    }

                                    // Reconnect with root for table creation
                                    $db = &ADONewConnection('mysqli');
                                    $db->Connect($_POST['db_host'], $_POST['db_user'], $_POST['db_password']);
                                    $db->SelectDB($_POST['db_name']);

                                    @define('PRFX', $_POST['db_prefix']);

                                    // Build Tables
                                    ?>
                                    <div class="step-indicator mb-4">
                                        <h5>Table Creation</h5>
                                    </div>

                                    <div class="table-responsive mb-4">
                                        <table class="table table-striped table-hover">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Operation</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php include("sql.php"); ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <?php
                                    ?>

                                    <!-- Admin Creation -->
                                    <div class="step-indicator mb-4 mt-5">
                                        <h5>Administrator Setup</h5>
                                    </div>

                                    <?php
                                    $q = "INSERT INTO " . PRFX . "TABLE_EMPLOYEE SET
                                EMPLOYEE_LOGIN              =" . $db->qstr($login) . ", 
                                EMPLOYEE_FIRST_NAME         =" . $db->qstr($_POST['first_name']) . ",
                                EMPLOYEE_LAST_NAME          =" . $db->qstr($_POST['last_name']) . ",
                                EMPLOYEE_DISPLAY_NAME       =" . $db->qstr($_POST['display_name']) . ",
                                EMPLOYEE_ADDRESS            =" . $db->qstr($_POST['address']) . ",
                                EMPLOYEE_CITY               =" . $db->qstr($_POST['city']) . ",
                                EMPLOYEE_STATE              =" . $db->qstr($_POST['state']) . ",
                                EMPLOYEE_ZIP                =" . $db->qstr($_POST['zip']) . ",
                                EMPLOYEE_TYPE               =" . $db->qstr(4) . ",
                                EMPLOYEE_WORK_PHONE         =" . $db->qstr($_POST['work_phone']) . ",
                                EMPLOYEE_HOME_PHONE         =" . $db->qstr($_POST['home_phone']) . ",
                                EMPLOYEE_MOBILE_PHONE       =" . $db->qstr($_POST['mobile_phone']) . ",
                                EMPLOYEE_STATUS             =" . $db->qstr(1) . ",
                                EMPLOYEE_PASSWD              =" . $db->qstr(md5($_POST['default_password'])) . ",
                                EMPLOYEE_EMAIL              =" . $db->qstr($_POST['default_email']);

                                    if (!$rs = $db->Execute($q)) {
                                        echo "<div class='alert alert-danger mb-3'>
                                        <strong>Failed:</strong> Create Default Admin - " . $db->ErrorMsg() . "
                                    </div>";
                                    } else {
                                        echo "<div class='alert alert-success mb-3'>
                                        <strong>Success:</strong> Default Admin created successfully
                                    </div>";
                                    }
                                    ?>

                                    <!-- Company Information -->
                                    <div class="step-indicator mb-4 mt-5">
                                        <h5>Company Information</h5>
                                    </div>

                                    <?php
                                    $q = " INSERT INTO " . PRFX . "TABLE_COMPANY SET
                                    COMPANY_NAME            =" . $db->qstr($_POST['COMPANY_NAME']) . ",
                                    COMPANY_ADDRESS         =" . $db->qstr($_POST['COMPANY_ADDRESS']) . ", 
                                    COMPANY_CITY            =" . $db->qstr($_POST['COMPANY_CITY']) . ", 
                                    COMPANY_STATE           =" . $db->qstr($_POST['COMPANY_STATE']) . ",
                                    COMPANY_ZIP             =" . $db->qstr($_POST['COMPANY_ZIP']) . ",
                                    COMPANY_COUNTRY         =" . $db->qstr($_POST['COMPANY_COUNTRY']) . ",
                                    COMPNAY_PHONE           =" . $db->qstr($_POST['COMPANY_PHONE']) . ",
                                    COMPNAY_MOBILE          =" . $db->qstr($_POST['COMPANY_MOBILE']) . ",
                                    COMPANY_EMAIL           =" . $db->qstr($_POST['COMPANY_EMAIL']) . ",
                                    COMPANY_TOLL_FREE       =" . $db->qstr($_POST['COMPANY_TOLL_FREE']);

                                    if (!$rs = $db->Execute($q)) {
                                        echo "<div class='alert alert-danger mb-3'>
                                        <strong>Failed:</strong> Adding Company Information - " . $db->ErrorMsg() . "
                                    </div>";
                                    } else {
                                        echo "<div class='alert alert-success mb-3'>
                                        <strong>Success:</strong> Company Information added successfully
                                    </div>";
                                    }

                                    // Completion
                                    if (isset($error_flag) and $error_flag == true) {
                                        echo "<div class='alert alert-danger mt-4'>
                                        <h5>Installation Failed</h5>
                                        <p>There were errors during the install. Your CRM is not enabled and needs to be reinstalled. Please remove the Database and reinstall. If the errors continue please submit a bug report.</p>
                                    </div>";
                                    } else {
                                        // create lock file
                                        if (!touch("../lock")) {
                                            echo "<div class='alert alert-warning mt-4'>Failed to create lock file. Please report this bug!</div>";
                                        }
                                    ?>

                                        <div class="alert alert-success mt-4">
                                            <h4 class="alert-heading">Installation Successful!</h4>
                                            <p>There are still a few steps that need to be completed:</p>
                                            <ol class="mb-0">
                                                <li>Move or rename the install directory to a location not accessible by your web server</li>
                                                <li>Login as admin to finish setting up the CRM in the Control Center</li>
                                            </ol>
                                        </div>

                                        <div class="card mt-4">
                                            <div class="card-body">
                                                <h5>Login Information</h5>
                                                <p><strong>Username:</strong> <?php echo $login; ?><br>
                                                    <strong>Password:</strong> [The password you supplied]
                                                </p>

                                                <h5 class="mt-4">Help Resources</h5>
                                                <ul>
                                                    <li><a href="http://www.incitecrm.com/doc/" target="_blank">User Documentation</a></li>
                                                    <li><a href="http://www.incitecrm.com/support/" target="_blank">Support Forum</a></li>
                                                    <li><a href="http://www.citecrm.com/bugs" target="_blank">Bug Reporting</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="mt-5 py-4 bg-dark text-white">
                <div class="container text-center">
                    <p class="mb-0">Copyright 2005 &copy; In-Cite CRM <a href="http://www.incitecrm.com" class="text-white" target="_blank">www.incitecrm.com</a> All rights reserved.</p>
                </div>
            </footer>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        </body>

        </html>
    <?php
        break;

    ################################
    #		Default						#
    ###############################
    default:
        $default_path = resolveDocumentRoot();
        $default_server = get_server_name();

        include('validate.js');
    ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Cite CRM Installer</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
            <style>
                .install-header {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 2rem 0;
                }

                .section-title {
                    border-left: 4px solid #667eea;
                    padding-left: 1rem;
                    margin: 1.5rem 0 1rem 0;
                }

                .required-field::after {
                    content: "*";
                    color: red;
                    margin-left: 4px;
                }

                .form-section {
                    background: #f8f9fa;
                    border-radius: 8px;
                    padding: 1.5rem;
                    margin-bottom: 1.5rem;
                }

                .file-check-item {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 0.5rem;
                    border-bottom: 1px solid #dee2e6;
                }

                .file-check-item:last-child {
                    border-bottom: none;
                }
            </style>
        </head>

        <body class="bg-light">
            <!-- Header -->
            <div class="install-header mb-4">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col">
                            <h1 class="display-4">Cite CRM</h1>
                            <p class="lead">Customer Relations Management - Installation Wizard</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="card shadow">
                            <div class="card-header bg-white">
                                <ul class="nav nav-tabs card-header-tabs">
                                    <li class="nav-item">
                                        <span class="nav-link active">Installation Setup</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <!-- Initial File Checks -->
                                <div class="form-section">
                                    <h4 class="section-title">Initial File Checks</h4>
                                    <p class="text-muted small">You need to set the config file conf.php to be writable by your webserver. After installation, make them read-only. The log/access.log and cache directory need to be writable by the server.</p>

                                    <div class="bg-white rounded p-3">
                                        <!-- Main Config Check -->
                                        <div class="file-check-item">
                                            <span><strong>Main Config</strong> <span class="text-muted">(../conf.php)</span></span>
                                            <?php
                                            if (!check_write('../conf.php')) {
                                                echo '<span class="badge bg-danger">Not Writable</span>';
                                                $errors[] = array('../conf.php' => 'Not Writable');
                                            } else {
                                                echo '<span class="badge bg-success">OK</span>';
                                            }
                                            ?>
                                        </div>

                                        <!-- Template Cache Check -->
                                        <div class="file-check-item">
                                            <span><strong>Template Cache</strong> <span class="text-muted">(../cache)</span></span>
                                            <?php
                                            if (!check_write('../cache')) {
                                                echo '<span class="badge bg-danger">Not Writable</span>';
                                                $errors[] = array('../cache' => 'Not Writable');
                                            } else {
                                                echo '<span class="badge bg-success">OK</span>';
                                            }
                                            ?>
                                        </div>

                                        <!-- Access Log Check -->
                                        <div class="file-check-item">
                                            <span><strong>Access Log</strong> <span class="text-muted">(../log/access.log)</span></span>
                                            <?php
                                            if (!check_write('../log/access.log')) {
                                                echo '<span class="badge bg-danger">Not Writable</span>';
                                                $errors[] = array('../log/access.log' => 'Not Writable');
                                            } else {
                                                echo '<span class="badge bg-success">OK</span>';
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <?php if (isset($errors) && is_array($errors)): ?>
                                        <div class="alert alert-danger mt-3">
                                            <h5 class="alert-heading">Setup cannot continue!</h5>
                                            <p class="mb-0">Please fix the following errors:</p>
                                            <ul class="mb-0 mt-2">
                                                <?php foreach ($errors as $key => $val): ?>
                                                    <?php foreach ($val as $k => $v): ?>
                                                        <li><strong><?php echo $k; ?>:</strong> <?php echo $v; ?></li>
                                                    <?php endforeach; ?>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php if (!isset($errors)): ?>
                                    <form action="index.php" method="POST" name="install" id="install" onsubmit="try { var myValidator = validate_install; } catch(e) { return true; } return myValidator(this);" class="needs-validation" novalidate>
                                        <input type="hidden" name="mode" value="install">

                                        <!-- Database Information -->
                                        <div class="form-section">
                                            <h4 class="section-title">Database Information</h4>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label required-field">Root Database User</label>
                                                        <input type="text" class="form-control" name="db_user" value="root" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label required-field">Root Database Password</label>
                                                        <input type="password" class="form-control" name="db_password" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label required-field">Root Database Host</label>
                                                        <input type="text" class="form-control" name="db_host" value="localhost" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label required-field">Database Name</label>
                                                        <input type="text" class="form-control" name="db_name" value="citecrm" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label required-field">Table Prefix</label>
                                                        <input type="text" class="form-control" name="db_prefix" value="CRM_" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label required-field">CRM Database User</label>
                                                        <input type="text" class="form-control" name="crm_db_user" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label required-field">CRM Database Password</label>
                                                        <input type="password" class="form-control" name="crm_db_password" required>
                                                    </div>

                                                    <div class="alert alert-info mt-4">
                                                        <small>Set the root user name and password for your MySQL Database Server. You can change the database name and table prefix to suit your needs. The pre-set examples will work fine for most installs. We only need the root user to create the database and tables.</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Administrator Information -->
                                        <div class="form-section">
                                            <h4 class="section-title">Administrator</h4>

                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="default_password" class="form-label required-field">Password</label>
                                                        <input id="default_password" type="password" class="form-control" name="default_password" minlength="6" pattern="[A-Za-z0-9]+" autocomplete="new-password" required>
                                                        <div class="form-text">Minimum 6 characters; letters and numbers only.</div>
                                                        <div class="invalid-feedback">Enter a valid administrator password.</div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="default_password2" class="form-label required-field">Confirm Password</label>
                                                        <input id="default_password2" type="password" class="form-control" name="default_password2" minlength="6" autocomplete="new-password" required>
                                                        <div class="invalid-feedback">Confirm the administrator password.</div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="display_name" class="form-label required-field">Display Name</label>
                                                        <input id="display_name" type="text" class="form-control" name="display_name" required>
                                                        <div class="invalid-feedback">Administrator display name is required.</div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="address" class="form-label required-field">Address</label>
                                                        <input id="address" type="text" class="form-control" name="address" required>
                                                        <div class="invalid-feedback">Administrator address is required.</div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="city" class="form-label required-field">City</label>
                                                        <input id="city" type="text" class="form-control" name="city" required>
                                                        <div class="invalid-feedback">Administrator city is required.</div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="first_name" class="form-label required-field">First Name</label>
                                                                <input id="first_name" type="text" class="form-control" name="first_name" required>
                                                                <div class="invalid-feedback">Administrator first name is required.</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="last_name" class="form-label required-field">Last Name</label>
                                                                <input id="last_name" type="text" class="form-control" name="last_name" required>
                                                                <div class="invalid-feedback">Administrator last name is required.</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="state" class="form-label required-field">State</label>
                                                                <input id="state" type="text" class="form-control" name="state" required>
                                                                <div class="invalid-feedback">Administrator state is required.</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="zip" class="form-label required-field">Zip</label>
                                                                <input id="zip" type="text" class="form-control" name="zip" required>
                                                                <div class="invalid-feedback">Administrator ZIP code is required.</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="home_phone" class="form-label">Home Phone</label>
                                                                <input id="home_phone" type="text" class="form-control" name="home_phone">
                                                                <div class="invalid-feedback">Enter a valid home phone.</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="work_phone" class="form-label">Work Phone</label>
                                                                <input id="work_phone" type="text" class="form-control" name="work_phone">
                                                                <div class="invalid-feedback">Enter a valid work phone.</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="mobile_phone" class="form-label">Mobile Phone</label>
                                                                <input id="mobile_phone" type="text" class="form-control" name="mobile_phone">
                                                                <div class="invalid-feedback">Enter a valid mobile phone.</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="default_email" class="form-label required-field">Email</label>
                                                        <input id="default_email" type="email" class="form-control" name="default_email" required>
                                                        <div class="invalid-feedback">Administrator email is required.</div>
                                                    </div>

                                                    <div class="alert alert-info mt-4">
                                                        <small>Add the default Administrator. This user will have full permissions to the program and database. The login will be created by using the first initial of the first name and the full last name.</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>




                                        <!-- Company Information -->
                                        <div class="form-section">
                                            <h4 class="section-title">Company Information</h4>

                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label required-field">Company Name</label>
                                                                <input type="text" class="form-control" name="COMPANY_NAME" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label required-field">Company Address</label>
                                                                <input type="text" class="form-control" name="COMPANY_ADDRESS" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label required-field">Company City</label>
                                                                <input type="text" class="form-control" name="COMPANY_CITY" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label required-field">Company State</label>
                                                                <input type="text" class="form-control" name="COMPANY_STATE" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label required-field">Company Zip</label>
                                                                <input type="text" class="form-control" name="COMPANY_ZIP" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label required-field">Company Country</label>
                                                                <select name="COMPANY_COUNTRY" class="form-select" required>
                                                                    <option value="US" selected>United States</option>
                                                                    <!-- Add other country options here -->
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Company Phone</label>
                                                                <input type="text" class="form-control" name="COMPANY_PHONE">
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Company Mobile</label>
                                                                <input type="text" class="form-control" name="COMPANY_MOBILE">
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Company Toll Free</label>
                                                                <input type="text" class="form-control" name="COMPANY_TOLL_FREE">
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Company Email</label>
                                                                <input type="email" class="form-control" name="COMPANY_EMAIL">
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="alert alert-info mt-4">
                                                                <small>This is your Company's contact information as it will show up on invoices and billing.</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Web Site Information -->
                                        <div class="form-section">
                                            <h4 class="section-title">Web Site Information</h4>

                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="mb-3">
                                                        <label class="form-label required-field">Full Path</label>
                                                        <input type="text" class="form-control" name="default_path" value="<?php echo $default_path; ?>/citecrm" required>
                                                        <div class="form-text">Do not include trailing slash. Example: /var/www/htdocs/citecrm</div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label required-field">Site Name</label>
                                                        <input type="text" class="form-control" name="default_site_name" value="http://<?php echo $default_server; ?>/citecrm" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary btn-lg">Install Cite CRM</button>
                                        </div>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            </div>
            </div>
            <!-- Footer -->
            <footer class="mt-5 py-4 bg-dark text-white">
                <div class="container text-center">
                    <p class="mb-0">Copyright 2005 &copy; In-Cite CRM <a href="http://www.incitecrm.com" class="text-white" target="_blank">www.incitecrm.com</a> All rights reserved.</p>
                </div>
            </footer>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
            <script>
                // Form validation
                (function() {
                    'use strict';
                    window.addEventListener('load', function() {
                        var forms = document.getElementsByClassName('needs-validation');
                        Array.prototype.filter.call(forms, function(form) {
                            form.addEventListener('submit', function(event) {
                                if (form.checkValidity() === false) {
                                    event.preventDefault();
                                    event.stopPropagation();
                                }
                                form.classList.add('was-validated');
                            }, false);
                        });
                    }, false);
                })();
            </script>
        </body>

        </html>
<?php
}

// Helper functions remain the same
function resolveDocumentRoot()
{
    $current_script = dirname($_SERVER['SCRIPT_NAME']);
    $current_path  = dirname($_SERVER['SCRIPT_FILENAME']);

    $adjust = explode("/", $current_script);
    $adjust = count($adjust) - 1;

    $traverse = str_repeat("../", $adjust);
    $adjusted_path = sprintf("%s/%s", $current_path, $traverse);

    return realpath($adjusted_path);
}

function get_server_name()
{
    return $_SERVER['SERVER_NAME'];
}

function check_lock_file()
{
    return file_exists("../lock");
}

function file_exists_incpath($file)
{
    $paths = explode(PATH_SEPARATOR, get_include_path());
    foreach ($paths as $path) {
        $fullpath = $path . DIRECTORY_SEPARATOR . $file;
        if (file_exists($fullpath)) {
            return true;
        }
    }
    return false;
}

function check_write($file)
{
    return is_writable($file);
}

function set_path($post_data)
{
    $install_date = date("M d Y h:i:s A", time());
    $filename = '../conf.php';
    $content = "<?php
#############################################################
# Cite CRM	Customer Relations Management							#
# Copyright (C) 2003 - 2005 In-Cite CRM								#
# www.citecrm.com  dev@incitecrm.com									#
# This program is distributed under the terms and 				#
# conditions of the GPL	and is free to use or modify			#
# 																				#
# Installer																	#
# Version 0.0.1	Fri Oct 21 05:49:41 PDT 2005						#
#############################################################

include('version.php');
@define('SEP',				'/');
@define('FILE_ROOT',			'" . $post_data['default_path'] . "'.SEP);
@define('WWW_ROOT',				'" . $post_data['default_site_name'] . "');
@define('IMG_URL',       		WWW_ROOT.'images');
@define('INCLUDE_URL',   		FILE_ROOT.'include'.SEP);
@define('SQL_URL',      		FILE_ROOT.'sql');
@define('CALENDAR_PATH',		FILE_ROOT.'DateTime');
@define('SMARTY_URL', 			INCLUDE_URL.'SMARTY'.SEP);
@define('ACCESS_LOG',			FILE_ROOT.'log'.SEP.'access.log');
@define('INSTALL_DATE',		'Dec 23 2005 10:50:45 PM');
@define('debug', 'no');

/* Database Settings */
@define('PRFX',			'" . $post_data['db_prefix'] . "');
@define('DB_HOST', 	'" . $post_data['db_host'] . "');
@define('DB_USER', 		'" . $post_data['crm_db_user'] . "');
@define('DB_PASS', 	'" . $post_data['crm_db_password'] . "');
@define('DB_NAME', 	'" . $post_data['db_name'] . "');

/* IN Cite CRM locations */
@define('INCITCRM', \"http://dev.incitecrm.com/index.php\");

/* Load required Includes */
require(INCLUDE_URL.SEP.'session.php');
require(INCLUDE_URL.SEP.'auth.php');

/* Set Path for SMARTY in the php include path */
set_include_path(get_include_path() . PATH_SEPARATOR . INCLUDE_URL.'SMARTY'.SEP);
require('Smarty.class.php');

/* Set Path for ADODB in the php include path */
set_include_path(get_include_path() . PATH_SEPARATOR . INCLUDE_URL.'ADODB'.SEP);
require('adodb.inc.php');

/* Load smarty template engine */
global \$smarty;
\$smarty = new Smarty;
\$smarty->template_dir 	= FILE_ROOT.'templates';
\$smarty->compile_dir 		= FILE_ROOT.'cache';
\$smarty->config_dir 		= SMARTY_URL.'configs';
\$smarty->cache_dir 		= SMARTY_URL.'cache';
\$smarty->load_filter('output','trimwhitespace');

\$strKey = 'kcmp7n2permbtr0dqebme6mpejhn3ki';

/* create adodb database connection */
\$db = &ADONewConnection('mysqli');
\$db->Connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
\n";

    if (is_writable($filename)) {
        if (!$handle = fopen($filename, 'w')) {
            error_check("Cannot open file: $filename");
        }

        if (fwrite($handle, $content) === FALSE) {
            error_check("Cannot write to file: $filename");
        }
        fclose($handle);
    } else {
        error_check("The file $filename is not writable");
    }
}

function error_check($error)
{
    echo ("<div class='alert alert-danger'><strong>Error:</strong> $error</div>");
    exit;
}

function validate($data)
{
    $required = array(
        'db_user' => 'Root database user',
        'db_password' => 'Root database password',
        'db_host' => 'Database host',
        'db_name' => 'Database name',
        'db_prefix' => 'Table prefix',
        'crm_db_user' => 'CRM database user',
        'crm_db_password' => 'CRM database password',
        'default_password' => 'Administrator password',
        'default_password2' => 'Password confirmation',
        'first_name' => 'Administrator first name',
        'last_name' => 'Administrator last name',
        'display_name' => 'Administrator display name',
        'address' => 'Administrator address',
        'city' => 'Administrator city',
        'state' => 'Administrator state',
        'zip' => 'Administrator ZIP code',
        'default_email' => 'Administrator email',
        'COMPANY_NAME' => 'Company name',
        'COMPANY_ADDRESS' => 'Company address',
        'COMPANY_CITY' => 'Company city',
        'COMPANY_STATE' => 'Company state',
        'COMPANY_ZIP' => 'Company ZIP code',
        'default_path' => 'Installation path',
        'default_site_name' => 'Site URL'
    );

    foreach ($required as $field => $label) {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            error_check("$label is required.");
        }
    }

    $password = trim($data['default_password']);
    if (strlen($password) < 6) {
        error_check('Administrator password must be at least 6 characters.');
    }
    if (strlen($password) > 50) {
        error_check('Administrator password cannot exceed 50 characters.');
    }
    if (!preg_match('/^[A-Za-z0-9]+$/', $password)) {
        error_check('Administrator password may only contain letters and numbers.');
    }

    if ($password !== trim($data['default_password2'])) {
        error_check('Administrator passwords do not match.');
    }

    if (!filter_var(trim($data['default_email']), FILTER_VALIDATE_EMAIL)) {
        error_check('Administrator email address is invalid.');
    }

    $site = trim($data['default_site_name']);
    if (!preg_match('/^https?:\//', $site)) {
        error_check('Site URL must begin with http:// or https://');
    }
}
?>