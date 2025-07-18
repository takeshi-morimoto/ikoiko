<?php
// サイトマップ自動生成スクリプト

// DBの初期化
require_once("./db_data/db_init.php");
$db->query("SET NAMES utf8");

// XMLヘッダー
$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' . "\n\n";

// 基本ページ
$pages = [
    ['loc' => 'https://koikoi.co.jp/ikoiko/', 'priority' => '1.0', 'changefreq' => 'daily'],
    ['loc' => 'https://koikoi.co.jp/ikoiko/machi/', 'priority' => '0.9', 'changefreq' => 'daily'],
    ['loc' => 'https://koikoi.co.jp/ikoiko/contact.php', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ['loc' => 'https://koikoi.co.jp/ikoiko/manga/', 'priority' => '0.8', 'changefreq' => 'weekly'],
];

// 基本ページを追加
foreach ($pages as $page) {
    $xml .= "<url>\n";
    $xml .= "  <loc>{$page['loc']}</loc>\n";
    $xml .= "  <priority>{$page['priority']}</priority>\n";
    $xml .= "  <changefreq>{$page['changefreq']}</changefreq>\n";
    $xml .= "</url>\n";
}

// リストページ
$listPages = ['list_1.php', 'list_2.php', 'list_3.php', 'list_4.php'];
foreach ($listPages as $listPage) {
    $xml .= "<url>\n";
    $xml .= "  <loc>https://koikoi.co.jp/ikoiko/{$listPage}</loc>\n";
    $xml .= "  <priority>0.8</priority>\n";
    $xml .= "  <changefreq>daily</changefreq>\n";
    $xml .= "</url>\n";
}

// マンガページ
for ($i = 2; $i <= 14; $i++) {
    $xml .= "<url>\n";
    $xml .= "  <loc>https://koikoi.co.jp/ikoiko/manga/manga_{$i}.php</loc>\n";
    $xml .= "  <priority>0.6</priority>\n";
    $xml .= "  <changefreq>monthly</changefreq>\n";
    $xml .= "</url>\n";
}

// エリア別イベントページ（データベースから取得）
try {
    $stmt = $db->query("SELECT DISTINCT area FROM area WHERE area IS NOT NULL AND area != '' ORDER BY area");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (!empty($row['area'])) {
            // アニメコンページ
            $xml .= "<url>\n";
            $xml .= "  <loc>https://koikoi.co.jp/ikoiko/event/" . htmlspecialchars($row['area']) . "</loc>\n";
            $xml .= "  <priority>0.7</priority>\n";
            $xml .= "  <changefreq>weekly</changefreq>\n";
            $xml .= "</url>\n";
            
            // 街コンページ
            $xml .= "<url>\n";
            $xml .= "  <loc>https://koikoi.co.jp/ikoiko/event_m/" . htmlspecialchars($row['area']) . "</loc>\n";
            $xml .= "  <priority>0.7</priority>\n";
            $xml .= "  <changefreq>weekly</changefreq>\n";
            $xml .= "</url>\n";
        }
    }
} catch (Exception $e) {
    // エラーログに記録
    error_log("Sitemap generation error: " . $e->getMessage());
}

// 固定ページ
$staticPages = ['参加.php', 'ポリシー.php', '特商法.php', '参加ルール.php', 'nazo.php', 'off.php', 'yoruuuu.php'];
foreach ($staticPages as $page) {
    $xml .= "<url>\n";
    $xml .= "  <loc>https://koikoi.co.jp/ikoiko/" . htmlspecialchars($page) . "</loc>\n";
    $xml .= "  <priority>0.5</priority>\n";
    $xml .= "  <changefreq>monthly</changefreq>\n";
    $xml .= "</url>\n";
}

$xml .= "\n</urlset>";

// ファイルに保存
$sitemap_path = "./sitemap.xml";
if (file_put_contents($sitemap_path, $xml)) {
    echo "サイトマップが正常に生成されました: " . $sitemap_path . "\n";
    echo "生成されたURL数: " . substr_count($xml, '<url>') . "\n";
} else {
    echo "エラー: サイトマップの保存に失敗しました。\n";
}

// robots.txtも更新
$robots_content = file_get_contents("./robots.txt");
if (strpos($robots_content, "Sitemap:") === false) {
    $robots_content .= "\n# サイトマップの場所\nSitemap: https://koikoi.co.jp/ikoiko/analytics/sitemap.xml\n";
    file_put_contents("./robots.txt", $robots_content);
}

// 最終更新日時を記録
$log_file = "./analytics/sitemap_generated.log";
$log_content = date('Y-m-d H:i:s') . " - サイトマップ生成完了\n";
file_put_contents($log_file, $log_content, FILE_APPEND);

?>