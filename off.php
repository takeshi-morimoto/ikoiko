<?php 

//DBの初期化
require("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/db_data/db_init.php");
$db->query("SET NAMES utf8");

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>KOIKOI街コン｜オフ会・趣味の交流イベント</title>
	<meta name="viewport" content="width=device-width" />
	<meta name="description" content="共通の趣味を持つ仲間と出会えるオフ会イベントを全国で開催中。アニメ、ゲーム、漫画など、好きなものが同じ人たちと楽しく交流できます。初参加でも安心の少人数制。">

	<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/outputHead.php"); ?>
	
	<link rel="canonical" href="https:https://koikoi.co.jp/ikoiko/off/" />
	
	<!-- Open Graph -->
	<meta property="og:title" content="KOIKOI街コン｜オフ会・趣味の交流イベント" />
	<meta property="og:description" content="共通の趣味を持つ仲間と出会えるオフ会イベントを全国で開催中。好きなものが同じ人たちと楽しく交流。" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="https:https://koikoi.co.jp/ikoiko/off/" />
	<meta property="og:site_name" content="こいこい" />
	
	<!-- Twitter Card -->
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:title" content="KOIKOI街コン｜オフ会・趣味の交流イベント" />
	<meta name="twitter:description" content="共通の趣味を持つ仲間と出会えるオフ会イベント。初参加でも安心の少人数制。" />
	
	<!-- 構造化データ -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "WebPage",
		"name": "オフ会・趣味の交流イベント",
		"description": "共通の趣味を持つ仲間と出会えるオフ会イベントを全国で開催中",
		"url": "https:https://koikoi.co.jp/ikoiko/off/",
		"breadcrumb": {
			"@type": "BreadcrumbList",
			"itemListElement": [{
				"@type": "ListItem",
				"position": 1,
				"name": "ホーム",
				"item": "https:https://koikoi.co.jp/ikoiko/"
			},{
				"@type": "ListItem",
				"position": 2,
				"name": "オフ会",
				"item": "https:https://koikoi.co.jp/ikoiko/off/"
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

	<h1 id="catch" style="background-color:#0ACEFF;">オフ会しましょうねーオフ会ですよー(´・ω・｀)</h1>

</div>

<div id="topContainer">
	
	<div id="pageHeader">
		
		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/pageHeader.php") ?>

	</div>

	<div id="mainVisual">
		
		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/off/mainVisual.php") ?>

	</div>

	<div id="search">

		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/off/search.php") ?>

	</div>

	<div id="mainContainer">
		<div id="mainContent">

			<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/off/next10.php") ?>


		</div>

		<div id="sideContent">
			
			<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/sideContent.php") ?>

		</div>
	</div>

</div>




<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/footer.php") ?>



</body>
</html>