<?php 
// エラー表示を有効化
error_reporting(E_ALL);
ini_set('display_errors', 1);

//表示する内容の切り替え（フォーム未入力、入力済み、完了画面）
if ( isset($_POST['submit_1']) ):
	$pagePat = 1 ;//フォームにデータが入力された場合・入力確認画面を表示
elseif ( isset($_POST['submit_2']) ):
	$pagePat = 2 ;//確認画面から入力完了ボタンが押された場合・内容をDBに格納
else:
	$pagePat = 0 ;//何も入力されてない場合・入力フォームと一覧表示を出力
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

<p>area_set_step1.php - データベース初期化まで完了</p>

</center>

<?php endif; ?>

</body>
</html>