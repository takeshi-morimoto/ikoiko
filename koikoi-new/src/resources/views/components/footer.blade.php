@php
$regions = [
    '北海道・東北' => [
        ['code' => 'hokkaido', 'name' => '北海道'],
        ['code' => 'miyagi', 'name' => '宮城'],
        ['code' => 'fukushima', 'name' => '福島'],
    ],
    '関東' => [
        ['code' => 'tokyo', 'name' => '東京'],
        ['code' => 'kanagawa', 'name' => '神奈川'],
        ['code' => 'saitama', 'name' => '埼玉'],
        ['code' => 'chiba', 'name' => '千葉'],
        ['code' => 'ibaraki', 'name' => '茨城'],
        ['code' => 'tochigi', 'name' => '栃木'],
        ['code' => 'gunma', 'name' => '群馬'],
    ],
    '中部' => [
        ['code' => 'aichi', 'name' => '愛知'],
        ['code' => 'shizuoka', 'name' => '静岡'],
        ['code' => 'niigata', 'name' => '新潟'],
        ['code' => 'nagano', 'name' => '長野'],
        ['code' => 'gifu', 'name' => '岐阜'],
    ],
    '近畿' => [
        ['code' => 'osaka', 'name' => '大阪'],
        ['code' => 'kyoto', 'name' => '京都'],
        ['code' => 'hyogo', 'name' => '兵庫'],
        ['code' => 'nara', 'name' => '奈良'],
        ['code' => 'shiga', 'name' => '滋賀'],
        ['code' => 'wakayama', 'name' => '和歌山'],
    ],
    '中国・四国' => [
        ['code' => 'hiroshima', 'name' => '広島'],
        ['code' => 'okayama', 'name' => '岡山'],
        ['code' => 'kagawa', 'name' => '香川'],
        ['code' => 'ehime', 'name' => '愛媛'],
    ],
    '九州・沖縄' => [
        ['code' => 'fukuoka', 'name' => '福岡'],
        ['code' => 'kumamoto', 'name' => '熊本'],
        ['code' => 'kagoshima', 'name' => '鹿児島'],
        ['code' => 'okinawa', 'name' => '沖縄'],
    ],
];
@endphp

<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>サービス</h3>
                <ul>
                    <li><a href="/anime/">アニメコン</a></li>
                    <li><a href="/machi/">街コン</a></li>
                    <li><a href="/events/">全イベント一覧</a></li>
                    <li><a href="/events/calendar/">カレンダー</a></li>
                </ul>
            </div>
            
            <!-- 地域別リンク -->
            @foreach($regions as $regionName => $prefectures)
            <div class="footer-section">
                <h3>{{ $regionName }}</h3>
                <ul>
                    @foreach($prefectures as $prefecture)
                    <li><a href="/area/{{ $prefecture['code'] }}/">{{ $prefecture['name'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            @endforeach
            
            <div class="footer-section">
                <h3>サポート</h3>
                <ul>
                    <li><a href="/contact/">お問い合わせ</a></li>
                    <li><a href="/faq/">よくある質問</a></li>
                    <li><a href="/guide/">ご利用ガイド</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>会社情報</h3>
                <ul>
                    <li><a href="/company/">会社概要</a></li>
                    <li><a href="/terms/">利用規約</a></li>
                    <li><a href="/privacy/">プライバシーポリシー</a></li>
                    <li><a href="/law/">特定商取引法に基づく表記</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} KOIKOI. All rights reserved.</p>
        </div>
    </div>
</footer>