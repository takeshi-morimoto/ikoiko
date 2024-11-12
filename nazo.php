
<?php 

//DBの初期化
require("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/db_data/db_init.php");
$db->query("SET NAMES utf8");

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>KOIKOI街コン｜謎解き</title>
	<meta name="viewport" content="width=device-width" />


	<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/outputHead.php"); ?>

	<script type='text/javascript' src='//koikoi.co.jp/ikoiko/js/都道府県検索MB.js'></script>

</head>
<body>

<div id="pageTop">
	
	<div id="inner">

		<ul>
			<li><a href="//koikoi.co.jp/ikoiko/">アニメコン</a></li>
			<li><a href="//koikoi.co.jp/ikoiko/machi/">街コン</a></li>
			<li><a href="//koikoi.co.jp/ikoiko/nazo/">謎解き</a></li>
			<li><a href="//koikoi.co.jp/ikoiko/off/">オフ会</a></li>
		</ul>		

	</div>

	<h1 id="catch" style="background-color:#4B7FCD;">謎解きコンですよー(´・ω・｀)</h1>

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

			<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/nazo/next10.php") ?>


		</div>

		<div id="sideContent">
			
			<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/sideContent.php") ?>

		</div>
	</div>

</div>




<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/footer.php") ?>



</body>
</html>