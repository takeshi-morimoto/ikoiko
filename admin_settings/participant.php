<?php 

//Noticeエラーを非表示にする
error_reporting(E_ALL & ~E_NOTICE);

//データベースの初期化
require_once("../db_data/db_init.php");
$db->query("SET NAMES utf8");


/* 状況に合わせて必要な処理をした後、ページで使用する識別子を取得
----------------------------------------------------------------------------*/


	//支払いの確認ボタンが押された場合
	if ( isset($_POST['submit_pay']) ):


		$today = date('Y-m-j') ;
		$price = (int)$_POST['payment_p'] ;
		$find = $_POST['find'] ;
		$localFind = $_POST['localFind'] ;
		$name = $_POST['name'] ;

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

		$find = $_POST['find'] ;
		$localFind = $_POST['localFind'] ;
		$name = $_POST['name'] ;

		$db->query("
					update customers 
						set state = 3 , payment_d = null , payment_p = null
						where find = '{$localFind}' and name = '{$name}' ;
					") ;


	//有効ボタンが押された場合
	elseif ( isset($_POST['submit_revival']) ):

		$find = $_POST['find'] ;
		$localFind = $_POST['localFind'] ;
		$name = $_POST['name'] ;

		$db->query("
					update customers 
						set state = 2 
						where find = '{$localFind}' and name = '{$name}' ;
					") ;


	//開放ボタンが押された場合
	elseif ( isset($_POST['submit_release']) ):

		$find = $_POST['find'] ;
		$localFind = $_POST['localFind'] ;
		$name = $_POST['name'] ;

		$db->query("
					update customers 
						set entry = 1 
						where find = '{$localFind}' and name = '{$name}' ;
					") ;


	//メモボタンが押された場合
	elseif ( isset($_POST['submit_memo']) ):

		$find = $_POST['find'] ;
		$localFind = $_POST['localFind'] ;
		$name = $_POST['name'] ;
		$memo = $_POST['memo'] . '｜' . $_POST['newMemo'] ;


		$db->query("
						update customers 
							set memo = '{$memo}'
							where find = '{$find}' and name = '{$name}' ;
						") ;

	//メール送信が押された場合			-----------------------------------------------------------------------------------------------------
	elseif ( isset($_POST['submit_send']) ):

		$find = $_POST['find'] ;
		$localFind = $_POST['localFind'] ;
		$name = $_POST['name'] ;
		$tempNum = $_POST['sendMail'];

		$ps = $db->prepare("
				select mail from customers 
				where find = :FIND and name = :NAME;
			");

		$ps->bindParam( ':FIND', $find );
		$ps->bindParam( ':NAME', $name );

		$ps->execute();

		$row = $ps->fetch();

		include("/home/users/0/sub.jp-koisuru/web/admin_settings/sendMail/mailTemp.php");

				//メール送信の準備
		mb_language("japanese");
		mb_internal_encoding("utf-8");

		//メールを送信するデータを変数にセット
		$from = 'from:' . mb_encode_mimeheader('こいこい街コン') . 	'<mail@p007.sub.jp>';

		$subject = $mail_sbj[$tempNum] ;

		$mess = $mail_body[$tempNum];

		$sendTo = $row['mail'];

		mb_send_mail($sendTo, $subject, $mess, $from);


		$memo = $_POST['memo'] . '｜' . $mail_title[$tempNum] . 'メール送信済み' ;

		$db->query("
						update customers 
							set memo = '{$memo}'
							where find = '{$find}' and name = '{$name}' ;
						") ;


	//開催中止ボタンが押された場合
	elseif ( isset($_POST['submit_stop']) ):

		$find = $_POST['find'] ;

		// フォームのステータスをキャンセル待ちに変更

		$ps = $db->prepare("
			UPDATE `events` SET `state_m`=3,`state_w`=3 WHERE `find` = '{$find}'
			");

		//インサート文を実行
		$res = $ps->execute();


		// 入金済みの参加者にメールを送信
		$ps = $db->prepare('
						select mail from customers 
						where find = :FIND
						and entry = 1 
						and state = 1 ;
					');

		$ps->bindParam( ':FIND', $find );
		$ps->execute();

		//メール送信の準備
		mb_language("japanese");
		mb_internal_encoding("utf-8");

		//メールを送信するデータを変数にセット
		$from = 

			'from:' . 
			mb_encode_mimeheader('こいこい街コン') . 
			'<mail@p007.sub.jp>';

		$subject = 'こいこい街コン' ;

		$mess = 
"
こいこい街コンです。

お申し込みいただいている、今回の街コンについてですが、
直前のキャンセル等により、開催予定人数を下回ってしまったため、
大変申し訳ないのですが、やむを得ず不開催とさせていただくこととなりました。
通達が遅くなってしまったこと、重ねてお詫び申し上げます。


参加費をお支払済のお客様には返金の手続きをさせていただきますので、
下記のURLにアクセスし口座情報をご入力ください。

※お支払い前のお客様は誤ってご入金されないようお願いいたします。
※ご返金は口座情報がこちらに届いてから、二週間以内とさせていただきますので、それ以降でのご確認をお願いいたします。

//goo.gl/forms/qZBFFTlqxF

お手数おかけしますが、何卒よろしくお願いいたします。

―――――――――――――――
お問合せ
こいこい街コン運営事務局
mail：mail@p007.sub.jp
Tel ：070-5025-0546（平日11:00～19:00）
―――――――――――――――
";


		while( $row = $ps->fetch() ):

			$sendTo = $row['mail'];
			mb_send_mail($sendTo, $subject, $mess, $from);

		endwhile;


	//別の開催地が指定された場合
	elseif ( isset($_POST['submit_else']) ):

		$find = ( empty($_POST['find_input']) ) ? $_POST['find_select'] : $_POST['find_input'] ;



	//検索ボタンが押された場合
	elseif ( isset($_POST['submit_search']) ):


		$find = $_POST['find'] ;
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


	else:

		//パスインフォから識別子を取得
		$pathInfo = $_SERVER['PATH_INFO'];
		$find = strtok($pathInfo, '/');


	endif;


	//検索結果からいずれかの操作ボタンが押された場合
	if ( isset($_POST['searchQuery']) ):

		$find = $_POST['find'] ;
		$showSearchResult = true ;


		$searchQuery = urldecode($_POST['searchQuery']) ;
		$searchResult = $db->query($searchQuery);
		$searchResult_enc = urlencode($searchQuery);


	endif;




/*----------------------------------------------------------------------------
[END] 状況に合わせて必要な処理をした後、ページで使用する識別子を取得*/




/* Totalテーブルに表示するデータを取得
----------------------------------------------------------------------------*/

	
	/* 男性
	-----------------------------*/


	//男性の申込者数を取得
	$tmp = $db->query("
						select sum(ninzu) from
							(
							select * from customers 
								where find = '{$find}' and sex = 'm' and state <> 3 
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
								where find = '{$find}' and sex = 'm' and state = 1 
								group by name 
							) as temp;
						");

	$comp_m = $tmp->fetch();
	list($comp_m) = $comp_m;
	$comp_m = $comp_m / 2 ;



	//男性のアクティブな申込者数を取得
	$tmp = $db->query("
						select sum(ninzu) from
							(
							select * from customers 
								where find = '{$find}' and sex = 'm' and state = 2 and entry <> 3 
								group by name 
							) as temp;
						");

	$act_m = $tmp->fetch();
	list($act_m) = $act_m;
	$act_m = $act_m / 2 ;



	//男性のキャンセル待ち申込者数を取得
	$tmp = $db->query("
						select sum(ninzu) from
							(
							select * from customers 
								where find = '{$find}' and sex = 'm' and entry = 3 
								group by name 
							) as temp;
						");

	$wait_m = $tmp->fetch();
	list($wait_m) = $wait_m;
	$wait_m = $wait_m / 2 ;



	//男性のキャンセル済みの申込者数を取得
	$tmp = $db->query("
						select sum(ninzu) from
							(
							select * from customers 
								where find = '{$find}' and sex = 'm' and state = 3 
								group by name 
							) as temp;
						");

	$cancel_m = $tmp->fetch();
	list($cancel_m) = $cancel_m;
	$cancel_m = $cancel_m / 2 ;



	/* 女性
	-----------------------------*/



	//女性の申込者数を取得
	$tmp = $db->query("
						select sum(ninzu) from
							(
							select * from customers 
								where find = '{$find}' and sex = 'w' and state <> 3 
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
								where find = '{$find}' and sex = 'w' and state = 1 
								group by name 
							) as temp;
						");

	$comp_w = $tmp->fetch();
	list($comp_w) = $comp_w;
	$comp_w = $comp_w / 2 ;



	//女性のアクティブな申込者数を取得
	$tmp = $db->query("
						select sum(ninzu) from
							(
							select * from customers 
								where find = '{$find}' and sex = 'w' and state = 2 and entry <> 3 
								group by name 
							) as temp;
						");

	$act_w = $tmp->fetch();
	list($act_w) = $act_w;
	$act_w = $act_w / 2 ;



	//女性のキャンセル待ち申込者数を取得
	$tmp = $db->query("
						select sum(ninzu) from
							(
							select * from customers 
								where find = '{$find}' and sex = 'w' and entry = 3 
								group by name 
							) as temp;
						");

	$wait_w = $tmp->fetch();
	list($wait_w) = $wait_w;
	$wait_w = $wait_w / 2 ;



	//女性のキャンセル済みの申込者数を取得
	$tmp = $db->query("
						select sum(ninzu) from
							(
							select * from customers 
								where find = '{$find}' and sex = 'w' and state = 3 
								group by name 
							) as temp;
						");

	$cancel_w = $tmp->fetch();
	list($cancel_w) = $cancel_w;
	$cancel_w = $cancel_w / 2 ;



/*----------------------------------------------------------------------------
[END] Totalテーブルに表示するデータを取得*/





/* 参加者テーブルに出力するデータをDBから取得
----------------------------------------------------------------------------*/


	$query = "select number , date , entry , state , payment_d , payment_p , name , hurigana , ninzu , memo
						from customers
						where find = '{$find}' and sex = 'm'
						group by name 
						order by state , entry , date ;" ;


	$table_m = $db->query($query) ;



	$query = "select number , date , entry , state , payment_d , payment_p , name , hurigana , ninzu , memo
						from customers
						where find = '{$find}' and sex = 'w'
						group by name 
						order by state , entry , date ;" ;


	$table_w = $db->query($query) ;



/*----------------------------------------------------------------------------
[END] 参加者テーブルに出力するデータをDBから取得*/


?>




<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<link rel="stylesheet" href="//koikoi.co.jp/ikoiko/css/admin.css">

	<script src="https://koikoi.co.jp/ikoiko/js/jquery-3.1.0.min.js" type="text/javascript"></script>

	<script type="text/javascript">

		// 削除ボタンが押された際の確認
		$(function(){
			$('#submit_stop').on('click', function(){

					var $execute;
					$execute = confirm('不開催処理を実行します。\nよろしいですか？');
					if( !$execute ){ return false; }

			});
			
		});

	</script>

</head>
<body>
	
<p style="font-size:40;"><a href="//koikoi.co.jp/ikoiko/admin_settings/admin.php">コントロールパネルトップにもどる</a></p>

<h1>参加者管理ツール</h1>


<p>完全版の購入をご希望の方はシステムの作成者までご連絡ください。<br />
(090-8697-7789　横内)</p>



<p>申込者の管理ができます。</p>



<h3>Instant Search</h3>

<hr />


<div id='searchCustomer'>

	<form action="//koikoi.co.jp/ikoiko/admin_settings/participant.php" method='post'>
					
		<?php print "<input type='hidden' name='find' value='{$find}' />" ?>

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
					<th>メール送信</th>
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
					<form action='//koikoi.co.jp/ikoiko/admin_settings/participant.php' method='post' accept-charset='utf-8'>
					<input type='hidden' name='localFind' value='{$localFind}' />
					<input type='hidden' name='find' value='{$find}' />
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
					<td class='td17'>
						<select name='sendMail'>
							<option value=''>テンプレートを選択</option>
							<option value='1'>キャンセル待ちメール</option>
							<option value='2'>催促メール</option>
							<option value='3'>処断返金メール</option>
							<option value='4'>直前未入金</option>
						</select>
						<input type='submit' name='submit_send' value='送信' />
					</td>
					<td class='td18'>{$td10}</td>
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


<div id='SearchElse'>

	<form action="//koikoi.co.jp/ikoiko/admin_settings/participant.php" method='post'>

		・ほかの開催地を見る
		<select name="find_select">
			<option value="">選択してください</option>


			<?php 

				$today = date('Y-m-j') ;

				//作成済みの開催エリアを取得してセレクトボックスで出力
				$ps = $db->query("
									select events.find as 'find' , area.ken as 'ken' , area.area_ja as 'area_ja' , events.date as 'date' 
										from events 
										join area using(area) 
										where events.date >= '{$today}'
										order by events.date") ;

				while ($row = $ps->fetch()):

				print "<option value='{$row['find']}'>{$row['date']} [{$row['ken']}・{$row['area_ja']}]</option>" ;

				endwhile;

			?>


		</select>

		 or 

		<input type="text" name="find_input" value="" size=10 />

		<input type="submit" name="submit_else" value="送信" />

	</form>

</div>

<?php 

$tmp = $db->query("select events.date as 'date' , area.area_ja as 'area_ja' from events join area using(area) where events.find = '{$find}' ;");
list($thisEvent_date,$thisEvent_area_ja) = $tmp->fetch();
$thisEvent = $thisEvent_date . ' ' . $thisEvent_area_ja ;


print 	"
		<h3>選択中：{$thisEvent}</h3>
		<form action='//koikoi.co.jp/ikoiko/admin_settings/participant.php/{$find}' method='post'>
			<input type='hidden' name='find' value='{$find}'>
			<input id='submit_stop' type='submit' name='submit_stop' value='空爆を開始'>
		</form>
		";


?>


<h2>Total</h2>

<table id='total'>
	<thead>
		<tr>
			<th>男性</th>
			<th>女性</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<?php print "
					<span class='veryBig'>{$comp_m}</span>/({$total_m}) <span class='act'>Act:{$act_m}</span> <span class='wait'>Wait:{$wait_m}</span> <span class='can'>Can:{$cancel_m}</span>
				";?>
			</td>
			<td>
				<?php print "
					<span class='veryBig'>{$comp_w}</span>/({$total_w}) <span class='act'>Act:{$act_w}</span> <span class='wait'>Wait:{$wait_w}</span> <span class='can'>Can:{$cancel_w}</span>
				";?>
			</td>
		</tr>
	</tbody>
</table>


<h2>Table</h2>


<div id='participant'>
<!-- /////////////////////////////////[ 参加者テーブル ]///////////////////////////////// -->




<table id='participant_m'>
	<thead>
		<tr>
			<th colspan=10>男性</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="th01" colspan=2>申込</th>
			<th class="th02" colspan=2>入金</th>
			<th class="th03">組数</th>
			<th class="th04">名前</th>
			<th class="th05">ふりがな</th>
			<th class="th06">メモ欄</th>
			<th class="th07">メモ</th>
			<th class="th08">操作</th>

		</tr>

		<?php 

			$n = 1 ;

			while ($row = $table_m->fetch()) :



				/*レコードを表示する準備
				--------------------------------------*/


				$name = $row['name'];
				$memo = $row['memo'];

				$date = strtok($row['date'], ' ');
				$y = strtok($date, '-');
				$m = strtok('-');
				$d = strtok('-');


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
									<div class='clickField' id='shown_id{$n}'>
									<a id='id{$n}' class='click'></a>
									</div>

									<div id='hidden_id{$n}' class='manageButton'>
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
					<form action='//koikoi.co.jp/ikoiko/admin_settings/participant.php' method='post' accept-charset='utf-8'>
					<input type='hidden' name='localFind' value='{$find}' />
					<input type='hidden' name='find' value='{$find}' />
					<input type='hidden' name='name' value='{$name}' />
					<input type='hidden' name='memo' value='{$memo}' />
					<td class='td01'>{$m}/{$d}</td>
					<td class='td02'><span class='fontWeight_bold'>{$entry}</span></td>
					<td class='td03'><span class='fontWeight_bold'>{$state}</span></td>
					<td class='td04'>{$payment}</td>
					<td class='td05'><span class='fontWeight_bold'>{$pair}</span></td>
					<td class='td06'>{$name}</td>
					<td class='td07'>{$row['hurigana']}</td>
					<td class='td08'>{$memo}</td>
					<td class='td09'><input type='text' name='newMemo' value='' size=4 /><input type='submit' name='submit_memo' value='メモ' /></td>
					<td class='td10'>{$td10}</td>
					</form>
					</tr>
					";

			$n += 1 ;

			endwhile;

		?>



	</tbody>
</table>


<table id='participant_w'>
	<thead>
		<tr>
			<th colspan=10>女性</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="th01" colspan=2>申込</th>
			<th class="th02" colspan=2>入金</th>
			<th class="th03">組数</th>
			<th class="th04">名前</th>
			<th class="th05">ふりがな</th>
			<th class="th06">メモ欄</th>
			<th class="th07">メモ</th>
			<th class="th08">操作</th>

		</tr>

		<?php 

			$n = 101 ;

			while ($row = $table_w->fetch()) :




				$name = $row['name'];
				$memo = $row['memo'];

				$date = strtok($row['date'], ' ');
				$y = strtok($date, '-');
				$m = strtok('-');
				$d = strtok('-');



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
									<div class='clickField' id='shown_id{$n}'>
									<a id='id{$n}' class='click'></a>
									</div>

									<div id='hidden_id{$n}' class='manageButton'>
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



				$pair = $row['ninzu'] / 2 ;


				print 
					"
					<tr class='{$trClass}'>
					<form action='//koikoi.co.jp/ikoiko/admin_settings/participant.php' method='post' accept-charset='utf-8'>
					<input type='hidden' name='localFind' value='{$find}' />
					<input type='hidden' name='find' value='{$find}' />
					<input type='hidden' name='name' value='{$name}' />
					<input type='hidden' name='memo' value='{$memo}' />
					<td class='td01'>{$m}/{$d}</td>
					<td class='td02'><span class='fontWeight_bold'>{$entry}</span></td>
					<td class='td03'><span class='fontWeight_bold'>{$state}</span></td>
					<td class='td04'>{$payment}</td>
					<td class='td05'><span class='fontWeight_bold'>{$pair}</span></td>
					<td class='td06'>{$name}</td>
					<td class='td07'>{$row['hurigana']}</td>
					<td class='td08'>{$memo}</td>
					<td class='td09'><input type='text' name='newMemo' value='' size=4 /><input type='submit' name='submit_memo' value='メモ' /></td>
					<td class='td10'>{$td10}</td>
					</form>
					</tr>
					";

			$n += 1 ;

			endwhile;

		?>



	</tbody>
</table>




<!-- /////////////////////////////////[ /参加者テーブル ]///////////////////////////////// -->
</div>


<hr />

<p>申込者の管理ができます。</p>

<hr />



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