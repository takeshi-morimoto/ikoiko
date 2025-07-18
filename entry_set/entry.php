<?php 

//DBの初期化
require_once("/home/users/0/sub.jp-p007/web/db_data/db_init.php");
$db->query("SET NAMES utf8");


//送信された識別子と性別を取得
$pathInfo = strtok($_SERVER['PATH_INFO'], '/') ;
$find = strtok($pathInfo, '-') ;
$sex = strtok('-') ;


//日本語で出力する性別を変数にセット
$sex_ja = ($sex === 'm') ? '男性' : '女性' ;

//DBから出力するフォームのデータをDBのeventsテーブル読み込み
$tmpData = $db->query("select `find`,`area`,`date`,`week`,`begin`,`end`,`state_m`,`state_w`,`free_text` from events where find = '$find' ;") ;
$formData = $tmpData->fetch();
list( $find , $area , $date , $week , $begin , $end , $state_m , $state_w , $free_text ) = $formData ;

//ページを出力するのに必要なデータをDBのareaテーブルから読み込み
$tmpData = $db->query("select `ken`,`area_ja`,`place`,`price_h`,`price_l`,`age_m`,`age_w` from area where area = '$area' ;") ;
$pageData = $tmpData->fetch();
list( $ken , $area_ja , $place , $price_h , $price_l , $age_m , $age_w ) = $pageData ;

//出力するフォームの性別を決定
$thisState = ($sex === 'm') ? $state_m : $state_w ;

//分割が必要な各変数の内容を分割して個別の変数に格納
//必要に応じて男女別の値段や年齢などを表示できます。


//$dateから日付データを年、月、日に分割
$y = strtok($date, '-');
$m = strtok('-');
$d = strtok('-');

//開始と終了時刻を時、分に分割
$beginHour = strtok($begin, ':');
$beginMin = strtok(':');

$endHour = strtok($end, ':');
$endMin = strtok(':');

//男女別の通常と早割の価格を個別に分割
$hiPrice_m = strtok($price_h, '/');
$hiPrice_w = strtok('/');

