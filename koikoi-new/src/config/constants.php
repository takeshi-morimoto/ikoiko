<?php

return [
    /**
     * ページネーション
     */
    'pagination' => [
        'items_per_page' => env('ITEMS_PER_PAGE', 20),
        'max_links' => 5,
    ],
    
    /**
     * アップロード設定
     */
    'upload' => [
        'max_size' => env('MAX_UPLOAD_SIZE', 10240), // KB
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'image_quality' => 85,
    ],
    
    /**
     * イベント設定
     */
    'events' => [
        'registration_deadline_days' => 3, // 開催日の何日前まで登録可能
        'cancellation_deadline_days' => 7, // 開催日の何日前までキャンセル可能
        'min_participants' => 10,
        'max_participants' => 100,
    ],
    
    /**
     * 料金設定
     */
    'pricing' => [
        'early_bird_discount' => 500, // 早割引き額
        'early_bird_days' => 14, // 開催日の何日前まで早割適用
        'group_discount_min' => 3, // グループ割引最小人数
        'group_discount_rate' => 0.1, // グループ割引率
    ],
    
    /**
     * セキュリティ設定
     */
    'security' => [
        'admin_ips' => explode(',', env('ADMIN_IPS', '')),
        'api_rate_limit' => env('API_RATE_LIMIT', 60),
        'password_min_length' => 8,
    ],
    
    /**
     * 通知設定
     */
    'notifications' => [
        'reminder_days_before' => 3, // イベント何日前にリマインダー送信
        'admin_email' => env('ADMIN_EMAIL', 'admin@koikoi.co.jp'),
        'support_email' => env('SUPPORT_EMAIL', 'support@koikoi.co.jp'),
    ],
    
    /**
     * キャッシュ設定
     */
    'cache' => [
        'ttl' => [
            'events_list' => 300, // 5分
            'event_detail' => 600, // 10分
            'areas' => 3600, // 1時間
            'prefectures' => 86400, // 1日
        ],
    ],
    
    /**
     * 機能フラグ
     */
    'features' => [
        'cache_enabled' => env('FEATURE_CACHE_ENABLED', true),
        'debug_bar' => env('FEATURE_DEBUG_BAR', false),
        'api_rate_limit' => env('FEATURE_API_RATE_LIMIT', true),
        'maintenance_mode' => env('FEATURE_MAINTENANCE_MODE', false),
    ],
];