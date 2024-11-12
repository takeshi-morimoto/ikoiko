<?php 


//Noticeエラーを非表示にする
error_reporting(E_ALL & ~E_NOTICE);


//DBの初期化
require_once("../db_data/db_init.php");
$db->query("SET NAMES utf8");


//押された送信ボタンによって表示画面と処理内容を切り替え(入力内容確認画面、申込完了画面)
$pagePat = ( isset($_POST['submit_1']) ) ? 1 : 2 ;


//パターン１：入力内容確認画面
if ( $pagePat === 1 ):

  //ページ内で使うデータをDBから取り出し
  $find = $_POST['find'] ;

  $obj = $db->query("select `area` from events where find = '{$find}' ;") ;
  $tmp = $obj->fetch();
  list($area) = $tmp ;

  $pageDataTmp = $db->query("select * from area where area = '$area' ;") ;
  $pageData = $pageDataTmp->fetch();

  //適切な変数に読み込んだデータを格納
  list($a,$page,$area,$area_ja,$ken,$place,$price_h,$price_l,$age_m,$age_w,$free_text1,$free_text2) = $pageData ;

  //入力内容をチェックして誤りがあればPagePatに０を代入

    //すべて入力しているかチェック
    if ( empty($_POST['name']) or empty($_POST['name_kana']) or empty($_POST['age']) or empty($_POST['mail_1']) or empty($_POST['mail_2']) or empty($_POST['tell']) or empty($_POST['ninzu']) ):

      $error_01 = 'お手数ですがすべての項目をご入力ください。<br />' ;
      $pagePat = 0 ;

    endif;

    //メールアドレスの確認
    if ( $_POST['mail_1'] !== $_POST['mail_2'] ):

      $error_02 = 'メールアドレスと確認用アドレスが一致しません。<br />' ;
      $pagePat = 0 ;

    endif;

    //規約の同意の確認
    if ( empty($_POST['rule']) ):

      $error_03 = '利用規約に同意してお申込みください。<br />' ;
      $pagePat = 0 ;

    endif;


  //送信されたフォームデータを配列に格納して最適化
  $formData = array_values($_POST);
  array_splice($formData, 7 , 1 );


  //テーブルに出力する確認用のデータのセット
  $td = array_slice($formData, 3 , 7 );
  $th = array('代表者のお名前','お名前ふりがな','ご年齢','メールアドレス','携帯電話番号','参加人数','利用規約');


//パターン２：申込み完了・DBにデータを格納
elseif ( $pagePat === 2 ):

  //POST送信されたデータを入れｔに格納
  $toDb = array_values($_POST);

  $find = $_POST['data_0'] ;
  $sex = $_POST['data_1'] ;
  $thisState = $_POST['data_2'];

  //日本語で出力する性別を変数にセット
  $sex_ja = ($sex === 'm') ? '男性' : '女性' ;

  //DBから必要なデータを読み込み
  $tmpData = $db->query("select `find`,`area`,`date`,`week`,`free_text` from events where find = '$find' ;") ;
  $formData = $tmpData->fetch();
  list( $find , $area , $date , $week , $free_text ) = $formData ;

  $tmpData = $db->query("select `ken`,`area_ja`,`price_h`,`price_l` from area where area = '$area' ;") ;
  $pageData = $tmpData->fetch();
  list( $ken , $area_ja , $price_h , $price_l ) = $pageData ;


  //分割が必要な各変数の内容を分割して個別の変数に格納
  //必要に応じて男女別の値段や年齢などを表示できます。

    //$dateから日付データを年、月、日に分割
    $y = strtok($date, '-');
    $m = strtok('-');
    $d = strtok('-');

    //男女別の通常と早割の価格を個別に分割
    $hiPrice_m = strtok($price_h, '/');
    $hiPrice_w = strtok('/');

    $lowPrice_m = strtok($price_l, '/');
    $lowPrice_w = strtok('/');


  //申込完了時刻を取得
  $now = date('Y-n-j H:i:s');

  //データベースへ送信するデータを$toDbにセットして情報を最適化
  array_splice( $toDb , 9 , 1 , $now ) ;
  $toDb[8] = (int)$toDb[8];

  //名前とふりがなの素スペースを削除
  $search = array( ' ' , '　' );
  $replace = array( '' , '' );


  $toDb[3] = str_replace($search, $replace, $toDb[3]);
  $toDb[4] = str_replace($search, $replace, $toDb[4]);

  /* 途中追加(customersにエリア情報も格納)
  -----------------------------------------------------*/

  array_splice($toDb, 1 , 0 , $area);

  $event = $date . ' ' . $area_ja ;
  array_splice($toDb, 2 , 0 , $event);

  print_r($toDb);

  /*-----------------------------------------------------
  途中追加(customersにエリア情報も格納)*/


  //プリペアドステートメントの準備
  $ps = $db->prepare("
          insert into customers (`find`,`area`,`event`,`sex`,`entry`,`name`,`hurigana`,`age`,`mail`,`tel`,`ninzu`,`date`) 
          values (?,?,?,?,?,?,?,?,?,?,?,?);
          ");

  //For文でバインドしてSQL文を完成
  for ($n = 0 ; $n <= 11 ; $n += 1):
    $ps->bindParam( $n + 1 , $toDb[$n]);
  endfor;

  //インサート文を実行
  $res = $ps->execute();

  //管理者と申込者にメールを送信
  require_once("sendmail.php");

endif;
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


            <h1 id="site-description">

                <?php print "{$sex_ja}お申込み<br />[こいこい街コンin{$area_ja}]"; ?>

            </h1>

            <strong>集合場所はメールでは届きません。<br />申し込み完了画面に掲載されます。</strong>

          <?php 

            //パターン１：入力内容確認画面
            if ( $pagePat === 1 ):

              //入力内容確認用のテーブルとフォームを出力
              print '<div class="center"><table id="entryForm">' ;
              
              for ( $n = 0 ; $n <= 6 ; $n += 1 ):
                print "<tr><th>{$th[$n]}</th><td>{$td[$n]}</td></tr>\n" ;
              endfor;

              print '</table><form action="entry_receive.php" method="post" accept-charset="utf-8">' ;

              for ( $n = 0 ; $n <= 8 ; $n += 1 ):
                print "<input type='hidden' name='data_{$n}' value='{$formData[$n]}'>" ;
              endfor;

              print '<input type="submit" name="submit_2" value="送信"></div>' ;


            //パターン２：申込み完了
            elseif ( $pagePat === 2 ):


              print 
                    "
                    <p>自動的に画面が切り替わらない方は<a href='//p007.sub.jp/entry_set/finish.php?d01={$thisState}&d02={$price_h}&d03={$price_l}&d04={$sex}&d05={$find}'>こちら</a>をクリックしてください。</p>
                    <script language='JavaScript'>
                    <!--
                    location.href='//p007.sub.jp/entry_set/finish.php?d01={$thisState}&d02={$price_h}&d03={$price_l}&d04={$sex}&d05={$find}';
                    //-->
                    </script> 
                    " ;




            //パターン３：入力内容に誤りがある場合エラーを出力
            elseif ( $pagePat === 0 ):

              print "<p>{$error_01}\n{$error_02}\n{$error_03}\n</p>
                  <p><a href=\"#\" onClick=\"history.back(); return false;\">入力画面にもどる</a></p>" ;


            endif;

      ?>


    </div>
    <div id="sideContent">サイドバー</div>
  </div>

</div>

  


</body>
</html>