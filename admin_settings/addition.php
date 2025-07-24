<?php 

//Noticeエラーを非表示にする
error_reporting(E_ALL & ~E_NOTICE);

//データベースの初期化
require_once("../db_data/db_init.php");
$db->query("SET NAMES utf8");


//参加者情報が入力された場合
if ( isset($_POST['submit_enter']) ):

	$toDb = array_values($_POST);

	$area = array( substr( $toDb[0] , 0 , strlen($toDb[0]) - 4 ) ) ;

	array_splice($toDb, 1 , 0 , $area);
	$toDb[9] = (int)$toDb[9];

  $ps = $db->query("select events.date as 'date' , area.area_ja as 'area_ja' 
                        from events 
                        join area using(area)
                        where find = '{$toDb[0]}' ;");

  $tmp = $ps->fetch() ;

  list($date,$area_ja) = $tmp ;
  $event = $date . ' ' . $area_ja ;

  array_splice($toDb, 2 , 0 , $event);



	print_r($toDb);


	//プリペアドステートメントの準備
	$ps = $db->prepare("
	      insert into customers (`find`,`area`,`event`,`sex`,`entry`,`name`,`hurigana`,`age`,`mail`,`tel`,`ninzu`,`date`) 
	      values (?,?,?,?,?,?,?,?,?,?,?,?);
	      ");


	//For文でバインドしてSQL文を完成
	for ($n = 0 ; $n <= 11 ; $n += 1):
	$ps->bindParam( $n + 1 , $toDb[$n]);
	endfor;

	//インサート文を実行
	$res = $ps->execute() or die('データベースへの挿入に失敗しました。');

	$infoMess = "<h3>{$toDb[5]}さんの登録が完了しました。</h3>";




endif;


if ( isset($_POST['submit_else']) ):

$find = ( empty($_POST['find_input']) ) ? $_POST['find_select'] : $_POST['find_input'] ;

else:

$find = $_POST['find'];

endif;



?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>参加者手動登録</title>
</head>
<body>


<p style="font-size:40;"><a href="//koikoi.co.jp/ikoiko/admin_settings/admin.php">コントロールパネルトップにもどる</a></p>
<?php print $infoMess; ?>
<h3>現在選択中の開催地コード： <?php print $find; ?></h3>
	

<?php if ( empty($find) ): ?>


<form action="addition.php" method='post'>

	開催地を選択してください。
	<select name="find_select">
		<option value="">選択してください</option>


		<?php 

			$today = date('Y-m-j') ;

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

	<input type="text" name="find_input" value="" size=10 />

	<input type="submit" name="submit_else" value="送信" />

</form>

<?php else: ?>

<form action="addition.php" method='post'>

	・別の開催地を選択
	<select name="find_select">
		<option value="">選択してください</option>


		<?php 

			$today = date('Y-m-j') ;

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

	<input type="text" name="find_input" value="" size=10 />

	<input type="submit" name="submit_else" value="送信" />

</form>


<hr />


<form action="addition.php" method="post">

	<?php print "<input type='hidden' name='find' value='{$find}' />" ?>

	<div class="center">


    <table id='entryForm'>

      <tr>
        <th>
          <span>性別</span><br />
        </th>
        <td>
        	<input type="radio" name="sex" value="m" checked='checked' />男性
        	<input type="radio" name="sex" value="w" />女性
        </td>
      </tr>
      <tr>
        <th>
          <span>申込State</span><br />
        </th>
        <td>
        	<input type="radio" name="entry" value="1" checked='checked' />通常
        	<input type="radio" name="entry" value="2" />早割り
        	<input type="radio" name="entry" value="3" />キャンセル待ち
        </td>
      </tr>
      <tr>
        <th>
          <span>名前</span><br />
        </th>
        <td>
          <input type="text" name="name" size="15"   value="" />
        </td>
      </tr>
      <tr>
        <th>
          <span>名前ふりがな</span><br />
          ※全角ひらがな
        </th>
        <td>
          <input type="text" name="name_kana" size="15"   value="" />
        </td>
      </tr>
      <tr>
        <th>
          <span>年齢</span>
        </th>
        <td>
          <input type="text" name="age" size="15"   value="" />
        </td>
      </tr>
      <tr>
        <th>
          <span>メールアドレス</span>
        </th>
        <td>
          <input type="text" name="mail_1" size="30"   value="" />
        </td>
      </tr>
      <tr>
        <th>
          <span>携帯電話番号</span><br />
        </th>
        <td>
          <input type="text" name="tell" size="30"   value="" />
        </td>
      </tr>
      <tr>
        <th>
          <span>参加人数</span><br />
        </th>
        <td>
          <ul>
          <li><label>
            <input type="radio" name="ninzu" value="2名" checked='checked' />２名
          </label></li>
          <li><label>
            <input type="radio" name="ninzu" value="4名" />４名
          </label></li>
          <li><label>
            <input type="radio" name="ninzu" value="6名" />６名
          </label></li>
          </ul>
        </td>
      </tr>
      <tr>
        <th>
          <span>登録日（空欄可）</span><br />
          ※書式注意[xxxx-mm-dd]
        </th>
        <td>
          <input type="text" name="entry_date" size="30"   value="2014-" />
        </td>
      </tr>

    </table>

    <input type="submit" name="submit_enter" value="確認する">

  </div>

</form>


<?php endif; ?>



</body>
</html>


