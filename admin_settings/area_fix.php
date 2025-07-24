<?php 

//Noticeエラーを非表示
error_reporting(E_ALL & ~E_NOTICE);

//表示する内容の切り替え（編集画面、完了画面）
if ( isset($_POST['submit']) ):
	$pagePat = 1 ;//DBのデータを更新して完了画面を表示
elseif ( isset($_POST['delete']) ):
	$pagePat = 2 ;//削除ボタンが押された場合該当するイベントを削除
else:
	$pagePat = 0 ;//開催エリアページ編集画面
endif;

//データベースの初期化
require_once("../db_data/db_init.php");
$db->query("SET NAMES utf8");

?>

<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>開催エリアページ編集フォーム</title>
</head>
<body>

<center>

<?php 

if ( $pagePat === 0 )://送信ボタンが押されてない場合編集画面

	//押されたボタンの識別子をPATH_INFOで取得
	$area = strtok($_SERVER['PATH_INFO'], "/");

	//識別子に該当するイベントデータをDBから取得
	$ps = $db->query("select page , ken , area_ja , area , place , price_h , price_l , age_m , age_w , free_text1 , free_text2 , content
						 from area where area = '{$area}'") ;
	$areaData = $ps->fetch() ;

	//データを個別の変数に格納
	list($page,$ken,$area_ja,$area,$place,$price_h,$price_l,$age_m,$age_w,$free_text1,$free_text2,$content) = $areaData ;

	//通常料金の選択済み初期値を設定
	if ( $price_h === '7500/3900' ):

		$priceCheck_h_01 = " checked='checked'";

	elseif ( $price_h === '6900/2900' ):

		$priceCheck_h_02 = " checked='checked'";

	else:

		$priceCheck_h_00 = " checked='checked'";
		$price_h_m = strtok($price_h, '/');
		$price_h_w = strtok('/');

	endif;

	//早割料金の選択済み初期値を設定
	if ( $price_l === '6500/2900' ):

		$priceCheck_l_01 = " checked='checked'";

	elseif ( $price_l === '5900/1900' ):

		$priceCheck_l_02 = " checked='checked'";

	else:

		$priceCheck_l_00 = " checked='checked'";
		$price_l_m = strtok($price_l, '/');
		$price_l_w = strtok('/');

	endif;

	//男性の年齢の選択済み初期値を設定
	if ( $age_m === '24/35' ):

		$ageCheck_m_01 = " checked='checked'";

	elseif ( $age_m === '27/35' ):

		$ageCheck_m_02 = " checked='checked'";

	else:

		$ageCheck_m_00 = " checked='checked'";
		$age_m_l = strtok($age_m, '/');
		$age_m_h = strtok('/');

	endif;

	//女性の年齢の選択済み初期値を設定
	if ( $age_w === '22/35' ):

		$ageCheck_w_01 = " checked='checked'";

	elseif ( $age_w === '27/35' ):

		$ageCheck_w_02 = " checked='checked'";

	else:

		$ageCheck_w_00 = " checked='checked'";
		$age_w_l = strtok($age_w, '/');
		$age_w_h = strtok('/');

	endif;

print <<<end
	<form action="../area_fix.php" method="post" enctype="multipart/form-data">
		<table border="1">
			<caption>登録情報を変更します。</caption>
			<tbody>
				<tr>
					<th width="">ページ設定
					</th>
					<td width="">
					{$page}
					</td>
				</tr>
				<tr>
					<th width="">都道府県
					</th>
					<td width="">
					{$ken}
					</td>
				</tr>
				<tr>
					<th>開催地の地名<br />
						<p>県名ではなく地名を入力してください。</p>
					</th>
					<td>
					{$area_ja}
					</td>
				</tr>
				<tr>
					<th>開催地名（ローマ字表記）<br />
						<p>URL等に使われます。</p>
					</th>
					<td>
					{$area}
					</td>
				</tr>
				<tr>
					<th>開催場所の詳細<br />
					</th>
					<td><input type="text" name="place" value="{$place}" /><br />
						例 "埼玉県大宮駅周辺"
					</td>
				</tr>
				<tr>
					<th>通常料金<br />
						<p>男性の料金/女性の料金</p>
					</th>
					<td><input type="radio" name="price_h" value="7500/3900"{$priceCheck_h_01} />7500/3900円<br />
						<input type="radio" name="price_h" value="6900/2900"{$priceCheck_h_02} />6900/2900円<br />
						<input type="radio" name="price_h" value="0"{$priceCheck_h_00} />
						<input type="text" name="price_h_inp1" value="{$price_h_m}" size="4">/
						<input type="text" name="price_h_inp2" value="{$price_h_w}" size="4">円<br />
					</td>
				</tr>
				<tr>
					<th>早割料金<br />
						<p>男性の料金/女性の料金</p>
					</th>
					<td><input type="radio" name="price_l" value="6500/2900"{$priceCheck_l_01} />6500/2900円<br />
						<input type="radio" name="price_l" value="5900/1900"{$priceCheck_l_02} />5900/1900円<br />
						<input type="radio" name="price_l" value="0"{$priceCheck_l_00} />
						<input type="text" name="price_l_inp1" value="{$price_l_m}" size="4">/
						<input type="text" name="price_l_inp2" value="{$price_l_w}" size="4">円<br />
					</td>
				</tr>
				<tr>
					<th>男性の年齢<br />
					</th>
					<td><input type="radio" name="age_m" value="24/35"{$ageCheck_m_01} />24～35歳<br />
						<input type="radio" name="age_m" value="27/35"{$ageCheck_m_02} />27～35歳<br />
						<input type="radio" name="age_m" value="0"{$ageCheck_m_00} />
						<input type="text" name="age_m_inp1" value="{$age_m_l}" size="4">～
						<input type="text" name="age_m_inp2" value="{$age_m_h}" size="4">歳<br />
					</td>
				</tr>
				<tr>
					<th>女性の年齢<br />
					</th>
					<td><input type="radio" name="age_w" value="22/35"{$ageCheck_w_01} />22～35歳<br />
						<input type="radio" name="age_w" value="27/35"{$ageCheck_w_02} />27～35歳<br />
						<input type="radio" name="age_w" value="0"{$ageCheck_w_00} />
						<input type="text" name="age_w_inp1" value="{$age_w_l}" size="4">～
						<input type="text" name="age_w_inp2" value="{$age_w_h}" size="4">歳<br />
					</td>
				</tr>
				<tr>
					<th>フリーテキスト １
					</th>
					<td><textarea name="free_text1" cols="50" rows="7" >{$free_text1}</textarea>
					</td>
				</tr>
				<tr>
					<th>フリーテキスト ２
					</th>
					<td><textarea name="free_text2" cols="50" rows="7" >{$free_text2}</textarea>
					</td>
				</tr>
				<tr>
					<th>ページコンテンツ
					</th>
					<td>
						<select name="content">
end;
								$ps = $db->prepare('select num, title from content;');
								$ps->execute();

								while( $row = $ps->fetch() ):

									if ( $row['num'] == $content ):

										$selected = 'selected';

									else:

										$selected = '';

									endif;

									print "<option value='{$row['num']}' {$selected}>{$row['title']}</option>";

								endwhile;
print <<<end2
						</select>
					</td>
				</tr>
				<tr>
				<th>TOP画像
				</th>
				<td><input type="file" name="img_main" />
				</td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="area" value="{$area}">
		<p>
		<input type="submit" name="submit" value="送信">
		<input type="submit" name="delete" value="削除">
		</p>
	</form>
	</center>
end2;


elseif ($pagePat === 1 )://submitボタンが押された場合 DBの内容を更新

	$area = $_POST['area'] ;

	//画像がアップロードされた場合は処理
	( empty($_FILES['img_main']['tmp_name']) ) ? : move_uploaded_file($_FILES['img_main']['tmp_name'], '../img/img_main/' . $area) ;


	//料金を変数にセット
	$inpData = $_POST["price_h_inp1"] . '/' . $_POST["price_h_inp2"];//任意で入力された値段を結合して変数に格納

	if ( $_POST["price_h"] == '0' )://値が０の場合は任意で入力された$inpDataのデータを代入

		$price_h = $inpData ;

	else:								  //０以外の場合はそのまま使用

		$price_h = $_POST["price_h"] ;

	endif;

	$inpData = $_POST["price_l_inp1"] . '/' . $_POST["price_l_inp2"];//任意で入力された値段を結合して変数に格納

	if ( $_POST["price_l"] == '0' )://値が０の場合は任意で入力された$inpDataのデータを代入

		$price_l = $inpData ;

	else:								  //０以外の場合はそのまま使用

		$price_l = $_POST["price_l"] ;

	endif;

	//年齢を変数にセット
	$inpData = $_POST["age_m_inp1"] . '/' . $_POST["age_m_inp2"];//任意で入力された年齢を結合して変数に格納

	if ( $_POST["age_m"] == '0' )://値が０の場合は任意で入力された$inpDataのデータを代入

		$age_m = $inpData ;

	else:								  //０以外の場合はそのまま使用

		$age_m = $_POST["age_m"] ;

	endif;

	$inpData = $_POST["age_w_inp1"] . '/' . $_POST["age_w_inp2"];//任意で入力された年齢を結合して変数に格納

	if ( $_POST["age_w"] == '0' )://値が０の場合は任意で入力された$inpDataのデータを代入

		$age_w = $inpData ;

	else:								  //０以外の場合はそのまま使用

		$age_w = $_POST["age_w"] ;

	endif;

	//DBに送るデータを変数$toDbにセット
	$toDb = array($_POST['place'],$price_h,$price_l,$age_m,$age_w,$_POST["free_text1"],$_POST["free_text2"],$_POST['content']) ;

	//プリペアドステートメントの準備
	$ps = $db->prepare("
		UPDATE `area` SET `place`=?,`price_h`=?,`price_l`=?,`age_m`=?,`age_w`=?,`free_text1`=?,`free_text2`=?,`content`=? WHERE `area` = '{$area}'
		");

	//For文でバインドしてSQL文を完成
	for ($n = 1 ; $n <= 8 ; $n += 1):
		$x = $n - 1 ;
		$ps->bindParam( $n , $toDb[$x]);
	endfor;

	//インサート文を実行
	$res = $ps->execute();

	if ($res == false):

		print "SQLの実行に失敗しました。" ;

	else:

		print '<h1>登録が完了しました。</h1><p>開催地ページ設定画面にもどるには<a href="area_set.php">こちら</a></p>' ;
	
	endif;


elseif ( $pagePat === 2 ):


	$area = $_POST['area'];

	//該当するイベントをDBから削除
	$db->query("delete from area where `area` = '{$area}' ");

	print '<h1>削除が完了しました。</h1><p>開催地ページ設定画面にもどるには<a href="area_set.php">こちら</a></p>' ;


endif;

?>

</body>
</html>