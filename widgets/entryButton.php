<?php 

$obj = $db->query("select ken from area where `area` = '$area' ;");
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
    $meetingPoint = 
      "
      <div class='meetingPoint'>
        <div class='showBody'>詳細情報を表示</div>
        <div class='hideBody'>閉じる</div>
        <div class='body'>{$eventData['pr_comment']}</div>
      </div>
      ";
  }

  //申込ボタンを出力
  print 
        "
          <div class='event-card'>
            <div class='event-image'>
              <img src='{$url}' alt='{$eventData['title']}'>
            </div>
            <div class='event-content'>
              <div class='event-title'>{$eventData['title']}</div>
              <div class='event-date'>
                {$dateTime['m']}月{$dateTime['d']}日({$dateTime['w']}) {$dateTime['bh']}:{$dateTime['bm']}～{$dateTime['eh']}:{$dateTime['em']}
              </div>
              <div class='event-buttons'>
                <a class='btn-apply' href='{$sale}'>このイベントに申し込む</a>
                <a class='btn-detail' href='#' onclick='showDetails(this); return false;'>詳細を表示</a>
              </div>
              <div class='event-details' style='display:none;'>
                {$eventData['pr_comment']}
              </div>
            </div>
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

<style>
.event-card {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
  background: #fff;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  display: flex;
  gap: 20px;
}

.event-image {
  flex-shrink: 0;
  width: 200px;
}

.event-image img {
  width: 100%;
  height: auto;
  border-radius: 5px;
}

.event-content {
  flex: 1;
}

.event-title {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 10px;
  color: #333;
}

.event-date {
  font-size: 14px;
  color: #666;
  margin-bottom: 15px;
}

.event-buttons {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.btn-apply, .btn-detail {
  padding: 10px 20px;
  border-radius: 5px;
  text-decoration: none;
  font-weight: bold;
  text-align: center;
  min-width: 120px;
}

.btn-apply {
  background: #FF9933;
  color: white;
}

.btn-apply:hover {
  background: #e68a00;
}

.btn-detail {
  background: #4CAF50;
  color: white;
}

.btn-detail:hover {
  background: #45a049;
}

.event-details {
  margin-top: 15px;
  padding: 15px;
  background: #f9f9f9;
  border-radius: 5px;
  border-left: 4px solid #4CAF50;
}

@media (max-width: 768px) {
  .event-card {
    flex-direction: column;
  }
  
  .event-image {
    width: 100%;
  }
}
</style>

<script>
function showDetails(button) {
  var card = button.closest('.event-card');
  var details = card.querySelector('.event-details');
  
  if (details.style.display === 'none') {
    details.style.display = 'block';
    button.textContent = '詳細を閉じる';
  } else {
    details.style.display = 'none';
    button.textContent = '詳細を表示';
  }
}

$(function(){
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