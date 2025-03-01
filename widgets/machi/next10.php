<?php 

/*

直近のイベント６つをサムネイル表示させるウィジット

*/

?>

<?php
// 1) 価格表示をテーブル形式にする関数を定義
function buildPriceTable($price_m, $price_f) {
    // テーブルレイアウトを組み立てる（「→」置換はしない）
    return "
    <table class='price-table'>
      <tr>
        <th>男性</th>
        <td>{$price_m}</td>
      </tr>
      <tr>
        <th>女性</th>
        <td>{$price_f}</td>
      </tr>
    </table>
    ";
}
?>

<!-- テーブルの簡易CSS -->
<style>
.price-table {
  border-collapse: collapse;
  margin-top: 0.5em;
}
.price-table th,
.price-table td {
  border: 1px solid #ccc;
  padding: 4px 8px;
  vertical-align: top;
}
.price-table th {
  background: #f0f0f0;
  white-space: nowrap;
  text-align: left;
}
</style>

<div id="searchResult">

    <?php 
    $today = date("Y") . '-' . date("m") . '-' . date("j") ;

    $result = $db->query("
        SELECT 
            events.find,
            events.title,
            events.date,
            events.week,
            events.begin,
            events.end,
            events.pr_comment,
            area.page,
            area.place,
            events.price_m,
            events.price_f,
            area.price_h,
            area.area,
            area.area_ja,
            area.content,
            events.img_url,
            events.feature
        FROM events
        JOIN area USING(area)
        WHERE date >= '$today'
          AND area.page = 'machi'
        ORDER BY events.date 
        LIMIT 6
    ");

    echo "
        <h2>もうすぐ開催の街コン情報</h2>
        <div id='resultList'>
    ";

    while ($row = $result->fetch()) {
        list(
            $find,
            $title,
            $date,
            $week,
            $begin,
            $end,
            $pr_comment,
            $page,
            $place,
            $price_m,
            $price_f,
            $price_h,
            $area,
            $area_ja,
            $content,
            $img_url,
            $feature
        ) = $row;

        // $dateから日付データを年,月,日に分割
        $y = strtok($date, '-');
        $m = strtok('-');
        $d = strtok('-');

        // 開始/終了時刻を分割
        $begin_H = strtok($begin, ':');
        $begin_M = strtok(':');
        $end_H   = strtok($end, ':');
        $end_M   = strtok(':');

        // 男女別の通常と早割の価格を個別に分割
        $area_price_m = strtok($price_h, '/');
        $area_price_f = strtok('/');

        // 金額が未設定ならエリアのデータを適用
        if (empty($price_m)) {
            $price_m = $area_price_m;
        }
        if (empty($price_f)) {
            $price_f = $area_price_f;
        }

        // イベントタイプ取得
        $result2 = $db->query("SELECT name FROM content WHERE num = {$content}");
        $tmp = $result2->fetch();
        $eventType = $tmp['name'];

        // タイトルが未設定の場合はイベントタイプとエリアを基に生成
        if (empty($title)) {
            $title = $eventType . $area_ja;
        }

        // 画像URLが未設定の場合は旧の処理
        if (empty($img_url)) {
            $img_url = "/ikoiko/img/img_thamb/{$find}";
        }

        // 2) 価格テーブルを組み立て
        $price_table_html = buildPriceTable($price_m, $price_f);

        echo "
        <div class='event'>
          <div class='image-box'>
            <a href='//koikoi.co.jp/ikoiko/event_m/{$area}'>
              <img src='{$img_url}' alt=''> 
              <p>{$feature}</p>
            </a>
          </div>

          <div class='eventInfo-box'>
            <!--
            <span class='place'>{$place}</span>
            -->
            <span class='eventName'>
              <a href='//koikoi.co.jp/ikoiko/event_m/{$area}'>{$title}</a>
            </span>
            <span class='dateTime'>
              {$m}月{$d}日({$week}){$begin_H}:{$begin_M}-{$end_H}:{$end_M}
            </span>
            <!-- 3) テーブル形式で価格を出力 -->
            <span class='price'>{$price_table_html}</span>
          </div>
        </div>
        ";
    }

    echo "
        <div class='btn_more_all'><a href='/ikoiko/list_2/'>もっと見る</a></div>
        </div>
    ";
    ?>

</div>
