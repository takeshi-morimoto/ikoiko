<?php 

if ( isset($searchArea) ):

  $area = $searchArea. '/';

else: 

  $area = '';

endif;


if ( isset($searchDate) ):

  $date = $searchDate. '/';

else: 

  $date = '';

endif;

print 

"
<div class='areaSearch_pc'>

  <h2>街コンをエリアで検索</h2>

  <table>
    <tr>
      <th>関東</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'off', '東京', '') . "' href='//koikoi.co.jp/ikoiko/list_4/東京'>東京</a></li>
              <li><a class='" . CSR( 'off', '神奈川', '') . "' href='//koikoi.co.jp/ikoiko/list_4/神奈川'>神奈川</a></li>
              <li><a class='" . CSR( 'off', '千葉', '') . "' href='//koikoi.co.jp/ikoiko/list_4/千葉'>千葉</a></li>
              <li><a class='" . CSR( 'off', '埼玉', '') . "' href='//koikoi.co.jp/ikoiko/list_4/埼玉'>埼玉</a></li>
              <li><a class='" . CSR( 'off', '茨城', '') . "' href='//koikoi.co.jp/ikoiko/list_4/茨城'>茨城</a></li>
              <li><a class='" . CSR( 'off', '群馬', '') . "' href='//koikoi.co.jp/ikoiko/list_4/群馬'>群馬</a></li>
              <li><a class='" . CSR( 'off', '栃木', '') . "' href='//koikoi.co.jp/ikoiko/list_4/栃木'>栃木</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>東北・北海道</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'off', '北海道', '') . "' href='//koikoi.co.jp/ikoiko/list_4/北海道'>北海道</a></li>
              <li><a class='" . CSR( 'off', '青森', '') . "' href='//koikoi.co.jp/ikoiko/list_4/青森'>青森</a></li>
              <li><a class='" . CSR( 'off', '岩手', '') . "' href='//koikoi.co.jp/ikoiko/list_4/岩手'>岩手</a></li>
              <li><a class='" . CSR( 'off', '秋田', '') . "' href='//koikoi.co.jp/ikoiko/list_4/秋田'>秋田</a></li>
              <li><a class='" . CSR( 'off', '宮城', '') . "' href='//koikoi.co.jp/ikoiko/list_4/宮城'>宮城</a></li>
              <li><a class='" . CSR( 'off', '山形', '') . "' href='//koikoi.co.jp/ikoiko/list_4/山形'>山形</a></li>
              <li><a class='" . CSR( 'off', '福島', '') . "' href='//koikoi.co.jp/ikoiko/list_4/福島'>福島</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>北陸・甲信越</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'off', '新潟', '') . "' href='//koikoi.co.jp/ikoiko/list_4/新潟'>新潟</a></li>
              <li><a class='" . CSR( 'off', '石川', '') . "' href='//koikoi.co.jp/ikoiko/list_4/石川'>石川</a></li>
              <li><a class='" . CSR( 'off', '富山', '') . "' href='//koikoi.co.jp/ikoiko/list_4/富山'>富山</a></li>
              <li><a class='" . CSR( 'off', '福井', '') . "' href='//koikoi.co.jp/ikoiko/list_4/福井'>福井</a></li>
              <li><a class='" . CSR( 'off', '長野', '') . "' href='//koikoi.co.jp/ikoiko/list_4/長野'>長野</a></li>
              <li><a class='" . CSR( 'off', '山梨', '') . "' href='//koikoi.co.jp/ikoiko/list_4/山梨'>山梨</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>中部</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'off', '愛知', '') . "' href='//koikoi.co.jp/ikoiko/list_4/愛知'>愛知</a></li>
              <li><a class='" . CSR( 'off', '岐阜', '') . "' href='//koikoi.co.jp/ikoiko/list_4/岐阜'>岐阜</a></li>
              <li><a class='" . CSR( 'off', '三重', '') . "' href='//koikoi.co.jp/ikoiko/list_4/三重'>三重</a></li>
              <li><a class='" . CSR( 'off', '静岡', '') . "' href='//koikoi.co.jp/ikoiko/list_4/静岡'>静岡</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>関西</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'off', '大阪', '') . "' href='//koikoi.co.jp/ikoiko/list_4/大阪'>大阪</a></li>
              <li><a class='" . CSR( 'off', '兵庫', '') . "' href='//koikoi.co.jp/ikoiko/list_4/兵庫'>兵庫</a></li>
              <li><a class='" . CSR( 'off', '京都', '') . "' href='//koikoi.co.jp/ikoiko/list_4/京都'>京都</a></li>
              <li><a class='" . CSR( 'off', '滋賀', '') . "' href='//koikoi.co.jp/ikoiko/list_4/滋賀'>滋賀</a></li>
              <li><a class='" . CSR( 'off', '奈良', '') . "' href='//koikoi.co.jp/ikoiko/list_4/奈良'>奈良</a></li>
              <li><a class='" . CSR( 'off', '和歌山', '') . "' href='//koikoi.co.jp/ikoiko/list_4/和歌山'>和歌山</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>中国</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'off', '広島', '') . "' href='//koikoi.co.jp/ikoiko/list_4/広島'>広島</a></li>
              <li><a class='" . CSR( 'off', '山口', '') . "' href='//koikoi.co.jp/ikoiko/list_4/山口'>山口</a></li>
              <li><a class='" . CSR( 'off', '島根', '') . "' href='//koikoi.co.jp/ikoiko/list_4/島根'>島根</a></li>
              <li><a class='" . CSR( 'off', '鳥取', '') . "' href='//koikoi.co.jp/ikoiko/list_4/鳥取'>鳥取</a></li>
              <li><a class='" . CSR( 'off', '岡山', '') . "' href='//koikoi.co.jp/ikoiko/list_4/岡山'>岡山</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>四国</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'off', '愛媛', '') . "' href='//koikoi.co.jp/ikoiko/list_4/愛媛'>愛媛</a></li>
              <li><a class='" . CSR( 'off', '香川', '') . "' href='//koikoi.co.jp/ikoiko/list_4/香川'>香川</a></li>
              <li><a class='" . CSR( 'off', '高知', '') . "' href='//koikoi.co.jp/ikoiko/list_4/高知'>高知</a></li>
              <li><a class='" . CSR( 'off', '徳島', '') . "' href='//koikoi.co.jp/ikoiko/list_4/徳島'>徳島</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>九州</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'off', '福岡', '') . "' href='//koikoi.co.jp/ikoiko/list_4/福岡'>福岡</a></li>
              <li><a class='" . CSR( 'off', '佐賀', '') . "' href='//koikoi.co.jp/ikoiko/list_4/佐賀'>佐賀</a></li>
              <li><a class='" . CSR( 'off', '長崎', '') . "' href='//koikoi.co.jp/ikoiko/list_4/長崎'>長崎</a></li>
              <li><a class='" . CSR( 'off', '大分', '') . "' href='//koikoi.co.jp/ikoiko/list_4/大分'>大分</a></li>
              <li><a class='" . CSR( 'off', '熊本', '') . "' href='//koikoi.co.jp/ikoiko/list_4/熊本'>熊本</a></li>
              <li><a class='" . CSR( 'off', '宮崎', '') . "' href='//koikoi.co.jp/ikoiko/list_4/宮崎'>宮崎</a></li>
              <li><a class='" . CSR( 'off', '鹿児島', '') . "' href='//koikoi.co.jp/ikoiko/list_4/鹿児島'>鹿児島</a></li>
              <li><a class='" . CSR( 'off', '沖縄', '') . "' href='//koikoi.co.jp/ikoiko/list_4/沖縄'>沖縄</a></li>
            </ul>
      </td>
    </tr>

  </table>

