<?php 

// Noticeエラーを非表示にする
error_reporting(E_ALL & ~E_NOTICE);

// 検索されたクエリの最適化
$pat = '/^2[0-9]{3}-[0-9]{1,2}-[0-9]{1,2}$/';
$tok = strtok($_SERVER['PATH_INFO'], "/");

if (preg_match($pat, $tok)):
    $searchDate = $tok;
else:
    $searchArea = $tok;
endif;

// 2個めのクエリがある場合は処理を実行
$tok2 = strtok('/');

if (!empty($tok2)):
    if (preg_match($pat, $tok2)):
        $searchDate = $tok2;
    else:
        $searchArea = $tok2;
    endif;
endif;

// where句の設定
if (empty($searchArea)):
    $where_1 = ' 1 = 1 ';
else:
    $where_1 = " area.ken = '{$searchArea}' ";
endif;

if (empty($searchDate)):
    $today = date("Y") . '-' . date("m") . '-' . date("j");
    $where_2 = " and events.date >= '{$today}' ";
else:
    $where_2 = " and events.date = '{$searchDate}' ";
endif;

// DBの初期化
require("./db_data/db_init.php");
$db->query("SET NAMES utf8");

/**
 * 価格表示をテーブル形式にする関数
 * 例：男性と女性の価格を2行のテーブルで表示
 * 
 * @param string $price_m 男性用の価格文字列
 * @param string $price_f 女性用の価格文字列
 * @return string <table>を含むHTML文字列
 */
function buildPriceTable($price_m, $price_f) {
    // テーブルレイアウトを組み立てる
    // 「→」の置換は行わず、そのまま出力
    $html = "
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

    return $html;
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <title>楽しいイベント盛りだくさん</title>

    <?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/outputHead.php") ?>

    <script type='text/javascript' src='//koikoi.co.jp/ikoiko/js/都道府県検索MB.js'></script>

    <!-- テーブルの簡易的なCSS例 -->
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
</head>
<body>

<div id="pageTop">
    <div id="inner">
        <ul>
            <li><a href="//koikoi.co.jp/ikoiko/">アニメコン</a></li>
            <li><a href="//koikoi.co.jp/ikoiko/machi/">街コン</a></li>
            <!--
            <li><a href="//koikoi.co.jp/ikoiko/nazo/">謎解き</a></li>
            <li><a href="//koikoi.co.jp/ikoiko/off/">オフ会</a></li>
            -->
        </ul>        
    </div>

    <h1 id="catch" style="background-color:#FF3686;">
        婚活・恋活・友活ならこいこい街コン
    </h1>
</div>

<div id="topContainer">
    <div id="pageHeader">
        <?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/pageHeader.php") ?>
    </div>

    <div id="mainVisual">
        <?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/machi/mainVisual.php") ?>
    </div>

    <div id="search">
        <?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/machi/search.php") ?>
    </div>

    <div id="mainContainer">
        <div id="mainContent">
            <div id="searchResult">
            <?php
            $today = date("Y") . '-' . date("m") . '-' . date("j");

            $sql = "
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
                WHERE {$where_1} {$where_2}
                  AND area.page = 'machi'
                ORDER BY events.date;
            ";
            $result = $db->query($sql);

            if (empty($searchArea)) {
                $searchArea = '全国';
            }

            echo "<h2>{$searchArea}の街コン情報</h2>";
            echo "<div id='resultList'>";

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

                // $dateから年、月、日を分割
                $y = strtok($date, '-');
                $m = strtok('-');
                $d = strtok('-');

                // 開始と終了時刻を分割
                $begin_H = strtok($begin, ':');
                $begin_M = strtok(':');

                $end_H = strtok($end, ':');
                $end_M = strtok(':');

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

                // タイトルが未設定の場合、イベントタイプとエリアを基に生成
                if (empty($title)) {
                    $title = $eventType . $area_ja;
                }

                // 画像URLが未設定の場合は旧処理
                if (empty($img_url)) {
                    $img_url = "/ikoiko/img/img_thamb/{$find}";
                }

                // テーブルで価格表示
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
                    <span class='eventName'>
                      <a href='//koikoi.co.jp/ikoiko/event_m/{$area}'>{$title}</a>
                    </span>
                    <span class='dateTime'>
                      {$m}月{$d}日({$week}){$begin_H}:{$begin_M}-{$end_H}:{$end_M}
                    </span>
                    <span class='price'>
                      {$price_table_html}
                    </span>
                  </div>
                </div>
                ";
            }

            echo "</div>";
            ?>
            </div>
        </div>

        <div id="sideContent">
            <?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/sideContent_m.php") ?>
        </div>
    </div>
</div>

<?php include("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/widgets/footer.php") ?>

</body>
</html>
