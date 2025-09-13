<?php
echo "test_start.php - 開始<br>";

// 1. 基本的なPHP動作確認
echo "1. PHP動作確認: OK<br>";

// 2. ファイル書き込み権限確認
$test_file = __DIR__ . '/test.txt';
if (file_put_contents($test_file, 'test') !== false) {
    echo "2. ファイル書き込み: OK<br>";
    unlink($test_file);
} else {
    echo "2. ファイル書き込み: NG<br>";
}

// 3. ob_start()のテスト
ob_start();
echo "3. ob_start: ";
ob_end_clean();
echo "OK<br>";

// 4. エラーハンドリングのテスト
set_error_handler(function($errno, $errstr) {
    echo "エラーハンドラ: $errstr<br>";
    return true;
});

// 5. area_set.phpの最初の部分だけをテスト
echo "5. area_set.phpの最初の部分をテスト:<br>";

try {
    // エラーログを有効化
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    echo "- error_reporting: OK<br>";
    
    // ログファイルへの書き込みテスト
    $log_content = date('Y-m-d H:i:s') . " - test\n";
    if (file_put_contents(__DIR__ . '/test_debug.log', $log_content) !== false) {
        echo "- ログファイル書き込み: OK<br>";
    } else {
        echo "- ログファイル書き込み: NG<br>";
    }
    
    // ob_start
    ob_start();
    echo "- ob_start: OK<br>";
    ob_end_flush();
    
} catch (Exception $e) {
    echo "エラー: " . $e->getMessage() . "<br>";
}

echo "テスト完了<br>";
?>