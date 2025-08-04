<?php

return [
    /**
     * 画像最適化設定
     */
    
    // 画像品質設定（0-100）
    'quality' => [
        'jpg' => env('IMAGE_QUALITY_JPG', 85),
        'jpeg' => env('IMAGE_QUALITY_JPEG', 85),
        'png' => env('IMAGE_QUALITY_PNG', 90),
        'webp' => env('IMAGE_QUALITY_WEBP', 85),
    ],
    
    // 最大ファイルサイズ（バイト）
    'max_file_size' => env('IMAGE_MAX_FILE_SIZE', 10 * 1024 * 1024), // 10MB
    
    // 許可するMIMEタイプ
    'allowed_mimes' => [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'image/webp',
    ],
    
    // 許可する拡張子
    'allowed_extensions' => [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'webp',
    ],
    
    // 画像サイズ設定
    'sizes' => [
        'thumbnail' => [
            'width' => 300,
            'height' => 300,
            'quality' => 90,
        ],
        'small' => [
            'width' => 600,
            'height' => 600,
            'quality' => 90,
        ],
        'medium' => [
            'width' => 1200,
            'height' => 1200,
            'quality' => 85,
        ],
        'large' => [
            'width' => 1920,
            'height' => 1920,
            'quality' => 85,
        ],
        'xlarge' => [
            'width' => 2560,
            'height' => 2560,
            'quality' => 80,
        ],
    ],
    
    // レスポンシブ画像設定
    'responsive' => [
        // 自動生成するサイズ
        'auto_sizes' => ['thumbnail', 'small', 'medium', 'large'],
        
        // WebP自動生成
        'auto_webp' => env('IMAGE_AUTO_WEBP', true),
        
        // 遅延読み込み
        'lazy_loading' => env('IMAGE_LAZY_LOADING', true),
        
        // デフォルトのsizes属性
        'default_sizes' => [
            '(max-width: 640px) 100vw',
            '(max-width: 1024px) 50vw',
            '33vw',
        ],
    ],
    
    // 画像処理ドライバー（gd or imagick）
    'driver' => env('IMAGE_DRIVER', 'gd'),
    
    // キャッシュ設定
    'cache' => [
        'enabled' => env('IMAGE_CACHE_ENABLED', true),
        'lifetime' => env('IMAGE_CACHE_LIFETIME', 60 * 24 * 30), // 30日
        'path' => storage_path('app/image-cache'),
    ],
    
    // 画像保存パス
    'storage' => [
        'disk' => env('IMAGE_STORAGE_DISK', 'public'),
        'path' => env('IMAGE_STORAGE_PATH', 'images'),
        'organize_by_date' => true, // YYYY/MM形式でフォルダ分け
    ],
    
    // 画像最適化の詳細設定
    'optimization' => [
        // 自動最適化を有効化
        'enabled' => env('IMAGE_OPTIMIZATION_ENABLED', true),
        
        // ストリップメタデータ（EXIF情報など）
        'strip_metadata' => env('IMAGE_STRIP_METADATA', true),
        
        // プログレッシブJPEG
        'progressive_jpeg' => true,
        
        // インターレースPNG
        'interlaced_png' => true,
        
        // 色数削減（PNG）
        'png_color_reduction' => true,
        
        // シャープニング
        'sharpen' => [
            'enabled' => false,
            'amount' => 10,
        ],
    ],
    
    // CDN設定
    'cdn' => [
        'enabled' => env('IMAGE_CDN_ENABLED', false),
        'url' => env('IMAGE_CDN_URL', ''),
        'pull_zone' => env('IMAGE_CDN_PULL_ZONE', ''),
    ],
    
    // 画像プレースホルダー
    'placeholder' => [
        'enabled' => true,
        'type' => 'blur', // blur, color, svg
        'blur_amount' => 20,
        'color' => '#f3f4f6',
        'svg_width' => 40,
        'svg_height' => 40,
    ],
    
    // セキュリティ設定
    'security' => [
        // アップロード時のウイルススキャン
        'virus_scan' => env('IMAGE_VIRUS_SCAN', false),
        
        // 危険なメタデータの削除
        'remove_dangerous_metadata' => true,
        
        // ファイル名のサニタイゼーション
        'sanitize_filename' => true,
        
        // ハッシュベースのファイル名
        'use_hash_filename' => true,
    ],
    
    // バッチ処理設定
    'batch' => [
        // 一度に処理する最大画像数
        'max_images' => 100,
        
        // タイムアウト（秒）
        'timeout' => 300,
        
        // メモリ制限
        'memory_limit' => '256M',
    ],
];