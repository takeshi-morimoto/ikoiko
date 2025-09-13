<?php
// 最もシンプルなテスト
echo "PHPが動作しています<br>";

// ステップ1: ファイルの存在確認
echo "ステップ1: ファイルの存在確認<br>";
$db_init = "../db_data/db_init.php";
echo "db_init.php: " . (file_exists($db_init) ? "存在" : "存在しない") . "<br>";

// ステップ2: require_onceのテスト
echo "ステップ2: require_onceのテスト<br>";
try {
    require_once($db_init);
    echo "require_once成功<br>";
} catch (Exception $e) {
    echo "require_onceエラー: " . $e->getMessage() . "<br>";
}

// ステップ3: データベース接続確認
echo "ステップ3: データベース接続確認<br>";
if (isset($db)) {
    echo "データベース接続オブジェクト: 存在<br>";
    try {
        $result = $db->query("SELECT 1");
        echo "テストクエリ: 成功<br>";
    } catch (Exception $e) {
        echo "テストクエリエラー: " . $e->getMessage() . "<br>";
    }
} else {
    echo "データベース接続オブジェクト: 存在しない<br>";
}

echo "テスト完了<br>";
?>