$lowPrice_m = strtok($price_l, '/');
$lowPrice_w = strtok('/');

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>森本さん</title>


  <?php include("/home/users/0/sub.jp-p007/web/widgets/outputHead.php") ?>

  <script type='text/javascript' src='https://koikoi.co.jp/ikoiko/js/prefecture-search-mb.js'></script>

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


            <?php 


            //ステータスが早割の場合はメッセージを表示
            if ( $thisState == 2 ):
              print /*---編集が可能なテキストです。[この次の行から]----*/ "


                <p>現在{$sex_ja}の方は早割でご案内中です。<br /></p> 


                "; /*---編集が可能なテキストです。[この上の行まで]---*/
            endif;



            //日付と場所を表示
            print /*---編集が可能なテキストです。[この次の行から]----*/ "


              
                <div class='alert alert-info'>
                    <span class='glyphicon glyphicon-ok'></span> {$m}月{$d}日{$area_ja}申込フォームです。
                </div>



              \n"; /*---編集が可能なテキストです。[この上の行まで]---*/




            //性別の確認メッセージを表示
            print /*---編集が可能なテキストです。[この次の行から]----*/ "


                <div class='alert alert-info'>
                  <span class='glyphicon glyphicon-ok'></span> こちらは{$sex_ja}用申込フォームです。
                </div>  


              \n"; /*---編集が可能なテキストです。[この上の行まで]---*/

            ?>

              <p>
                入力完了後にお支払い方法を記載した自動返信メールが届きます。<br />
                
                ※スマートフォンをお使いの方は、受信可能なPC用のメールアドレス（Gmail、Yahoo icloud　maillなど）をご入力ください。<br />
                 PCのメールを持ちでない携帯メールの方は必ずドメイン解除を行って下さい。<br />


                【p007.sub.jp】からのメールが受信できるようにドメイン設定を解除の上
                お申込みをお願いいたします。 <br /><br />



                【ドコモのアドレスを使用される方】<br />
                docomo.ne.jpのメールアドレスは初期設定で迷惑メール設定がされており、送受信でエラーになる可能性が御座いますので、
                お申し込みの前に必ず【p007.sub.jp】の受信設定をしておいて下さい。ご協力をお願い致します。<br />
              
              </p>

              <div class="black">ご注意</div>

              <p class="small">
                <?php print "<a href='//p007.sub.jp/rules/{$area}'>利用規約</a>に同意の上お申込下さい。"; ?>
              </p>

            <?php

            //ステータスがキャンセル待ちの場合はメッセージを表示
            if ( $thisState == 3 ):
              print /*---編集が可能なテキストです。[この次の行から]----*/ "


                <div class='alert alert-info'>
                  <span class='glyphicon glyphicon-ok'></span> 現在キャンセル待ち。
                </div>

                <p>{$sex_ja}の方は<span class='accent red'>キャンセル待ち</span>となっております。<br /><span class='accent red'>お支払いはしないでください</span>。</p>


                \n"; /*---編集が可能なテキストです。[この上の行まで]---*/
            endif;



            //ステータスが電話受付の場合はメッセージを表示してフォームを非表示にします。
            if ( $thisState == 4 ):
              print /*---編集が可能なテキストです。[この次の行から]----*/ "


                <div>
                  現在はお電話でのみお申込み可能です。
                </div>
                <a href='tel:000-0000-0000'>tel:000-0000-0000</a>


                \n"; /*---編集が可能なテキストです。[この上の行まで]---*/



            //ステータスが停止の場合はメッセージを表示してフォームを非表示にします。
            elseif ( $thisState == 0 ):
              print /*---編集が可能なテキストです。[この次の行から]----*/ "


                <div>
                  {$sex_ja}の受付は終了いたしました。
                </div>


                \n"; /*---編集が可能なテキストです。[この上の行まで]---*/



            //電話受付と停止以外の場合は申込みフォームを表示
            else:

              print
                '<form action="//p007.sub.jp/entry_set/entry_receive.php" method="post">' . 
                "<input type='hidden' name='find' value='{$find}'>\n" .
                "<input type='hidden' name='sex' value='{$sex}'>\n" .
                "<input type='hidden' name='thisState' value='{$thisState}'>\n";

              ?>

              <div>


                <table id='entryForm'>

                  <tr>
                    <th>
                      <span>代表者のお名前</span><br />
                      ※全角漢字
                    </th>
                    <td>
                      <input type="text" name="name" size="15"   value="" />
                    </td>
                  </tr>
                  <tr>
                    <th>
                      <span>お名前ふりがな</span><br />
                      ※全角ひらがな
                    </th>
                    <td>
                      <input type="text" name="name_kana" size="15"   value="" />
                    </td>
                  </tr>
                  <tr>
                    <th>
                      <span>ご年齢</span>
                    </th>
                    <td>
                      <select name="age">
                        <option value=""></option>
                        <option  value="20">20</option>
                        <option  value="21">21</option>
                        <option  value="22">22</option>
                        <option  value="23">23</option>
                        <option  value="24">24</option>
                        <option  value="25">25</option>
                        <option  value="----------">----------</option>
                        <option  value="26">26</option>
                        <option  value="27">27</option>
                        <option  value="28">28</option>
                        <option  value="29">29</option>
                        <option  value="30">30</option>
                        <option  value="----------">----------</option>
                        <option  value="31">31</option>
                        <option  value="32">32</option>
                        <option  value="33">33</option>
                        <option  value="34">34</option>
                        <option  value="35">35</option>
                        <option  value="----------">----------</option>
                        <option  value="36">36</option>
                        <option  value="37">37</option>
                        <option  value="38">38</option>
                        <option  value="39">39</option>
                        <option  value="40">40</option>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <th>
                      <span>メールアドレス</span>
                    </th>
                    <td>
                      <input type="text" name="mail_1" size="30"   value="" />
                    </td>
                  </tr>
                  <tr>
                    <th>
                      <span>メールアドレス</span>(確認用)
                    </th>
                    <td>
                      <input type="text" name="mail_2" size="30"   value="" />
                    </td>
                  </tr><tr>
                    <th>
                      <span>携帯電話番号</span><br />
                      半角ハイフン(-)なしで入力
                    </th>
                    <td>
                      <input type="text" name="tell" size="30"   value="" />
                    </td>
                  </tr>
                  <tr>
                    <th>
                      <span>参加人数</span><br />
                      １組２名様でのご参加となります。
                    </th>
                    <td>
                      <ul>
                      <li><label>
                        <input type="radio" name="ninzu"   value="2名" />２名
                      </label></li>
                      <li><label>
                        <input type="radio" name="ninzu"   value="4名" />４名
                      </label></li>
                      <li><label>
                        <input type="radio" name="ninzu"   value="6名" />６名
                      </label></li>
                      </ul>
                    </td>
                  </tr>
                  <tr>
                    <th>
                      <span>利用規約</span>
                    </th>
                    <td>
                      <label><input type="checkbox" name="rule" value="同意する" />利用規約に同意する</label>
                    </td>
                  </tr>

                </table>

                項目はすべて必須項目となっております。<br />
                <input type="submit" name="submit_1" value="確認する">

              </div>

            </form>

            <?php

            endif;

            ?>


    </div>
    <div id="sideContent">サイドバー</div>
  </div>

</div>

  


</body>
</html>