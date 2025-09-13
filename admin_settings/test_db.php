<?php
// エラー表示を有効化
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>データベース接続テスト</h2>";

// ファイルパスの確認
echo "<h3>1. ファイルパスの確認</h3>";
$db_init_path = "../db_data/db_init.php";
$db_info_path = "../db_data/db_info/db_info.php";

echo "db_init.php の存在: " . (file_exists($db_init_path) ? "OK" : "NG") . "<br>";
echo "db_info.php の存在: " . (file_exists($db_info_path) ? "OK" : "NG") . "<br>";

// 絶対パスで表示
echo "db_init.php の絶対パス: " . realpath($db_init_path) . "<br>";
echo "db_info.php の絶対パス: " . realpath($db_info_path) . "<br>";

// データベース接続テスト
echo "<h3>2. データベース接続テスト</h3>";
try {
    require_once($db_info_path);
    echo "データベース設定読み込み: OK<br>";
    echo "サーバー: $SERV<br>";
    echo "ユーザー: $USER<br>";
    echo "データベース名: $DBNM<br>";
    
    $dsn = "mysql:host=$SERV;dbname=$DBNM";
    $db = new PDO($dsn, $USER, $PASS);
    echo "PDO接続: OK<br>";
    
    $db->exec("SET NAMES utf8");
    echo "文字コード設定: OK<br>";
    
    // テーブルの存在確認
    echo "<h3>3. テーブルの存在確認</h3>";
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "テーブル一覧:<br>";
    foreach ($tables as $table) {
        echo "- $table<br>";
    }
    
} catch (PDOException $e) {
    echo "<span style='color: red;'>PDOエラー: " . $e->getMessage() . "</span><br>";
    echo "エラーコード: " . $e->getCode() . "<br>";
} catch (Exception $e) {
    echo "<span style='color: red;'>エラー: " . $e->getMessage() . "</span><br>";
}

// PHPの設定確認
echo "<h3>4. PHP設定の確認</h3>";
echo "PHP バージョン: " . phpversion() . "<br>";
echo "PDO 拡張: " . (extension_loaded('pdo') ? "有効" : "無効") . "<br>";
echo "PDO MySQL: " . (extension_loaded('pdo_mysql') ? "有効" : "無効") . "<br>";
?>