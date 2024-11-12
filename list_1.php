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
require("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/db_data/db_init.php");
$db->query("SET NAMES utf8");

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>楽しいイベント盛りだくさん</title>


	<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/outputHead.php") ?>

	<script type='text/javascript' src='//koikoi.co.jp/ikoiko/js/都道府県検索MB.js'></script>

	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-MQ4VFQRSYR"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'G-MQ4VFQRSYR');
	</script>

</head>
<body>

<div id="pageTop">
	
	<div id="inner">

		<ul>
			<li><a href="//koikoi.co.jp/ikoiko/">アニメコン</a></li>
			<li><a href="//koikoi.co.jp/ikoiko/machi/">街コン</a></li>
			<!--
			<li><a href="//koikoi.co.jp/ikoiko/nazo/">謎解き</a></li>
			<li><a href="//koikoi.co.jp/ikoiko/off/">オフ会</a></li>
			-->
		</ul>		

	</div>

	<h1 id="catch" style="background-color:#ff9933;">総動員数NO.1のアニメコン公式サイト オタクの友達作り・街コン・恋活・婚活パーティー</h1>

</div>

<div id="topContainer">
	
	<div id="pageHeader">
		
		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/pageHeader.php") ?>

	</div>

	<div id="mainVisual">
		
		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/ani/mainVisual.php") ?>

	</div>

	<div id="search">
		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/ani/search.php"); ?>
	</div>

	<div id="mainContainer">
		
		<div id="mainContent">
			
			<div id="searchResult">

			    <?php 

			    $today = date("Y") . '-' . date("m") . '-' . date("j") ;

			    $result = $db->query("
				select events.find, events.title, events.date , events.week , events.begin , events.end, events.pr_comment , area.page, area.place, events.price_m, events.price_f, area.price_h, area.area, area.area_ja, area.content , events.img_url , events.feature
				from events join area using(area)
			                  where {$where_1} {$where_2} and area.page = 'ani' 
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

					list( $find, $title, $date, $week, $begin, $end, $pr_comment, $page, $place, $price_m, $price_f, $price_h, $area, $area_ja, $content, $img_url ,$feature) = $row ;

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
					$area_price_m = strtok($price_h, '/');
					$area_price_f = strtok('/');

					// 金額が未設定ならエリアのデータを適用
					if ( empty($price_m) ) { $price_m = $area_price_m; }
					if ( empty($price_f) ) { $price_f = $area_price_f; }

				    $result2 = $db->query("select name from content where num = {$content}");

				    $tmp = $result2->fetch();
				    $eventType = $tmp['name'];

				    // タイトルが未設定の場合はイベントタイプとエリアを基に生成
					if ( empty($title) ) {
						$title = $eventType . $area_ja;
					}

					// 画像URLが未設定の場合は旧の処理
					if ( empty($img_url) ) {
						$img_url = "/ikoiko/img/img_thamb/{$find}";
					}

			      print 

			      "
				  <div class='event'>
				  <div class='image-box'>
					<a href='//koikoi.co.jp/ikoiko/event/{$area}'>
					  <img src='{$img_url}' alt=''> 
					  <p>{$feature}</p>
					</a>
				  </div>
		
				  <div class='eventInfo-box'>
					  <!--
					  <span class='place'>{$place}</span>
					  -->
					  <span class='eventName'><a href='//koikoi.co.jp/ikoiko/event/{$area}'>{$title}</a></span>
					  <span class='dateTime'>{$m}月{$d}日({$week}){$begin_H}:{$begin_M}-{$end_H}:{$end_M}</span>
					  <span class='price'>男性 {$price_m}円　女性 {$price_f}円</span>
				  </div>
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