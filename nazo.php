
<?php 

//DBの初期化
require("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/db_data/db_init.php");
$db->query("SET NAMES utf8");

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>KOIKOI街コン｜謎解きイベント・謎解きコン</title>
	<meta name="viewport" content="width=device-width" />
	<meta name="description" content="謎解きイベント・謎解きコンを全国で開催中。推理力と協力が試される謎解きゲームを楽しみながら、新しい出会いを見つけませんか？初心者でも安心して参加できます。">

	<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/outputHead.php"); ?>
	
	<link rel="canonical" href="https://koikoi.co.jp/ikoiko/nazo/" />
	
	<!-- Open Graph -->
	<meta property="og:title" content="KOIKOI街コン｜謎解きイベント・謎解きコン" />
	<meta property="og:description" content="謎解きイベント・謎解きコンを全国で開催中。推理力と協力が試される謎解きゲームを楽しみながら、新しい出会いを。" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="https://koikoi.co.jp/ikoiko/nazo/" />
	<meta property="og:site_name" content="こいこい" />
	
	<!-- Twitter Card -->
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:title" content="KOIKOI街コン｜謎解きイベント・謎解きコン" />
	<meta name="twitter:description" content="謎解きゲームを楽しみながら新しい出会いを。初心者でも安心して参加できます。" />
	
	<!-- 構造化データ -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "WebPage",
		"name": "謎解きイベント・謎解きコン",
		"description": "謎解きイベント・謎解きコンを全国で開催中",
		"url": "https://koikoi.co.jp/ikoiko/nazo/",
		"breadcrumb": {
			"@type": "BreadcrumbList",
			"itemListElement": [{
				"@type": "ListItem",
				"position": 1,
				"name": "ホーム",
				"item": "https://koikoi.co.jp/ikoiko/"
			},{
				"@type": "ListItem",
				"position": 2,
				"name": "謎解き",
				"item": "https://koikoi.co.jp/ikoiko/nazo/"
			}]
		}
	}
	</script>

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