<?php 


//Noticeエラーを非表示にする
error_reporting(E_ALL & ~E_NOTICE);


//表示する内容の切り替え（編集画面、完了画面）
if ( isset($_POST['submit']) ):
	$pagePat = 1 ;//DBのデータを更新して完了画面を表示
elseif ( isset($_POST['delete']) ):
	$pagePat = 2 ;//削除ボタンが押された場合該当するイベントを削除
else:
	$pagePat = 0 ;//イベント編集画面
endif;


//データベースの初期化
require_once("../db_data/db_init.php");
$db->query("SET NAMES utf8");

?>

<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>イベント登録フォーム</title>
	<style>.none{display: none;}</style>
</head>
<body>

<center>

<?php 

if ( $pagePat === 0 )://送信ボタンが押されてない場合編集画面

	//押されたボタンの識別子をPATH_INFOで取得
	$find = strtok($_SERVER['PATH_INFO'], "/");

	//識別子に該当するイベントデータをDBから取得
	$ps = $db->query("select
		area.ken,
		area.area_ja,
		events.title,
		events.date,
		events.week,
		events.begin,
		events.end,
		events.price_m,
		events.price_f,
		events.pr_comment,
		events.sale,
		events.feature,
		events.img_url,
		events.state_m,
		events.state_w,
		events.meetingpoint
		from events join area using(area) where events.find = '{$find}'");

	// pr_commentは集合場所として使われています。

	$eventData = $ps->fetch();

	//データを個別の変数に格納
	list(
		$ken,
		$area,
		$title,
		$date,
		$week,
		$begin,
		$end,
		$price_m,
		$price_f,
		$pr_comment, // 集合場所として使われています。
		$sale,
		$feature,
		$imgURL,
		$state_m,
		$state_w,
		$meetingpoint,
	) = $eventData ;

	//$dateを年と月と日に分割
	$y = strtok( $date , '-' );
	$m = strtok( '-' );
	$d = strtok( '-' );

	// チェックボックス用変数の初期化
	$beginTimeCheck_00 = '';
	$beginTimeCheck_01 = '';
	$beginTimeCheck_02 = '';
	$endTimeCheck_00 = '';
	$endTimeCheck_01 = '';
	$endTimeCheck_02 = '';
	$staCheck_m_00 = '';
	$staCheck_m_01 = '';
	$staCheck_m_02 = '';
	$staCheck_m_03 = '';
	$staCheck_m_04 = '';
	$staCheck_w_00 = '';
	$staCheck_w_01 = '';
	$staCheck_w_02 = '';
	$staCheck_w_03 = '';
	$staCheck_w_04 = '';
	$beginHour = '';
	$beginMin = '';
	$endHour = '';
	$endMin = '';
	$mpCheck_00 = '';
	$mpCheck_01 = '';

	//開始時刻の選択済み初期値を設定
	if ( $begin === '14:00:00' ):

		$beginTimeCheck_01 = " checked='checked'";

	elseif ( $begin === '20:00:00' ):

		$beginTimeCheck_02 = " checked='checked'";

	else:

		$beginTimeCheck_00 = " checked='checked'";
		$beginHour = strtok( $begin, ':' );
		$beginMin = strtok( ':' );

	endif;

	//終了時刻の選択済み初期値を設定
	if ( $end === '17:00:00' ):

		$endTimeCheck_01 = " checked='checked'";

	elseif ( $end === '23:00:00' ):

		$endTimeCheck_02 = " checked='checked'";

	else:

		$endTimeCheck_00 = " checked='checked'";
		$endHour = strtok( $end, ':' );
		$endMin = strtok( ':' );

	endif;

	//フォームの稼働ステータスに合わせてチェック済みにする
	switch ($state_m):

		case 1 :

			$staCheck_m_01 = " checked='checked'" ;
			break;

		case 2 :

			$staCheck_m_02 = " checked='checked'" ;
			break;

		case 3 :

			$staCheck_m_03 = " checked='checked'" ;
			break;

		case 4 :

			$staCheck_m_04 = " checked='checked'" ;
			break;

		case 0 :
		
			$staCheck_m_00 = " checked='checked'" ;
			break;

	endswitch;

	//フォームの稼働ステータスに合わせてチェック済みにする
	switch ($state_w):

		case 1 :

			$staCheck_w_01 = " checked='checked'" ;
			break;

		case 2 :

			$staCheck_w_02 = " checked='checked'" ;
			break;

		case 3 :

			$staCheck_w_03 = " checked='checked'" ;
			break;

		case 4 :

			$staCheck_w_04 = " checked='checked'" ;
			break;

		case 0 :
		
			$staCheck_w_00 = " checked='checked'" ;
			break;

	endswitch;

	//集合場所の表示・非表示のチェック済みを選択
	( $meetingpoint == 0 ) ? $mpCheck_00 = " checked='checked'" : $mpCheck_01 = " checked='checked'" ;


print <<<end
	<form action="../event_fix.php" method="post" enctype="multipart/form-data">
		<table border="1">
			<caption>登録情報を変更します。</caption>
			<tbody>
				<tr>
					<th width="30%" height="50px">開催エリア <br />
					</th>
					<td width="30%">{$ken}・{$area}
					</td>
				</tr>
				<tr>
					<th>開催日程<br />
					</th>
					<td>{$y}年{$m}月{$d}日（{$week}）
					</td>
				</tr>
				<tr>
					<th>イベント名
					</th>
					<td>
						<input type="text" name="title" value="{$title}">
					</td>
				</tr>
				<tr>
					<th>開始時刻<br />
					</th>
					<td>
						<input type="radio" name="beginTime" value="14:00"{$beginTimeCheck_01} />１４：００～<br />
						<input type="radio" name="beginTime" value="20:00"{$beginTimeCheck_02} />２０：００～<br />
						<input type="radio" name="beginTime" value="0"{$beginTimeCheck_00} />
						<input type="text" name="beginTime_inp1" value="{$beginHour}" size="2">：
						<input type="text" name="beginTime_inp2" value="{$beginMin}" size="2">～
					</td>
				</tr>
				<tr>
					<th>終了時刻<br />
					</th>
					<td>
						<input type="radio" name="endTime" value="17:00"{$endTimeCheck_01} />１７：００～<br />
						<input type="radio" name="endTime" value="23:00"{$endTimeCheck_02} />２３：００～<br />
						<input type="radio" name="endTime" value="0"{$endTimeCheck_00} />
						<input type="text" name="endTime_inp1" value="{$endHour}" size="2">：
						<input type="text" name="endTime_inp2" value="{$endMin}" size="2">～
					</td>
				</tr>
				<tr class="none">
					<th>男性フォームのステータス
					</th>
					<td><input type="radio" name="state_m" value="1"{$staCheck_m_01} />募集中<br />
					<input type="radio" name="state_m" value="2"{$staCheck_m_02} />早割中<br />
					<input type="radio" name="state_m" value="3"{$staCheck_m_03} />キャンセル待ち<br />
					<input type="radio" name="state_m" value="4"{$staCheck_m_04} />電話受付のみ<br />
					<input type="radio" name="state_m" value="0"{$staCheck_m_00} />停止
					</td>
				</tr>
				<tr class="none">
					<th>女性フォームのステータス
					</th>
					<td><input type="radio" name="state_w" value="1"{$staCheck_w_01} />募集中<br />
					<input type="radio" name="state_w" value="2"{$staCheck_w_02} />早割中<br />
					<input type="radio" name="state_w" value="3"{$staCheck_w_03} />キャンセル待ち<br />
					<input type="radio" name="state_w" value="4"{$staCheck_w_04} />電話受付のみ<br />
					<input type="radio" name="state_w" value="0"{$staCheck_w_00} />停止
					</td>
				</tr>
				<tr>
					<th>チケット価格<br />
					</th>
					<td>
						男性 <input type="text" name="price_m" value="{$price_m}"><br>
						女性 <input type="text" name="price_f" value="{$price_f}">
					</td>
				</tr>
				<tr>
					<th>集合場所
					</th>
					<td><textarea name="pr_comment" cols="50" rows="7" >{$pr_comment}</textarea>
					</td>
				</tr>
				<tr>
					<th>Petix URL
					</th>
					<td><input type="text" name="sale" value="{$sale}">
					</td>
				</tr>
				<tr>
					<th>キャッチコピー
					</th>
					<td><input type="text" name="feature" value="{$feature}">
					</td>
				</tr>
				<tr>
					<th>サムネイル画像URL
					</th>
					<td><input type="text" name="img_url" value="{$imgURL}">
					</td>
				</tr>
				<tr>
					<th>サムネイル表示用画像<br />
					</th>
					<td>
						<p><img style="width: 30%;" src="/ikoiko/img/img_thamb/{$find}" alt=""></p>
						<input type="file" name="img_thamb" />
					</td>
				</tr>

			</tbody>
		</table>
		<input type="hidden" name="find" value="{$find}">
		<p>
		<input type="submit" name="submit" value="送信" />
		<input type="submit" name="delete" value="削除" />
		</p>
	</form>
	</center>
end;

elseif ( $pagePat === 1 )://submitボタンが押された場合 DBの内容を更新

	$find = $_POST['find'] ;


	//画像がアップロードされた場合は処理
	( empty($_FILES['img_thamb']['tmp_name']) ) ?  : move_uploaded_file($_FILES['img_thamb']['tmp_name'], '../img/img_thamb/' . $find) ;
	( empty($_FILES['img_banner']['tmp_name']) ) ? : move_uploaded_file($_FILES['img_banner']['tmp_name'], '../img/img_banner/' . $find) ;


	//開始、終了時刻を変数にセット
	$inpData = $_POST["beginTime_inp1"] . ':' . $_POST["beginTime_inp2"];//任意で入力された時間と分を結合して変数に格納

	if ( $_POST["beginTime"] == '0' )://値が０の場合は任意で入力された$inpDataのデータを代入

		$beginTime = $inpData ;

	else:								  //０以外の場合はそのまま使用

		$beginTime = $_POST["beginTime"] ;

	endif;

	$inpData = $_POST["endTime_inp1"] . ':' . $_POST["endTime_inp2"];//任意で入力された時間と分を結合して変数に格納

	if ( $_POST["endTime"] == '0' )://値が０の場合は任意で入力された$inpDataのデータを代入

		$endTime = $inpData ;

	else:								  //０以外の場合はそのまま使用

		$endTime = $_POST["endTime"] ;

	endif;

	//プリペアドステートメントの準備
	$ps = $db->prepare("
		UPDATE `events`
		SET 
			`title`= :title,
			`begin`= :begin,
			`end`= :end,
			`price_m`= :price_m,
			`price_f`= :price_f,
			`pr_comment`= :pr_comment,
			`sale`= :sale,
			`feature`= :feature,
			`img_url`= :img_url
		WHERE `find` = '{$find}'
		");

	$ps->bindParam( ':title' , $_POST['title']);
	$ps->bindParam( ':begin' , $beginTime);
	$ps->bindParam( ':end' , $endTime);
	$ps->bindParam( ':price_m' , $_POST['price_m']);
	$ps->bindParam( ':price_f' , $_POST['price_f']);
	$ps->bindParam( ':pr_comment' , $_POST['pr_comment']);
	$ps->bindParam( ':sale' , $_POST['sale']);
	$ps->bindParam( ':feature' , $_POST['feature']);
	$ps->bindParam( ':img_url' , $_POST['img_url']);

	//インサート文を実行
	$res = $ps->execute();

	if ($res == false):

		print "SQLの実行に失敗しました。" ;

	else:

		print '<h1>変更が完了しました。</h1><p><a href="event_set.php">イベント一覧にもどる</a></p>' ;

	endif;

elseif ( $pagePat === 2 ):

	$find = $_POST['find'];

	//該当するイベントをDBから削除
	$db->query("delete from events where `find` = '{$find}' ");

	print '<h1>削除が完了しました。</h1><p><a href="event_set.php">イベント一覧にもどる</a></p>' ;

endif;

?>

</body>
</html>