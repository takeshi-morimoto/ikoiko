<?php
echo "<h2>ファイルとディレクトリの権限確認</h2>";

// 現在のディレクトリ
echo "現在のディレクトリ: " . __DIR__ . "<br>";
echo "ディレクトリ書き込み可能: " . (is_writable(__DIR__) ? "はい" : "いいえ") . "<br><br>";

// ファイルの確認
$files = ['area_set.php', 'event_set.php', 'opcache_reset.php', 'area_set_fixed.php'];

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    echo "<h3>$file</h3>";
    
    if (file_exists($path)) {
        echo "存在: はい<br>";
        echo "サイズ: " . filesize($path) . " bytes<br>";
        echo "読み取り可能: " . (is_readable($path) ? "はい" : "いいえ") . "<br>";
        echo "書き込み可能: " . (is_writable($path) ? "はい" : "いいえ") . "<br>";
        echo "最終更新: " . date('Y-m-d H:i:s', filemtime($path)) . "<br>";
        
        // ファイルの権限（8進数）
        $perms = fileperms($path);
        echo "権限: " . sprintf('%o', $perms & 0777) . "<br>";
    } else {
        echo "存在: いいえ<br>";
    }
}

// PHPの実行ユーザー
echo "<h3>PHP実行環境</h3>";
if (function_exists('posix_getpwuid')) {
    $user_info = posix_getpwuid(posix_geteuid());
    echo "実行ユーザー: " . $user_info['name'] . "<br>";
}
echo "PHPバージョン: " . PHP_VERSION . "<br>";
echo "SAPI: " . PHP_SAPI . "<br>";
?>