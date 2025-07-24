<?php 

//送信された識別子と性別を取得
$pathInfo = strtok($_SERVER['PATH_INFO'], '/') ;
$find = strtok($pathInfo, '-') ;
$sex = strtok('-') ;


?>


<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>森本さん</title>


	<?php include("/home/users/0/sub.jp-p007/web/widgets/outputHead.php") ?>


</head>
<body>

<div id="pageTop">
	
	<div id="inner">

		<ul>
			<li><a href="">アニメコン</a></li>
			<li><a href="">街コン</a></li>
		</ul>		

	</div>

	<div id="catch">キャッチコピー</div>

</div>

<div id="topContainer">
	
	<div id="pageHeader">
		
		<img id="logo" src="//p007.sub.jp/img/logo.png" alt="" />

		<div id="contact">
			
				<div class="box_001"><span>カード決済対応開始いたしました。</span><img class="icon_card" src="//p007.sub.jp/img/icon/icon_card.png" alt="" /></div>

				<div class="box_002">
					<img class="icon_tel" src="//p007.sub.jp/img/icon/icon_tel.png" alt="" />
					<span>03-0000-0000</span>
				</div>

		</div>

	</div>

	<div id="mainVisual">
		
		<nav id="globalNavi">
			<ul>
				<li><a href=''>TOPページ<br /><small>Go Home</small></a></li>
				<li><a href=''>TOPページ<br /><small>Go Home</small></a></li>
				<li><a href=''>TOPページ<br /><small>Go Home</small></a></li>
				<li><a href=''>TOPページ<br /><small>Go Home</small></a></li>
				<li><a href=''>TOPページ<br /><small>Go Home</small></a></li>
				<li><a href=''>TOPページ<br /><small>Go Home</small></a></li>
			</ul>
		</nav>

		<div id="peculiarity">
			<h3>アニメコンの特徴</h3>
			<p>アニメコンはいいですよーアニメコンはいいですよーアニメコンはいいですよーアニメコンはいいですよーアニメコンはいいですよーアニメコンはいいですよーアニメコンはいいですよーアニメコンはいいですよーアニメコンはいいですよーアニメコンはいいですよー</p>
		</div>

	</div>

	<div id="mainContainer">
		<div id="mainContent">

			メインコンテンツ<br />
			
			<p>説明文とかいれますよねー</p>

			<div id="select_plan">
				<div><a href="">クレジットカードの方はこちら</a></div>
				<div><?php print "<a href='//p007.sub.jp/entry_set/entry/{$find}-{$sex}'>銀行振込の方はこちら</a>"; ?></div>
			</div>

			<p>説明文とかいろいろ書きますねぇ</p>

		</div>
		<div id="sideContent">サイドバー</div>
	</div>

</div>

	


</body>
</html>