</div>

<div class='areaSearch_mb'>

  <h2>街コンをエリアで検索</h2>

            <ul class='region_1'>
              <li><span>関東</span></li>
              <li><span>北海道</span></li>
              <li><span>東北</span></li>
              <li><span>中部</span></li>
            </ul>



            <div class='area'>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/東京'>東京</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/神奈川'>神奈川</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/千葉'>千葉</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/埼玉'>埼玉</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/茨城'>茨城</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/群馬'>群馬</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/栃木'>栃木</a></li>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/北海道'>北海道</a></li>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/青森'>青森</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/岩手'>岩手</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/秋田'>秋田</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/宮城'>宮城</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/山形'>山形</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/福島'>福島</a></li>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/愛知'>愛知</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/静岡'>静岡</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/岐阜'>岐阜</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/新潟'>新潟</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/石川'>石川</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/富山'>富山</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/福井'>福井</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/長野'>長野</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/山梨'>山梨</a></li>
              </ul>
              <ul>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/三重'>三重</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/大阪'>大阪</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/兵庫'>兵庫</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/京都'>京都</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/滋賀'>滋賀</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/奈良'>奈良</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/和歌山'>和歌山</a></li>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/広島'>広島</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/山口'>山口</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/島根'>島根</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/鳥取'>鳥取</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/岡山'>岡山</a></li>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/愛媛'>愛媛</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/香川'>香川</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/高知'>高知</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/徳島'>徳島</a></li>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/福岡'>福岡</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/佐賀'>佐賀</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/長崎'>長崎</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/大分'>大分</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/熊本'>熊本</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/宮崎'>宮崎</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/鹿児島'>鹿児島</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_4/沖縄'>沖縄</a></li>
              </ul>
            </div>






            <ul class='region_2'>
              <li><span>近畿</span></li>
              <li><span>中国</span></li>
              <li><span>四国</span></li>
              <li><span>九州</span></li>
            </ul>

            


</div>

";

?>