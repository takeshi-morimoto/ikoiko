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
				<th>コンテンツ選択</th>
				<td>
					<select name="content">
						<?php 
							try {
								$ps = $db->prepare('select num, title from content;');
								$ps->execute();
							} catch (PDOException $e) {
								echo "SQLエラー(content): " . $e->getMessage() . "<br>";
							}

							while( $row = $ps->fetch() ):
								print "<option value='{$row['num']}'>{$row['title']}</option>";
							endwhile;
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

//登録済み開催地ページの一覧表示部分[ここから]

try {
	$ps = $db->query("select area.ken,area.area_ja,area.place,area.price_h,area.price_l,area.age_m,area.age_w,count(events.find) as count , area.area ,area.page as page
					from events 
					right join area 
					using(area)
					group by area.area
					order by count desc;") ;
} catch (PDOException $e) {
	echo "SQLエラー: " . $e->getMessage() . "<br>";
	die();
}

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

//登録済み開催地ページの一覧表示部分[ここまで]

?>

<p>area_set_step3.php - SQLクエリまで完了</p>

<?php endif; ?>

</body>
</html>