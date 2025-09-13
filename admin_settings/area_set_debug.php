<?php
// エラー表示を有効化
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>area_set.php デバッグ</h2>";

// エラーハンドラを設定
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo "<div style='color: red; border: 1px solid red; padding: 10px;'>";
    echo "エラー: $errstr<br>";
    echo "ファイル: $errfile<br>";
    echo "行: $errline<br>";
    echo "</div>";
    return true;
});

// 実際のarea_set.phpのコードを実行
try {
    // パターン判定
    if ( isset($_POST['submit_1']) ):
        $pagePat = 1 ;
    elseif ( isset($_POST['submit_2']) ):
        $pagePat = 2 ;
    else:
        $pagePat = 0 ;
    endif;
    
    echo "pagePat = $pagePat<br>";
    
    // HTMLヘッダー出力
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Document</title>
    </head>
    <body>
    
    <p style="font-size:40;"><a href="admin.php">コントロールパネルトップにもどる</a></p>
    
    <?php
    
    if ( $pagePat === 0 ):
        echo "データベース初期化開始<br>";
        
        //データベースの初期化
        require_once("../db_data/db_init.php");
        
        echo "データベース初期化完了<br>";
        
        // テスト用のクエリ実行
        try {
            $test = $db->query("SELECT 1");
            echo "データベース接続テスト: OK<br>";
        } catch (Exception $e) {
            echo "データベースクエリエラー: " . $e->getMessage() . "<br>";
        }
        
    endif;
    
} catch (Exception $e) {
    echo "<div style='color: red; font-weight: bold;'>";
    echo "致命的エラー: " . $e->getMessage() . "<br>";
    echo "トレース:<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}

echo "<h3>PHPエラーログ確認</h3>";
$error = error_get_last();
if ($error) {
    echo "<pre>";
    print_r($error);
    echo "</pre>";
}
?>