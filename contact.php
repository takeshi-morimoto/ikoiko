<?php 

if ( isset($_POST['submit']) ):

	$PP = 1;

else: 

	$PP = 0;

endif;


if( $PP === 1 ):


	//管理者と申込者にメールを送信

	//メール送信の準備
	mb_language("japanese");
	mb_internal_encoding("utf-8");

	//メールを送信するデータを変数にセット
	$from = 

		'from:' . 
		mb_encode_mimeheader('こいこい街コン事務局') . 
		'<machikon@koikoi.co.jp>';


	//管理者宛メールの件名を設定
	$sub_admin = "問い合わせがありました。" ;

	//管理者宛メールの本文を設定
	$mess_admin = "問い合わせがありました。\n\n名前：{$_POST['name']}\nアドレス：{$_POST['mail']}\n\n内容：\n{$_POST['content']}";

	$sub_cus = "お問い合わせを受け付けました。" ;

	$mess_cus = "お問い合わせありがとうございました。\n\n内容：\n{$_POST['content']}";

	mb_send_mail("machikon@koikoi.co.jp", $sub_admin, $mess_admin, $from);
	mb_send_mail($_POST['mail'], $sub_cus, $mess_cus, $from);

endif;

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>お問い合わせ｜KOIKOI街コン</title>
	<meta name="description" content="KOIKOI街コン・アニメコンに関するお問い合わせはこちらから。イベントに関するご質問、ご要望など、お気軽にお問い合わせください。">

	<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/outputHead.php") ?>
	
	<!-- モダンCSS追加 -->
	<link rel="stylesheet" href="css/modern-base.css">
	<link rel="stylesheet" href="css/modern-components.css">
	<link rel="stylesheet" href="css/responsive.css">
	<link rel="stylesheet" href="css/news-fix.css">
	<link rel="stylesheet" href="css/layout-spacing.css">
	
	<link rel="canonical" href="https://koikoi.co.jp/ikoiko/contact.php" />
	
	<!-- Open Graph -->
	<meta property="og:title" content="お問い合わせ｜KOIKOI街コン" />
	<meta property="og:description" content="KOIKOI街コン・アニメコンに関するお問い合わせはこちらから。お気軽にご連絡ください。" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="https://koikoi.co.jp/ikoiko/contact.php" />
	<meta property="og:site_name" content="こいこい" />
	
	<!-- Twitter Card -->
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:title" content="お問い合わせ｜KOIKOI街コン" />
	<meta name="twitter:description" content="KOIKOI街コン・アニメコンに関するお問い合わせはこちらから。" />
	
	<!-- 構造化データ -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "ContactPage",
		"name": "お問い合わせ",
		"description": "KOIKOI街コン・アニメコンに関するお問い合わせページ",
		"url": "https://koikoi.co.jp/ikoiko/contact.php",
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
				"name": "お問い合わせ",
				"item": "https://koikoi.co.jp/ikoiko/contact.php"
			}]
		}
	}
	</script>

		<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-MQ4VFQRSYR"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'G-MQ4VFQRSYR');
	</script>

</head>
<body>

<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/pageTop.php") ?>

<div id="topContainer">
	
	<!-- モダンなヘッダーに変更 -->
	<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/pageHeader_modern.php") ?>
	
	<div id="mainVisual">
					
		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/ani/globalMenu.php") ?>
		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/ani/globalMenu_mb.php") ?>

	</div>

	<div id="mainContainer">
		<div id="mainContent">
		<!-------------------------------- メインコンテンツ[Start] -------------------------------->


<?php if ( $PP === 0 ): ?>
		
	<form action="" method="post">
		
		<h2>お問い合わせ</h2>

		<div style="width:80%;margin:3% auto 10%;">
			<div style="margin:1%;">
				お名前<br />
				<input type="text" name="name" value="" style="display:inline-block;width:50%;padding:3px 5px;font-size:120%;" />
			</div>

			<div style="margin:1%;">
				メールアドレス<br />
				<input type="text" name="mail" value="" style="display:inline-block;width:50%;padding:3px 5px;font-size:120%;" />
			</div>

			<div style="margin:1%;">
				お問い合わせ内容<br />
				<textarea name="content" rows="10" style="width:100%;"></textarea>
			</div>
		</div>
				
		<div class="centering_text"><input type="submit" name="submit" value="送信" style="display:inline-block;padding:5px 20px;font-size:150%;" /></div>
				
	</form>

	<br />
	<br />
	<div class="event_freeArea">
    <div class="event_freeArea_inner">
    お急ぎの場合はお電話でもお問い合わせいただけます。<br>
	03-6754-6371<br>
	■お問い合わせ時間<br>
	火～土(11:00～20:00）<br>
	日(11:00～15:00）<br>
	※休憩時間等お電話に出られない場合がございます。1時間以内には戻りますので、お掛け直しいただけますと幸いです。<br>
    </div>
    </div>

	<br />
	<br />
	<center>
	<div class="banner" style="padding: 10px 20px;">
	<a href="/ikoiko/contact.php">
		<img src="/ikoiko/img/sidebar/170312happymail_600x600.jpg" alt="幸せ報告待ってます" title="幸せ報告待ってます" width="auto">
	</a>
	</div>
	</center>


	<br />
	<div class="event_freeArea">
    <div class="event_freeArea_inner">
	報告するとAmazonギフト券1,000円分プレゼント！<br />
	報告方法は問い合わせフォームから下記をご記載の上送信ください。<br />
	・お名前（ニックネームでもOK）<br />
	・メールアドレス<br />
	・住所<br />
	・電話番号<br />
	・幸せメッセージ<br />
	</div>
	</div>
	
<?php elseif ( $PP === 1 ): ?>


	<h2>お問い合わせ</h2>

	<p>お問い合わせを受け付けました。</p>


<?php endif; ?>



		<!-------------------------------- メインコンテンツ[End] -------------------------------->
		</div>

		<div id="sideContent">
			
			<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/sideContent_modern.php") ?>

		</div>
	</div>

</div>

<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/footer.php") ?>

</body>
</html>