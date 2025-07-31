<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Prefecture;
use App\Models\Area;
use App\Models\EventType;
use App\Models\Event;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('サンプルデータの作成を開始します...');
        
        // 1. 都道府県データ
        $this->createPrefectures();
        
        // 2. イベントタイプ
        $this->createEventTypes();
        
        // 3. エリアデータ
        $this->createAreas();
        
        // 4. イベントデータ
        $this->createEvents();
        
        $this->command->info('サンプルデータの作成が完了しました！');
    }
    
    private function createPrefectures()
    {
        $this->command->info('都道府県データを作成中...');
        
        $prefectures = [
            ['code' => '13', 'code_en' => 'tokyo', 'name' => '東京都', 'display_order' => 13],
            ['code' => '14', 'code_en' => 'kanagawa', 'name' => '神奈川県', 'display_order' => 14],
            ['code' => '11', 'code_en' => 'saitama', 'name' => '埼玉県', 'display_order' => 11],
            ['code' => '12', 'code_en' => 'chiba', 'name' => '千葉県', 'display_order' => 12],
            ['code' => '27', 'code_en' => 'osaka', 'name' => '大阪府', 'display_order' => 27],
            ['code' => '23', 'code_en' => 'aichi', 'name' => '愛知県', 'display_order' => 23],
            ['code' => '40', 'code_en' => 'fukuoka', 'name' => '福岡県', 'display_order' => 40],
            ['code' => '01', 'code_en' => 'hokkaido', 'name' => '北海道', 'display_order' => 1],
            ['code' => '04', 'code_en' => 'miyagi', 'name' => '宮城県', 'display_order' => 4],
        ];
        
        foreach ($prefectures as $pref) {
            Prefecture::updateOrCreate(
                ['code' => $pref['code']],
                $pref
            );
        }
    }
    
    private function createEventTypes()
    {
        $this->command->info('イベントタイプデータを作成中...');
        
        $eventTypes = [
            [
                'slug' => 'anime',
                'name' => 'アニメコン',
                'description' => 'アニメ・マンガ・ゲーム好きのための婚活イベント'
            ],
            [
                'slug' => 'machi',
                'name' => '街コン',
                'description' => '地域密着型の大人数婚活イベント'
            ],
        ];
        
        foreach ($eventTypes as $type) {
            EventType::updateOrCreate(
                ['slug' => $type['slug']],
                $type
            );
        }
    }
    
    private function createAreas()
    {
        $this->command->info('エリアデータを作成中...');
        
        $areas = [
            // 東京
            ['prefecture_code' => '13', 'slug' => 'ikebukuro', 'name' => '池袋', 'name_kana' => 'イケブクロ'],
            ['prefecture_code' => '13', 'slug' => 'shinjuku', 'name' => '新宿', 'name_kana' => 'シンジュク'],
            ['prefecture_code' => '13', 'slug' => 'shibuya', 'name' => '渋谷', 'name_kana' => 'シブヤ'],
            ['prefecture_code' => '13', 'slug' => 'akihabara', 'name' => '秋葉原', 'name_kana' => 'アキハバラ'],
            ['prefecture_code' => '13', 'slug' => 'roppongi', 'name' => '六本木', 'name_kana' => 'ロッポンギ'],
            ['prefecture_code' => '13', 'slug' => 'ginza', 'name' => '銀座', 'name_kana' => 'ギンザ'],
            
            // 神奈川
            ['prefecture_code' => '14', 'slug' => 'yokohama', 'name' => '横浜', 'name_kana' => 'ヨコハマ'],
            ['prefecture_code' => '14', 'slug' => 'kawasaki', 'name' => '川崎', 'name_kana' => 'カワサキ'],
            
            // 埼玉
            ['prefecture_code' => '11', 'slug' => 'omiya', 'name' => '大宮', 'name_kana' => 'オオミヤ'],
            
            // 千葉
            ['prefecture_code' => '12', 'slug' => 'chiba', 'name' => '千葉', 'name_kana' => 'チバ'],
            ['prefecture_code' => '12', 'slug' => 'funabashi', 'name' => '船橋', 'name_kana' => 'フナバシ'],
            
            // 大阪
            ['prefecture_code' => '27', 'slug' => 'umeda', 'name' => '梅田', 'name_kana' => 'ウメダ'],
            ['prefecture_code' => '27', 'slug' => 'namba', 'name' => '難波', 'name_kana' => 'ナンバ'],
            ['prefecture_code' => '27', 'slug' => 'shinsaibashi', 'name' => '心斎橋', 'name_kana' => 'シンサイバシ'],
            
            // 愛知
            ['prefecture_code' => '23', 'slug' => 'nagoya', 'name' => '名古屋', 'name_kana' => 'ナゴヤ'],
            ['prefecture_code' => '23', 'slug' => 'sakae', 'name' => '栄', 'name_kana' => 'サカエ'],
            
            // 福岡
            ['prefecture_code' => '40', 'slug' => 'hakata', 'name' => '博多', 'name_kana' => 'ハカタ'],
            ['prefecture_code' => '40', 'slug' => 'tenjin', 'name' => '天神', 'name_kana' => 'テンジン'],
            
            // 北海道
            ['prefecture_code' => '01', 'slug' => 'sapporo', 'name' => '札幌', 'name_kana' => 'サッポロ'],
            
            // 宮城
            ['prefecture_code' => '04', 'slug' => 'sendai', 'name' => '仙台', 'name_kana' => 'センダイ'],
        ];
        
        foreach ($areas as $area) {
            $prefecture = Prefecture::where('code', $area['prefecture_code'])->first();
            
            Area::updateOrCreate(
                ['slug' => $area['slug'], 'prefecture_id' => $prefecture->id],
                [
                    'name' => $area['name'],
                    'name_kana' => $area['name_kana'],
                    'description' => null,
                ]
            );
        }
    }
    
    private function createEvents()
    {
        $this->command->info('イベントデータを作成中...');
        
        $animeType = EventType::where('slug', 'anime')->first();
        $machiType = EventType::where('slug', 'machi')->first();
        
        // 主要エリアを取得
        $ikebukuro = Area::where('slug', 'ikebukuro')->first();
        $shinjuku = Area::where('slug', 'shinjuku')->first();
        $shibuya = Area::where('slug', 'shibuya')->first();
        $akihabara = Area::where('slug', 'akihabara')->first();
        $yokohama = Area::where('slug', 'yokohama')->first();
        $umeda = Area::where('slug', 'umeda')->first();
        $nagoya = Area::where('slug', 'nagoya')->first();
        
        // 今後3ヶ月分のイベントを作成
        $startDate = Carbon::now()->startOfWeek()->addWeek();
        
        $events = [
            // アニメコン
            [
                'type' => $animeType,
                'area' => $ikebukuro,
                'date' => $startDate->copy()->next(Carbon::SATURDAY),
                'title' => '池袋アニメコン〜推しキャラ語り合い婚活〜',
                'description' => "アニメ・マンガ・ゲーム好きが集まる婚活パーティー！\n\n同じ趣味を持つ人と出会えるチャンス。推しキャラの話で盛り上がりましょう！\n\nコスプレ参加もOK！（更衣室あり）",
                'venue_name' => '池袋パーティースペース',
                'price_male' => 6000,
                'price_female' => 2000,
            ],
            [
                'type' => $animeType,
                'area' => $akihabara,
                'date' => $startDate->copy()->next(Carbon::SUNDAY),
                'title' => '秋葉原アニメコン〜オタク婚活パーティー〜',
                'description' => "秋葉原で開催！オタク趣味を隠さない婚活イベント。\n\nアニメ、マンガ、ゲーム、声優、コスプレなど、好きなものを語り合える仲間を見つけよう！",
                'venue_name' => 'アキバホール',
                'price_male' => 5500,
                'price_female' => 1500,
            ],
            [
                'type' => $animeType,
                'area' => $shibuya,
                'date' => $startDate->copy()->addWeeks(2)->next(Carbon::SATURDAY),
                'title' => '渋谷アニメコン〜カジュアルオタク婚活〜',
                'description' => "ライトなアニメファンも大歓迎！\n\n最近のアニメから懐かしの作品まで、幅広く楽しめる婚活パーティーです。",
                'venue_name' => '渋谷イベントスペース',
                'price_male' => 6500,
                'price_female' => 2500,
                'capacity_male' => 25,
                'capacity_female' => 25,
            ],
            
            // 街コン
            [
                'type' => $machiType,
                'area' => $shinjuku,
                'date' => $startDate->copy()->next(Carbon::FRIDAY)->setTime(19, 30),
                'title' => '新宿街コン〜金曜夜の出会い〜',
                'description' => "仕事帰りに気軽に参加できる街コン！\n\n新宿の人気居酒屋で美味しい料理とお酒を楽しみながら、素敵な出会いを見つけてください。",
                'venue_name' => '新宿ダイニングバー',
                'price_male' => 7000,
                'price_female' => 3000,
                'price_male_early' => 6000,
                'price_female_early' => 2000,
                'age_min_male' => 25,
                'age_max_male' => 39,
                'age_min_female' => 23,
                'age_max_female' => 37,
            ],
            [
                'type' => $machiType,
                'area' => $yokohama,
                'date' => $startDate->copy()->next(Carbon::SATURDAY)->setTime(14, 0),
                'title' => '横浜街コン〜港町で素敵な出会い〜',
                'description' => "おしゃれな港町・横浜で開催する街コン！\n\n海の見えるレストランで、ゆったりとした時間を過ごしながら出会いを楽しみましょう。",
                'venue_name' => '横浜ベイサイドレストラン',
                'price_male' => 7500,
                'price_female' => 3500,
                'capacity_male' => 30,
                'capacity_female' => 30,
            ],
            [
                'type' => $machiType,
                'area' => $umeda,
                'date' => $startDate->copy()->addWeek()->next(Carbon::SATURDAY),
                'title' => '梅田街コン〜関西最大級の出会い〜',
                'description' => "大阪・梅田で開催する大規模街コン！\n\n100名規模の参加者で、たくさんの出会いのチャンスがあります。",
                'venue_name' => '梅田スカイビル パーティールーム',
                'price_male' => 6500,
                'price_female' => 2500,
                'capacity_male' => 50,
                'capacity_female' => 50,
                'registered_male' => 35,
                'registered_female' => 42,
            ],
            [
                'type' => $machiType,
                'area' => $nagoya,
                'date' => $startDate->copy()->addWeeks(2)->next(Carbon::SUNDAY),
                'title' => '名古屋街コン〜名古屋めし婚活〜',
                'description' => "名古屋名物を楽しみながらの婚活イベント！\n\n手羽先、味噌カツ、ひつまぶしなど、美味しい料理と共に素敵な出会いを。",
                'venue_name' => '名古屋グルメダイニング',
                'price_male' => 6000,
                'price_female' => 2000,
                'age_min_male' => 20,
                'age_max_male' => 35,
                'age_min_female' => 20,
                'age_max_female' => 35,
            ],
        ];
        
        foreach ($events as $eventData) {
            $eventDate = $eventData['date'];
            $eventCode = $this->generateEventCode($eventData['type'], $eventDate);
            $slug = Event::generateSlug($eventData['area'], $eventDate, $eventCode);
            
            $event = Event::create([
                'event_type_id' => $eventData['type']->id,
                'area_id' => $eventData['area']->id,
                'event_code' => $eventCode,
                'slug' => $slug,
                'title' => $eventData['title'],
                'description' => $eventData['description'],
                'event_date' => $eventDate->format('Y-m-d'),
                'start_time' => $eventDate->format('H:i:s'),
                'end_time' => $eventDate->copy()->addHours(3)->format('H:i:s'),
                'capacity_male' => $eventData['capacity_male'] ?? 20,
                'capacity_female' => $eventData['capacity_female'] ?? 20,
                'registered_male' => $eventData['registered_male'] ?? rand(5, 15),
                'registered_female' => $eventData['registered_female'] ?? rand(5, 15),
                'price_male' => $eventData['price_male'],
                'price_female' => $eventData['price_female'],
                'price_male_early' => $eventData['price_male_early'] ?? null,
                'price_female_early' => $eventData['price_female_early'] ?? null,
                'early_deadline' => isset($eventData['price_male_early']) ? $eventDate->copy()->subWeek() : null,
                'age_min_male' => $eventData['age_min_male'] ?? 20,
                'age_max_male' => $eventData['age_max_male'] ?? 39,
                'age_min_female' => $eventData['age_min_female'] ?? 20,
                'age_max_female' => $eventData['age_max_female'] ?? 39,
                'venue_name' => $eventData['venue_name'],
                'venue_address' => null,
                'status' => 'published',
                'is_accepting_male' => true,
                'is_accepting_female' => true,
            ]);
            
            $this->command->info("イベント作成: {$event->title}");
        }
        
        // 過去のイベントも少し作成（実績表示用）
        $pastEvents = [
            [
                'type' => $animeType,
                'area' => $ikebukuro,
                'date' => Carbon::now()->subWeeks(2)->next(Carbon::SATURDAY),
                'title' => '池袋アニメコン〜春の出会い編〜',
                'registered_male' => 18,
                'registered_female' => 20,
            ],
            [
                'type' => $machiType,
                'area' => $shinjuku,
                'date' => Carbon::now()->subWeek()->next(Carbon::FRIDAY),
                'title' => '新宿街コン〜TGIF婚活〜',
                'registered_male' => 25,
                'registered_female' => 28,
            ],
        ];
        
        foreach ($pastEvents as $eventData) {
            $eventDate = $eventData['date'];
            $eventCode = $this->generateEventCode($eventData['type'], $eventDate);
            $slug = Event::generateSlug($eventData['area'], $eventDate, $eventCode);
            
            Event::create([
                'event_type_id' => $eventData['type']->id,
                'area_id' => $eventData['area']->id,
                'event_code' => $eventCode,
                'slug' => $slug,
                'title' => $eventData['title'],
                'description' => '過去のイベントです。',
                'event_date' => $eventDate->format('Y-m-d'),
                'start_time' => '14:00:00',
                'end_time' => '17:00:00',
                'capacity_male' => 20,
                'capacity_female' => 20,
                'registered_male' => $eventData['registered_male'],
                'registered_female' => $eventData['registered_female'],
                'price_male' => 5000,
                'price_female' => 2000,
                'venue_name' => 'イベントスペース',
                'status' => 'published',
                'is_accepting_male' => false,
                'is_accepting_female' => false,
            ]);
        }
    }
    
    private function generateEventCode($eventType, $eventDate)
    {
        $prefix = strtoupper(substr($eventType->slug, 0, 1));
        $dateStr = $eventDate->format('Ymd');
        $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return "{$prefix}{$dateStr}{$random}";
    }
}
