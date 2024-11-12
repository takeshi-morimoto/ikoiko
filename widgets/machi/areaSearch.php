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
              <li><a class='" . CSR( 'machi', '東京', '') . "' href='//koikoi.co.jp/ikoiko/list_2/東京'>東京</a></li>
              <li><a class='" . CSR( 'machi', '神奈川', '') . "' href='//koikoi.co.jp/ikoiko/list_2/神奈川'>神奈川</a></li>
              <li><a class='" . CSR( 'machi', '千葉', '') . "' href='//koikoi.co.jp/ikoiko/list_2/千葉'>千葉</a></li>
              <li><a class='" . CSR( 'machi', '埼玉', '') . "' href='//koikoi.co.jp/ikoiko/list_2/埼玉'>埼玉</a></li>
              <li><a class='" . CSR( 'machi', '茨城', '') . "' href='//koikoi.co.jp/ikoiko/list_2/茨城'>茨城</a></li>
              <li><a class='" . CSR( 'machi', '群馬', '') . "' href='//koikoi.co.jp/ikoiko/list_2/群馬'>群馬</a></li>
              <li><a class='" . CSR( 'machi', '栃木', '') . "' href='//koikoi.co.jp/ikoiko/list_2/栃木'>栃木</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>東北・北海道</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'machi', '北海道', '') . "' href='//koikoi.co.jp/ikoiko/list_2/北海道'>北海道</a></li>
              <li><a class='" . CSR( 'machi', '青森', '') . "' href='//koikoi.co.jp/ikoiko/list_2/青森'>青森</a></li>
              <li><a class='" . CSR( 'machi', '岩手', '') . "' href='//koikoi.co.jp/ikoiko/list_2/岩手'>岩手</a></li>
              <li><a class='" . CSR( 'machi', '秋田', '') . "' href='//koikoi.co.jp/ikoiko/list_2/秋田'>秋田</a></li>
              <li><a class='" . CSR( 'machi', '宮城', '') . "' href='//koikoi.co.jp/ikoiko/list_2/宮城'>宮城</a></li>
              <li><a class='" . CSR( 'machi', '山形', '') . "' href='//koikoi.co.jp/ikoiko/list_2/山形'>山形</a></li>
              <li><a class='" . CSR( 'machi', '福島', '') . "' href='//koikoi.co.jp/ikoiko/list_2/福島'>福島</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>北陸・甲信越</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'machi', '新潟', '') . "' href='//koikoi.co.jp/ikoiko/list_2/新潟'>新潟</a></li>
              <li><a class='" . CSR( 'machi', '石川', '') . "' href='//koikoi.co.jp/ikoiko/list_2/石川'>石川</a></li>
              <li><a class='" . CSR( 'machi', '富山', '') . "' href='//koikoi.co.jp/ikoiko/list_2/富山'>富山</a></li>
              <li><a class='" . CSR( 'machi', '福井', '') . "' href='//koikoi.co.jp/ikoiko/list_2/福井'>福井</a></li>
              <li><a class='" . CSR( 'machi', '長野', '') . "' href='//koikoi.co.jp/ikoiko/list_2/長野'>長野</a></li>
              <li><a class='" . CSR( 'machi', '山梨', '') . "' href='//koikoi.co.jp/ikoiko/list_2/山梨'>山梨</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>中部</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'machi', '愛知', '') . "' href='//koikoi.co.jp/ikoiko/list_2/愛知'>愛知</a></li>
              <li><a class='" . CSR( 'machi', '岐阜', '') . "' href='//koikoi.co.jp/ikoiko/list_2/岐阜'>岐阜</a></li>
              <li><a class='" . CSR( 'machi', '三重', '') . "' href='//koikoi.co.jp/ikoiko/list_2/三重'>三重</a></li>
              <li><a class='" . CSR( 'machi', '静岡', '') . "' href='//koikoi.co.jp/ikoiko/list_2/静岡'>静岡</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>関西</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'machi', '大阪', '') . "' href='//koikoi.co.jp/ikoiko/list_2/大阪'>大阪</a></li>
              <li><a class='" . CSR( 'machi', '兵庫', '') . "' href='//koikoi.co.jp/ikoiko/list_2/兵庫'>兵庫</a></li>
              <li><a class='" . CSR( 'machi', '京都', '') . "' href='//koikoi.co.jp/ikoiko/list_2/京都'>京都</a></li>
              <li><a class='" . CSR( 'machi', '滋賀', '') . "' href='//koikoi.co.jp/ikoiko/list_2/滋賀'>滋賀</a></li>
              <li><a class='" . CSR( 'machi', '奈良', '') . "' href='//koikoi.co.jp/ikoiko/list_2/奈良'>奈良</a></li>
              <li><a class='" . CSR( 'machi', '和歌山', '') . "' href='//koikoi.co.jp/ikoiko/list_2/和歌山'>和歌山</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>中国</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'machi', '広島', '') . "' href='//koikoi.co.jp/ikoiko/list_2/広島'>広島</a></li>
              <li><a class='" . CSR( 'machi', '山口', '') . "' href='//koikoi.co.jp/ikoiko/list_2/山口'>山口</a></li>
              <li><a class='" . CSR( 'machi', '島根', '') . "' href='//koikoi.co.jp/ikoiko/list_2/島根'>島根</a></li>
              <li><a class='" . CSR( 'machi', '鳥取', '') . "' href='//koikoi.co.jp/ikoiko/list_2/鳥取'>鳥取</a></li>
              <li><a class='" . CSR( 'machi', '岡山', '') . "' href='//koikoi.co.jp/ikoiko/list_2/岡山'>岡山</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>四国</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'machi', '愛媛', '') . "' href='//koikoi.co.jp/ikoiko/list_2/愛媛'>愛媛</a></li>
              <li><a class='" . CSR( 'machi', '香川', '') . "' href='//koikoi.co.jp/ikoiko/list_2/香川'>香川</a></li>
              <li><a class='" . CSR( 'machi', '高知', '') . "' href='//koikoi.co.jp/ikoiko/list_2/高知'>高知</a></li>
              <li><a class='" . CSR( 'machi', '徳島', '') . "' href='//koikoi.co.jp/ikoiko/list_2/徳島'>徳島</a></li>
            </ul>
      </td>
    </tr>
    <tr>
      <th>九州</th>
      <td>
            <ul>
              <li><a class='" . CSR( 'machi', '福岡', '') . "' href='//koikoi.co.jp/ikoiko/list_2/福岡'>福岡</a></li>
              <li><a class='" . CSR( 'machi', '佐賀', '') . "' href='//koikoi.co.jp/ikoiko/list_2/佐賀'>佐賀</a></li>
              <li><a class='" . CSR( 'machi', '長崎', '') . "' href='//koikoi.co.jp/ikoiko/list_2/長崎'>長崎</a></li>
              <li><a class='" . CSR( 'machi', '大分', '') . "' href='//koikoi.co.jp/ikoiko/list_2/大分'>大分</a></li>
              <li><a class='" . CSR( 'machi', '熊本', '') . "' href='//koikoi.co.jp/ikoiko/list_2/熊本'>熊本</a></li>
              <li><a class='" . CSR( 'machi', '宮崎', '') . "' href='//koikoi.co.jp/ikoiko/list_2/宮崎'>宮崎</a></li>
              <li><a class='" . CSR( 'machi', '鹿児島', '') . "' href='//koikoi.co.jp/ikoiko/list_2/鹿児島'>鹿児島</a></li>
              <li><a class='" . CSR( 'machi', '沖縄', '') . "' href='//koikoi.co.jp/ikoiko/list_2/沖縄'>沖縄</a></li>
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
                <li><a href='//koikoi.co.jp/ikoiko/list_2/東京'>東京</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/神奈川'>神奈川</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/千葉'>千葉</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/埼玉'>埼玉</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/茨城'>茨城</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/群馬'>群馬</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/栃木'>栃木</a></li>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/北海道'>北海道</a></li>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/青森'>青森</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/岩手'>岩手</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/秋田'>秋田</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/宮城'>宮城</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/山形'>山形</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/福島'>福島</a></li>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/愛知'>愛知</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/静岡'>静岡</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/岐阜'>岐阜</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/新潟'>新潟</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/石川'>石川</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/富山'>富山</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/福井'>福井</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/長野'>長野</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/山梨'>山梨</a></li>
              </ul>
              <ul>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/三重'>三重</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/大阪'>大阪</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/兵庫'>兵庫</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/京都'>京都</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/滋賀'>滋賀</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/奈良'>奈良</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/和歌山'>和歌山</a></li>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/広島'>広島</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/山口'>山口</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/島根'>島根</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/鳥取'>鳥取</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/岡山'>岡山</a></li>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/愛媛'>愛媛</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/香川'>香川</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/高知'>高知</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/徳島'>徳島</a></li>
              </ul>
              <ul>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/福岡'>福岡</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/佐賀'>佐賀</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/長崎'>長崎</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/大分'>大分</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/熊本'>熊本</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/宮崎'>宮崎</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/鹿児島'>鹿児島</a></li>
                <li><a href='//koikoi.co.jp/ikoiko/list_2/沖縄'>沖縄</a></li>
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