<?php
require_once("include.php");

if (!function_exists('xml2php') || !xml2php("messaging")) {
    $smarty->assign('error_msg', "Error in language file");
}

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';

function _validate_contact_input(&$data, &$errors)
{
    $errors = array();
    $first = trim(isset($data['first_name']) ? $data['first_name'] : '');
    $last = trim(isset($data['last_name']) ? $data['last_name'] : '');
    $email = trim(isset($data['email']) ? $data['email'] : '');

    if ($first === '' && $last === '' && $email === '') {
        $errors[] = 'Please provide a name or email for the contact.';
    }
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address.';
    }
    if (strlen($first) > 255 || strlen($last) > 255) {
        $errors[] = 'Name fields are too long.';
    }
    if (isset($data['job_title']) && strlen($data['job_title']) > 255) {
        $errors[] = 'Job title is too long.';
    }
    if (isset($data['social_handle']) && strlen($data['social_handle']) > 255) {
        $errors[] = 'Social handle is too long.';
    }
    // basic phone cleanup (optional)
    if (isset($data['phone']) && trim($data['phone']) !== '') {
        $data['phone'] = trim($data['phone']);
        if (strlen($data['phone']) > 100) {
            $errors[] = 'Phone number is too long.';
        }
    }
    return count($errors) === 0;
}

// ---------- Contacts: list / view / new / edit / delete ----------
if ($action === 'list') {
    $q = "SELECT c.*, b.BUSINESS_NAME FROM " . PRFX . "TABLE_CONTACT c LEFT JOIN " . PRFX . "TABLE_BUSINESS b ON c.BUSINESS_ID = b.BUSINESS_ID ORDER BY c.LAST_NAME, c.FIRST_NAME";
    if (!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    }
    $contacts = $rs->GetArray();
    $smarty->assign('contacts', $contacts);
    $smarty->display('messaging' . SEP . 'contacts_list.tpl');
    exit;
}

if ($action === 'view' && isset($_GET['contact_id'])) {
    $contact_id = (int)$_GET['contact_id'];
    $q = "SELECT c.*, b.BUSINESS_NAME, b.CONTRACT_RENEWAL_DATE, b.PRODUCT_PREFERENCES FROM " . PRFX . "TABLE_CONTACT c LEFT JOIN " . PRFX . "TABLE_BUSINESS b ON c.BUSINESS_ID = b.BUSINESS_ID WHERE c.CONTACT_ID=" . $db->qstr($contact_id) . " LIMIT 1";
    if (!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    }
    $contact = $rs->FetchRow();
    if (!$contact) {
        force_page('core', 'error&error_msg=Contact not found');
        exit;
    }
    $smarty->assign('contact', $contact);
    $smarty->display('messaging' . SEP . 'contacts_view.tpl');
    exit;
}

