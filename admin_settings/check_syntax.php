<?php
// エラー表示を有効化
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>PHP構文チェック</h2>";

// チェックするファイル
$files = [
    'area_set.php',
    'event_set.php'
];

foreach ($files as $file) {
    echo "<h3>$file の構文チェック</h3>";
    
    // ファイルの存在確認
    if (!file_exists($file)) {
        echo "<span style='color: red;'>ファイルが存在しません</span><br>";
        continue;
    }
    
    // PHPの構文チェック
    $output = [];
    $return_var = 0;
    exec("php -l $file 2>&1", $output, $return_var);
    
    if ($return_var === 0) {
        echo "<span style='color: green;'>構文エラーなし</span><br>";
    } else {
        echo "<span style='color: red;'>構文エラーあり:</span><br>";
        echo "<pre>" . implode("\n", $output) . "</pre>";
    }
    
    // ファイルを読み込んでみる
    echo "ファイル読み込みテスト: ";
    ob_start();
    $error = false;
    
    try {
        // 出力バッファリングで実行を防ぐ
        $content = file_get_contents($file);
        
        // PHPタグのバランスをチェック
        $open_tags = substr_count($content, '<?php');
        $close_tags = substr_count($content, '?>');
        echo "PHPタグ - 開始: $open_tags, 終了: $close_tags<br>";
        
        // 括弧のバランスをチェック
        $open_paren = substr_count($content, '(');
        $close_paren = substr_count($content, ')');
        $open_brace = substr_count($content, '{');
        $close_brace = substr_count($content, '}');
        $open_bracket = substr_count($content, '[');
        $close_bracket = substr_count($content, ']');
        
        echo "括弧のバランス:<br>";
        echo "- 丸括弧 (): $open_paren / $close_paren " . ($open_paren == $close_paren ? "✓" : "✗") . "<br>";
        echo "- 波括弧 {}: $open_brace / $close_brace " . ($open_brace == $close_brace ? "✓" : "✗") . "<br>";
        echo "- 角括弧 []: $open_bracket / $close_bracket " . ($open_bracket == $close_bracket ? "✓" : "✗") . "<br>";
        
    } catch (Exception $e) {
        $error = true;
        echo "<span style='color: red;'>エラー: " . $e->getMessage() . "</span><br>";
    }
    
    ob_end_clean();
    
    if (!$error) {
        echo "<span style='color: green;'>OK</span><br>";
    }
}

// PHPの設定確認
echo "<h3>PHP設定</h3>";
echo "PHP バージョン: " . phpversion() . "<br>";
echo "メモリ制限: " . ini_get('memory_limit') . "<br>";
echo "実行時間制限: " . ini_get('max_execution_time') . "秒<br>";
echo "エラーログ: " . ini_get('error_log') . "<br>";

// 最近のエラー
echo "<h3>最近のPHPエラー</h3>";
$error = error_get_last();
if ($error) {
    echo "<pre>";
    print_r($error);
    echo "</pre>";
} else {
    echo "エラーなし<br>";
}
?>