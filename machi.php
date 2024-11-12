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

	<script type='text/javascript' src='//koikoi.co.jp/ikoiko/js/都道府県検索MB.js'></script>

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