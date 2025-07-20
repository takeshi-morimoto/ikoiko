<?php 

$obj = $db->query("select * from area where `area` = '$area' ;");
$tmp = $obj->fetch();

//今日の日付を取得
$today = date('Y-m-j');

//イベントのデータを読み込み
$eventDataTmp = $db->query("select * from events where `area` = '$area' and `date` >= '$today' order by date ;") ;

$n = 0;
$isExtra = false;
while ( $eventData = $eventDataTmp->fetch() ):

  if ($n === 0) {
    print "<h2 style='text-align:center;'>{$tmp['ken']}お申し込みはこちら</h2><div class='entryArea'>";
  }
  // 画像URLが未設定の場合は旧の処理
  if ( empty($eventData['img_url']) ) {
    $url = "/ikoiko/img/img_thamb/{$eventData['find']}";
  } else {
    $url = $eventData['img_url'];
  }

  // アイテムが４つ以上あるときは開閉して表示
  if ( $n === 3 ) { 
    $isExtra = true; 
    echo '<div class="showMore"><span class="show">以降のイベントを表示</span><span class="hide">閉じる</span></div>';
    echo '<div class="extra">';
  }

  $find = $eventData['find'];
  $meetingPoint = '';

  //セール情報が登録されていれば表示
  if ( ! empty($eventData['sale']) ):

    // セール情報
    $sale = $eventData['sale'];

  endif;


  //読み込んだイベントデータの日程と時刻を変数にセット
  $dateTime = array(
                'y' => strtok($eventData['date'], '-') , 'm' => strtok('-') , 'd' => strtok('-') , //開催日
                'w' => $eventData['week'] ,                                                        //曜日
                'bh' => strtok($eventData['begin'], ':') , 'bm' => strtok(':') ,                   //開始時刻
                'eh' => strtok($eventData['end'], ':') , 'em' => strtok(':') ,                     //終了時刻
              );

  if ( !empty($eventData['pr_comment']) ) {
    
    // 価格情報の組み立て
    $priceHTML = "";
    
    // イベント個別の価格がある場合
    if (!empty($eventData['price_m']) || !empty($eventData['price_f'])) {
      if (!empty($eventData['price_m']) || !empty($eventData['price_f'])) {
        $priceHTML .= "<tr><th>参加費</th><td>";
        if (!empty($eventData['price_m'])) $priceHTML .= "男性：" . number_format($eventData['price_m']) . "円　";
        if (!empty($eventData['price_f'])) $priceHTML .= "女性：" . number_format($eventData['price_f']) . "円";
        $priceHTML .= "</td></tr>";
      }
    } 
    // エリア共通の価格がある場合
    else if (!empty($tmp['price_l'])) {
      $price_l_m = strtok($tmp['price_l'], "/");
      $price_l_w = strtok("/");
      if ($price_l_m || $price_l_w) {
        $priceHTML .= "<tr><th>参加費</th><td>";
        if ($price_l_m) $priceHTML .= "男性：" . number_format($price_l_m) . "円～　";
        if ($price_l_w) $priceHTML .= "女性：" . number_format($price_l_w) . "円～";
        $priceHTML .= "</td></tr>";
      }
    }

    // pr_commentがHTMLテーブルを含む場合と含まない場合の処理
    $bodyContent = $eventData['pr_comment'];
    if (!empty($priceHTML)) {
      if (strpos($bodyContent, '<table') !== false) {
        // 既存のテーブルに価格情報を追加
        $bodyContent = str_replace('</table>', $priceHTML . '</table>', $bodyContent);
      } else {
        // 新しいテーブルを作成
        $bodyContent = "<table id='eventPlace'>" . $priceHTML . "</table>" . $bodyContent;
      }
    }

    $meetingPoint = 
      "
      <div class='meetingPoint'>
        <div class='showBody'>詳細情報を表示</div>
        <div class='hideBody'>閉じる</div>
        <div class='body'>{$bodyContent}</div>
      </div>
      ";
  }

  //申込ボタンを出力
  print 
        "
          <div class='row'>
            <div><img src='{$url}'></div>
            <div class='feature'>{$eventData['feature']}</div>
            <h3 class='title' style='text-align: center !important; margin: 10px 0; font-size: 1.25rem; width: 100%;'>{$eventData['title']}</h3>
            <div class='info-box' style='display: flex; border: 1px solid #ddd; border-radius: 8px; overflow: visible; margin: 15px 0; min-height: 120px;'>
              <div class='datetime-box' style='flex: 1; padding: 20px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border-right: 1px solid #ddd;'>
                <p class='dateTime' style='margin: 0; font-size: 1.1rem; font-weight: 600; text-align: center;'>
                  {$dateTime['m']}月{$dateTime['d']}日({$dateTime['w']})<br>
                  {$dateTime['bh']}:{$dateTime['bm']}～{$dateTime['eh']}:{$dateTime['em']}
                </p>
              </div>
              <div class='button-box' style='flex: 1; padding: 10px 20px; display: flex; align-items: center; justify-content: center; min-height: 100px;'>
                <a class='entryButton' href='{$sale}' style='display: block; width: 100%; max-width: 280px;'><img src='//koikoi.co.jp/ikoiko/img/entry.jpg' alt='' style='width: 100%; height: auto; display: block;' /></a>
              </div>
            </div>
            {$meetingPoint}
          </div> 
        ";

  $sale = null ; //セールの初期化
  $n++;

endwhile;

if ($isExtra) {
  echo '</div><!-- end .extra -->';
}

?>
</div>
<script>
$(function(){
  $('.entryArea .showBody').on('click', function() {
    var context = $(this).closest('.meetingPoint');
    $(this).hide();
    context.find('.body').slideDown();
    context.find('.hideBody').show();
  });

  $('.entryArea .hideBody').on('click', function() {
    var context = $(this).closest('.meetingPoint');
    $(this).hide();
    context.find('.body').slideUp();
    context.find('.showBody').show();
  });

  $('.showMore .hide').hide();

  $('.showMore .show').on('click', function() {
    $('.extra').slideDown();
    $(this).hide().closest('div').find('.hide').show();
  });

  $('.showMore .hide').on('click', function() {
    $('.extra').slideUp();
    $(this).hide().closest('div').find('.show').show();
  });

});
</script> 