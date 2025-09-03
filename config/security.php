<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains security-related configuration options for your
    | application. These settings help protect against various attacks.
    |
    */

    'password' => [
        'min_length' => env('PASSWORD_MIN_LENGTH', 12),
        'require_uppercase' => env('PASSWORD_REQUIRE_UPPERCASE', true),
        'require_lowercase' => env('PASSWORD_REQUIRE_LOWERCASE', true),
        'require_numbers' => env('PASSWORD_REQUIRE_NUMBERS', true),
        'require_symbols' => env('PASSWORD_REQUIRE_SYMBOLS', true),
    ],

    'login' => [
        'max_attempts' => env('LOGIN_MAX_ATTEMPTS', 5),
        'lockout_duration' => env('LOGIN_LOCKOUT_DURATION', 15), // minutes
        'lockout_threshold' => env('LOGIN_LOCKOUT_THRESHOLD', 3),
    ],

    'session' => [
        'regenerate_id' => env('SESSION_REGENERATE_ID', true),
        'regenerate_interval' => env('SESSION_REGENERATE_INTERVAL', 300), // seconds
    ],

    'headers' => [
        'x_frame_options' => env('X_FRAME_OPTIONS', 'DENY'),
        'x_content_type_options' => env('X_CONTENT_TYPE_OPTIONS', 'nosniff'),
        'x_xss_protection' => env('X_XSS_PROTECTION', '1; mode=block'),
        'referrer_policy' => env('REFERRER_POLICY', 'strict-origin-when-cross-origin'),
    ],

    'csp' => [
        'enabled' => env('CSP_ENABLED', true),
        'report_only' => env('CSP_REPORT_ONLY', false),
        'report_uri' => env('CSP_REPORT_URI'),
    ],

    'rate_limiting' => [
        'enabled' => env('RATE_LIMITING_ENABLED', true),
        'default_attempts' => env('RATE_LIMIT_DEFAULT_ATTEMPTS', 120),
        'default_decay' => env('RATE_LIMIT_DEFAULT_DECAY', 1), // minutes
    ],

    'file_uploads' => [
        'max_size' => env('FILE_UPLOAD_MAX_SIZE', 5 * 1024 * 1024), // 5MB
        'allowed_mimes' => [
            'image' => ['jpeg', 'png', 'jpg', 'gif'],
            'document' => ['pdf', 'doc', 'docx'],
        ],
    ],
];
