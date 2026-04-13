<?php
/**
 * E-Medicine Development Server Router
 * 
 * This file provides routing for the built-in PHP development server.
 * Usage: php -S localhost:8000 backend/server.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// If the request targets an existing file (like an image or CSS), serve it directly
if ($uri !== '/' && file_exists(__DIR__ . '/..' . $uri)) {
    return false;
}

// Security headers
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");

// Basic routing for common entry points
if ($uri === '/') {
    header('Location: /frontend/index.html');
    exit;
}

// Log 404s to the PHP built in server console
error_log("404 Not Found: " . $uri);
http_response_code(404);
echo "<html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this server.</p></body></html>";
