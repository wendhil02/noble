<?php
session_start();

if (!isset($_SESSION['noble_user']) || !isset($_SESSION['noble_lvl'])) {
    $_SESSION['access_denied'] = "You must login first.";
    return false;
}

function require_role($allowed_roles = []) {
    if (!in_array($_SESSION['noble_lvl'], $allowed_roles)) {
        $_SESSION['access_denied'] = "You don't have permission to access this section.";
        return false;
    }
    return true;
}


include '../role_check.php';
require_role(['admin', 'superadmin']); // allow only admin and superadmin

// Admin dashboard content below
echo "Welcome Admin: " . $_SESSION['noble_user'];

include '../role_check.php';
require_role(['employee']); // only employees allowed

echo "Employee Page - Hello " . $_SESSION['noble_user'];


include '../role_check.php';
require_role(['superadmin']); // only superadmins allowed

echo "Superadmin Panel - " . $_SESSION['noble_user'];


if (!require_role(['admin', 'superadmin'])) {
    // Do nothing, just let the page load with the notification
}