<?php
/**
 * Helper functions for the application
 */

/**
 * Redirect to a URL with the correct base path
 * @param string $path The path to redirect to (without leading slash)
 */
function redirect($path) {
    $path = trim($path, '/');
    // Get the server's hostname and protocol
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    
    // Construct the full URL
    $url = $protocol . $host . BASE_URL;
    if (!empty($path)) {
        $url .= '/' . $path;
    }
    
    header('Location: ' . $url);
    exit;
}

/**
 * Get the full URL for a path
 * @param string $path The path (without leading slash)
 * @return string The full URL
 */
function url($path) {
    $path = trim($path, '/');
    // Get the server's hostname and protocol
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    
    // Construct the full URL
    $baseUrl = $protocol . $host . BASE_URL;
    if (empty($path)) {
        return $baseUrl;
    }
    return $baseUrl . '/' . $path;
}

/**
 * Check if the current user is an admin
 * @return bool
 */
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Check if a user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Get the current user's ID
 * @return int|null
 */
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Format a date in the preferred format
 * @param string $date The date to format
 * @return string The formatted date
 */
function formatDate($date) {
    return date('d/m/Y H:i', strtotime($date));
}

/**
 * Escape HTML to prevent XSS
 * @param string $string The string to escape
 * @return string The escaped string
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Debug function to print variables
 * @param mixed $var The variable to debug
 * @param bool $die Whether to die after printing
 */
function dd($var, $die = true) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    if ($die) die();
}

/**
 * Require authentication for protected routes
 * @param string $redirectPath The path to redirect to if not authenticated
 */
function requireAuth($redirectPath = 'login') {
    if (!isLoggedIn()) {
        redirect($redirectPath);
    }
}

/**
 * Require admin role for admin routes
 * @param string $redirectPath The path to redirect to if not admin
 */
function requireAdmin($redirectPath = 'login') {
    if (!isAdmin()) {
        redirect($redirectPath);
    }
} 