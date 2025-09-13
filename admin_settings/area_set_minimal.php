<?php
// 最小限のarea_set.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "area_set_minimal.php 開始<br>";

// パターン判定
if ( isset($_POST['submit_1']) ):
    $pagePat = 1 ;
elseif ( isset($_POST['submit_2']) ):
    $pagePat = 2 ;
else:
    $pagePat = 0 ;
endif;

echo "pagePat = $pagePat<br>";

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
    echo "パターン0の処理開始<br>";
    
    //データベースの初期化
    require_once("../db_data/db_init.php");
    echo "データベース初期化完了<br>";
    
    ?>
    <center>
    <hr />
    開催地ページを作成します。<br />
    イベントを作成するには<a href="event_set.php">こちら</a>
    <hr />
    
    <form action="area_set.php" method="post" enctype="multipart/form-data">
        <table border="1" width="80%">
            <tr>
                <th>テスト</th>
                <td>テストフォーム</td>
            </tr>
        </table>
    </form>
    
    <?php
    echo "テーブル一覧を取得開始<br>";
    
    // シンプルなクエリでテスト
    try {
        $ps = $db->query("SELECT 1");
        echo "テストクエリ成功<br>";
        
        // contentテーブルの確認
        $ps = $db->query("SELECT num, title FROM content LIMIT 1");
        echo "contentテーブル読み取り成功<br>";
        
        // areaテーブルの確認
        $ps = $db->query("SELECT area FROM area LIMIT 1");
        echo "areaテーブル読み取り成功<br>";
        
    } catch (PDOException $e) {
        echo "SQLエラー: " . $e->getMessage() . "<br>";
    }
    
endif;
?>

</body>
</html>