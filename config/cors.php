<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins
    |--------------------------------------------------------------------------
    |
    | Konfigurasi domain yang diizinkan untuk CORS.
    | Bisa diatur melalui .env dengan format:
    | CORS_ALLOWED_ORIGINS=https://domain1.com,https://domain2.com,https://domain3.com
    |
    | Atau bisa juga menggunakan pattern regex di allowed_origins_patterns
    |
    */
    'allowed_origins' => array_filter(
        array_map('trim', explode(',', env('CORS_ALLOWED_ORIGINS', env('APP_URL', 'http://localhost'))))
    ),

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins Patterns
    |--------------------------------------------------------------------------
    |
    | Pattern regex untuk domain yang diizinkan (untuk subdomain wildcard).
    | Contoh: '^https://.*\.plniconplus\.com$' untuk semua subdomain plniconplus.com
    |
    | Bisa diatur melalui .env dengan format:
    | CORS_ALLOWED_ORIGINS_PATTERNS=^https://.*\.plniconplus\.com$,^https://.*\.pln\.co\.id$
    |
    */
    'allowed_origins_patterns' => array_filter(
        array_map('trim', explode(',', env('CORS_ALLOWED_ORIGINS_PATTERNS', '')))
    ),

    'allowed_headers' => [
        'Accept',
        'Authorization',
        'Content-Type',
        'X-Requested-With',
        'X-CSRF-TOKEN',
        'X-XSRF-TOKEN',
    ],

    'exposed_headers' => [],

    // Preflight cache untuk 1 jam (3600 detik)
    'max_age' => 3600,

    // Untuk internal PLN, credentials bisa diaktifkan jika diperlukan
    'supports_credentials' => false,

];
