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
          <div class='row'>
            <div><img src='{$url}'></div>
            <div class='feature'>{$eventData['feature']}</div>
            <h3 class='title'>{$eventData['title']}</h3>
            <div class='info-box'>
              <div class='datetime-box'>
                <p class='dateTime'>
                  {$dateTime['m']}月{$dateTime['d']}日({$dateTime['w']})<br>
                  {$dateTime['bh']}:{$dateTime['bm']}～{$dateTime['eh']}:{$dateTime['em']}
                </p>
              </div>
              <div class='button-box'>
                <a class='entryButton' href='{$sale}'><img src='//koikoi.co.jp/ikoiko/img/entry.jpg' alt='' /></a>
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