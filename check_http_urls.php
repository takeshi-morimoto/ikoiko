<?php
// データベース内のHTTP URLをチェックするスクリプト

// DBの初期化
require_once("./db_data/db_init.php");
$db->query("SET NAMES utf8");

echo "=== データベース内のHTTP URLチェック ===\n\n";

// チェック対象のテーブルと列を定義
$tables_to_check = [
    'events' => ['event_name', 'place', 'event_contents', 'detail', 'access', 'url'],
    'area' => ['area', 'name', 'description'],
    'participant' => ['name', 'mail', 'message', 'comment'],
    'mail_template' => ['subject', 'body'],
];

$total_http_urls = 0;
$results = [];

foreach ($tables_to_check as $table => $columns) {
    try {
        // テーブルが存在するか確認
        $check_table = $db->query("SHOW TABLES LIKE '$table'");
        if ($check_table->rowCount() == 0) {
            echo "テーブル '$table' は存在しません\n";
            continue;
        }
        
        echo "テーブル: $table をチェック中...\n";
        
        foreach ($columns as $column) {
            try {
                // カラムが存在するか確認
                $check_column = $db->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
                if ($check_column->rowCount() == 0) {
                    continue;
                }
                
                // HTTP URLを検索
                $query = "SELECT `$column`, COUNT(*) as count FROM `$table` 
                         WHERE `$column` LIKE '%http://%' 
                         GROUP BY `$column`";
                
                $stmt = $db->query($query);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (count($rows) > 0) {
                    foreach ($rows as $row) {
                        $results[] = [
                            'table' => $table,
                            'column' => $column,
                            'value' => $row[$column],
                            'count' => $row['count']
                        ];
                        $total_http_urls += $row['count'];
                        
                        echo "  - カラム '$column' に HTTP URL 発見: " . substr($row[$column], 0, 100) . "... (件数: {$row['count']})\n";
                    }
                }
            } catch (Exception $e) {
                // カラムエラーは無視
            }
        }
    } catch (Exception $e) {
        echo "エラー: テーブル '$table' の処理中 - " . $e->getMessage() . "\n";
    }
}

echo "\n=== 結果サマリー ===\n";
echo "合計 HTTP URL数: $total_http_urls\n\n";

if ($total_http_urls > 0) {
    echo "=== 更新が必要なデータ ===\n";
    foreach ($results as $result) {
        echo "テーブル: {$result['table']}, カラム: {$result['column']}\n";
        echo "値: " . substr($result['value'], 0, 200) . "\n";
        echo "件数: {$result['count']}\n\n";
    }
    
    echo "\n=== 推奨される対処法 ===\n";
    echo "1. 各テーブルのHTTP URLをHTTPSに更新\n";
    echo "2. 外部サイトのURLは、そのサイトがHTTPSに対応しているか確認が必要\n";
    echo "3. UPDATE文の例:\n";
    echo "   UPDATE table_name SET column_name = REPLACE(column_name, 'http://', 'https://') WHERE column_name LIKE '%http://www.koikoi.co.jp%';\n";
} else {
    echo "データベース内にHTTP URLは見つかりませんでした。\n";
}

// 追加チェック: eventsテーブルの構造を確認
echo "\n=== eventsテーブルの構造確認 ===\n";
try {
    $columns = $db->query("SHOW COLUMNS FROM events");
    echo "eventsテーブルのカラム:\n";
    while ($col = $columns->fetch(PDO::FETCH_ASSOC)) {
        echo "  - {$col['Field']} ({$col['Type']})\n";
    }
} catch (Exception $e) {
    echo "eventsテーブルの確認エラー: " . $e->getMessage() . "\n";
}

?>