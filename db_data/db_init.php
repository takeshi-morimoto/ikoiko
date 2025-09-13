<?php 

require_once(__DIR__ . "/db_info/db_info.php");

try {
    $dsn = "mysql:host=$SERV;dbname=$DBNM;charset=utf8";
    $db = new PDO($dsn, $USER, $PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("SET NAMES utf8");
} catch (PDOException $e) {
    die("データベース接続エラー: " . $e->getMessage());
}

?>