if ($action === 'new' || $action === 'edit') {
    if (isset($_POST['submit'])) {
        $business_id = !empty($_POST['business_id']) ? (int)$_POST['business_id'] : null;
        $payload = array(
            'first_name' => isset($_POST['first_name']) ? $_POST['first_name'] : '',
            'last_name' => isset($_POST['last_name']) ? $_POST['last_name'] : '',
            'email' => isset($_POST['email']) ? $_POST['email'] : '',
            'phone' => isset($_POST['phone']) ? $_POST['phone'] : '',
            'job_title' => isset($_POST['job_title']) ? $_POST['job_title'] : '',
            'social_handle' => isset($_POST['social_handle']) ? $_POST['social_handle'] : '',
            'notes' => isset($_POST['notes']) ? $_POST['notes'] : '',
        );

        $errors = array();
        if (!_validate_contact_input($payload, $errors)) {
            $smarty->assign('error_msg', implode('<br>', $errors));
            // re-display form with posted values
            $businesses = array();
            $q = "SELECT BUSINESS_ID, BUSINESS_NAME FROM " . PRFX . "TABLE_BUSINESS ORDER BY BUSINESS_NAME";
            if ($rs = $db->Execute($q)) {
                $businesses = $rs->GetArray();
            }
            $smarty->assign('businesses', $businesses);
            $smarty->assign('contact', array_merge($payload, array('BUSINESS_ID' => $business_id)));
            $smarty->display('messaging' . SEP . 'contacts_edit.tpl');
            exit;
        }

        if ($action === 'new') {
            // enforce unique email
            if (!empty($payload['email'])) {
                $check = $db->Execute("SELECT CONTACT_ID FROM " . PRFX . "TABLE_CONTACT WHERE EMAIL=" . $db->qstr($payload['email']) . " LIMIT 1");
                if ($check && !$check->EOF) {
                    $smarty->assign('error_msg', 'A contact with that email already exists.');
                    $businesses = array();
                    $q = "SELECT BUSINESS_ID, BUSINESS_NAME FROM " . PRFX . "TABLE_BUSINESS ORDER BY BUSINESS_NAME";
                    if ($rs = $db->Execute($q)) {
                        $businesses = $rs->GetArray();
                    }
                    $smarty->assign('businesses', $businesses);
                    $smarty->assign('contact', array_merge($payload, array('BUSINESS_ID' => $business_id)));
                    $smarty->display('messaging' . SEP . 'contacts_edit.tpl');
                    exit;
                }
            }
            $sql = "INSERT INTO " . PRFX . "TABLE_CONTACT SET BUSINESS_ID=" . $db->qstr($business_id) . ", FIRST_NAME=" . $db->qstr($payload['first_name']) . ", LAST_NAME=" . $db->qstr($payload['last_name']) . ", EMAIL=" . $db->qstr($payload['email']) . ", PHONE=" . $db->qstr($payload['phone']) . ", JOB_TITLE=" . $db->qstr($payload['job_title']) . ", SOCIAL_HANDLE=" . $db->qstr($payload['social_handle']) . ", NOTES=" . $db->qstr($payload['notes']);
            if (!$db->Execute($sql)) {
                force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg());
                exit;
            }
            $contact_id = $db->Insert_ID();
            force_page('messaging', 'contacts&action=view&contact_id=' . $contact_id . '&page_title=Contact');
            exit;
        } else {
            $contact_id = (int)$_POST['contact_id'];
            // enforce unique email for edits
            if (!empty($payload['email'])) {
                $check = $db->Execute("SELECT CONTACT_ID FROM " . PRFX . "TABLE_CONTACT WHERE EMAIL=" . $db->qstr($payload['email']) . " LIMIT 1");
                if ($check && !$check->EOF && (int)$check->fields['CONTACT_ID'] !== $contact_id) {
                    $smarty->assign('error_msg', 'A contact with that email already exists.');
                    $businesses = array();
                    $q = "SELECT BUSINESS_ID, BUSINESS_NAME FROM " . PRFX . "TABLE_BUSINESS ORDER BY BUSINESS_NAME";
                    if ($rs = $db->Execute($q)) {
                        $businesses = $rs->GetArray();
                    }
                    $smarty->assign('businesses', $businesses);
                    $smarty->assign('contact', array_merge($payload, array('BUSINESS_ID' => $business_id, 'CONTACT_ID' => $contact_id)));
                    $smarty->display('messaging' . SEP . 'contacts_edit.tpl');
                    exit;
                }
            }
            $sql = "UPDATE " . PRFX . "TABLE_CONTACT SET BUSINESS_ID=" . $db->qstr($business_id) . ", FIRST_NAME=" . $db->qstr($payload['first_name']) . ", LAST_NAME=" . $db->qstr($payload['last_name']) . ", EMAIL=" . $db->qstr($payload['email']) . ", PHONE=" . $db->qstr($payload['phone']) . ", JOB_TITLE=" . $db->qstr($payload['job_title']) . ", SOCIAL_HANDLE=" . $db->qstr($payload['social_handle']) . ", NOTES=" . $db->qstr($payload['notes']) . " WHERE CONTACT_ID=" . $db->qstr($contact_id);
            if (!$db->Execute($sql)) {
                force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg());
                exit;
            }
            force_page('messaging', 'contacts&action=view&contact_id=' . $contact_id . '&page_title=Contact');
            exit;
        }
    } else {
        // show form
        $businesses = array();
        $q = "SELECT BUSINESS_ID, BUSINESS_NAME FROM " . PRFX . "TABLE_BUSINESS ORDER BY BUSINESS_NAME";
        if ($rs = $db->Execute($q)) {
            $businesses = $rs->GetArray();
        }
        $smarty->assign('businesses', $businesses);

        if ($action === 'edit' && isset($_GET['contact_id'])) {
            $cid = (int)$_GET['contact_id'];
            $q = "SELECT * FROM " . PRFX . "TABLE_CONTACT WHERE CONTACT_ID=" . $db->qstr($cid) . " LIMIT 1";
            if ($rs = $db->Execute($q)) {
                $contact = $rs->FetchRow();
                $smarty->assign('contact', $contact);
            }
        }
        $smarty->display('messaging' . SEP . 'contacts_edit.tpl');
        exit;
    }
}

if ($action === 'delete' && isset($_GET['contact_id'])) {
    $cid = (int)$_GET['contact_id'];
    $q = "DELETE FROM " . PRFX . "TABLE_CONTACT WHERE CONTACT_ID=" . $db->qstr($cid);
    if (!$db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg());
        exit;
    }
    force_page('messaging', 'contacts&action=list&page_title=Contacts');
    exit;
}

// ---------- Business: list / view / new / edit / delete ----------
if ($action === 'business_list') {
    $q = "SELECT * FROM " . PRFX . "TABLE_BUSINESS ORDER BY BUSINESS_NAME";
    if (!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg());
        exit;
    }
    $businesses = $rs->GetArray();
    $smarty->assign('businesses', $businesses);
    $smarty->display('messaging' . SEP . 'business_list.tpl');
    exit;
}

