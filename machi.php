<?php 

//DBの初期化
require("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/db_data/db_init.php");
$db->query("SET NAMES utf8");

?>


<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>KOIKOI街コン｜街コンを探す</title>

	<meta name="description" content="街コンを全国で開催中。婚活・恋活はもちろん、お友達探しにもおススメです。完全着席形式で、美味しいお食事、ドリンクを楽しみながら出会えます。プロフィールシート・マッチングゲームなどの出会いをお手伝いする仕組みもご用意しております。">


	<meta name="viewport" content="width=device-width" />


	<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/outputHead.php"); ?>
	
	<link rel="canonical" href="https://koikoi.co.jp/ikoiko/machi/" />
	
	<!-- Open Graph -->
	<meta property="og:title" content="KOIKOI街コン｜街コンを探す" />
	<meta property="og:description" content="街コンを全国で開催中。婚活・恋活はもちろん、お友達探しにもおススメです。完全着席形式で出会いをサポート。" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="https://koikoi.co.jp/ikoiko/machi/" />
	<meta property="og:site_name" content="こいこい" />
	
	<!-- Twitter Card -->
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:title" content="KOIKOI街コン｜街コンを探す" />
	<meta name="twitter:description" content="街コンを全国で開催中。婚活・恋活はもちろん、お友達探しにもおススメです。" />
	
	<!-- 構造化データ -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "WebPage",
		"name": "KOIKOI街コン",
		"description": "街コンを全国で開催中",
		"url": "https://koikoi.co.jp/ikoiko/machi/",
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
				"name": "街コン",
				"item": "https://koikoi.co.jp/ikoiko/machi/"
			}]
		}
	}
	</script>

	<script type='text/javascript' src='https://koikoi.co.jp/ikoiko/js/prefecture-search-mb.js'></script>

</head>
<body class="machi">

<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/pageTop_m.php") ?>

<div id="topContainer">
	
	<div id="pageHeader">
		
		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/pageHeader_m.php") ?>

	</div>

	<div id="mainVisual">
		
		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/machi/mainVisual.php") ?>

	</div>

	<div id="search">

		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/machi/search.php") ?>

	</div>

	<div id="mainContainer">
		<div id="mainContent">

			<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/machi/next10.php") ?>
			<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/new-article.machi.php") ?>
		</div>

		<div id="sideContent">
			
			<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/sideContent_m.php") ?>

		</div>
	</div>

</div>




<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/footer.php") ?>



</body>
</html>