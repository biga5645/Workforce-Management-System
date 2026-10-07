<?php
/**
 * BIGAAPP Web Admin Configuration - Environment Template
 * --------------------------------------------------------------------------
 * Copy this file to 'config.php' and update with your Firebase credentials.
 */

// Application Info
define('APP_NAME', 'BIGAAPP');
define('APP_VERSION', '1.2.0');
define('ADMIN_PHONE', '0600000000'); // Default administrator phone identifier
define('ADMIN_DEFAULT_PASSWORD', '123456789'); // Administrator authentication key

// Firebase Web Configuration
// Extract these keys from your Firebase Console -> Project Settings -> General -> Your apps
$firebaseConfig = [
    'apiKey'            => 'YOUR_FIREBASE_API_KEY',
    'authDomain'        => 'your-project-id.firebaseapp.com',
    'projectId'         => 'your-project-id',
    'storageBucket'     => 'your-project-id.firebasestorage.app',
    'messagingSenderId' => 'YOUR_MESSAGING_SENDER_ID',
    'appId'             => 'YOUR_FIREBASE_APP_ID'
];

// Start session for auth checks
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
