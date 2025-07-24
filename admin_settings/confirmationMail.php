<?php 


//メール送信の準備
mb_language("japanese");
mb_internal_encoding("utf-8");

//メールを送信するデータを変数にセット
$from = 

	'from:' . 
	mb_encode_mimeheader('こいこい街コン') . 
	'<mail@p007.sub.jp>';

$subject = 'ご入金確認のご連絡(こいこい街コン)' ;

$mess = 
"
こいこい街コン

お支払いありがとうございました。
本日ご入金の確認が取れましたのでご報告いたします。
入金額に誤りがある場合等は再度ご連絡する場合がございます。

集合場所はメールでは届きません！
申し込み完了画面に記載されます。下記のURLに前日までに集合場所が掲載されます。
//koikoi.co.jp/ikoiko/entry_set/finish.php?d01={$thisState}&d02={$price_h}&d03={$price_l}&d04={$sex}&d05={$find}

";

//送信
mb_send_mail($sendTo, $subject, $mess, $from);



 ?>