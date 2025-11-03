<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for rate limiting across the
    | application. Rate limiting helps prevent abuse and brute force attacks.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Login Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configuration for login rate limiting to prevent brute force attacks.
    |
    */
    'login' => [
        'max_attempts' => env('LOGIN_MAX_ATTEMPTS', 5),
        'decay_minutes' => env('LOGIN_DECAY_MINUTES', 15),
        'lockout_duration' => env('LOGIN_LOCKOUT_DURATION', 30), // minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | API Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configuration for API rate limiting to prevent abuse.
    |
    */
    'api' => [
        'max_attempts' => env('API_MAX_ATTEMPTS', 60),
        'decay_minutes' => env('API_DECAY_MINUTES', 1),
    ],

    /*
    |--------------------------------------------------------------------------
    | Global Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configuration for global rate limiting across the application.
    |
    */
    'global' => [
        'max_attempts' => env('GLOBAL_MAX_ATTEMPTS', 100),
        'decay_minutes' => env('GLOBAL_DECAY_MINUTES', 1),
    ],

    /*
    |--------------------------------------------------------------------------
    | IP-based Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configuration for IP-based rate limiting.
    |
    */
    'ip' => [
        'max_attempts' => env('IP_MAX_ATTEMPTS', 200),
        'decay_minutes' => env('IP_DECAY_MINUTES', 1),
        'blocked_duration' => env('IP_BLOCKED_DURATION', 60), // minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Messages
    |--------------------------------------------------------------------------
    |
    | Custom messages for rate limiting violations.
    |
    */
    'messages' => [
        'login_too_many_attempts' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam :seconds detik.',
        'api_too_many_requests' => 'Terlalu banyak request. Silakan coba lagi dalam :seconds detik.',
        'ip_blocked' => 'IP Anda telah diblokir karena terlalu banyak request. Silakan coba lagi dalam :minutes menit.',
        'global_limit_exceeded' => 'Batas request global telah terlampaui. Silakan coba lagi nanti.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Storage
    |--------------------------------------------------------------------------
    |
    | Storage driver for rate limiting data.
    |
    */
    'storage' => env('RATE_LIMITING_STORAGE', 'cache'), // cache, database, redis

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Monitoring
    |--------------------------------------------------------------------------
    |
    | Enable monitoring and logging for rate limiting.
    |
    */
    'monitoring' => [
        'enabled' => env('RATE_LIMITING_MONITORING', true),
        'log_violations' => env('RATE_LIMITING_LOG_VIOLATIONS', true),
        'alert_threshold' => env('RATE_LIMITING_ALERT_THRESHOLD', 10), // violations per minute
    ],
];
