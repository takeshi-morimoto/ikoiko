<?php 

	//管理者と申込者にメールを送信

		//メール送信の準備
		mb_language("japanese");
		mb_internal_encoding("utf-8");


		//早割料金を２倍にして１組の料金を作成
		$hiPrice_m_2 = $hiPrice_m * 2 ;
		$hiPrice_w_2 = $hiPrice_w * 2 ;



		//メールを送信するデータを変数にセット
		$from = 

			'from:' . 
			mb_encode_mimeheader('こいこい街コン') . 
			'<mail@p007.sub.jp>';

		//ステータスがキャンセル待ちの場合
		if ($thisState == 3):

		$cancel = "!!!キャンセル待ち" ;

		$cancel2 = 

				"※キャンセル待ちでの受付けとなっておりますので、ご案内が可能になった時点でご連絡をいたします。\nそれまではご入金をせずにお待ちください。\n";

		endif;


		//ステータス早割の場合
		if ($thisState == 2):

			$sale = "!!!早割" ;

			//早割料金を２倍にして１組の料金を作成
			$lowPrice_m_2 = $lowPrice_m * 2 ;
			$lowPrice_w_2 = $lowPrice_w * 2 ;

			$sale2 = "\n\n早割期間中にお支払いの方は
					男性１組 {$lowPrice_m_2}円(一人{$lowPrice_m}円)
					女性１組 {$lowPrice_w_2}円（一人{$lowPrice_w}円)";

		endif;

		//管理者宛メールの件名を設定
		$sub_admin = "{$m}-{$d}-{$area_ja}-{$sex}-{$_POST['data_3']}" ;

		//管理者宛メールの本文を設定
		$mess_admin = 

"
{$cancel}
{$sale}
{$find}
{$m}月{$d}日 {$area_ja}

{$_POST['data_3']}

{$_POST['data_4']}

{$_POST['data_5']}

{$_POST['data_6']}

{$_POST['data_7']}

参加人数： {$_POST['data_8']}

-------------------------------------

申し込み日[{$now}]

";

		$sub_cus = "お申込みありがとうございます[こいこい街コン運営事務局]" ;

		$mess_cus =

"

この度はお申込いただきありがとうございます。こいこい街コン運営事務局です。
このメールは、お支払いが済むまで保管くださいますようお願い致します。

集合場所はメールでは届きません！
申し込み完了画面に記載されます。下記のURLに前日までに集合場所が掲載されます。

//p007.sub.jp/entry_set/finish.php?d01={$thisState}&d02={$price_h}&d03={$price_l}&d04={$sex}&d05={$find}

{$cancel2}
{$free_text}

【お支払いのご案内】

男性１組 {$hiPrice_m_2}円(一人{$hiPrice_m}円)
女性１組 {$hiPrice_w_2}円(一人{$hiPrice_w}円)
{$sale2}


2組4名、3組6名様でお申込みのお客様は合算した金額でのお振込みでお願いします。

日中お振り込みができない方には、コンビニエンスストアのATMからのお振込みをおすすめしております。
(24時間振込可能)※一部金融機関をのぞく。


[※重要※]
期日後にご入金いただいてもご案内ができない場合がございます。
必ず下記の注意事項、お支払い期限をご確認ください。

下記の注意事項をよくお読みの上、
お申込み完了画面に記載の振込先にご入金ください。

お支払い期限：

お申込みより1週間以内のご入金をお願いしております。
期日を過ぎた場合は他のお客様をご案内させていただきますので、再度お申込みいただくか一度弊社運営事務局までご連絡ください。

開催日の直前にお申込みの方へ
入金受付は基本的に直前の金曜日午後3時となっております
金曜日午後3時以降にお申込みのお客様は、
事務局　(070-5025-0546)まで必ずご連絡下さい。
ご案内方法をお伝えいたします。なおご連絡をいただいていないお客様で
当日、受付会場にお越しいただいてもご案内できかねますのでご了承下さい。
下記のお振込先に直接お振込みください。ご入金が確認出来次第、確認メールをお送りします。

１組2名様でのご参加となりますので2名様分の参加費を申込者様のお名前でお振込みください。

お振込人名義はお申込時にご記入いただいたお名前と同一のものをご使用ください。
お名前が一致しないと入金の確認が取れない場合がございますので、お申し込み時のお名前と異なる場合は弊社事務局宛にメールもしくはお電話にてご連絡ください。

[※重要※]
振込人名（お客様の名前）の欄に弊社の口座名義を間違って入力する例が発生しています。
正しく入力していただけないとご案内や場合によってはご返金も難しくなることがありますので必ずご確認をお願いいたします。

{$cancel2}
銀行名 　　：　三菱東京UFJ銀行
支店　 　　：　小岩支店
店番　　 　：　206
口座種別　：　普通
口座番号　：　0054779
口座名義　：　ミヤウチ　ケイゴ

------------------------------


↓【お申込み内容をご確認ください】↓

[{$m}月{$d}日 {$area_ja}]

■お名前：{$_POST['data_3']}
■ふりがな：{$_POST['data_4']}
■ご年齢：{$_POST['data_5']}
■メールアドレス：{$_POST['data_6']}
■携帯電話番号：{$_POST['data_7']}
■参加人数： {$_POST['data_8']}

-----------------------------------



━━━━━━━━━━━━━━━━━
こいこい街コン運営事務局
mail mail@p007.sub.jp
tel  090-xxxx-xxxx
(受付時間　10：00～19：00)
━━━━━━━━━━━━━━━━━
";

		mb_send_mail("mail@p007.sub.jp", $sub_admin, $mess_admin, $from);
		mb_send_mail($_POST['data_6'], $sub_cus, $mess_cus, $from);

 ?>