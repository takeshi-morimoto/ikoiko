<?php
echo "<h2>ファイルエンコーディングチェック</h2>";

$files = [
    'area_set.php',
    'event_set.php'
];

foreach ($files as $file) {
    echo "<h3>$file</h3>";
    
    if (!file_exists($file)) {
        echo "ファイルが存在しません<br>";
        continue;
    }
    
    // ファイルの最初の数バイトを読み取り
    $handle = fopen($file, "rb");
    $bom = fread($handle, 3);
    fclose($handle);
    
    // BOMチェック
    if (substr($bom, 0, 3) == "\xEF\xBB\xBF") {
        echo "<span style='color: red;'>UTF-8 BOMが検出されました！</span><br>";
    } else {
        echo "BOMなし<br>";
    }
    
    // ファイルサイズ
    echo "ファイルサイズ: " . filesize($file) . " bytes<br>";
    
    // 最初の行を確認
    $firstLine = fgets(fopen($file, 'r'));
    echo "最初の行: " . htmlspecialchars($firstLine) . "<br>";
    
    // 改行コードの確認
    $content = file_get_contents($file);
    $crlf = substr_count($content, "\r\n");
    $lf = substr_count($content, "\n") - $crlf;
    $cr = substr_count($content, "\r") - $crlf;
    
    echo "改行コード - CRLF: $crlf, LF: $lf, CR: $cr<br>";
    
    // 非ASCII文字の数
    $nonAsciiCount = 0;
    for ($i = 0; $i < strlen($content); $i++) {
        if (ord($content[$i]) > 127) {
            $nonAsciiCount++;
        }
    }
    echo "非ASCII文字数: $nonAsciiCount<br>";
}
?>