if ($action === 'business_view' && isset($_GET['business_id'])) {
    $bid = (int)$_GET['business_id'];
    $q = "SELECT * FROM " . PRFX . "TABLE_BUSINESS WHERE BUSINESS_ID=" . $db->qstr($bid) . " LIMIT 1";
    if (!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg());
        exit;
    }
    $business = $rs->FetchRow();
    if (!$business) {
        force_page('core', 'error&error_msg=Business not found');
        exit;
    }
    $smarty->assign('business', $business);
    $smarty->display('messaging' . SEP . 'business_view.tpl');
    exit;
}

if ($action === 'business_new' || $action === 'business_edit') {
    if (isset($_POST['submit'])) {
        $name = isset($_POST['business_name']) ? trim($_POST['business_name']) : '';
        $address = isset($_POST['business_address']) ? trim($_POST['business_address']) : '';
        $phone = isset($_POST['business_phone']) ? trim($_POST['business_phone']) : '';
        $renewal = isset($_POST['contract_renewal_date']) ? trim($_POST['contract_renewal_date']) : null;
        $prefs = isset($_POST['product_preferences']) ? trim($_POST['product_preferences']) : '';

        if ($name === '') {
            $smarty->assign('error_msg', 'Please provide a business name.');
            $smarty->assign('business', $_POST);
            $smarty->display('messaging' . SEP . 'business_edit.tpl');
            exit;
        }

        // validate contract renewal date (YYYY-MM-DD) if provided
        if ($renewal && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $renewal)) {
            $smarty->assign('error_msg', 'Invalid contract renewal date. Use YYYY-MM-DD.');
            $smarty->assign('business', $_POST);
            $smarty->display('messaging' . SEP . 'business_edit.tpl');
            exit;
        }

        if ($action === 'business_new') {
            // unique business name
            $chk = $db->Execute("SELECT BUSINESS_ID FROM " . PRFX . "TABLE_BUSINESS WHERE BUSINESS_NAME=" . $db->qstr($name) . " LIMIT 1");
            if ($chk && !$chk->EOF) {
                $smarty->assign('error_msg', 'A business with that name already exists.');
                $smarty->assign('business', $_POST);
                $smarty->display('messaging' . SEP . 'business_edit.tpl');
                exit;
            }
            $sql = "INSERT INTO " . PRFX . "TABLE_BUSINESS SET BUSINESS_NAME=" . $db->qstr($name) . ", BUSINESS_ADDRESS=" . $db->qstr($address) . ", BUSINESS_PHONE=" . $db->qstr($phone) . ", CONTRACT_RENEWAL_DATE=" . $db->qstr($renewal) . ", PRODUCT_PREFERENCES=" . $db->qstr($prefs);
            if (!$db->Execute($sql)) {
                force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg());
                exit;
            }
            $bid = $db->Insert_ID();
            force_page('messaging', 'contacts&action=view&contact_id=' . (int)$_GET['contact_id'] . '&page_title=Business');
            // fall back to business view
            force_page('messaging', 'contacts&action=business_view&business_id=' . $bid);
            exit;
        } else {
            $bid = (int)$_POST['business_id'];
            // unique business name for edit
            $chk = $db->Execute("SELECT BUSINESS_ID FROM " . PRFX . "TABLE_BUSINESS WHERE BUSINESS_NAME=" . $db->qstr($name) . " LIMIT 1");
            if ($chk && !$chk->EOF && (int)$chk->fields['BUSINESS_ID'] !== $bid) {
                $smarty->assign('error_msg', 'A business with that name already exists.');
                $smarty->assign('business', $_POST);
                $smarty->display('messaging' . SEP . 'business_edit.tpl');
                exit;
            }
            $sql = "UPDATE " . PRFX . "TABLE_BUSINESS SET BUSINESS_NAME=" . $db->qstr($name) . ", BUSINESS_ADDRESS=" . $db->qstr($address) . ", BUSINESS_PHONE=" . $db->qstr($phone) . ", CONTRACT_RENEWAL_DATE=" . $db->qstr($renewal) . ", PRODUCT_PREFERENCES=" . $db->qstr($prefs) . " WHERE BUSINESS_ID=" . $db->qstr($bid);
            if (!$db->Execute($sql)) {
                force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg());
                exit;
            }
            force_page('messaging', 'contacts&action=business_view&business_id=' . $bid);
            exit;
        }
    } else {
        if ($action === 'business_edit' && isset($_GET['business_id'])) {
            $bid = (int)$_GET['business_id'];
            $q = "SELECT * FROM " . PRFX . "TABLE_BUSINESS WHERE BUSINESS_ID=" . $db->qstr($bid) . " LIMIT 1";
            if ($rs = $db->Execute($q)) {
                $business = $rs->FetchRow();
                $smarty->assign('business', $business);
            }
        }
        $smarty->display('messaging' . SEP . 'business_edit.tpl');
        exit;
    }
}

if ($action === 'business_delete' && isset($_GET['business_id'])) {
    $bid = (int)$_GET['business_id'];
    $q = "DELETE FROM " . PRFX . "TABLE_BUSINESS WHERE BUSINESS_ID=" . $db->qstr($bid);
    if (!$db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg());
        exit;
    }
    force_page('messaging', 'contacts&action=business_list');
    exit;
}

// fallback: list
force_page('messaging', 'contacts&action=list');

?>
