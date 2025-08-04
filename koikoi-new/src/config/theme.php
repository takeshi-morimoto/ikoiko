<?php

return [
    /**
     * テーマカラー設定
     * UIの統一性を保つための色定義
     */
    'colors' => [
        // アニメコン用カラー
        'anime' => [
            'primary' => '#0575E6',      // 深い青
            'secondary' => '#21D4FD',    // シアンブルー
            'gradient' => 'linear-gradient(135deg, #0575E6 0%, #21D4FD 100%)',
            'icon' => '#0575E6',
        ],
        
        // 街コン用カラー
        'machi' => [
            'primary' => '#FA709A',      // ピンク
            'secondary' => '#FEE140',    // 黄色
            'gradient' => 'linear-gradient(135deg, #FA709A 0%, #FEE140 100%)',
            'icon' => '#FA709A',
        ],
        
        // 共通カラー
        'common' => [
            'primary' => '#FF6B6B',
            'secondary' => '#4ECDC4',
            'accent' => '#45B7D1',
            'text_dark' => '#2C3E50',
            'text_light' => '#7F8C8D',
            'bg_light' => '#F8F9FA',
            'border' => '#E1E8ED',
        ],
    ],
    
    /**
     * レイアウト設定
     */
    'layout' => [
        'header' => [
            'height' => '4rem',
            'padding' => '1rem',
        ],
        'container' => [
            'max_width' => '1200px',
            'padding' => '1rem',
        ],
        'section' => [
            'padding' => '4rem 0',
        ],
    ],
    
    /**
     * ブレークポイント
     */
    'breakpoints' => [
        'sm' => '640px',
        'md' => '768px',
        'lg' => '1024px',
        'xl' => '1280px',
    ],
];