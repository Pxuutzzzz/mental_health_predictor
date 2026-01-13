<?php
/**
 * Google OAuth Configuration
 * 
 * Untuk mendapatkan Client ID:
 * 1. Buka https://console.cloud.google.com/
 * 2. Buat project baru atau pilih project yang ada
 * 3. Aktifkan Google+ API atau Google Identity Services
 * 4. Buat OAuth 2.0 Credentials (Web Application)
 * 5. Tambahkan Authorized JavaScript origins:
 *    - http://localhost:9000
 *    - http://127.0.0.1:9000
 * 6. Salin Client ID ke file ini
 */

return [
    // Google OAuth Client ID
    // Ganti dengan Client ID dari Google Cloud Console
    'client_id' => getenv('GOOGLE_CLIENT_ID') ?: '40071412388-bd6rj8mqmpsit1j4nau4niknh68om9f5.apps.googleusercontent.com',
    
    // Allowed domains (optional - kosongkan untuk allow semua)
    'allowed_domains' => [
        // Contoh: 'gmail.com', 'yourdomain.com'
    ],
    
    // Auto-register users from Google
    'auto_register' => true,
];
