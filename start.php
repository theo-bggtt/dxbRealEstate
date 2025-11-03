<?php
// Set secure session cookie parameters before starting session
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Strict'
]);

// Start the session
session_start();

// Define available languages
define('AVAILABLE_LANGUAGES', ['en', 'fr']);

// Set default language if not set
if (!isset($_SESSION['langue']) || empty($_SESSION['langue'])) {
    $_SESSION['langue'] = 'en'; // Default to 'en' for consistency
}

// Handle language change from form submission
if (isset($_GET['langue']) && in_array($_GET['langue'], AVAILABLE_LANGUAGES)) {
    $_SESSION['langue'] = $_GET['langue'];
    // Use absolute path with BASE_URL
    // Redirect back to the current page, removing the "langue" query param to avoid a loop
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    parse_str($_SERVER['QUERY_STRING'] ?? '', $qs);
    unset($qs['langue']);
    $query = http_build_query($qs);
    header('Location: ' . $scheme . '://' . $host . $path . ($query ? '?' . $query : ''));
    exit;
}
?>