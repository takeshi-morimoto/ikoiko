<?php 

// エラー表示を有効化（デバッグ用）
ini_set('display_errors', 1);
error_reporting(E_ALL);

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
				<th width="" height="50px">ページ設定
				</th>
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
				<th width="" height="50px">都道府県
				</th>
				<td width="">
					<select name="data_02">
			            <option>選択してください</option>
			            <option>----関東----</option>
			            <option value="東京">東京</option>
			            <option value="茨城">茨城</option>
			            <option value="栃木">栃木</option>
			            <option value="群馬">群馬</option>
			            <option value="埼玉">埼玉</option>
			            <option value="千葉">千葉</option>
			            <option value="神奈川">神奈川</option>
			            <option>--東北・北海道--</option>
			            <option value="北海道">北海道</option>
			            <option value="青森">青森</option>
			            <option value="岩手">岩手</option>
			            <option value="宮城">宮城</option>
			            <option value="秋田">秋田</option>
			            <option value="山形">山形</option>
			            <option value="福島">福島</option>
			            <option>----東海・北陸----</option>
			            <option value="新潟">新潟</option>
			            <option value="富山">富山</option>
			            <option value="石川">石川</option>
			            <option value="福井">福井</option>
			            <option value="山梨">山梨</option>
			            <option value="長野">長野</option>
			            <option value="岐阜">岐阜</option>
			            <option value="静岡">静岡</option>
			            <option value="愛知">愛知</option>
			            <option>----近畿----</option>
			            <option value="三重">三重</option>
			            <option value="滋賀">滋賀</option>
			            <option value="京都">京都</option>
			            <option value="大阪">大阪</option>
			            <option value="兵庫">兵庫</option>
			            <option value="奈良">奈良</option>
			            <option value="和歌山">和歌山</option>
			            <option>----中国----</option>
			            <option value="鳥取">鳥取</option>
			            <option value="島根">島根</option>
			            <option value="岡山">岡山</option>
			            <option value="広島">広島</option>
			            <option value="山口">山口</option>
			            <option>----四国----</option>
			            <option value="徳島">徳島</option>
			            <option value="香川">香川</option>
			            <option value="愛媛">愛媛</option>
			            <option value="高知">高知</option>
			            <option>----九州----</option>
			            <option value="福岡">福岡</option>
			            <option value="佐賀">佐賀</option>
			            <option value="長崎">長崎</option>
			            <option value="熊本">熊本</option>
			            <option value="大分">大分</option>
			            <option value="宮崎">宮崎</option>
			            <option value="鹿児島">鹿児島</option>
			            <option value="沖縄">沖縄</option>
		            </select>
				</td>
			</tr>
			<tr>
				<th>開催地の地名<br />
					<p>県名ではなく地名を入力してください。</p>
				</th>
				<td><input type="text" name="data_03" /><br />
					例 "大宮"
				</td>
			</tr>
			<tr>
				<th>開催地名（ローマ字表記）<br />
					<p>URL等に使われます。</p>
				</th>
				<td><input type="text" name="data_04" /><br />
					例 "omiya"
				</td>
			</tr>
			<tr>
				<th>開催場所の詳細<br />
				</th>
				<td><input type="text" name="data_05" /><br />
					例 "埼玉県大宮駅周辺"
				</td>
			</tr>
			<tr>
				<th>通常料金<br />
					<p>男性の料金/女性の料金</p>
				</th>
				<td><input type="radio" name="data_06" value="9800/1000" checked="checked" />9800/1000円<br />
					<input type="radio" name="data_06" value="8500/1000" />8500/1000円<br />
					<input type="radio" name="data_06" value="0" /><input type="text" name="data_06inp1" value="" size="4">/<input type="text" name="data_06inp2" value="" size="4">円<br />
					</td>
			</tr>
			<tr>
				<th>早割料金<br />
					<p>男性の料金/女性の料金</p>
				</th>
				<td><input type="radio" name="data_07" value="6500/2900" checked="checked" />6500/2900円<br />
					<input type="radio" name="data_07" value="5900/1900" />5900/1900円<br />
					<input type="radio" name="data_07" value="0" /><input type="text" name="data_07inp1" value="" size="4">/<input type="text" name="data_07inp2" value="" size="4">円<br />
					</td>
			</tr>
			<tr>
				<th>男性の年齢<br />
				</th>
				<td><input type="radio" name="data_08" value="20/40" checked="checked" />20～40歳<br />
					<input type="radio" name="data_08" value="20/29" />20～29歳<br />
					<input type="radio" name="data_08" value="0" /><input type="text" name="data_08inp1" value="" size="4">～<input type="text" name="data_08inp2" value="" size="4">歳<br />
					</td>
			</tr>
			<tr>
				<th>女性の年齢<br />
				</th>
				<td><input type="radio" name="data_09" value="20/40" checked="checked" />20～40歳<br />
					<input type="radio" name="data_09" value="20/29" />20～29歳<br />
					<input type="radio" name="data_09" value="0" /><input type="text" name="data_09inp1" value="" size="4">～<input type="text" name="data_09inp2" value="" size="4">歳<br />
					</td>
			</tr>
			<tr>
				<th>フリーテキスト部分１<br />
				</th>
				<td><textarea name="data_10" cols="50" rows="7" ></textarea>
					</td>
			</tr>
			<tr>
				<th>フリーテキスト部分２<br />
				</th>
				<td><textarea name="data_11" cols="50" rows="7" ></textarea>
				</td>
			</tr>
			<tr>
				<th>ページコンテンツ<br />
				</th>
				<td>
					<select name="content">

						<?php 
							
							$ps = $db->prepare('select num, title from content;');
							$ps->execute();

							while( $row = $ps->fetch() ):

								print "<option value='{$row['num']}'>{$row['title']}</option>";

							endwhile;
						?>

					</select>
				</td>
			</tr>
			<tr>
				<th>TOP画像<br />
				</th>
				<td><input type="file" name="img_main" />
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

