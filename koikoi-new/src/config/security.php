<?php

return [
    /**
     * セキュリティ設定
     */
    
    // CSRFトークン設定
    'csrf' => [
        'enabled' => true,
        'exclude_routes' => [
            'api/*',
            'webhook/*'
        ],
        'token_lifetime' => 120, // 分
    ],
    
    // XSS保護設定
    'xss' => [
        'enabled' => true,
        'auto_escape' => true,
        'allowed_tags' => [
            'p', 'br', 'strong', 'em', 'u', 'a', 'ul', 'ol', 'li'
        ],
        'allowed_attributes' => [
            'a' => ['href', 'title', 'target']
        ]
    ],
    
    // Content Security Policy
    'csp' => [
        'enabled' => true,
        'report_only' => false,
        'report_uri' => '/csp-report',
        'directives' => [
            'default-src' => ["'self'"],
            'script-src' => ["'self'", "'unsafe-inline'", "'unsafe-eval'", 'https://cdn.jsdelivr.net'],
            'style-src' => ["'self'", "'unsafe-inline'", 'https://fonts.googleapis.com'],
            'img-src' => ["'self'", 'data:', 'https:'],
            'font-src' => ["'self'", 'https://fonts.gstatic.com'],
            'connect-src' => ["'self'"],
            'media-src' => ["'self'"],
            'object-src' => ["'none'"],
            'child-src' => ["'self'"],
            'frame-ancestors' => ["'self'"],
            'form-action' => ["'self'"],
            'base-uri' => ["'self'"],
        ]
    ],
    
    // レート制限設定
    'rate_limiting' => [
        'enabled' => true,
        'limits' => [
            'default' => ['max_attempts' => 60, 'decay_minutes' => 1],
            'api' => ['max_attempts' => 100, 'decay_minutes' => 1],
            'login' => ['max_attempts' => 5, 'decay_minutes' => 15],
            'register' => ['max_attempts' => 3, 'decay_minutes' => 60],
            'password_reset' => ['max_attempts' => 3, 'decay_minutes' => 60],
            'contact' => ['max_attempts' => 5, 'decay_minutes' => 30],
            'search' => ['max_attempts' => 30, 'decay_minutes' => 1],
            'upload' => ['max_attempts' => 10, 'decay_minutes' => 10],
        ]
    ],
    
    // ファイルアップロード設定
    'upload' => [
        'max_file_size' => 10485760, // 10MB
        'allowed_extensions' => [
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx'],
            'video' => ['mp4', 'avi', 'mov'],
        ],
        'allowed_mime_types' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ],
        'scan_for_virus' => false,
    ],
    
    // パスワードポリシー
    'password' => [
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_special_chars' => false,
        'bcrypt_rounds' => 10,
        'rehash_on_login' => true,
    ],
    
    // セッション設定
    'session' => [
        'secure_cookie' => env('SESSION_SECURE_COOKIE', true),
        'http_only' => true,
        'same_site' => 'lax',
        'regenerate_on_login' => true,
        'lifetime' => 120, // 分
        'idle_timeout' => 30, // 分
    ],
    
    // ログ設定
    'logging' => [
        'log_failed_logins' => true,
        'log_suspicious_activity' => true,
        'log_admin_actions' => true,
        'retention_days' => 90,
    ],
    
    // IPブロック設定
    'ip_blocking' => [
        'enabled' => false,
        'max_failed_attempts' => 10,
        'block_duration' => 3600, // 秒
        'whitelist' => [
            // '127.0.0.1',
        ],
        'blacklist' => [
            // '192.168.1.100',
        ]
    ],
    
    // 二要素認証
    '2fa' => [
        'enabled' => false,
        'enforced' => false,
        'methods' => ['totp', 'email'],
        'remember_days' => 30,
    ],
    
    // APIセキュリティ
    'api' => [
        'require_https' => true,
        'api_key_header' => 'X-API-Key',
        'signature_header' => 'X-Signature',
        'timestamp_tolerance' => 300, // 秒
    ],
    
    // セキュリティヘッダー
    'headers' => [
        'x_frame_options' => 'SAMEORIGIN',
        'x_content_type_options' => 'nosniff',
        'x_xss_protection' => '1; mode=block',
        'referrer_policy' => 'strict-origin-when-cross-origin',
        'permissions_policy' => 'geolocation=(), microphone=(), camera=()',
        'strict_transport_security' => [
            'enabled' => env('APP_ENV') === 'production',
            'max_age' => 31536000,
            'include_subdomains' => true,
        ]
    ],
];