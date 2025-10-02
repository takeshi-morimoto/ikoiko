<?php 

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');

// ▼ 1) 価格表示をテーブル形式にする関数を定義
function buildPriceTable($price_m, $price_f) {
    // 男性/女性を行で分けたテーブルHTMLを返す
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

<!-- テーブルの簡易CSS（必要に応じて調整） -->
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

    // まず、eventsテーブルの構造を確認
    try {
        $columnsResult = $db->query("SHOW COLUMNS FROM events");
        $existingColumns = [];
        while ($row = $columnsResult->fetch()) {
            $existingColumns[] = $row['Field'];
        }
        
        // 必要なカラムが存在するかチェック
        $hasPriceM = in_array('price_m', $existingColumns);
        $hasPriceF = in_array('price_f', $existingColumns);
        $hasImgUrl = in_array('img_url', $existingColumns);
        $hasFeature = in_array('feature', $existingColumns);
        
        // クエリを動的に構築
        $selectFields = "
        events.find,
        events.title,
        events.date,
        events.week,
        events.begin,
        events.end,
        events.pr_comment,
        area.page,
        area.place,
        area.price_h,
        area.area,
        area.area_ja,
        area.content
        ";
        
        if ($hasPriceM) $selectFields .= ", events.price_m";
        if ($hasPriceF) $selectFields .= ", events.price_f";
        if ($hasImgUrl) $selectFields .= ", events.img_url";
        if ($hasFeature) $selectFields .= ", events.feature";
        
        $query = "
          SELECT {$selectFields}
          FROM events
          JOIN area USING(area)
          WHERE date >= '$today'
            AND area.page = 'ani'
          ORDER BY events.date
          LIMIT 6
        ";

        $result = $db->query($query);
        
        echo "<h2>本日以降開催の街コン情報</h2>";
        echo "<div id='resultList'>";

        while ($row = $result->fetch()) {
            // 動的にカラム値を取得
            $find = $row['find'];
            $title = $row['title'];
            $date = $row['date'];
            $week = $row['week'];
            $begin = $row['begin'];
            $end = $row['end'];
            $pr_comment = $row['pr_comment'];
            $page = $row['page'];
            $place = $row['place'];
            $price_h = $row['price_h'];
            $area = $row['area'];
            $area_ja = $row['area_ja'];
            $content = $row['content'];
            
            // 存在する場合のみ取得
            $price_m = $hasPriceM ? $row['price_m'] : null;
            $price_f = $hasPriceF ? $row['price_f'] : null;
            $img_url = $hasImgUrl ? $row['img_url'] : null;
            $feature = $hasFeature ? $row['feature'] : null;

            // $date から年・月・日を分割
            $y = strtok($date, '-');
            $m = strtok('-');
            $d = strtok('-');

            // 開始/終了時刻を分割
            $begin_H = strtok($begin, ':');
            $begin_M = strtok(':');
            $end_H   = strtok($end, ':');
            $end_M   = strtok(':');

            // 男女別の通常/早割の価格を個別に分割
            $area_price_m = strtok($price_h, '/');
            $area_price_f = strtok('/');

            // 金額が未設定ならエリアのデータを適用
            if (empty($price_m)) {
                $price_m = $area_price_m;
            }
            if (empty($price_f)) {
                $price_f = $area_price_f;
            }

            // イベントタイプ名を取得
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

            // ▼ 2) 価格テーブルを組み立て
            $price_table_html = buildPriceTable($price_m, $price_f);

            echo "
            <div class='event'>
              <div class='image-box'>
                <a href='//koikoi.co.jp/ikoiko/event/{$area}'>
                  <img src='{$img_url}' alt=''> 
                  <p>" . ($feature ?: '') . "</p>
                </a>
              </div>

              <div class='eventInfo-box'>
                <span class='eventName'>
                  <a href='//koikoi.co.jp/ikoiko/event/{$area}'>{$title}</a>
                </span>
                <span class='dateTime'>
                  {$m}月{$d}日({$week}){$begin_H}:{$begin_M}-{$end_H}:{$end_M}
                </span>
                <!-- ▼ 3) テーブル形式で価格を出力 -->
                <span class='price'>{$price_table_html}</span>
              </div>
            </div>
            ";
        }

        echo "
          <div class='btn_more_all'>
            <a href='/ikoiko/list_1/'>もっと見る</a>
          </div>
        </div>
        ";
        
    } catch (Exception $e) {
        echo "<p style='color:red;'>エラーが発生しました: " . $e->getMessage() . "</p>";
        echo "<p>デバッグ情報:</p>";
        echo "<pre>" . print_r($e, true) . "</pre>";
    }
  ?>

</div>
