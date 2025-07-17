<?php
// OPcache有効化後の確認用スクリプト
echo "<h1>OPcache有効化確認</h1>";

if (!function_exists('opcache_get_status')) {
    die('<p style="color: red;">OPcacheがインストールされていません</p>');
}

$status = opcache_get_status();
$config = opcache_get_configuration();

echo "<h2>現在の状態</h2>";
echo "<pre>";
echo "OPcache有効: " . (ini_get('opcache.enable') ? '<span style="color: green;">はい</span>' : '<span style="color: red;">いいえ</span>') . "\n";
echo "CLI有効: " . (ini_get('opcache.enable_cli') ? 'はい' : 'いいえ') . "\n";

if ($status !== false) {
    echo "\n<strong>メモリ使用状況:</strong>\n";
    $mem = $status['memory_usage'];
    $used = round($mem['used_memory'] / 1024 / 1024, 2);
    $free = round($mem['free_memory'] / 1024 / 1024, 2);
    $total = $used + $free;
    $percentage = round(($used / $total) * 100, 2);
    
    echo "使用中: {$used} MB / {$total} MB ({$percentage}%)\n";
    echo "空き: {$free} MB\n";
    
    echo "\n<strong>キャッシュ統計:</strong>\n";
    $stats = $status['opcache_statistics'];
    echo "キャッシュされたスクリプト数: " . $stats['num_cached_scripts'] . "\n";
    echo "キャッシュヒット数: " . number_format($stats['hits']) . "\n";
    echo "キャッシュミス数: " . number_format($stats['misses']) . "\n";
    if ($stats['hits'] + $stats['misses'] > 0) {
        $hit_rate = round(($stats['hits'] / ($stats['hits'] + $stats['misses'])) * 100, 2);
        echo "ヒット率: {$hit_rate}%\n";
    }
    
    echo "\n<strong style='color: green;'>✓ OPcacheは正常に動作しています！</strong>\n";
} else {
    echo "\n<strong style='color: red;'>✗ OPcacheは無効です</strong>\n";
    echo "\nロリポップ管理画面で以下を確認してください：\n";
    echo "1. PHP設定でopcache.enableを「On」に設定\n";
    echo "2. 設定を保存後、数分待ってから再度確認\n";
}
echo "</pre>";

// リセットボタン（管理者用）
echo '<hr>';
echo '<p>管理者用: <a href="?reset=1" onclick="return confirm(\'OPcacheをリセットしますか？\')">OPcacheをリセット</a></p>';

if (isset($_GET['reset']) && $_GET['reset'] === '1') {
    if (opcache_reset()) {
        echo '<p style="color: green;">OPcacheがリセットされました！</p>';
    } else {
        echo '<p style="color: red;">OPcacheのリセットに失敗しました</p>';
    }
}
?>