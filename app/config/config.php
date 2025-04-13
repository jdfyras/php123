<?php
// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Initialize session
session_start();

// Base URL configuration - remove trailing slash if present
$base_url = '/event_management/public';
define('BASE_URL', rtrim($base_url, '/'));

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'event_management');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application settings
define('APP_NAME', 'Gestion d\'Événements');
define('APP_DEBUG', true);

// Error reporting
if (APP_DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
} 