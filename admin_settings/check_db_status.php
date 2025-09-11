<?php
require_once "../db_data/db_init.php";
$db->query("SET NAMES utf8");

echo "<h2>データベース状態チェック</h2>";

// MySQLバージョン確認
$version = $db->query("SELECT VERSION()")->fetchColumn();
echo "<p>MySQL Version: " . $version . "</p>";

// sql_mode確認
$sql_mode = $db->query("SELECT @@sql_mode")->fetchColumn();
echo "<p>SQL Mode: " . $sql_mode . "</p>";

// areaテーブルの制約確認
echo "<h3>areaテーブルの制約</h3>";
$constraints = $db->query("SHOW CREATE TABLE area")->fetch(PDO::FETCH_ASSOC);
echo "<pre>" . htmlspecialchars($constraints['Create Table']) . "</pre>";

// 重複するarea値の確認
echo "<h3>重複するarea値の確認</h3>";
$duplicates = $db->query("
    SELECT area, COUNT(*) as count 
    FROM area 
    GROUP BY area 
    HAVING count > 1
")->fetchAll(PDO::FETCH_ASSOC);

if (count($duplicates) > 0) {
    echo "<p style='color:red;'>重複するarea値が見つかりました：</p>";
    foreach ($duplicates as $dup) {
        echo "<p>area: " . $dup['area'] . " (count: " . $dup['count'] . ")</p>";
    }
} else {
    echo "<p style='color:green;'>重複するarea値はありません。</p>";
}

// a_aichi_nagoya_hiruの存在確認
echo "<h3>a_aichi_nagoya_hiruの確認</h3>";
$check = $db->query("SELECT * FROM area WHERE area = 'a_aichi_nagoya_hiru'")->fetch(PDO::FETCH_ASSOC);
if ($check) {
    echo "<p>a_aichi_nagoya_hiruは既に存在します。</p>";
    echo "<pre>" . print_r($check, true) . "</pre>";
} else {
    echo "<p>a_aichi_nagoya_hiruは存在しません。</p>";
}
?>