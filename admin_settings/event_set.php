<?php 


//Noticeエラーを非表示にする
error_reporting(E_ALL & ~E_NOTICE);


//表示する内容の切り替え（フォーム未入力、入力済み、完了画面）
if ( isset($_POST['submit_1']) ):
	$pagePat = 1 ;//フォームにデータが入力された場合・入力確認画面を表示
elseif ( isset($_POST['submit_2']) ):
	$pagePat = 2 ;//確認画面から入力完了ボタンが押された場合・内容をDBに格納
else:
	$pagePat = 0 ;//何も入力されてない場合・入力フォームと一覧表示を出力
endif;

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>エベント登録フォーム</title>
	<link rel="stylesheet" href="https://koikoi.co.jp/ikoiko/css/admin.css">
	<link rel="stylesheet" href="https://koikoi.co.jp/ikoiko/css/chosen.min.css">
	<script src="https://koikoi.co.jp/ikoiko/js/jquery-3.7.1.min.js"></script>
	<script src="https://koikoi.co.jp/ikoiko/js/jquery-migrate-3.4.1.min.js"></script>
	<script src="https://koikoi.co.jp/ikoiko/js/chosen.jquery.min.js"></script>
	<style>.none{display: none;}</style>
</head>
<body>

<script>
	$(function(){ // セレクトボックスのフィルタ
		$("select.chosen").chosen({
			search_contains: true
		});
	});
</script>

<p style="font-size:40;"><a href="admin.php">コントロールパネルトップにもどる</a></p>

<?php

if ( $pagePat === 0 )://パターン０：何も入力されてない場合・入力フォームと一覧表示を出力

	//データベースの初期化
	require_once("../db_data/db_init.php");

	?>

	<center>
	<hr />
	作成済みの開催地ページにイベントを作成します。<br />
	開催地を作成するには<a href="area_set.php">こちら</a>
	<hr />
	<form action="event_set.php" method="post" enctype="multipart/form-data">
		<style>
			table.addinl_1 td {
				padding: 10px;
			}
		</style>
		<table class="addinl_1" border="1" width="80%">
			<tbody>
				<tr>
					<th width="">都道府県
					</th>
					<td width="">
						<select class="chosen" name="area">

						<?php 

						//作成済みの開催エリアを取得してセレクトボックスで出力
						$ps = $db->query("select `page`,`area`,`ken`,`area_ja`, `price_h` from area order by `ken`") ;
						
						while ($row = $ps->fetch()):

							print "<option value='" . $row['area'] . "'>" . $row['ken'] . "・" . $row['area_ja'] . '[' . $row['page'] . ']' .
									'(' . $row['price_h'] . ')' . "</option>" ;

						endwhile;

						?>

			            </select><br />
			            <font size="2">作成していない開催地は選択できません。<br />
			            <a href="area_set.php">こちら</a>で作成してください。</font>
					</td>
				</tr>
				<tr>
					<th>イベント名</th>
					<td>
						<input type="text" name="title">
					</td>
				</tr>
				<tr>
					<th>開催日程<br />
					</th>
					<td>
						<?php 
							$thisYear = date('Y'); 
							$nextYear = $thisYear + 1;
						?>
						<select name="year">
						<?php 
							echo 
								"
								<option value='{$thisYear}'>{$thisYear}年</option>
								<option value='{$nextYear}'>{$nextYear}年</option>
								"
						?>
						</select>
						<input type="text" name="month" size="4" />月
						<input type="text" name="day" size="4" />日
					</td>
				</tr>
				<tr>
					<th>開始時刻<br />
					</th>
					<td>
						<input type="radio" name="begin" value="13:50" checked="checked">１３：５０～<br />
						<input type="radio" name="begin" value="14:00">１４：００～<br />
						<input type="radio" name="begin" value="19:20">１９：２０～<br />
						<input type="radio" name="begin" value="19:30">１９：３０～<br />
						<input type="radio" name="begin" value="input">
							<input type="text" name="begin_h" value="" size="2">：
							<input type="text" name="begin_m" value="" size="2">～
					</td>
				</tr>
				<tr>
					<th>終了時刻<br />
					</th>
					<td>
						<input type="radio" name="end" value="16:30" checked="checked" />１６：３０まで<br />
						<input type="radio" name="end" value="17:00">１７：００まで<br />
						<input type="radio" name="end" value="22:00">２２：００まで<br />
						<input type="radio" name="end" value="input">
							<input type="text" name="end_h" value="" size="2">：
							<input type="text" name="end_m" value="" size="2">まで
					</td>
				</tr>
				<tr>
					<th>チケット価格<br />
					</th>
					<td>
						男性 <input type="text" name="price_m" /><br>
						女性 <input type="text" name="price_f" />
					</td>
				</tr>
				<tr>
					<th>Petix URL<br />
					</th>
					<td><input type="text" name="sale" />
					</td>
				</tr>
				<tr>
					<th>チャッチコピー</th>
					<td><input type="text" name="feature" />
					</td>
				</tr>
				<tr>
					<th>サムネイル画像URL</th>
					<td><input type="text" name="img_url" />
					</td>
				</tr>
				<tr>
					<th>サムネイル表示用画像<br />
						300-250px以外の画像を選択するとTOPのレイアウトが崩れます！
					</th>
					<td><input type="file" name="img_thamb" />
					</td>
				</tr>
			</tbody>
		</table>
		<center>
		<p><input type="submit" name="submit_1" value="送信" /></p>
		</center>
	</form>

	<hr />
	有効なイベントデータを一覧表示しています。<br />
	編集ボタンからイベントを個別に編集することが可能です。<br />
	<hr />

	<?php 

