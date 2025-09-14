<?php
// エラー表示を有効化
error_reporting(E_ALL);
ini_set('display_errors', 1);

// データベース接続
require_once("../db_data/db_init.php");
$db->query("SET NAMES utf8");

echo "<h1>データベース診断</h1>";

try {
    // eventsテーブルの構造を確認
    echo "<h2>eventsテーブルの構造</h2>";
    $stmt = $db->query("DESCRIBE events");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    $column_names = [];
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>" . $col['Field'] . "</td>";
        echo "<td>" . $col['Type'] . "</td>";
        echo "<td>" . $col['Null'] . "</td>";
        echo "<td>" . $col['Key'] . "</td>";
        echo "<td>" . $col['Default'] . "</td>";
        echo "<td>" . $col['Extra'] . "</td>";
        echo "</tr>";
        $column_names[] = $col['Field'];
    }
    echo "</table>";
    
    // 必要なカラムのチェック
    echo "<h2>必要なカラムの確認</h2>";
    $required = ['state_m', 'state_w', 'meetingpoint'];
    $missing = [];
    
    foreach ($required as $col) {
        if (in_array($col, $column_names)) {
            echo "✅ $col: 存在します<br>";
        } else {
            echo "❌ $col: <b style='color:red'>存在しません</b><br>";
            $missing[] = $col;
        }
    }
    
    // 不足しているカラムを追加するSQL
    if (!empty($missing)) {
        echo "<h2>実行が必要なSQL</h2>";
        echo "<div style='background:#f0f0f0; padding:10px; border:1px solid #ccc;'>";
        echo "<pre>";
        foreach ($missing as $col) {
            if ($col === 'state_m' || $col === 'state_w') {
                echo "ALTER TABLE events ADD COLUMN $col INT DEFAULT 0;\n";
            } else if ($col === 'meetingpoint') {
                echo "ALTER TABLE events ADD COLUMN $col INT DEFAULT 1;\n";
            }
        }
        echo "</pre>";
        echo "</div>";
        echo "<p style='color:red;'>上記のSQLをデータベースで実行してください。</p>";
    } else {
        echo "<p style='color:green;'>すべての必要なカラムが存在します。</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color:red;'>エラー: " . $e->getMessage() . "</p>";
}
?>