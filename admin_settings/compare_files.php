<?php
echo "<h2>ファイル比較</h2>";

// 動作するファイルと動作しないファイルを比較
$working_file = 'area_set_v2.php';
$broken_file = 'area_set.php';

echo "<h3>$working_file (動作OK) vs $broken_file (ホワイトスクリーン)</h3>";

// ファイルサイズ
echo "ファイルサイズ:<br>";
echo "- $working_file: " . filesize($working_file) . " bytes<br>";
echo "- $broken_file: " . filesize($broken_file) . " bytes<br><br>";

// 最初の500文字を比較
$working_content = file_get_contents($working_file);
$broken_content = file_get_contents($broken_file);

echo "最初の500文字:<br>";
echo "<h4>$working_file:</h4>";
echo "<pre>" . htmlspecialchars(substr($working_content, 0, 500)) . "</pre>";

echo "<h4>$broken_file:</h4>";
echo "<pre>" . htmlspecialchars(substr($broken_content, 0, 500)) . "</pre>";

// 行数
$working_lines = count(file($working_file));
$broken_lines = count(file($broken_file));
echo "行数:<br>";
echo "- $working_file: $working_lines 行<br>";
echo "- $broken_file: $broken_lines 行<br><br>";

// MD5ハッシュ
echo "MD5ハッシュ:<br>";
echo "- $working_file: " . md5_file($working_file) . "<br>";
echo "- $broken_file: " . md5_file($broken_file) . "<br>";
?>