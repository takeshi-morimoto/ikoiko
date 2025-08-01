<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 管理画面設定
    |--------------------------------------------------------------------------
    |
    | KOIKOI管理画面の設定値を管理します。
    | これらの値は管理画面の動作をカスタマイズするために使用されます。
    |
    */

    // ダッシュボード設定
    'dashboard' => [
        // 統計カードの表示順序
        'stats_order' => [
            'total_events',
            'total_participants', 
            'total_revenue',
            'active_staff'
        ],
        
        // 今日のイベント表示数上限
        'today_events_limit' => 10,
        
        // 人気エリア表示数上限
        'popular_areas_limit' => 5,
        
        // 最近のアクティビティ表示数上限
        'recent_activities_limit' => 10,
        
        // 自動更新間隔（分）
        'auto_refresh_minutes' => 5,
    ],

    // チャート設定
    'charts' => [
        // 色設定
        'colors' => [
            'primary' => '#3498db',
            'success' => '#27ae60',
            'warning' => '#f39c12',
            'danger' => '#e74c3c',
            'info' => '#17a2b8',
        ],
        
        // 売上チャートの設定
        'revenue_chart' => [
            'height' => 300,
            'animation_duration' => 1000,
            'tension' => 0.4,
        ],
    ],

    // テーブル設定
    'tables' => [
        // デフォルトページネーション数
        'default_per_page' => 25,
        
        // エクスポート可能な最大レコード数
        'max_export_records' => 10000,
        
        // 検索結果の最大表示数
        'max_search_results' => 100,
    ],

    // ファイルアップロード設定
    'uploads' => [
        // 最大ファイルサイズ（MB）
        'max_file_size' => 10,
        
        // 許可する画像拡張子
        'allowed_image_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        
        // 許可するドキュメント拡張子
        'allowed_document_extensions' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv'],
        
        // アップロード先ディレクトリ
        'upload_path' => 'uploads/admin',
    ],

    // 通知設定
    'notifications' => [
        // 通知の自動非表示時間（ミリ秒）
        'auto_hide_duration' => 5000,
        
        // メール通知を送信するイベント
        'email_events' => [
            'new_registration',
            'payment_completed',
            'event_cancelled',
            'staff_shift_changed',
        ],
    ],

    // セキュリティ設定
    'security' => [
        // セッションタイムアウト（分）
        'session_timeout' => 120,
        
        // ログイン試行回数制限
        'max_login_attempts' => 5,
        
        // IPアドレス制限（空の場合は制限なし）
        'allowed_ips' => [
            // '192.168.1.1',
            // '10.0.0.1',
        ],
        
        // 2段階認証を必須とするかどうか
        'require_2fa' => false,
    ],

    // API設定
    'api' => [
        // APIレート制限（1分間あたりのリクエスト数）
        'rate_limit' => 60,
        
        // APIトークンの有効期限（時間）
        'token_expires_in' => 24,
    ],

    // ログ設定
    'logging' => [
        // 管理画面操作のログを記録するかどうか
        'log_admin_actions' => true,
        
        // ログ保持期間（日）
        'log_retention_days' => 90,
        
        // ログレベル
        'log_level' => 'info',
    ],

    // バックアップ設定
    'backup' => [
        // 自動バックアップを有効にするかどうか
        'auto_backup_enabled' => true,
        
        // バックアップ実行時間（24時間形式）
        'backup_time' => '02:00',
        
        // バックアップ保持数
        'backup_retention_count' => 7,
        
        // バックアップ先ディスク
        'backup_disk' => 'local',
    ],

    // パフォーマンス設定
    'performance' => [
        // キャッシュ有効期間（分）
        'cache_duration' => 60,
        
        // 統計データキャッシュ有効期間（分）
        'stats_cache_duration' => 30,
        
        // クエリキャッシュを使用するかどうか
        'enable_query_cache' => true,
    ],

    // UI設定
    'ui' => [
        // サイドバー幅（px）
        'sidebar_width' => 280,
        
        // ヘッダー高さ（px）
        'header_height' => 60,
        
        // デフォルトテーマ
        'default_theme' => 'light',
        
        // 利用可能なテーマ
        'available_themes' => ['light', 'dark'],
        
        // ページネーションの表示オプション
        'pagination_options' => [10, 25, 50, 100],
    ],

    // エラーハンドリング設定
    'error_handling' => [
        // エラー時のリダイレクト先
        'error_redirect_route' => 'admin.dashboard',
        
        // エラーメッセージの表示時間（ミリ秒）
        'error_message_duration' => 8000,
        
        // デバッグモードでの詳細エラー表示
        'show_detailed_errors' => env('APP_DEBUG', false),
    ],

    // 多言語設定
    'localization' => [
        // デフォルト言語
        'default_locale' => 'ja',
        
        // 利用可能な言語
        'available_locales' => ['ja', 'en'],
        
        // 日付フォーマット
        'date_format' => 'Y-m-d',
        'datetime_format' => 'Y-m-d H:i:s',
        'time_format' => 'H:i',
    ],
];