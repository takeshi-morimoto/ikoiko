<?php
echo "<h2>ログファイルの確認</h2>";

// debug.logの確認
$debug_log = __DIR__ . '/debug.log';
echo "<h3>debug.log</h3>";
if (file_exists($debug_log)) {
    echo "<pre>" . htmlspecialchars(file_get_contents($debug_log)) . "</pre>";
} else {
    echo "debug.logが存在しません<br>";
}

// error.logの確認
$error_log = __DIR__ . '/error.log';
echo "<h3>error.log</h3>";
if (file_exists($error_log)) {
    echo "<pre>" . htmlspecialchars(file_get_contents($error_log)) . "</pre>";
} else {
    echo "error.logが存在しません<br>";
}

// システムのエラーログ
echo "<h3>最近のPHPエラー</h3>";
$error = error_get_last();
if ($error) {
    echo "<pre>";
    print_r($error);
    echo "</pre>";
}
?>