<?php
// 最小限から始める
echo "area_set_v2.php 開始<br>";

// エラー表示
ini_set('display_errors', 1);
error_reporting(E_ALL);

// パターン判定
if ( isset($_POST['submit_1']) ):
    $pagePat = 1;
elseif ( isset($_POST['submit_2']) ):
    $pagePat = 2;
else:
    $pagePat = 0;
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
    // データベースの初期化
    echo "データベース初期化開始<br>";
    require_once("../db_data/db_init.php");
    echo "データベース初期化完了<br>";
?>

<center>
<hr />
開催地ページを作成します。<br />
イベントを作成するには<a href="event_set.php">こちら</a>
<hr />

<p>動作確認: このページは表示されています。</p>

</center>

<?php endif; ?>

</body>
</html>