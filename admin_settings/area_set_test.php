<?php
// area_set.phpのテスト版 - 段階的に機能を追加
ini_set('display_errors', 1);
error_reporting(E_ALL);

//表示する内容の切り替え（フォーム未入力、入力済み、完了画面）
if ( isset($_POST['submit_1']) ):
    $pagePat = 1 ;
elseif ( isset($_POST['submit_2']) ):
    $pagePat = 2 ;
else:
    $pagePat = 0 ;
endif;

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

    //データベースの初期化
    require_once("../db_data/db_init.php");

 ?>

<center>

<hr />
開催地ページを作成します。<br />
イベントを作成するには<a href="event_set.php">こちら</a>
<hr />

<form action="area_set.php" method="post" enctype="multipart/form-data">
    <table border="1" width="80%">
        <tbody>
            <tr>
                <th width="" height="50px">ページ設定</th>
                <td width="">
                    <select name="data_01">
                        <option>選択してください</option>
                        <option value="ani">アニメ</option>
                        <option value="machi">街コン</option>
                        <option value="nazo">謎解き</option>
                        <option value="off">オフ会</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>コンテンツ選択</th>
                <td>
                    <select name="content">
                        <?php 
                            try {
                                $ps = $db->prepare('select num, title from content;');
                                $ps->execute();
                                
                                while( $row = $ps->fetch() ):
                                    print "<option value='{$row['num']}'>{$row['title']}</option>";
                                endwhile;
                                
                            } catch (PDOException $e) {
                                echo "SQLエラー(content): " . $e->getMessage() . "<br>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <center><p>
    <input type="submit" name="submit_1" value="送信">
    </p></center>
</form>

<hr />
作成した開催地ページを一覧表示しています。<br />
ページを個別に編集することが可能です。<br />
<hr />

<?php 

echo "開催地一覧の表示開始<br>";

//登録済み開催地ページの一覧表示部分[ここから]
try {
    // まずシンプルなクエリでテスト
    $ps = $db->query("SELECT area FROM area LIMIT 1");
    echo "areaテーブル読み取り成功<br>";
    
    // 次により複雑なクエリをテスト
    $ps = $db->query("SELECT area.ken, area.area_ja FROM area LIMIT 1");
    echo "area複数カラム読み取り成功<br>";
    
    // JOINをテスト
    $ps = $db->query("SELECT area.area FROM events RIGHT JOIN area USING(area) LIMIT 1");
    echo "JOIN成功<br>";
    
    // 完全なクエリをテスト - area を area.area に修正
    $ps = $db->query("SELECT area.ken,area.area_ja,area.place,area.price_h,area.price_l,area.age_m,area.age_w,count(events.find) as count, area.area, area.page as page
                    FROM events 
                    RIGHT JOIN area 
                    USING(area)
                    GROUP BY area.area
                    ORDER BY count DESC;");
    echo "完全なクエリ成功<br>";
    
    //WHILE文でテーブルを出力
    print '<center><form action="form_fix" method="POST" accept-charset="utf-8"><table border="1" width="80%">' . 
            '<tr><th height="50px">開催エリア</th><th>開催場所</th><th>価格(通常＝早割)</th><th>対象年齢</th><th>登録数</th><th>編集</th></tr>';
    
    while ($row = $ps->fetch()) {
        print 
            '<tr><td height="50px"><font size="5">' .
            $row['area_ja'] . '(' . $row['ken'] . ')' . $row['page'] .
            '</font></td><td width="">' . 
            $row['place'] . 
            '</td><td width="">' . 
            $row['price_h'] . '＝' . $row['price_l'] .
            '</td><td width="">' . 
            '男性：' . $row['age_m'] . '女性：' . $row['age_w'] .
            '</td><td width="3%"><center>' . 
            $row['count'] .
            '</center></td><td width=""><center>' . 
            "<input type='button' name='fix_button' value='編集' onClick='location.href = \"area_fix.php/{$row['area']}\"' />" .
            '</center></td></tr>' ;
    }
    print '</table></form></center>';
    
} catch (PDOException $e) {
    echo "SQLエラー: " . $e->getMessage() . "<br>";
    echo "エラーコード: " . $e->getCode() . "<br>";
    echo "SQLSTATE: " . $e->errorInfo[0] . "<br>";
}

//登録済み開催地ページの一覧表示部分[ここまで]

?>

<?php endif; ?>
    
</body>
</html>