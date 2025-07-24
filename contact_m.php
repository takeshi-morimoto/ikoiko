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
	<title>お問い合わせ - 街コン | KOIKOI</title>
	<meta name="description" content="街コンに関するお問い合わせフォーム。イベントに関するご質問、ご要望などお気軽にお問い合わせください。">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/outputHead.php") ?>

	<!-- モダンCSS追加 -->
	<link rel="stylesheet" href="/ikoiko/css/modern-base.css">
	<link rel="stylesheet" href="/ikoiko/css/modern-components.css">
	<link rel="stylesheet" href="/ikoiko/css/responsive.css">
	<link rel="stylesheet" href="/ikoiko/css/news-fix.css">
	<link rel="stylesheet" href="/ikoiko/css/layout-spacing.css">
	<link rel="stylesheet" href="/ikoiko/css/header-supreme.css">

	<link rel="canonical" href="https://koikoi.co.jp/ikoiko/contact_m.php" />

</head>
<body>

<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/pageTop_m.php") ?>

<div id="topContainer">
	<!-- 究極のヘッダーに変更 -->
	<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/pageHeader_ultimate.php") ?>
	
	<div id="mainVisual">
					
		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/machi/globalMenu.php") ?>
		<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/machi/globalMenu_mb.php") ?>

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



<?php elseif ( $PP === 1 ): ?>


	<h2>お問い合わせ</h2>

	<p>お問い合わせを受け付けました。</p>


<?php endif; ?>



		<!-------------------------------- メインコンテンツ[End] -------------------------------->
		</div>

		<div id="sideContent">
			
			<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/sideContent_m_modern.php") ?>

		</div>
	</div>

</div>

<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/footer.php") ?>

</body>
</html>