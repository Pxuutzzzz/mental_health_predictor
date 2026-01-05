<?php
/**
 * Router script for PHP built-in server
 * Serves static files directly, routes everything else to index.php
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$filepath = __DIR__ . $uri;

// If it's a static file and exists, let PHP serve it
if ($uri !== '/' && is_file($filepath)) {
    return false; // Let PHP's built-in server handle it
}

// Otherwise, route to index.php
require __DIR__ . '/index.php';
