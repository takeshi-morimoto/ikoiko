<?php 

//Noticeエラーを非表示にする
error_reporting(E_ALL & ~E_NOTICE);

//DBの初期化
require_once("./db_data/db_init.php");
$db->query("SET NAMES utf8");

$area = strtok($_SERVER['PATH_INFO'], "/");

//このページで使うデータをDBから読み込み
$pageDataTmp = $db->query("select * from area where area = '$area' ;") ;
$pageData = $pageDataTmp->fetch();

//適切な変数に読み込んだデータを格納
list($a,$page,$area,$area_ja,$ken,$place,$price_h,$price_l,$age_m,$age_w,$free_text1,$free_text2,$content) = $pageData ;
$price_h_m = strtok($price_h, "/");
$price_h_w = strtok("/");
$price_l_m = strtok($price_l, "/");
$price_l_w = strtok("/");
$age_l_m = strtok($age_m, "/");
$age_h_m = strtok("/");
$age_l_w = strtok($age_w, "/");
$age_h_w = strtok("/");

$ps = $db->prepare("select text from content where num = {$content};");
$ps->execute();
$row = $ps->fetch();
$content = $row['text'];

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8" />
<title><?php echo $area_ja; ?>のイベント情報｜KOIKOI街コン</title>
<meta name="description" content="<?php echo $area_ja; ?>で開催される街コン・アニメコン・謎解きイベントの情報。参加費<?php echo number_format($price_l_m); ?>円〜、<?php echo $age_l_m; ?>歳〜<?php echo $age_h_m; ?>歳対象。完全着席形式で安心して参加できます。">

<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/outputHead.php") ?>

<link rel="canonical" href="https://koikoi.co.jp/ikoiko/event/<?php echo $area; ?>/" />

<!-- Open Graph -->
<meta property="og:title" content="<?php echo $area_ja; ?>のイベント情報｜KOIKOI街コン" />
<meta property="og:description" content="<?php echo $area_ja; ?>で開催される街コン・アニメコン・謎解きイベントの情報。" />
<meta property="og:type" content="website" />
<meta property="og:url" content="https://koikoi.co.jp/ikoiko/event/<?php echo $area; ?>/" />
<meta property="og:site_name" content="こいこい" />

<!-- Twitter Card -->
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="<?php echo $area_ja; ?>のイベント情報｜KOIKOI街コン" />
<meta name="twitter:description" content="<?php echo $area_ja; ?>で開催される街コン・アニメコンの情報。" />

	<!-- 構造化データ -->
	<!-- 構造化データ - Event -->
<script type="application/ld+json">
{
"@context": "https://schema.org",
"@type": "Event",
"name": "<?php echo $area_ja; ?>の街コン・アニメコンイベント",
"description": "<?php echo strip_tags($free_text1); ?>",
"location": {
"@type": "Place",
"name": "<?php echo $area_ja; ?>",
"address": "<?php echo $ken; ?>"
},
"offers": {
"@type": "Offer",
"price": "<?php echo $price_l_m; ?>",
"priceCurrency": "JPY"
		},
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
				"name": "<?php echo $area_ja; ?>",
				"item": "https://koikoi.co.jp/ikoiko/event/<?php echo $area; ?>/"
			}]
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
		},{
			"@type": "ListItem",
			"position": 2,
			"name": "<?php echo $area_ja; ?>",
			"item": "https://koikoi.co.jp/ikoiko/event/<?php echo $area; ?>/"
		}]
	}
	</script>

	<script type='text/javascript' src='//koikoi.co.jp/ikoiko/js/都道府県検索MB.js'></script>
	<script type='text/javascript' src='https://koikoi.co.jp/ikoiko/js/prefecture-search-mb.js'></script>

</head>
<body>

<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/pageTop.php") ?>

<div id="topContainer">

<div id="pageHeader">

<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/pageHeader.php") ?>

</div>

<div id="mainVisual">

<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/{$page}/mainVisual.php") ?>

</div>

<div id="mainContainer">
<div id="mainContent" class="general">

<?php print $free_text1 ; ?>

<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/entryButton.php") ?>

<?php print $content ; ?>

<?php print $free_text2 ; ?>			

<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/entryButton.php") ?>


</div>

<div id="sideContent">

<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/sideContent.php") ?>

</div>
</div>

</div>

<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/footer.php") ?>

</body>