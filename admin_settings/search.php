<?php 


//Noticeエラーを非表示にする
error_reporting(E_ALL & ~E_NOTICE);

//データベースの初期化
require_once("../db_data/db_init.php");
$db->query("SET NAMES utf8");



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


elseif ( isset($_POST['submit_download']) ):

	$query = $_POST['searchQuery'];
	$query = urldecode($query) ;
	$result = $db->query($query);

	$n = 0 ;

	while ( $row = $result->fetch() ):


		//過去に登録したアリアを全て取得
		$mail = $row['mail'];

		$result_2 = $db->query("
								select area.ken as 'ken' , area.area_ja as 'area_ja' from customers inner join events on customers.find = events.find inner join area on events.area = area.area
									where customers.mail = '{$mail}'
							;");

		$interestArea = '';

		while ( $row_2 = $result_2->fetch() ):

			$interestArea = $interestAre . $row_2['ken'] . ' ' . $row_2['area_ja'] . ' ' ;

		endwhile;

		//CSVファイルに書き込み
		$sex = ( $row['sex'] == 'm' ) ? '男性' : '女性' ;

		$date = strtok($row['date'], ' ');
		$y = strtok($date, '-');
		$m = strtok('-');
		$d = strtok('-');

		$toWrite = mb_convert_encoding( "{$sex},{$y}/{$m}/{$d},{$row['name']},{$row['hurigana']},{$row['age']},{$row['mail']},{$interestArea}\n", 'SJIS' , 'UTF-8' ) ;

		$mode = ( $n === 0 ) ? 'wt' : 'at' ;
		$fh = fopen( './downloadList/tmpList.csv', $mode );
		fwrite( $fh, $toWrite );
		fclose($fh);


		$n += 1 ;


	endwhile;

	//ファイルをダウンロードさせる
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename=tmpList.csv');
	readfile('https://koikoi.co.jp/ikoiko/admin_settings/downloadList/tmpList.csv');



elseif ( isset($_POST['submit_quick']) ):

	$ps = $db->prepare('select * from customers where download = 0 ;');
	$ps->execute();

	$n = 0 ;

	while ( $row = $ps->fetch() ):

		//過去に登録したアリアを全て取得
		$mail = $row['mail'];

		$result_2 = $db->query("
								select area.ken as 'ken' , area.area_ja as 'area_ja' from customers inner join events on customers.find = events.find inner join area on events.area = area.area
									where customers.mail = '{$mail}'
							;");

		$interestArea = '';

		while ( $row_2 = $result_2->fetch() ):

			$interestArea = $interestAre . $row_2['ken'] . ' ' . $row_2['area_ja'] . ' ' ;

		endwhile;

		//CSVファイルに書き込み
		$sex = ( $row['sex'] == 'm' ) ? '男性' : '女性' ;

		$date = strtok($row['date'], ' ');
		$y = strtok($date, '-');
		$m = strtok('-');
		$d = strtok('-');

		$toWrite = mb_convert_encoding( "{$sex},{$y}/{$m}/{$d},{$row['name']},{$row['hurigana']},{$row['age']},{$row['mail']},{$interestArea}\n", 'SJIS' , 'UTF-8' ) ;

		$mode = ( $n === 0 ) ? 'wt' : 'at' ;
		$fh = fopen( './downloadList/tmpList.csv', $mode );
		fwrite( $fh, $toWrite );
		fclose($fh);


		$n += 1 ;


	endwhile;

	//ファイルをダウンロードさせる
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename=tmpList.csv');
	readfile('https://koikoi.co.jp/ikoiko/admin_settings/downloadList/tmpList.csv');

	$db->query("UPDATE customers SET download = 1 WHERE 1;");


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
	<script src="https://koikoi.co.jp/ikoiko/js/jquery-3.1.0.min.js" type="text/javascript"></script>
</head>
<body>



<p style="font-size:40;"><a href="admin.php">コントロールパネルトップにもどる</a></p>



<h2>Instant Search</h2>

<hr />


<div id='searchCustomer'>

	<form action="https://koikoi.co.jp/ikoiko/admin_settings/search.php" method='post'>
					

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

<hr />

<form action="https://koikoi.co.jp/ikoiko/admin_settings/search.php" method="post">
	<input type="submit" name="submit_quick" value="クイックダウンロード">
	未ダウンロードのデータを抽出してダウンロードできます。
</form>



<!--//////////////////////////////[ 検索結果のブロック ]//////////////////////////////-->
<?php if ($showSearchResult): ?>

<hr />

<?php  


if ( ! $searchResult ):

	print '検索結果は0件でした。';

else:

	//ダウンロードボタンを表示
	print 
			"
			<form action='search.php' method='post' accept-charset='utf-8'>
				<input type='hidden' name='searchQuery' value='{$searchResult_enc}' />
				メールASP用のファイルを検索結果からダウンロードができます。<input type='submit' name='submit_download' value='ダウンロード'>
			</form>
			";

?>


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


<?php


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
<h2>Detailed Search</h2>
<hr />
準備中。


<hr />
<h2>Downlode</h2>
顧客リストをダウンロードできます。
<hr />







<script type='text/javascript'>

			$('a[id^=id]').bind('click' , function() {

				name = $(this).attr('id');


				$( 'div[id^=hidden]:visible' ).hide();
				$( 'div[id^=shown]:hidden' ).show();
				$( '#hidden_' + name ).show();
				$( '#shown_' + name ).hide();

			} );

</script>


	
</body>
</html>