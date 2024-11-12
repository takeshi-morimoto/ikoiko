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
	<title>楽しいイベント盛りだくさん</title>


	<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/outputHead.php") ?>

	<script type='text/javascript' src='//koikoi.co.jp/ikoiko/js/都道府県検索MB.js'></script>

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
</html>