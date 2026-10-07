<?php
/**
 * BIGAAPP Web Admin Configuration
 */

// Application Info
define('APP_NAME', 'BIGAAPP');
define('APP_VERSION', '1.2.0');
define('ADMIN_PHONE', getenv('ADMIN_PHONE') ?: '0600000000');
define('ADMIN_DEFAULT_PASSWORD', getenv('ADMIN_DEFAULT_PASSWORD') ?: '123456789');

// Firebase Web Configuration
// (Extracted from your google-services.json and previous requests)
$firebaseConfig = [
    'apiKey' => 'AIzaSyAskU33gOclm61_b2lhi7673JCwpU9A7U8',
    'authDomain' => 'bigapp-9aa52.firebaseapp.com',
    'projectId' => 'bigapp-9aa52',
    'storageBucket' => 'bigapp-9aa52.firebasestorage.app',
    'messagingSenderId' => '60564347273',
    'appId' => '1:60564347273:android:340ed96016051f2d45e958'
];

// Start session for auth checks
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
