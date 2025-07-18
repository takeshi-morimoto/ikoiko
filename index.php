<?php 

//DBの初期化
require("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/db_data/db_init.php");
$db->query("SET NAMES utf8");

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>アニメコン・オタク街コン【公式】全国開催 | KOIKOI</title>

	<meta name="description" content="アニメ・マンガ好きのための街コン＆婚活パーティー！全国主要都市で毎週開催中。完全着席形式で初参加でも安心。共通の趣味で繋がる恋活・婚活・友活イベント。20代〜40代まで幅広い年齢層が参加中。">
	<!-- Google Search Console 確認用 - 確認後は削除可能 -->
	<!-- <meta name="google-site-verification" content="ここにGoogle提供のコードを入力" /> -->

	<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/outputHead.php"); ?>
	
	<!-- モダンCSS追加 -->
	<link rel="stylesheet" href="css/modern-base.css">
	<link rel="stylesheet" href="css/modern-components.css">
	<link rel="stylesheet" href="css/responsive.css">

	<link rel="canonical" href="https://koikoi.co.jp/ikoiko/" />
	
	<!-- Open Graph -->
	<meta property="og:title" content="アニメコン-総動員数NO.1！株式会社KOIKOI" />
	<meta property="og:description" content="アニメ好きの集まる街コンを全国で開催中。婚活・恋活はもちろん、オタクのお友達探しにもおススメです。" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="https://koikoi.co.jp/ikoiko/" />
	<meta property="og:site_name" content="こいこい" />
	<meta property="og:image" content="https://koikoi.co.jp/ikoiko/img/ogp/main-visual.jpg" />
	<meta property="og:image:width" content="1200" />
	<meta property="og:image:height" content="630" />
	
	<!-- Twitter Card -->
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:site" content="@machikonkoikoi" />
	<meta name="twitter:title" content="アニメコン-総動員数NO.1！株式会社KOIKOI" />
	<meta name="twitter:description" content="アニメ好きの集まる街コンを全国で開催中。婚活・恋活はもちろん、オタクのお友達探しにもおススメです。" />
	<meta name="twitter:image" content="https://koikoi.co.jp/ikoiko/img/ogp/main-visual.jpg" />
	
	<!-- 構造化データ -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "Organization",
		"name": "株式会社KOIKOI",
		"url": "https://koikoi.co.jp/ikoiko/",
		"description": "アニメ好きの集まる街コンを全国で開催中",
		"sameAs": [
			"https://twitter.com/machikonkoikoi"
		],
		"logo": {
			"@type": "ImageObject",
			"url": "https://koikoi.co.jp/ikoiko/img/common/icon/logo.png"
		}
	}
	</script>
	
	<!-- 構造化データ - パンくずリスト -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "BreadcrumbList",
		"itemListElement": [{
			"@type": "ListItem",
			"position": 1,
			"name": "ホーム",
			"item": "https://koikoi.co.jp/ikoiko/"
		}]
	}
	</script>

	<script type='text/javascript' src='https://koikoi.co.jp/ikoiko/js/prefecture-search-mb.js'></script>

</head>
<body>

<div id="pageTop">
	
	<div id="inner">

		<ul>
			<li><a href="https://koikoi.co.jp/ikoiko/">アニメコン<br />を探す　＞</a></li>
			<li><a href="https://koikoi.co.jp/ikoiko/machi/">街コン　　<br />を探す　＞</a></li>
			<!--
			<li><a href="https://koikoi.co.jp/ikoiko/nazo/">謎解きを探す　＞</a></li>
			<li><a href="https://koikoi.co.jp/ikoiko/off/">オフ会を探す　＞</a></li>
			-->
		</ul>		

	</div>

	<h1 id="catch" style="background-color:#FF9933;">アニメコン公式サイト オタクの友達作り・街コン・恋活・婚活パーティー</h1>

</div>

<div id="topContainer">
	
	<div id="pageHeader">
		
		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/pageHeader.php") ?>

	</div>

	<div id="mainVisual">
		
		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/ani/mainVisual.php") ?>

	</div>

	<section id="search">

		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/ani/search.php") ?>

	</section>

	<div id="mainContainer">
		<main id="mainContent">
			<!-- カード型レイアウトを適用 -->
			<section class="card">
				<h2 class="card-header">近日開催のイベント</h2>
				<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/ani/next10.php") ?>
			</section>
			
			<section class="card mt-4">
				<h2 class="card-header">最新のお知らせ</h2>
				<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/new-article.ani.php") ?>
			</section>

		</main>

		<aside id="sideContent">
			
			<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/sideContent.php") ?>

		</aside>
	</div>

</div>




<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/footer.php") ?>



</body>
</html>