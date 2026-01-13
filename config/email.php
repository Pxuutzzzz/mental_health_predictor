<?php
/**
 * Email Configuration
 * Update this file with your email credentials
 */

return [
    // Email method: 'mail' (PHP mail function) or 'smtp' (SMTP server)
    'method' => 'mail', // Change to 'smtp' for Gmail
    
    // SMTP Configuration (untuk Gmail)
    'smtp' => [
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'encryption' => 'tls', // or 'ssl'
        'username' => 'putriindah2343@gmail.com', // Your Gmail address
        'password' => '', // Your Gmail App Password (NOT regular password!)
        'from_email' => 'putriindah2343@gmail.com',
        'from_name' => 'Mental Health Predictor',
    ],
    
    // Mail Function Configuration (default PHP mail)
    'mail' => [
        'from_email' => 'noreply@mentalhealthpredictor.com',
        'from_name' => 'Mental Health Predictor',
    ],
    
    // General Settings
    'reply_to' => 'support@mentalhealthpredictor.com',
];
