<?php 

//Noticeエラーを非表示にする
error_reporting(E_ALL & ~E_NOTICE);

// 検索されたクエリの最適化
$pat = '/^2[0-9]{3}-[0-9]{1,2}-[0-9]{1,2}$/';
$tok = strtok($_SERVER['PATH_INFO'], "/");

if ( preg_match( $pat,$tok ) ):

	$searchDate = $tok;

else:

	$searchArea = $tok;

endif;

// 2個めのクエリがある場合は処理を実行
$tok2 = strtok('/');

if ( !empty($tok2) ):

	if ( preg_match( $pat,$tok2 ) ):

		$searchDate = $tok2;

	else:

		$searchArea = $tok2;

	endif;

endif;


// where句の設定
if ( empty($searchArea) ):

	$where_1 = ' 1 = 1 ';

else:

	$where_1 = " area.ken = '{$searchArea}' ";

endif;

if ( empty($searchDate) ):

    $today = date("Y") . '-' . date("m") . '-' . date("j") ;	
	$where_2 = " and events.date >= '{$today}' ";

else:

	$where_2 = " and events.date = '{$searchDate}' ";

endif;

//DBの初期化
require("./db_data/db_init.php");
$db->query("SET NAMES utf8");

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>楽しいイベント盛りだくさん</title>


	<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/outputHead.php") ?>

	<script type='text/javascript' src='https://koikoi.co.jp/ikoiko/js/prefecture-search-mb.js'></script>

</head>
<body>

<div id="pageTop">
	
	<div id="inner">

		<ul>
			<li><a href="https://koikoi.co.jp/ikoiko/">アニメコン</a></li>
			<li><a href="https://koikoi.co.jp/ikoiko/machi/">街コン</a></li>
			<li><a href="https://koikoi.co.jp/ikoiko/nazo/">謎解き</a></li>
			<li><a href="https://koikoi.co.jp/ikoiko/off/">オフ会</a></li>
		</ul>		

	</div>

	<h1 id="catch" style="background-color:#4B7FCD;">普通の街コンですよー(´・ω・｀)</h1>

</div>

<div id="topContainer">
	
	<div id="pageHeader">
		
		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/pageHeader.php") ?>

	</div>

	<div id="mainVisual">
		
		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/nazo/mainVisual.php") ?>

	</div>

	<div id="search">
		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/nazo/search.php") ?>
	</div>

	<div id="mainContainer">
		
		<div id="mainContent">
			
			<div id="searchResult">

			    <?php 

			    $today = date("Y") . '-' . date("m") . '-' . date("j") ;

			    $result = $db->query("
			                  select events.find , events.date , events.week , events.begin , events.end, events.pr_comment , area.page , area.place , area.price_h , area.area , area.area_ja 
			                  from events join area using(area)
			                  where {$where_1} {$where_2} and area.page = 'nazo' 
			                  order by events.date;" );

			    if ( empty($searchArea) ):

			    	$searchArea = '全国';

			    endif;

			    print 
			        "
			        <h2>{$searchArea}のイベント情報</h2>
			        <div id='resultList'>
			        ";

			    while ( $row = $result->fetch() ):

			      list( $find, $date, $week, $begin, $end, $pr_comment, $page, $place, $price_h, $area, $area_ja ) = $row ;

			      //$dateから日付データを年、月、日に分割
			          $y = strtok($date, '-');
			          $m = strtok('-');
			          $d = strtok('-');

			          //開始と終了時刻を時、分に分割
			          $begin_H = strtok($begin, ':');
			          $begin_M = strtok(':');

			          $end_H = strtok($end, ':');
			          $end_M = strtok(':');

			          //男女別の通常と早割の価格を個別に分割
			          $price_m = strtok($price_h, '/');
			          $price_w = strtok('/');

			          switch ( $page ):

			            case "machi" :

			              $eventType = 'こいこい街コン in' ;
			              break;

			            case "ani" :

			              $eventType = 'アニメコン' ;
			              break;

			            case "nazo" :

			              $eventType = '謎解きコン' ;
			              break;

			            case "off" :

			              $eventType = '' ;
			              break;


			          endswitch;

			      print 

			      "
			      <div class='event'>

			            <img class='eventImg' src='https://koikoi.co.jp/ikoiko/img/img_thamb/{$find}' alt='' />

			            <div class='eventInfo'>
							<span class='place'>{$place}</span>
							<span class='eventName'><a href='https://koikoi.co.jp/ikoiko/event/{$area}'>{$eventType} {$area_ja}</a></span>
							<span class='dateTime'>{$m}月{$d}日({$week}){$begin_H}:{$begin_M}-{$end_H}:{$end_M}</span>
							<span class='price'>男性 {$price_m}円　女性 {$price_w}円</span>
			            </div>

			            <p class='prComment'>
			              <b>イチ押しポイント</b><br />
			              {$pr_comment}
			            </p>

			      </div>
			      ";

			    endwhile;

			    print "</div>";

			    ?>

			</div>

		</div>

		<div id="sideContent">
			
			<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/sideContent.php") ?>

		</div>

	</div>



</div>

<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/footer.php") ?>


</body>
</html>