<?php 

//DBの初期化
require("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/db_data/db_init.php");
$db->query("SET NAMES utf8");

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>アニメコン-総動員数NO.1！株式会社KOIKOI</title>

	<meta name="description" content="アニメ好きの集まる街コンを全国で開催中。婚活・恋活はもちろん、オタクのお友達探しにもおススメです。完全着席形式で、マンガ・アニメ・ゲーム・コスプレ・声優など、好きなジャンルが一目でわかるプロフィールシート・マッチングゲームなどの工夫をしております。">

	<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/outputHead.php"); ?>

	<script type='text/javascript' src='//koikoi.co.jp/ikoiko/js/都道府県検索MB.js'></script>

	</script>

</head>
<body>

<div id="pageTop">
	
	<div id="inner">

		<ul>
			<li><a href="//koikoi.co.jp/ikoiko/">アニメコン<br />を探す　＞</a></li>
			<li><a href="//koikoi.co.jp/ikoiko/machi/">街コン　　<br />を探す　＞</a></li>
			<!--
			<li><a href="//koikoi.co.jp/ikoiko/nazo/">謎解きを探す　＞</a></li>
			<li><a href="//koikoi.co.jp/ikoiko/off/">オフ会を探す　＞</a></li>
			-->
		</ul>		

	</div>

	<h1 id="catch" style="background-color:#FF9933;">総動員数NO.1のアニメコン公式サイト オタクの友達作り・街コン・恋活・婚活パーティー</h1>

</div>

<div id="topContainer">
	
	<div id="pageHeader">
		
		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/pageHeader.php") ?>

	</div>

	<div id="mainVisual">
		
		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/ani/mainVisual.php") ?>

	</div>

	<div id="search">

		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/ani/search.php") ?>

	</div>

	<div id="mainContainer">
		<div id="mainContent">

			<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/ani/next10.php") ?>
			<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/new-article.ani.php") ?>


		</div>

		<div id="sideContent">
			
			<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/sideContent.php") ?>

		</div>
	</div>

</div>




<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/footer.php") ?>



</body>
</html>