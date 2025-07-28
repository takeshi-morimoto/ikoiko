<?php
/**
 * Canonical URL Helper
 * PATH_INFOやクエリパラメータを含むURLを正規化
 */

/**
 * 現在のページの正規URLを生成
 * @param string $base_url ベースURL（例: https://koikoi.co.jp/ikoiko/event.php）
 * @param string $path_info PATH_INFO部分（オプション）
 * @return string 正規化されたURL
 */
function get_canonical_url($base_url, $path_info = null) {
    // 基本的なURLパターンの正規化
    $patterns = [
        // event.php/area → event/area/
        '/\/event\.php\/(.+)$/' => '/event/$1/',
        // event_m.php/area → event_m/area/
        '/\/event_m\.php\/(.+)$/' => '/event_m/$1/',
        // list_1.php/param → list_1.php
        '/\/(list_[1-4])\.php\/.+$/' => '/$1.php',
        // machi.php/ → machi.php
        '/\/(machi|index|nazo|off|yoruuuu)\.php\/$/' => '/$1.php',
    ];
    
    foreach ($patterns as $pattern => $replacement) {
        if (preg_match($pattern, $base_url, $matches)) {
            $base_url = preg_replace($pattern, $replacement, $base_url);
            break;
        }
    }
    
    // PATH_INFOがある場合の処理
    if ($path_info && !empty(trim($path_info, '/'))) {
        $path_info = trim($path_info, '/');
        
        // event/event_mページの場合は末尾スラッシュを追加
        if (strpos($base_url, '/event/') !== false || strpos($base_url, '/event_m/') !== false) {
            if (!preg_match('/\/$/', $base_url)) {
                $base_url .= '/';
            }
        }
    }
    
    return $base_url;
}

/**
 * メタタグ用のrobots設定を生成
 * @param bool $is_quality_content 品質の高いコンテンツかどうか
 * @param bool $has_content コンテンツが存在するか
 * @return string robots metaタグの値
 */
function get_robots_meta($is_quality_content = true, $has_content = true) {
    if (!$has_content || !$is_quality_content) {
        return 'noindex, follow';
    }
    return 'index, follow';
}

/**
 * 構造化データ（JSON-LD）を生成
 * @param array $data イベント情報など
 * @return string JSON-LD形式の構造化データ
 */
function generate_structured_data($data) {
    $structured = [
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => $data['title'] ?? '',
        'startDate' => $data['date'] ?? '',
        'location' => [
            '@type' => 'Place',
            'name' => $data['place'] ?? '',
            'address' => [
                '@type' => 'PostalAddress',
                'addressRegion' => $data['area'] ?? ''
            ]
        ],
        'offers' => [
            '@type' => 'Offer',
            'price' => $data['price'] ?? '',
            'priceCurrency' => 'JPY',
            'availability' => 'https://schema.org/InStock'
        ],
        'organizer' => [
            '@type' => 'Organization',
            'name' => 'KOIKOI',
            'url' => 'https://koikoi.co.jp'
        ]
    ];
    
    return '<script type="application/ld+json">' . json_encode($structured, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
}
?>