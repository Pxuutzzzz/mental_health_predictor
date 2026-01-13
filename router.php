<?php
/**
 * Router script for PHP built-in server
 * Serves static files directly, routes everything else to index.php
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Serve static files and PHP files in root directly
if ($uri !== '/') {
    $filepath = __DIR__ . $uri;
    if (is_file($filepath)) {
        // For .php files, execute them
        if (pathinfo($filepath, PATHINFO_EXTENSION) === 'php') {
            require $filepath;
            return;
        }
        // For other files (css, js, images), let PHP serve them
        return false;
    }
}

// Otherwise, route to index.php
require __DIR__ . '/index.php';
