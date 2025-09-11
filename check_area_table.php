<?php
// データベース接続
require_once 'db_data/db_info/db_info.php';

try {
    // テーブル構造を確認
    echo "=== areaテーブルの構造 ===\n\n";
    
    // SHOW CREATE TABLE
    $stmt = $db->query("SHOW CREATE TABLE area");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "CREATE TABLE文:\n";
    echo $result['Create Table'] . "\n\n";
    
    // インデックス情報
    echo "=== インデックス情報 ===\n\n";
    $stmt = $db->query("SHOW INDEXES FROM area");
    $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($indexes as $index) {
        echo sprintf("キー名: %s, カラム: %s, ユニーク: %s, NULL許可: %s\n", 
            $index['Key_name'], 
            $index['Column_name'], 
            $index['Non_unique'] == 0 ? 'YES' : 'NO',
            $index['Null']
        );
    }
    
    // 'a_aichi_nagoya_hiru' の既存レコードを確認
    echo "\n=== 'a_aichi_nagoya_hiru' の既存レコード ===\n\n";
    $stmt = $db->prepare("SELECT * FROM area WHERE area = ?");
    $stmt->execute(['a_aichi_nagoya_hiru']);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($records) > 0) {
        echo "レコード数: " . count($records) . "\n";
        foreach ($records as $i => $record) {
            echo "\nレコード " . ($i + 1) . ":\n";
            foreach ($record as $key => $value) {
                echo "  $key: " . ($value !== null ? $value : 'NULL') . "\n";
            }
        }
    } else {
        echo "該当レコードなし\n";
    }
    
    // area列の重複チェック
    echo "\n=== area列の重複チェック ===\n\n";
    $stmt = $db->query("SELECT area, COUNT(*) as count FROM area GROUP BY area HAVING count > 1");
    $duplicates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($duplicates) > 0) {
        echo "重複があるarea値:\n";
        foreach ($duplicates as $dup) {
            echo "  " . $dup['area'] . " (件数: " . $dup['count'] . ")\n";
        }
    } else {
        echo "重複なし\n";
    }
    
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage() . "\n";
}
?>