<?php 

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');

 ?>



<div id="searchResult">

  <?php 
    $today = date("Y") . '-' . date("m") . '-' . date("j") ;

    $result = $db->query("
      select events.find, events.title, events.date , events.week , events.begin , events.end, events.pr_comment , area.page, area.place, events.price_m, events.price_f, area.price_h, area.area, area.area_ja, area.content , events.img_url , events.feature
      
      from events join area using(area)
      where date >= '$today' and area.page = 'ani'
      order by events.date 
      limit 6 ;" );

    print 
    "<h2>本日以降開催の街コン情報</h2>
    <div id='resultList'>";

      while ( $row = $result->fetch() ):

        list( $find, $title, $date, $week, $begin, $end, $pr_comment, $page, $place, $price_m, $price_f, $price_h, $area, $area_ja, $content, $img_url ,$feature) = $row ;

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
        $area_price_m = strtok($price_h, '/');
        $area_price_f = strtok('/');

        // 金額が未設定ならエリアのデータを適用
        if ( empty($price_m) ) { $price_m = $area_price_m; }
        if ( empty($price_f) ) { $price_f = $area_price_f; }

        $result2 = $db->query("select name from content where num = {$content}");

        $tmp = $result2->fetch();
        $eventType = $tmp['name'];

        // タイトルが未設定の場合はイベントタイプとエリアを基に生成
        if ( empty($title) ) {
          $title = $eventType . $area_ja;
        }

        // 画像URLが未設定の場合は旧の処理
            if ( empty($img_url) ) {
              $img_url = "/ikoiko/img/img_thamb/{$find}";
            }


        print 

        "
        <div class='event'>
          <div class='image-box'>
            <a href='//koikoi.co.jp/ikoiko/event/{$area}'>
              <img src='{$img_url}' alt=''> 
              <p>{$feature}</p>
            </a>
          </div>

          <div class='eventInfo-box'>
              <!--
              <span class='place'>{$place}</span>
              -->
              <span class='eventName'><a href='//koikoi.co.jp/ikoiko/event/{$area}'>{$title}</a></span>
              <span class='dateTime'>{$m}月{$d}日({$week}){$begin_H}:{$begin_M}-{$end_H}:{$end_M}</span>
              <span class='price'>男性 {$price_m}円　女性 {$price_f}円</span>
          </div>
        </div>
        ";

      endwhile;

      print 
      
      "
      <div class='btn_more_all'><a href='/ikoiko/list_1/'>もっと見る</a></div>
    </div>
    ";

  ?>

</div>
