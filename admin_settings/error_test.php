<?php
// エラーを強制的に表示
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// カスタムエラーハンドラ
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo "<div style='border: 2px solid red; padding: 10px; margin: 10px;'>";
    echo "<h3>エラーが発生しました</h3>";
    echo "エラーレベル: $errno<br>";
    echo "エラーメッセージ: $errstr<br>";
    echo "ファイル: $errfile<br>";
    echo "行番号: $errline<br>";
    echo "</div>";
    return true;
});

// シャットダウンハンドラ（致命的エラーをキャッチ）
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        echo "<div style='border: 2px solid red; padding: 10px; margin: 10px;'>";
        echo "<h3>致命的エラー</h3>";
        echo "タイプ: " . $error['type'] . "<br>";
        echo "メッセージ: " . $error['message'] . "<br>";
        echo "ファイル: " . $error['file'] . "<br>";
        echo "行: " . $error['line'] . "<br>";
        echo "</div>";
    }
});

echo "<h2>area_set.phpをインクルードしてエラーを確認</h2>";

// 出力をキャプチャ
ob_start();
$error_occurred = false;

try {
    // area_set.phpの内容を取得して評価
    $content = file_get_contents('area_set.php');
    
    // PHPコードの構文チェック
    $tokens = token_get_all($content);
    echo "PHPトークン数: " . count($tokens) . "<br>";
    
    // 構文エラーをチェック
    $temp_file = tempnam(sys_get_temp_dir(), 'php_check');
    file_put_contents($temp_file, $content);
    $output = shell_exec("php -l $temp_file 2>&1");
    unlink($temp_file);
    
    if (strpos($output, 'No syntax errors') === false) {
        echo "<div style='color: red;'>構文エラー: $output</div>";
        $error_occurred = true;
    } else {
        echo "<div style='color: green;'>構文エラーなし</div>";
    }
    
    // メモリ使用量
    echo "現在のメモリ使用量: " . memory_get_usage(true) / 1024 / 1024 . " MB<br>";
    echo "メモリ制限: " . ini_get('memory_limit') . "<br>";
    
} catch (Exception $e) {
    echo "<div style='color: red;'>例外: " . $e->getMessage() . "</div>";
    $error_occurred = true;
}

$output = ob_get_clean();
echo $output;

// 同様にevent_set.phpも確認
echo "<h2>event_set.phpをインクルードしてエラーを確認</h2>";

ob_start();
try {
    $content = file_get_contents('event_set.php');
    $tokens = token_get_all($content);
    echo "PHPトークン数: " . count($tokens) . "<br>";
    
    $temp_file = tempnam(sys_get_temp_dir(), 'php_check');
    file_put_contents($temp_file, $content);
    $output = shell_exec("php -l $temp_file 2>&1");
    unlink($temp_file);
    
    if (strpos($output, 'No syntax errors') === false) {
        echo "<div style='color: red;'>構文エラー: $output</div>";
    } else {
        echo "<div style='color: green;'>構文エラーなし</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='color: red;'>例外: " . $e->getMessage() . "</div>";
}

$output = ob_get_clean();
echo $output;
?>