/* [start] 登録済みイベントの一覧表示部分
-----------------------------------------------------------------------------------------*/


	$day = date('Y-m-j');

	$ps = $db->query("select concat( events.date,'(',events.week,')' ) as date , area.area_ja as area , events.state_m , events.state_w , events.find as find, events.title as title 
						from events  
						join area using(area)
						where events.date >= '$day'
						order by events.date;") ;
						
	//WHILE文でテーブルを出力
	print '<form action="form_fix" method="POST" accept-charset="utf-8"><table id="eventsTable">';

	while ($row = @$ps->fetch()) :

		$thisFind = $row['find'] ;

		/* 申込状況を取得
		--------------------------------------------*/


			//男性の申込者数を取得
			$tmp = $db->query("
								select sum(ninzu) from
									(
									select * from customers 
										where find = '{$thisFind}' and sex = 'm' and state <> 3 
										group by name 
									) as temp;
								");

			$total_m = $tmp->fetch();
			list($total_m) = $total_m;
			$total_m = $total_m / 2 ;


			//男性の入金者数を取得
			$tmp = $db->query("
								select sum(ninzu) from
									(
									select * from customers 
										where find = '{$thisFind}' and sex = 'm' and state = 1 
										group by name 
									) as temp;
								");

			$comp_m = $tmp->fetch();
			list($comp_m) = $comp_m;
			$comp_m = $comp_m / 2 ;


			//女性の申込者数を取得
			$tmp = $db->query("
								select sum(ninzu) from
									(
									select * from customers 
										where find = '{$thisFind}' and sex = 'w' and state <> 3 
										group by name 
									) as temp;
								");

			$total_w = $tmp->fetch();
			list($total_w) = $total_w;
			$total_w = $total_w / 2 ;


			//女性の入金者数を取得
			$tmp = $db->query("
								select sum(ninzu) from
									(
									select * from customers 
										where find = '{$thisFind}' and sex = 'w' and state = 1 
										group by name 
									) as temp;
								");

			$comp_w = $tmp->fetch();
			list($comp_w) = $comp_w;
			$comp_w = $comp_w / 2 ;




		/*--------------------------------------------
		[end] 申込状況を取得 */




		//フォームの稼働ステータスを見やすいように日本語で表示させる準備
		if ($row['state_m'] == 1 ):
			$sta_m = '募集中' ;
		elseif ($row['state_m'] == 2 ):
			$sta_m = '早割中' ;
		elseif ($row['state_m'] == 3 ):
			$sta_m = 'キャンセル待ち' ;
		elseif ($row['state_m'] == 4 ):
			$sta_m = '電話受付のみ' ;
		else:
			$sta_m = '停止' ;
		endif;

		if ($row['state_w'] == 1 ):
			$sta_w = '募集中' ;
		elseif ($row['state_w'] == 2 ):
			$sta_w = '早割中' ;
		elseif ($row['state_w'] == 3 ):
			$sta_w = 'キャンセル待ち' ;
		elseif ($row['state_w'] == 4 ):
			$sta_w = '電話受付のみ' ;
		else:
			$sta_w = '停止' ;
		endif;


		print 
			"
			<tr>
			<td class='td01'>{$row['date']}</td>
			<td class='td02'>{$row['area']}</td>
			<td style='width:40%;'>{$row['title']}</td>
			<td class='td08'>
				<a href='event_fix.php/{$row['find']}'>編集</a>
			</td>
			</tr>
			";

		/* old 
			"
			<tr>
			<td class='td01'>{$row['date']}</td>
			<td class='td02'>{$row['area']}</td>
			<td style='width:40%;'>{$row['title']}</td>
			<td class='td03'>男性：{$sta_m}</td>
			<td class='td04'>女性：{$sta_w}</td>
			<td class='td05'><span class='accent'>M:{$comp_m}</span>/($total_m)</td>
			<td class='td06'><span class='accent'>W:{$comp_w}</span>/($total_w)</td>
			<td class='td07'><input type='button' name='fix_button' value='申込管理' onClick='location.href = \"participant.php/{$row['find']}\"' /></td>
			<td class='td08'><input type='button' name='fix_button' value='編集' onClick='location.href = \"event_fix.php/{$row['find']}\"' /></td>
			</tr>
			";
		*/

	endwhile;

	print '</table></form>';
	

/*-----------------------------------------------------------------------------------------
 [end] 登録済みイベントの一覧表示部分 */

	?>

	<hr />
	有効なイベントデータを一覧表示しています。<br />
	編集ボタンからイベントを個別に編集することが可能です。<br />
	<hr />

	<?php 

elseif ( $pagePat === 1 )://パターン１：フォームにデータが入力された場合、入力内容を表示して確認画面を表示

	// 時刻が直接入力された場合の処理
		if ($_POST['begin'] === 'input') {
			$begin = $_POST['begin_h'] . ':' . $_POST['begin_m'];
		} else {
			$begin = $_POST['begin'];
		}

		if ($_POST['end'] === 'input') {
			$end = $_POST['end_h'] . ':' . $_POST['end_m'];
		} else {
			$end = $_POST['end'];
		}

	$date = $_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['day'];
	$time = strtotime($date);
	$weekdays = array("日", "月", "火", "水", "木", "金", "土");
	$w = date("w", $time);
	$week = $weekdays[$w];

	$find = $_POST['area'] . $_POST['month'] . $_POST['day'] ;

	//アップロードされたファイルを処理
	move_uploaded_file($_FILES['img_thamb']['tmp_name'], '../img/img_thamb/' . $find);
	

	//テーブルで入力内容を出力（フォームのデータは別で出力します。）
	print 
		"
		<center>
		<table border='1' width='80%'>
			<tr>
				<th width='30%' height='40px'>開催地</th>
				<td>{$_POST['area']}</td>
			</tr>
			<tr>
				<th>開催日程</th>
				<td>{$date} ({$week})</td>
			</tr>
			<tr>
				<th>開催時刻</th>
				<td>{$begin}～{$end}</td>
			</tr>
			<tr>
				<th>チケット価格</th>
				<td>
				男性： {$_POST['price_m']}円 <br>
				女性： {$_POST['price_f']}円
				</td>
			</tr>
			<tr>
				<th>Peatix URL</th>
				<td>{$_POST['sale']}</td>
			</tr>
			<tr>
				<th>キャッチコピー</th>
				<td>{$_POST['feature']}</td>
			</tr>
			<tr>
				<th>サムネイル画像</th>
				<td><img src='{$_POST['img_url']}'></td>
			</tr>
		</table>
		";

	//テーブルのセルをFor文で出力
	for ( $n = 0 ; $n <= 5 ; $n += 1 ):

		print "<tr><th width='30%' height='40px'>" . $th[$n] . "</th><td width='30%'>" . $td[$n] . "</td></tr>" ;

	endfor;


	//フォームのデータをHiddenで出力
	print '<form action="event_set.php" method="post" accept-charset="utf-8">';
	
	print 
			"
			<input type='hidden' name='find' value='{$find}'>
			<input type='hidden' name='area' value='{$_POST['area']}'>
			<input type='hidden' name='title' value='{$_POST['title']}'>
			<input type='hidden' name='date' value='{$date}'>
			<input type='hidden' name='week' value='{$week}'>
			<input type='hidden' name='begin' value='{$begin}'>
			<input type='hidden' name='end' value='{$end}'>
			<input type='hidden' name='price_m' value='{$_POST['price_m']}'>
			<input type='hidden' name='price_f' value='{$_POST['price_f']}'>
			<input type='hidden' name='sale' value='{$_POST['sale']}'>
			<input type='hidden' name='feature' value='{$_POST['feature']}'>
			<input type='hidden' name='img_url' value='{$_POST['img_url']}'>
			";

	print "<input type='submit' name='submit_2' value='確認完了'></form></center>";

	?>


	<?php 

elseif ( $pagePat === 2 )://パターン２：確認画面から入力完了ボタンが押された場合・内容をDBに格納（登録完了画面を表示）

	//DBの初期化
	require_once("../db_data/db_init.php");

	//POSTで送られてきたデータを配列に格納
	$toDb = array_values($_POST);

	//プリペアドステートメントの準備
	$ps = $db->prepare("
		insert into events (`find`,`area`,`title`,`date`,`week`,`begin`,`end`,`price_m`,`price_f`,`sale`,`feature`,`img_url`) 
		values ( :find, :area, :title, :date, :week, :begin, :end, :price_m, :price_f, :sale, :feature, :img_url );
		");

	$ps->bindParam( ':find' , $_POST['find']);
	$ps->bindParam( ':area' , $_POST['area']);
	$ps->bindParam( ':title' , $_POST['title']);
	$ps->bindParam( ':date' , $_POST['date']);
	$ps->bindParam( ':week' , $_POST['week']);
	$ps->bindParam( ':begin' , $_POST['begin']);
	$ps->bindParam( ':end' , $_POST['end']);
	$ps->bindParam( ':price_m' , $_POST['price_m']);
	$ps->bindParam( ':price_f' , $_POST['price_f']);
	$ps->bindParam( ':sale' , $_POST['sale']);
	$ps->bindParam( ':feature' , $_POST['feature']);
	$ps->bindParam( ':img_url' , $_POST['img_url']);

	//インサート文を実行
	$res = $ps->execute();

	//失敗した場合の通知
	if ($res == false):

	?>
	<h1>データの挿入に失敗しました</h1>
	<p>識別IDが重複しているなど入力内容に不備があるようです。<br />
	<a href="//localhost/develop2/admin_settings/admin.php">最初の画面</a>に戻ってやり直してください。</p>
	<?php 

	else:
		

		?>

		<h1>登録が完了しました。</h1>
		<p>イベント設定ページにもどるには<a href="event_set.php">こちら</a></p>

		<?php


	endif;

endif;
	
?>

</body>
</html>