$ps = $db->query("select area.ken,area.area_ja,area.place,area.price_h,area.price_l,area.age_m,area.age_w,count(events.find) as count , area ,area.page as page
					from events 
					right join area 
					using(area)
					group by area.area
					order by count desc;") ;

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

<hr />
	作成した開催地ページを一覧表示しています。<br />
	ページを個別に編集することが可能です。<br />
<hr />

<?php

elseif ( $pagePat === 1 )://フォームにデータが入力された場合、入力内容を表示して確認画面を表示

	$area = $_POST['data_04'];

	//アップロードされたファイルを処理
	move_uploaded_file($_FILES['img_main']['tmp_name'], '../img/img_main/' . $area);
	move_uploaded_file($_FILES['img_entry']['tmp_name'], '../img/img_entry/' . $area);


	//POSTで送信された連想配列を配列に格納
	$td = array_values($_POST);

	//変数tdの配列を最適化
	for ( $n = 6 ; $n <= 9 ; $n += 1 ):

		${'data_0'.$n} = $_POST["data_0{$n}inp1"] . '/' . $_POST["data_0{$n}inp2"];

		if ( $_POST["data_0{$n}"] == '0' ):

			array_splice($td, $n - 1 , 3 , ${'data_0'.$n});

		else:

			array_splice($td, $n , 2 );

		endif;

	endfor;

	//テーブルで出力されるTH内容を配列にセット
	$th = array('ページ設定','都道府県','開催地の地名','開催地名（ローマ字表記）','開催場所の詳細','通常料金','早割料金','男性の年齢','女性の年齢','フリーテキスト部分１','フリーテキスト部分２');

	//テーブルで入力内容を出力とHiddenでフォームデータを出力
	print "<form action='area_set.php' method='post' accept-charset='utf-8'>" . 
			"<table border='1' width='80%'>" ;

	for ( $n = 0 ; $n <= 10 ; $n += 1 ):

		print "<input type='hidden' name='data_" . $n . "' value='" . $td[$n] . "'>" .
			"<tr><th width='30%' height='40px'>" . $th[$n] . "</th><td width='30%'>" . $td{$n} . "</td></tr>" ;

	endfor;

	print "<input type='hidden' name='content' value='{$_POST['content']}'>";

	//アップロードされた画像の表示
	print 
			"
			<tr><th width='30%' height='40px'>TOP画像</th><td width='30%'><img width='30%' src='../img/img_main/{$area}' /></td></tr>
			<tr><th width='30%' height='40px'>見出し画像</th><td width='30%'><img width='30%' src='../img/img_entry/{$area}' /></td></tr>
			";

	print "</table><center><p><input type='submit' name='submit_2' value='確認完了'></p></center></form>" ;
	//テーブルとフォームの出力終わり

?>


<?php 
elseif ( $pagePat === 2 )://確認完了ボタンが押されたので入力内容をDBに格納

	// エラー表示を有効化（デバッグ用）
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	//データベースの初期化
	require_once("../db_data/db_init.php");


	$sendData = array_values($_POST);

	//プリペアドステートメントの準備
	$ps = $db->prepare("
		insert into area (`page`,`ken`,`area_ja`,`area`,`place`,`price_h`,`price_l`,`age_m`,`age_w`,`free_text1`,`free_text2`,`content`) 
		values (?,?,?,?,?,?,?,?,?,?,?,?);
		");

	//For文でバインドしてSQL文を完成
	for ($n =0 ; $n <= 11 ; $n += 1):
		$ps->bindParam( $n + 1 , $sendData[$n]);
	endfor;

	//インサート文を実行
	$res = $ps->execute();

?>

	<h1>登録が完了しました。</h1>
	<p>開催地ページ設定画面にもどるには<a href="area_set.php">こちら</a></p>


<?php 
endif;
?>

	
</body>
</html>
