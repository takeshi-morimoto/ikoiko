<?php
// データベース接続
require_once("../db_data/db_init.php");
$db->query("SET NAMES utf8");

echo "<h2>eventsテーブルのカラム確認</h2>";

try {
    // eventsテーブルの構造を取得
    $stmt = $db->query("DESCRIBE events");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1'>";
    echo "<tr><th>カラム名</th><th>型</th><th>NULL</th><th>キー</th><th>デフォルト</th></tr>";
    
    $required_columns = ['state_m', 'state_w', 'meetingpoint'];
    $existing_columns = [];
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "</tr>";
        
        $existing_columns[] = $column['Field'];
    }
    echo "</table>";
    
    echo "<h3>必要なカラムの存在確認</h3>";
    foreach ($required_columns as $col) {
        if (in_array($col, $existing_columns)) {
            echo "✓ $col: 存在します<br>";
        } else {
            echo "✗ $col: <b style='color:red'>存在しません</b><br>";
        }
    }
    
    // 不足しているカラムを追加するSQL
    echo "<h3>不足しているカラムを追加するSQL</h3>";
    echo "<pre>";
    foreach ($required_columns as $col) {
        if (!in_array($col, $existing_columns)) {
            if ($col === 'state_m' || $col === 'state_w') {
                echo "ALTER TABLE events ADD COLUMN $col INT DEFAULT 0;\n";
            } else if ($col === 'meetingpoint') {
                echo "ALTER TABLE events ADD COLUMN $col INT DEFAULT 1;\n";
            }
        }
    }
    echo "</pre>";
    
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}
?>