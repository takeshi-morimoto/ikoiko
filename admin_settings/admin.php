<?php 


//Noticeエラーを非表示にする
error_reporting(E_ALL & ~E_NOTICE);



//データベースの設定ファイルがあるか確認
$dbInfoExist = is_file('../db_data/db_info/db_info.csv');

if ( $dbInfoExist == false ):

	print '<h2>データベース設定ファイルが存在しません。<br />セットアップを実行してください。</h2><p><a href="setup.php">セットアップ</a></p>' ;

else:

	//データベースの初期化
	require_once("../db_data/db_init.php");
	$db->query("SET NAMES utf8");

endif;



//支払いの確認ボタンが押された場合
if ( isset($_POST['submit_pay']) ):


	$today = date('Y-m-j') ;
	$price = (int)$_POST['payment_p'] ;
	$name = $_POST['name'] ;
	$localFind = $_POST['localFind'] ;

	$db->query("
				update customers 
					set state = 1 , payment_d = '{$today}' , payment_p = {$price} 
					where find = '{$localFind}' and name = '{$name}' ;
				") ;

	
	//入金確認メールの送信
	$ps = $db->query("
				select mail
					from customers  
					where find = '{$localFind}' and name = '{$name}'
					group by mail ;
				") ;

	while ( $mail = $ps->fetch() ):

		list($sendTo) = $mail ; 
		require('confirmationMail.php');

	endwhile;




//取消ボタンが押された場合
elseif ( isset($_POST['submit_cancel']) ):

	$name = $_POST['name'] ;
	$localFind = $_POST['localFind'] ;

	$db->query("
				update customers 
					set state = 3 , payment_d = null , payment_p = null
					where find = '{$localFind}' and name = '{$name}' ;
				") ;


//有効ボタンが押された場合
elseif ( isset($_POST['submit_revival']) ):

	$name = $_POST['name'] ;
	$localFind = $_POST['localFind'] ;

	$db->query("
				update customers 
					set state = 2 
					where find = '{$localFind}' and name = '{$name}' ;
				") ;


//開放ボタンが押された場合
elseif ( isset($_POST['submit_release']) ):

	$name = $_POST['name'] ;
	$localFind = $_POST['localFind'] ;

	$db->query("
				update customers 
					set entry = 1 
					where find = '{$localFind}' and name = '{$name}' ;
				") ;


//メモボタンが押された場合
elseif ( isset($_POST['submit_memo']) ):

	$name = $_POST['name'] ;
	$memo = $_POST['memo'] . '｜' . $_POST['newMemo'] ;
	$localFind = $_POST['localFind'] ;


	$db->query("
					update customers 
						set memo = '{$memo}'
						where find = '{$localFind}' and name = '{$name}' ;
					") ;

//検索ボタンが押された場合
elseif ( isset($_POST['submit_search']) ):


	$showSearchResult = true ;


	//検索ワードが入力された場合
	if ( !empty($_POST['word']) ):

		$inputWord = str_replace( '　' , ' ' , $_POST['word'] );
		

		$firstWord = strtok($inputWord, ' ') ;
		$searchWord = "and concat_ws(char(0),name,hurigana,mail,tel) like '%{$firstWord}%' " ;


		while ( $word = strtok(' ') ):

			$searchWord = $searchWord . "and concat_ws(char(0),name,hurigana,mail,tel) like '%{$word}%' " ;

		endwhile;



	endif;


	//開催地で絞り込まれた場合はSQLを追加
	if ( empty($_POST['targetFind_input']) & empty($_POST['targetFind_select']) ):

		$targetFind = ' ' ;

	elseif ( $_POST['targetFind_input'] ):

		$targetFind = "and customers.find = '{$_POST['targetFind_input']}' " ;

	else:

		$targetFind = "and customers.find = '{$_POST['targetFind_select']}' " ;

	endif;


	//性別で絞り込まれた場合SQLを追加
	switch ( $_POST['sex'] ):

		case 'all' :

			$targetSex = ' ' ;

		break;

		case 'm' :

			$targetSex = 'and customers.sex = "m" ' ;

		break;

		case 'w' :

			$targetSex = 'and customers.sex = "w" ' ;

		break;

	endswitch;


	//重複の申し込みを無視する場合はグループかのSQLを追加
	$noRepeat = ( empty($_POST['noRepeat']) ) ? ' ' : 'group by customers.name' ;




	$searchQuery = 

				"
				select * from customers 

				 where 

					1 = 1 
					{$searchWord}
					{$targetFind} 
					{$targetSex} 

				{$noRepeat} 

				order by customers.date desc ;

				";


	$searchResult = $db->query($searchQuery);

	$searchResult_enc = urlencode($searchQuery);


endif;


//検索結果からいずれかの操作ボタンが押された場合
if ( isset($_POST['searchQuery']) ):

	$showSearchResult = true ;


	$searchQuery = urldecode($_POST['searchQuery']) ;
	$searchResult = $db->query($searchQuery);
	$searchResult_enc = urlencode($searchQuery);


endif;



 ?>




<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>街コン設定用コントロールパネル</title>
	<link rel="stylesheet" href="https://koikoi.co.jp/ikoiko/css/admin.css">
	<script src="https://koikoi.co.jp/ikoiko/js/jquery-3.7.1.min.js" type="text/javascript"></script>
	<script src="https://koikoi.co.jp/ikoiko/js/jquery-migrate-3.4.1.min.js" type="text/javascript"></script>
</head>
<body>

<h1>Wellcome to control panel!!</h1>
システムの開発者はあなたの寄付を必要としています(・ω・<br />
var 2.1.8

<ul>
	<li>2.3.1 セール情報を表示させる機能を追加</li>
	<li>2.3.0 参加者リストのCSVダウロード機能を追加</li>
	<li>2.2.0 登録済みイベントリストに申込状況の表示を追加</li>
	<li>2.1.8 セットアップのテーブル作成SQLを修正</li>
	<li>2.1.7 申込管理のページに選択中のイベント表示</li>
</ul>


<h3>Menu</h3>
<p>
・<a href="help.html">使い方の説明</a><br />
<br />
・<a href="search.php">参加者の検索とCSVダウンロード</a><br />
<br />
・<a href="event_set.php">新しいイベントを作成と編集</a><br />
<br />
・<a href="area_set.php">新しい開催地ページの作成と編集</a><br />
<br />
・<a href="addition.php">参加者の手動登録</a><br />
<br />
・<a href="editContent.php">コンテンツの編集</a><br />
<br />
・<a href="io-management.php">データ入出力</a><br />
<br />
・<a href="setup.php">セットアップ</a>
</p>





<h2>Instant Search</h2>

<hr />


<div id='searchCustomer'>

	<form action="https://koikoi.co.jp/ikoiko/admin_settings/admin.php" method='post'>
					

		[-- search --]


		<input type="text" name="word" value="" />

		<input type="submit" name="submit_search" value="検索" />

		[-- option --]

		開催地：

		<select name="targetFind_select">
			<option value="">ALL</option>


			<?php 


				//作成済みの開催エリアを取得してセレクトボックスで出力
				$ps = $db->query("
									select events.find as 'find' , area.ken as 'ken' , area.area_ja as 'area_ja' , events.date as 'date' 
										from events 
										join area using(area) 
										order by events.date") ;

				while ($row = $ps->fetch()):

				print "<option value='{$row['find']}'>{$row['date']} [{$row['ken']}・{$row['area_ja']}]</option>" ;

				endwhile;

			?>


		</select>

		 or 

		<input type="text" name="targetFind_input" value="" size=10 />
		｜
		性別：
		<input type="radio" name="sex" value="all" />全て
		<input type="radio" name="sex" value="m" />男性のみ
		<input type="radio" name="sex" value="w" />女性のみ
		｜
		<input type="checkbox" name="noRepeat" value="noRepeat" />重複の申込を無視｜




		<input type="submit" name="submit_search" value="検索" />

	</form>

</div>





<!--//////////////////////////////[ 検索結果のブロック ]//////////////////////////////-->
<?php if ($showSearchResult): ?>

<hr />

<?php  


if ( ! $searchResult ):

	print '検索結果は0件でした。';

else:

	//検索結果をテーブルで表示
	print 

		'
		<table id="searchResult">
			<thead>
				<tr>
					<th>番号</th>
					<th>識別子</th>
					<th>申込イベント</th>
					<th colspan=2>申込日</th>
					<th colspan=2>支払日</th>
					<th>性別</th>
					<th>組数</th>
					<th>名前</th>
					<th>ふりがな</th>
					<th>年齢</th>
					<th>メールアドレス</th>
					<th>電話番号</th>
					<th>メモ欄</th>
					<th>メモ</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
		';


	$n = 0 ;
	$resultRecord = 200 ;

	while ( $row = $searchResult->fetch() ):


		/*レコードを表示する準備
		--------------------------------------*/


		$name = $row['name'];
		$memo = $row['memo'];
		$localFind = $row['find'];

		$date = strtok($row['date'], ' ');
		$y = strtok($date, '-');
		$m = strtok('-');
		$d = strtok('-');


		$sex = ( $row['sex'] == 'm' ) ? '男性' : '女性' ;


		//支払情報のセルに表示するデータをセット
		switch ($row['state']):

			case 1 :

				$state = '○';

				$pay_y = strtok( $row['payment_d'], '-' );
				$pay_m = strtok( '-' );
				$pay_d = strtok( '-' );

				$payment = "{$pay_m}/{$pay_d}<br />[{$row['payment_p']}]";
				$td10 = "<input type='submit' name='submit_cancel' value='取消' />" ;

				$trClass = 'complete' ;

			break;

			case 2 :

				$state = '';

				$payment = "
							<div class='clickField' id='shown_id{$resultRecord}'>
							<a id='id{$resultRecord}' class='click'></a>
							</div>

							<div id='hidden_id{$resultRecord}' class='manageButton'>
							<input type='text' name='payment_p' value='' size=1>
							<input type='submit' name='submit_pay' value='確認'>
							</div>
							";

				$td10 = "<input type='submit' name='submit_cancel' value='取消' />" ;

				$trClass = 'normal' ;

			break;

			case 3 :

				$state = '×';

				$payment = "キャンセル";
				$td10 = "<input type='submit' name='submit_revival' value='有効' />" ;
				$trClass = 'cancel' ;

			break;

		endswitch;


		//申込ステートをわかりやすく表示する準備
		switch ($row['entry']):

			case 1 :
				$entry = '';
			break;

			case 2 :
				$entry = '<span class="blue">早</span>';
			break;

			case 3 :
				$entry = '待';
				$trClass = 'wait' ;
				$td10 = "<input type='submit' name='submit_release' value='開放' />" ;
			break;

		endswitch;


		//DBに格納されている申込み人数を組数に変更
		$pair = $row['ninzu'] / 2 ;



		/*--------------------------------------
		レコードを表示する準備*/


		print 
				"
				<tr class='{$trClass}'>
					<form action='https://koikoi.co.jp/ikoiko/admin_settings/admin.php' method='post' accept-charset='utf-8'>
					<input type='hidden' name='localFind' value='{$localFind}' />
					<input type='hidden' name='name' value='{$name}' />
					<input type='hidden' name='memo' value='{$memo}' />
					<input type='hidden' name='searchQuery' value='{$searchResult_enc}' />

					<td class='td01'>{$row['number']}</td>
					<td class='td02'>{$row['find']}</td>
					<td class='td03'>{$row['event']}</td>
					<td class='td04'>{$m}/{$d}</td>
					<td class='td05'><span class='fontWeight_bold'>{$entry}</span></td>
					<td class='td06'><span class='fontWeight_bold'>{$state}</span></td>
					<td class='td07'>{$payment}</td>
					<td class='td08'>{$sex}</td>
					<td class='td09'><span class='fontWeight_bold'>{$pair}</span></td>
					<td class='td10'>{$name}</td>
					<td class='td11'>{$row['hurigana']}</td>
					<td class='td12'>{$row['age']}</td>
					<td class='td13'>{$row['mail']}</td>
					<td class='td14'>{$row['tel']}</td>
					<td class='td15'>{$memo}</td>
					<td class='td16'><input type='text' name='newMemo' value='' size=4 /><input type='submit' name='submit_memo' value='メモ' /></td>
					<td class='td17'>{$td10}</td>
					</form>
				</tr>
				";

		$n += 1 ;
		$resultRecord += 1 ;

	endwhile;

	print '</tbody></table>';

	print "検索結果は{$n}件でした";

endif;

?>



<?php endif; ?>
<!--//////////////////////////////[ /検索結果のブロック ]//////////////////////////////-->





<hr />
<h2>Events Table</h2>
<hr />

<?php 

/* [start] 登録済みイベントの一覧表示部分
-----------------------------------------------------------------------------------------*/


	//現在の日付を取得して以降のイベントを読み込み
	$day = sprintf( "%02d" , date("j") );
	$today = date("Y") . '-' . date("m") . '-' . $day ;
	$ps = $db->query("select concat( events.date,'(',events.week,')' ) as date , area.area_ja as area , events.state_m , events.state_w , events.find, events.title as title
						from events  
						join area using(area)
						where events.date >= '$today'
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
<center>
<h2>Areas Table</h2>
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



<script type='text/javascript'>

			$('a[id^=id]').on('click' , function() {

				name = $(this).attr('id');


				$( 'div[id^=hidden]:visible' ).hide();
				$( 'div[id^=shown]:hidden' ).show();
				$( '#hidden_' + name ).show();
				$( '#shown_' + name ).hide();

			} );

</script>
	
</body>
</html>