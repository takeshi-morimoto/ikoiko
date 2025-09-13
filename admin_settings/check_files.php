<?php
echo "<h2>ファイルの先頭部分を確認</h2>";

$files = ['area_set.php', 'event_set.php'];

foreach ($files as $file) {
    echo "<h3>$file</h3>";
    
    $handle = fopen($file, 'rb');
    if (!$handle) {
        echo "ファイルを開けません<br>";
        continue;
    }
    
    // 最初の100バイトを読む
    $first_bytes = fread($handle, 100);
    fclose($handle);
    
    // 16進数で表示
    echo "最初の20バイト（16進数）: ";
    for ($i = 0; $i < min(20, strlen($first_bytes)); $i++) {
        echo sprintf("%02X ", ord($first_bytes[$i]));
    }
    echo "<br>";
    
    // ASCII表示
    echo "最初の100文字: <pre>" . htmlspecialchars($first_bytes) . "</pre>";
    
    // 空白文字をチェック
    if (preg_match('/^\s+<\?php/', $first_bytes)) {
        echo "<span style='color: red;'>警告: PHPタグの前に空白があります！</span><br>";
    }
    
    // ファイル全体を読んで基本的なチェック
    $content = file_get_contents($file);
    
    // PHPの開始・終了タグ
    $php_open = substr_count($content, '<?php');
    $php_close = substr_count($content, '?>');
    echo "PHPタグ - 開始: $php_open, 終了: $php_close<br>";
    
    // 最後の文字を確認
    $last_char = substr($content, -1);
    echo "最後の文字のASCIIコード: " . ord($last_char) . "<br>";
    
    if ($last_char !== "\n" && $last_char !== ">") {
        echo "<span style='color: orange;'>注意: ファイルが改行で終わっていません</span><br>";
    }
}

// PHPバージョンと拡張機能の確認
echo "<h3>PHP環境</h3>";
echo "PHPバージョン: " . PHP_VERSION . "<br>";
echo "loaded extensions: <br>";
$extensions = get_loaded_extensions();
echo implode(", ", $extensions);
?>