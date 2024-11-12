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
              <li><a class='" . CSR( 'nazo', '東京', '') . "' href='//koikoi.co.jp/ikoiko/list_3/東京'>東京</a></li>
              <li><a class='" . CSR( 'nazo', '神奈川', '') . "' href='//koikoi.co.jp/ikoiko/list_3/神奈川'>神奈川</a></li>
              <li><a class='" . CSR( 'nazo', '千葉', '') . "' href='//koikoi.co.jp/ikoiko/list_3/千葉'>千葉</a></li>
              <li><a class='" . CSR( 'nazo', '埼玉', '') . "' href='//koikoi.co.jp/ikoiko/list_3/埼玉'>埼玉</a></li>
              <li><a class='" . CSR( 'nazo', '茨城', '') . "' href='//koikoi.co.jp/ikoiko/list_3/茨城'>茨城</a></li>
              <li><a class='" . CSR( 'nazo', '群馬', '') . "' href='//koikoi.co.jp/ikoiko/list_3/群馬'>群馬</a></li>
              <li><a class='" . CSR( 'nazo', '栃木', '') . "' href='//koikoi.co.jp/ikoiko/list_3/栃木'>栃木</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>東北・北海道</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'nazo', '北海道', '') . "' href='//koikoi.co.jp/ikoiko/list_3/北海道'>北海道</a></li>
              <li><a class='" . CSR( 'nazo', '青森', '') . "' href='//koikoi.co.jp/ikoiko/list_3/青森'>青森</a></li>
              <li><a class='" . CSR( 'nazo', '岩手', '') . "' href='//koikoi.co.jp/ikoiko/list_3/岩手'>岩手</a></li>
              <li><a class='" . CSR( 'nazo', '秋田', '') . "' href='//koikoi.co.jp/ikoiko/list_3/秋田'>秋田</a></li>
              <li><a class='" . CSR( 'nazo', '宮城', '') . "' href='//koikoi.co.jp/ikoiko/list_3/宮城'>宮城</a></li>
              <li><a class='" . CSR( 'nazo', '山形', '') . "' href='//koikoi.co.jp/ikoiko/list_3/山形'>山形</a></li>
              <li><a class='" . CSR( 'nazo', '福島', '') . "' href='//koikoi.co.jp/ikoiko/list_3/福島'>福島</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>北陸・甲信越</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'nazo', '新潟', '') . "' href='//koikoi.co.jp/ikoiko/list_3/新潟'>新潟</a></li>
              <li><a class='" . CSR( 'nazo', '石川', '') . "' href='//koikoi.co.jp/ikoiko/list_3/石川'>石川</a></li>
              <li><a class='" . CSR( 'nazo', '富山', '') . "' href='//koikoi.co.jp/ikoiko/list_3/富山'>富山</a></li>
              <li><a class='" . CSR( 'nazo', '福井', '') . "' href='//koikoi.co.jp/ikoiko/list_3/福井'>福井</a></li>
              <li><a class='" . CSR( 'nazo', '長野', '') . "' href='//koikoi.co.jp/ikoiko/list_3/長野'>長野</a></li>
              <li><a class='" . CSR( 'nazo', '山梨', '') . "' href='//koikoi.co.jp/ikoiko/list_3/山梨'>山梨</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>中部</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'nazo', '愛知', '') . "' href='//koikoi.co.jp/ikoiko/list_3/愛知'>愛知</a></li>
              <li><a class='" . CSR( 'nazo', '岐阜', '') . "' href='//koikoi.co.jp/ikoiko/list_3/岐阜'>岐阜</a></li>
              <li><a class='" . CSR( 'nazo', '三重', '') . "' href='//koikoi.co.jp/ikoiko/list_3/三重'>三重</a></li>
              <li><a class='" . CSR( 'nazo', '静岡', '') . "' href='//koikoi.co.jp/ikoiko/list_3/静岡'>静岡</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>関西</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'nazo', '大阪', '') . "' href='//koikoi.co.jp/ikoiko/list_3/大阪'>大阪</a></li>
              <li><a class='" . CSR( 'nazo', '兵庫', '') . "' href='//koikoi.co.jp/ikoiko/list_3/兵庫'>兵庫</a></li>
              <li><a class='" . CSR( 'nazo', '京都', '') . "' href='//koikoi.co.jp/ikoiko/list_3/京都'>京都</a></li>
              <li><a class='" . CSR( 'nazo', '滋賀', '') . "' href='//koikoi.co.jp/ikoiko/list_3/滋賀'>滋賀</a></li>
              <li><a class='" . CSR( 'nazo', '奈良', '') . "' href='//koikoi.co.jp/ikoiko/list_3/奈良'>奈良</a></li>
              <li><a class='" . CSR( 'nazo', '和歌山', '') . "' href='//koikoi.co.jp/ikoiko/list_3/和歌山'>和歌山</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>中国</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'nazo', '広島', '') . "' href='//koikoi.co.jp/ikoiko/list_3/広島'>広島</a></li>
              <li><a class='" . CSR( 'nazo', '山口', '') . "' href='//koikoi.co.jp/ikoiko/list_3/山口'>山口</a></li>
              <li><a class='" . CSR( 'nazo', '島根', '') . "' href='//koikoi.co.jp/ikoiko/list_3/島根'>島根</a></li>
              <li><a class='" . CSR( 'nazo', '鳥取', '') . "' href='//koikoi.co.jp/ikoiko/list_3/鳥取'>鳥取</a></li>
              <li><a class='" . CSR( 'nazo', '岡山', '') . "' href='//koikoi.co.jp/ikoiko/list_3/岡山'>岡山</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>四国</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'nazo', '愛媛', '') . "' href='//koikoi.co.jp/ikoiko/list_3/愛媛'>愛媛</a></li>
              <li><a class='" . CSR( 'nazo', '香川', '') . "' href='//koikoi.co.jp/ikoiko/list_3/香川'>香川</a></li>
              <li><a class='" . CSR( 'nazo', '高知', '') . "' href='//koikoi.co.jp/ikoiko/list_3/高知'>高知</a></li>
              <li><a class='" . CSR( 'nazo', '徳島', '') . "' href='//koikoi.co.jp/ikoiko/list_3/徳島'>徳島</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>九州</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'nazo', '福岡', '') . "' href='//koikoi.co.jp/ikoiko/list_3/福岡'>福岡</a></li>
              <li><a class='" . CSR( 'nazo', '佐賀', '') . "' href='//koikoi.co.jp/ikoiko/list_3/佐賀'>佐賀</a></li>
              <li><a class='" . CSR( 'nazo', '長崎', '') . "' href='//koikoi.co.jp/ikoiko/list_3/長崎'>長崎</a></li>
              <li><a class='" . CSR( 'nazo', '大分', '') . "' href='//koikoi.co.jp/ikoiko/list_3/大分'>大分</a></li>
              <li><a class='" . CSR( 'nazo', '熊本', '') . "' href='//koikoi.co.jp/ikoiko/list_3/熊本'>熊本</a></li>
              <li><a class='" . CSR( 'nazo', '宮崎', '') . "' href='//koikoi.co.jp/ikoiko/list_3/宮崎'>宮崎</a></li>
              <li><a class='" . CSR( 'nazo', '鹿児島', '') . "' href='//koikoi.co.jp/ikoiko/list_3/鹿児島'>鹿児島</a></li>
              <li><a class='" . CSR( 'nazo', '沖縄', '') . "' href='//koikoi.co.jp/ikoiko/list_3/沖縄'>沖縄</a></li>
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
                <li><a href='//koikoi.co.jp/ikoiko/list_3/東京'>東京</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/神奈川'>神奈川</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/千葉'>千葉</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/埼玉'>埼玉</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/茨城'>茨城</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/群馬'>群馬</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/栃木'>栃木</a></li>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/北海道'>北海道</a></li>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/青森'>青森</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/岩手'>岩手</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/秋田'>秋田</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/宮城'>宮城</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/山形'>山形</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/福島'>福島</a></li>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/愛知'>愛知</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/静岡'>静岡</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/岐阜'>岐阜</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/新潟'>新潟</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/石川'>石川</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/富山'>富山</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/福井'>福井</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/長野'>長野</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/山梨'>山梨</a></li>
              </ul>
              <ul>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/三重'>三重</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/大阪'>大阪</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/兵庫'>兵庫</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/京都'>京都</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/滋賀'>滋賀</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/奈良'>奈良</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/和歌山'>和歌山</a></li>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/広島'>広島</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/山口'>山口</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/島根'>島根</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/鳥取'>鳥取</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/岡山'>岡山</a></li>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/愛媛'>愛媛</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/香川'>香川</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/高知'>高知</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/徳島'>徳島</a></li>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/福岡'>福岡</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/佐賀'>佐賀</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/長崎'>長崎</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/大分'>大分</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/熊本'>熊本</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/宮崎'>宮崎</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/鹿児島'>鹿児島</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_3/沖縄'>沖縄</a></li>
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