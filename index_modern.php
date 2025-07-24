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
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<!-- モダンCSS -->
	<link rel="stylesheet" href="css/modern-base.css">
	<link rel="stylesheet" href="css/modern-components.css">
	
	<!-- 既存のCSS（段階的に移行） -->
	<link rel="stylesheet" href="css/onepcssgrid.css">
	<link rel="stylesheet" href="css/globalMenu.css">
	<link rel="stylesheet" href="css/footer.css">
	
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

	<script src="https://koikoi.co.jp/ikoiko/js/jquery-3.7.1.min.js"></script>
	<script src="https://koikoi.co.jp/ikoiko/js/jquery-migrate-3.4.1.min.js"></script>
	<script src="https://koikoi.co.jp/ikoiko/js/prefecture-search-mb.js"></script>
	
	<!-- モダンJavaScript -->
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		// スクロール時のヘッダー縮小
		const header = document.getElementById('pageHeader');
		window.addEventListener('scroll', function() {
			if (window.scrollY > 50) {
				header.classList.add('scrolled');
			} else {
				header.classList.remove('scrolled');
			}
		});
		
		// モバイルメニュートグル
		const menuToggle = document.querySelector('.mobile-menu-toggle');
		const navMenu = document.querySelector('.nav-menu');
		if (menuToggle) {
			menuToggle.addEventListener('click', function() {
				navMenu.classList.toggle('active');
			});
		}
	});
	</script>
</head>
<body>

<!-- スキップリンク（アクセシビリティ） -->
<a href="#mainContent" class="skip-link">メインコンテンツへスキップ</a>

<div class="page-wrapper">
	
	<!-- ヘッダー -->
	<header id="pageHeader">
		<div class="container">
			<a href="/ikoiko/" class="logo-link">
				<img id="logo" src="/ikoiko/img/common/icon/logo.png" alt="KOIKOI アニメコン・街コン公式サイト" loading="lazy">
			</a>
			
			<nav class="nav-menu" role="navigation" aria-label="メインナビゲーション">
				<a href="/ikoiko/" class="nav-link">アニメコン</a>
				<a href="/ikoiko/machi/" class="nav-link">街コン</a>
				<a href="/ikoiko/list_1/" class="nav-link">開催スケジュール</a>
				<a href="/ikoiko/contact/" class="nav-link">お問い合わせ</a>
			</nav>
			
			<button class="mobile-menu-toggle" aria-label="メニューを開く">
				☰
			</button>
		</div>
	</header>

	<!-- メインビジュアル -->
	<section class="hero-section">
		<div class="container">
			<h1 class="hero-title">アニメ・マンガ好きが集まる街コン</h1>
			<p class="hero-subtitle">共通の趣味で繋がる、新しい出会いがここに</p>
			<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/ani/mainVisual.php") ?>
		</div>
	</section>

	<!-- 検索セクション -->
	<section class="search-section">
		<div class="container">
			<h2 class="text-center mb-3">イベントを探す</h2>
			<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/ani/search.php") ?>
		</div>
	</section>

	<!-- メインコンテンツ -->
	<main id="mainContent" class="main-content">
		<div class="container">
			<div class="row">
				<div class="col" style="flex: 0 0 70%;">
					<!-- 次回開催イベント -->
					<section class="upcoming-events card">
						<h2 class="card-header">近日開催のイベント</h2>
						<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/ani/next10.php") ?>
					</section>
					
					<!-- お知らせ -->
					<section class="news-section card mt-4">
						<h2 class="card-header">最新のお知らせ</h2>
						<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/new-article.ani.php") ?>
					</section>
				</div>
				
				<aside class="col" style="flex: 0 0 30%;">
					<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/sideContent.php") ?>
				</aside>
			</div>
		</div>
	</main>

</div>

<!-- フッター -->
<footer id="footer">
	<div class="container">
		<div class="footer-content">
			<div class="footer-section">
				<h3>KOIKOIについて</h3>
				<ul class="footer-links">
					<li><a href="/ikoiko/about/">会社概要</a></li>
					<li><a href="/ikoiko/privacy/">プライバシーポリシー</a></li>
					<li><a href="/ikoiko/terms/">利用規約</a></li>
				</ul>
			</div>
			<div class="footer-section">
				<h3>イベント情報</h3>
				<ul class="footer-links">
					<li><a href="/ikoiko/">アニメコン</a></li>
					<li><a href="/ikoiko/machi/">街コン</a></li>
					<li><a href="/ikoiko/list_1/">開催スケジュール</a></li>
				</ul>
			</div>
			<div class="footer-section">
				<h3>お問い合わせ</h3>
				<ul class="footer-links">
					<li><a href="tel:03-6754-6371">03-6754-6371</a></li>
					<li><a href="/ikoiko/contact/">お問い合わせフォーム</a></li>
				</ul>
			</div>
		</div>
		<div class="footer-bottom">
			<p>&copy; 2024 株式会社KOIKOI. All rights reserved.</p>
		</div>
	</div>
</footer>

</body>
</html>