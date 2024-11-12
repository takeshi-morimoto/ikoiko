<?php 

/*

直近のイベント６つをサムネイル表示させるウィジット

*/

//DBの初期化
require_once("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/db_data/db_init.php");
$db->query("SET NAMES utf8");

 ?>

<div id="searchResult">

    <?php 

    $today = date("Y") . '-' . date("m") . '-' . date("j") ;

    $result = $db->query("
                  select events.find , events.date , events.week , events.begin , events.end, events.pr_comment , area.page , area.place , area.price_h , area.area , area.area_ja 
                  from events join area using(area)
                  where date >= '$today' and area.page = 'nazo'
                  order by events.date 
                  limit 20 ;" );

    print 
        "
        <h2>もうすぐ開催のイベント</h2>
        <div id='resultList'>
        ";

    while ( $row = $result->fetch() ):

      list( $find, $date, $week, $begin, $end, $pr_comment, $page, $place, $price_h, $area, $area_ja ) = $row ;

      //$dateから日付データを年、月、日に分割
          $y = strtok($date, '-');
          $m = strtok('-');
          $d = strtok('-');

          //開始と終了時刻を時、分に分割
          $begin_H = strtok($begin, ':');
          $begin_M = strtok(':');

          $end_H = strtok($end, ':');
          $end_M = strtok(':');

          //男女別の通常と早割の価格を個別に分割
          $price_m = strtok($price_h, '/');
          $price_w = strtok('/');

          switch ( $page ):

            case "machi" :

              $eventType = 'こいこい街コン in' ;
              break;

            case "ani" :

              $eventType = 'アニメコン' ;
              break;

            case "nazo" :

              $eventType = '謎解きコン' ;
              break;

            case "off" :

              $eventType = '' ;
              break;


          endswitch;

      print 

      "
      <div class='event'>

            <img class='eventImg' src='//koikoi.co.jp/ikoiko/img/img_thamb/{$find}' alt='' />

            <div class='eventInfo'>
                <span class='place'>{$place}</span>
                <span class='eventName'><a href='//koikoi.co.jp/ikoiko/event/{$area}'>{$eventType} {$area_ja}</a></span>
                <span class='dateTime'>{$m}月{$d}日({$week}){$begin_H}:{$begin_M}-{$end_H}:{$end_M}</span>
                <span class='price'>男性 {$price_m}円　女性 {$price_w}円</span>
            </div>

            <p class='prComment'>
              <b>イチ押しポイント</b><br />
              {$pr_comment}
            </p>

      </div>
      ";

    endwhile;

    print "</div>";

    ?>

</div>
