<?php
/**
 * Simple Authentication Check
 */
require_once(__DIR__ . '/config.php');

// If user is not logged in and not on login page, redirect to login
$current_file = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION['user_logged_in']) && $current_file != 'login.php') {
    header("Location: login.php");
    exit();
}
?>
