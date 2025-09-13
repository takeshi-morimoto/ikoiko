<?php
echo "<h2>OPcache管理</h2>";

// OPcacheの状態確認
if (function_exists('opcache_get_status')) {
    $status = opcache_get_status();
    echo "OPcache有効: " . ($status['opcache_enabled'] ? 'はい' : 'いいえ') . "<br>";
    echo "キャッシュヒット数: " . $status['opcache_statistics']['hits'] . "<br>";
    echo "キャッシュミス数: " . $status['opcache_statistics']['misses'] . "<br>";
    
    // OPcacheをリセット
    if (opcache_reset()) {
        echo "<div style='color: green;'>OPcacheをリセットしました</div>";
    } else {
        echo "<div style='color: red;'>OPcacheのリセットに失敗しました</div>";
    }
    
    // 特定のファイルを無効化
    $files = ['area_set.php', 'event_set.php'];
    foreach ($files as $file) {
        if (opcache_invalidate($file, true)) {
            echo "$file のキャッシュを無効化しました<br>";
        }
    }
} else {
    echo "OPcache拡張が利用できません<br>";
}

// 代替方法：clearstatcache
clearstatcache(true);
echo "ファイルステータスキャッシュをクリアしました<br>";

echo "<br><a href='area_set.php'>area_set.phpを再度開く</a><br>";
echo "<a href='event_set.php'>event_set.phpを再度開く</a><br>";
?>