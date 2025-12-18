<?php
/**
 * Database Configuration
 * Update these settings according to your XAMPP/MySQL setup
 */

return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'database' => getenv('DB_DATABASE') ?: 'mental_health_db',
    'username' => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '', // Update to match your MySQL password
    'charset' => 'utf8mb4',
];
