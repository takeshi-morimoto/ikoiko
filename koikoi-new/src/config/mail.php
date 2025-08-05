<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send all email
    | messages unless another mailer is explicitly specified when sending
    | the message. All additional mailers can be configured within the
    | "mailers" array. Examples of each type of mailer are provided.
    |
    */

    'default' => env('MAIL_MAILER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the mailers used by your application plus
    | their respective settings. Several examples have been configured for
    | you and you are free to add your own as your application requires.
    |
    | Laravel supports a variety of mail "transport" drivers that can be used
    | when delivering an email. You may specify which one you're using for
    | your mailers below. You may also add additional mailers if needed.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses", "ses-v2",
    |            "postmark", "resend", "log", "array",
    |            "failover", "roundrobin"
    |
    */

    'mailers' => [

        'smtp' => [
            'transport' => 'smtp',
            'scheme' => env('MAIL_SCHEME'),
            'url' => env('MAIL_URL'),
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 2525),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'postmark' => [
            'transport' => 'postmark',
            // 'message_stream_id' => env('POSTMARK_MESSAGE_STREAM_ID'),
            // 'client' => [
            //     'timeout' => 5,
            // ],
        ],

        'resend' => [
            'transport' => 'resend',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
            'retry_after' => 60,
        ],

        'roundrobin' => [
            'transport' => 'roundrobin',
            'mailers' => [
                'ses',
                'postmark',
            ],
            'retry_after' => 60,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all emails sent by your application to be sent from
    | the same address. Here you may specify a name and address that is
    | used globally for all emails that are sent by your application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'noreply@koikoi.co.jp'),
        'name' => env('MAIL_FROM_NAME', 'KOIKOI'),
    ],

    /*
    |--------------------------------------------------------------------------
    | KOIKOI 固有設定
    |--------------------------------------------------------------------------
    */

    // 管理者メールアドレス
    'admin_email' => env('MAIL_ADMIN_EMAIL', 'admin@koikoi.co.jp'),
    
    // サポートメールアドレス
    'support_email' => env('MAIL_SUPPORT_EMAIL', 'support@koikoi.co.jp'),
    
    // レート制限設定
    'rate_limit' => [
        'max_emails' => env('MAIL_RATE_LIMIT_MAX', 10),
        'time_window' => env('MAIL_RATE_LIMIT_WINDOW', 3600), // 秒
    ],
    
    // メール送信設定
    'notification' => [
        // 自動リマインダー
        'auto_reminder' => [
            'enabled' => env('MAIL_AUTO_REMINDER_ENABLED', true),
            'days_before' => env('MAIL_REMINDER_DAYS_BEFORE', 1),
        ],
        
        // 確認メール
        'confirmation' => [
            'enabled' => env('MAIL_CONFIRMATION_ENABLED', true),
            'send_immediately' => true,
        ],
        
        // キャンセル通知
        'cancellation' => [
            'enabled' => env('MAIL_CANCELLATION_ENABLED', true),
            'include_refund_info' => true,
        ],
    ],
    
    // メールテンプレート設定
    'templates' => [
        'theme' => env('MAIL_THEME', 'koikoi'),
        'logo_url' => env('MAIL_LOGO_URL', '/images/logo.png'),
        'footer_text' => env('MAIL_FOOTER_TEXT', '© KOIKOI. All rights reserved.'),
        'unsubscribe_url' => env('MAIL_UNSUBSCRIBE_URL', ''),
    ],
    
    // キュー設定
    'queue' => [
        'enabled' => env('MAIL_QUEUE_ENABLED', true),
        'connection' => env('MAIL_QUEUE_CONNECTION', 'database'),
        'queue' => env('MAIL_QUEUE_NAME', 'emails'),
    ],
    
    // メール追跡
    'tracking' => [
        'enabled' => env('MAIL_TRACKING_ENABLED', false),
        'open_tracking' => env('MAIL_OPEN_TRACKING', false),
        'click_tracking' => env('MAIL_CLICK_TRACKING', false),
    ],
    
    // セキュリティ
    'security' => [
        'dkim_enabled' => env('MAIL_DKIM_ENABLED', false),
        'spf_enabled' => env('MAIL_SPF_ENABLED', false),
        'encrypt_sensitive' => env('MAIL_ENCRYPT_SENSITIVE', true),
    ],

];
