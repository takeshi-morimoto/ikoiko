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
				<th width="" height="50px">都道府県</th>
				<td width="">
					<select name="data_02">
			            <option>選択してください</option>
			            <option value="東京">東京</option>
			            <option value="大阪">大阪</option>
		            </select>
				</td>
			</tr>
		</tbody>
	</table>
	<center><p>
	<input type="submit" name="submit_1" value="送信">
	</p></center>
</form>

<p>area_set_step2.php - フォーム表示まで完了</p>

</center>

<?php endif; ?>

</body>
</html>