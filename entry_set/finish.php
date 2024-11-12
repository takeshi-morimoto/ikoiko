<?php 

//Noticeエラーを非表示にする
error_reporting(E_ALL & ~E_NOTICE);


$thisState = $_GET['d01'];
$price_h = $_GET['d02'];
$price_l = $_GET['d03'];
$area = $_GET['d04'];
$find = $_GET['d05'];


//DBの初期化
require_once("../db_data/db_init.php");
$db->query("SET NAMES utf8");

$ps = $db->query("select `meetingpoint` , `store` , `address` , `url` , `free_text`
                    from events 
                    where `find` = '{$find}' ;") ;

$meetingInfo = $ps->fetch();
list($meetingpoint,$store,$address,$url,$free_text) = $meetingInfo ;



//男女別の通常と早割の価格を個別に分割
$hiPrice_m = strtok($price_h, '/');
$hiPrice_w = strtok('/');

$lowPrice_m = strtok($price_l, '/');
$lowPrice_w = strtok('/');

//１組２名分の料金
$hiPrice_m_2 = $hiPrice_m * 2 ;
$hiPrice_w_2 = $hiPrice_w * 2 ;
$lowPrice_m_2 = $lowPrice_m * 2 ;
$lowPrice_w_2 = $lowPrice_w * 2 ;


//早割の人にメッセージ
if ( $thisState == 2 ):

  $mess = 
          "
          早割期間中にお支払いの方は<br />
          <br />
          <strong>男性１組 {$lowPrice_m_2}円(一人{$lowPrice_m}円)<br />
          女性１組 {$lowPrice_w_2}円（一人{$lowPrice_w}円)</strong>
          ";

endif;



//このページで使うデータをDBから読み込み
$pageDataTmp = $db->query("select * from area where area = '$area' ;") ;
$pageData = $pageDataTmp->fetch();

//適切な変数に読み込んだデータを格納
list($a,$page,$area,$area_ja,$ken,$place,$price_h,$price_l,$age_m,$age_w,$free_text1,$free_text2) = $pageData ;
$price_h_m = strtok($price_h, "/");
$price_h_w = strtok("/");
$price_l_m = strtok($price_l, "/");
$price_l_w = strtok("/");
$age_l_m = strtok($age_m, "/");
$age_h_m = strtok("/");
$age_l_w = strtok($age_w, "/");
$age_h_w = strtok("/");

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>森本さん</title>


  <?php include("/home/users/0/sub.jp-p007/web/widgets/outputHead.php") ?>

  <script type='text/javascript' src='//p007.sub.jp/js/都道府県検索MB.js'></script>

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

  <div id="mainContainer">
    <div id="mainContent">

       <h1>お申込みありがとうございます。</h1>

        <div class=''>
        閉じる前にご確認下さい。
        </div>

        <p>
        集合場所の情報はこちらに掲載されますので必ずブックマークしてください！
        </p>

        <strong><span class=''>集合場所情報はメールでは届きません。</span><br />こちらのページに掲載されます。</strong><br /><br />

        <?php 

        print '<h2>今回の集合場所</h2>' ;


        if ($meetingpoint):

            print

                "
                <p>
                店舗：{$store}<br />
                住所：{$address} <br />
                URL：{$url} <br />

                街コンは参加人数の都合により単独開催が難しくなってしまったため他社街コン「街コンALICE」に統合して開催させていただく運びとなりました。
                </p>
                ";

        else:

            print '<p>現在集合場所は準備中です。開催直前になるとここに集合場所が掲載されます。</p>';

        endif;
         ?>




        ----------------------------------- <br /><br />
        <br />


        <h2>【お支払いのご案内】</h2>
        <p>お支払いただきます料金は下記になっております。<br />
        お支払い方法はページ下部に記載しております。</p>

        <?php 

        print 
              "
              <p><strong>男性１組 {$hiPrice_m_2}円(一人{$hiPrice_m}円)<br />
              女性１組 {$hiPrice_w_2}円（一人{$hiPrice_w}円)</strong><br /><br />
              {$free_text}
              
              {$mess}

              </p>
              ";


         ?>


            2組4名、3組6名様でお申込みのお客様は合算した金額でのお振込みでお願いします。<br /><br />

            <h2>重要</h2>
            <p><strong>
            期日後にご入金いただいてもご案内ができない場合がございます。<br />
            必ず下記の注意事項、お支払い期限をご確認ください。
            </strong></p>

            <p>下記の注意事項をよくお読みの上、
            お申込み完了画面に記載の振込先にご入金ください。</p>

            <h3>お支払い期限</h3>

            お申込みより1週間以内のご入金をお願いしております。<br />
            期日を過ぎた場合は他のお客様をご案内させていただきますので、
            再度お申込みいただくか一度弊社運営事務局までご連絡ください。<br />

            <h3> 開催日の直前にお申込みの方へ</h3>
            開催日の直前にお申込みの方へ<br />
            入金受付は基本的に直前の金曜日午後3時となっております<br />
            金曜日午後3時以降にお申込みのお客様は、<br />
            事務局　(000-0000-0000)まで必ずご連絡下さい。<br />
            ご案内方法をお伝えいたします。なおご連絡をいただいていないお客様で<br />
            当日、受付会場にお越しいただいてもご案内できかねますのでご了承下さい。<br />

            <br />
            下記のお振込先に直接お振込みください。ご入金が確認出来次第、確認メールをお送りします。
            入金をしてから2日以内に入金確認メールが届かない場合はお客様の迷惑メール設定の影響でメールが届かない可能性がございますので
            必ず弊社事務局(000-0000-0000)までお電話にてご連絡ください。なおご連絡をいただいていないお客様で当日、受付会場にお越しいただいてもご案内できかねますのでご了承下さい。<br />
            <br />
            １組2名様でのご参加となりますので2名様分の参加費を申込者様のお名前でお振込みください。<br />

            お振込人名義はお申込時にご記入いただいたお名前と同一のものをご使用ください。<br />
            お名前が一致しないと入金の確認が取れない場合がございますので、お申し込み時のお名前と異なる場合は弊社事務局宛にメールもしくはお電話にてご連絡ください。<br />

            <h2>重要</h2>
            <p><strong>
            振込人名（お客様の名前）の欄に弊社の口座名義を間違って入力する例が発生しています。<br />
            正しく入力していただけないとご案内や場合によってはご返金も難しくなることがありますので必ずご確認をお願いいたします。
            </strong></p>
            
            銀行名 br />
            支店<br />
            店番<br />
            口座種別<br />
            口座番号<br />
            口座名義<br />
      ?>


    </div>
    <div id="sideContent">サイドバー</div>
  </div>

</div>

  


</body>
</html>