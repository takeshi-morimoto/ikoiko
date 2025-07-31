<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Prefecture;
use App\Models\Area;
use App\Models\EventType;
use App\Models\Event;
use App\Models\Customer;
use Carbon\Carbon;

class MigrateOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:old-data 
                            {--prefectures : 都道府県データのみ移行}
                            {--areas : エリアデータのみ移行}
                            {--events : イベントデータのみ移行}
                            {--customers : 顧客データのみ移行}
                            {--all : すべてのデータを移行}
                            {--source-db=koikoi_old : 移行元データベース名}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '旧KOIKOIシステムからデータを移行';

    /**
     * 旧データベース接続
     */
    protected $oldDb;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('旧システムからのデータ移行を開始します...');
        
        // 移行元データベースの設定
        $sourceDb = $this->option('source-db');
        $this->configureOldDatabase($sourceDb);
        
        try {
            // 接続テスト
            $this->oldDb = DB::connection('old_system');
            $this->oldDb->getPdo();
            $this->info('旧データベースへの接続に成功しました。');
        } catch (\Exception $e) {
            $this->error('旧データベースへの接続に失敗しました: ' . $e->getMessage());
            return 1;
        }

        // 移行実行
        if ($this->option('all')) {
            $this->migrateAll();
        } else {
            if ($this->option('prefectures')) {
                $this->migratePrefectures();
            }
            if ($this->option('areas')) {
                $this->migrateAreas();
            }
            if ($this->option('events')) {
                $this->migrateEvents();
            }
            if ($this->option('customers')) {
                $this->migrateCustomers();
            }
        }

        $this->info('データ移行が完了しました！');
        return 0;
    }

    /**
     * 旧データベースの設定
     */
    protected function configureOldDatabase($database)
    {
        config([
            'database.connections.old_system' => [
                'driver' => 'mysql',
                'host' => env('OLD_DB_HOST', '127.0.0.1'),
                'port' => env('OLD_DB_PORT', '3306'),
                'database' => $database,
                'username' => env('OLD_DB_USERNAME', 'root'),
                'password' => env('OLD_DB_PASSWORD', ''),
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
                'strict' => false,
            ]
        ]);
    }

    /**
     * すべてのデータを移行
     */
    protected function migrateAll()
    {
        $this->migratePrefectures();
        $this->migrateEventTypes();
        $this->migrateAreas();
        $this->migrateEvents();
        $this->migrateCustomers();
    }

    /**
     * 都道府県データの移行
     */
    protected function migratePrefectures()
    {
        $this->info('都道府県データを移行中...');
        
        // 日本の都道府県マスタデータ
        $prefectures = [
            ['code' => '01', 'code_en' => 'hokkaido', 'name' => '北海道', 'display_order' => 1],
            ['code' => '02', 'code_en' => 'aomori', 'name' => '青森県', 'display_order' => 2],
            ['code' => '03', 'code_en' => 'iwate', 'name' => '岩手県', 'display_order' => 3],
            ['code' => '04', 'code_en' => 'miyagi', 'name' => '宮城県', 'display_order' => 4],
            ['code' => '05', 'code_en' => 'akita', 'name' => '秋田県', 'display_order' => 5],
            ['code' => '06', 'code_en' => 'yamagata', 'name' => '山形県', 'display_order' => 6],
            ['code' => '07', 'code_en' => 'fukushima', 'name' => '福島県', 'display_order' => 7],
            ['code' => '08', 'code_en' => 'ibaraki', 'name' => '茨城県', 'display_order' => 8],
            ['code' => '09', 'code_en' => 'tochigi', 'name' => '栃木県', 'display_order' => 9],
            ['code' => '10', 'code_en' => 'gunma', 'name' => '群馬県', 'display_order' => 10],
            ['code' => '11', 'code_en' => 'saitama', 'name' => '埼玉県', 'display_order' => 11],
            ['code' => '12', 'code_en' => 'chiba', 'name' => '千葉県', 'display_order' => 12],
            ['code' => '13', 'code_en' => 'tokyo', 'name' => '東京都', 'display_order' => 13],
            ['code' => '14', 'code_en' => 'kanagawa', 'name' => '神奈川県', 'display_order' => 14],
            ['code' => '15', 'code_en' => 'niigata', 'name' => '新潟県', 'display_order' => 15],
            ['code' => '16', 'code_en' => 'toyama', 'name' => '富山県', 'display_order' => 16],
            ['code' => '17', 'code_en' => 'ishikawa', 'name' => '石川県', 'display_order' => 17],
            ['code' => '18', 'code_en' => 'fukui', 'name' => '福井県', 'display_order' => 18],
            ['code' => '19', 'code_en' => 'yamanashi', 'name' => '山梨県', 'display_order' => 19],
            ['code' => '20', 'code_en' => 'nagano', 'name' => '長野県', 'display_order' => 20],
            ['code' => '21', 'code_en' => 'gifu', 'name' => '岐阜県', 'display_order' => 21],
            ['code' => '22', 'code_en' => 'shizuoka', 'name' => '静岡県', 'display_order' => 22],
            ['code' => '23', 'code_en' => 'aichi', 'name' => '愛知県', 'display_order' => 23],
            ['code' => '24', 'code_en' => 'mie', 'name' => '三重県', 'display_order' => 24],
            ['code' => '25', 'code_en' => 'shiga', 'name' => '滋賀県', 'display_order' => 25],
            ['code' => '26', 'code_en' => 'kyoto', 'name' => '京都府', 'display_order' => 26],
            ['code' => '27', 'code_en' => 'osaka', 'name' => '大阪府', 'display_order' => 27],
            ['code' => '28', 'code_en' => 'hyogo', 'name' => '兵庫県', 'display_order' => 28],
            ['code' => '29', 'code_en' => 'nara', 'name' => '奈良県', 'display_order' => 29],
            ['code' => '30', 'code_en' => 'wakayama', 'name' => '和歌山県', 'display_order' => 30],
            ['code' => '31', 'code_en' => 'tottori', 'name' => '鳥取県', 'display_order' => 31],
            ['code' => '32', 'code_en' => 'shimane', 'name' => '島根県', 'display_order' => 32],
            ['code' => '33', 'code_en' => 'okayama', 'name' => '岡山県', 'display_order' => 33],
            ['code' => '34', 'code_en' => 'hiroshima', 'name' => '広島県', 'display_order' => 34],
            ['code' => '35', 'code_en' => 'yamaguchi', 'name' => '山口県', 'display_order' => 35],
            ['code' => '36', 'code_en' => 'tokushima', 'name' => '徳島県', 'display_order' => 36],
            ['code' => '37', 'code_en' => 'kagawa', 'name' => '香川県', 'display_order' => 37],
            ['code' => '38', 'code_en' => 'ehime', 'name' => '愛媛県', 'display_order' => 38],
            ['code' => '39', 'code_en' => 'kochi', 'name' => '高知県', 'display_order' => 39],
            ['code' => '40', 'code_en' => 'fukuoka', 'name' => '福岡県', 'display_order' => 40],
            ['code' => '41', 'code_en' => 'saga', 'name' => '佐賀県', 'display_order' => 41],
            ['code' => '42', 'code_en' => 'nagasaki', 'name' => '長崎県', 'display_order' => 42],
            ['code' => '43', 'code_en' => 'kumamoto', 'name' => '熊本県', 'display_order' => 43],
            ['code' => '44', 'code_en' => 'oita', 'name' => '大分県', 'display_order' => 44],
            ['code' => '45', 'code_en' => 'miyazaki', 'name' => '宮崎県', 'display_order' => 45],
            ['code' => '46', 'code_en' => 'kagoshima', 'name' => '鹿児島県', 'display_order' => 46],
            ['code' => '47', 'code_en' => 'okinawa', 'name' => '沖縄県', 'display_order' => 47],
        ];

        foreach ($prefectures as $pref) {
            Prefecture::updateOrCreate(
                ['code' => $pref['code']],
                $pref
            );
        }

        $this->info('都道府県データの移行が完了しました。');
    }

    /**
     * イベントタイプの移行
     */
    protected function migrateEventTypes()
    {
        $this->info('イベントタイプデータを移行中...');
        
        $eventTypes = [
            ['slug' => 'anime', 'name' => 'アニメコン', 'description' => 'アニメ・マンガ・ゲーム好きのための婚活イベント'],
            ['slug' => 'machi', 'name' => '街コン', 'description' => '地域密着型の大人数婚活イベント'],
        ];

        foreach ($eventTypes as $type) {
            EventType::updateOrCreate(
                ['slug' => $type['slug']],
                $type
            );
        }

        $this->info('イベントタイプデータの移行が完了しました。');
    }

    /**
     * エリアデータの移行
     */
    protected function migrateAreas()
    {
        $this->info('エリアデータを移行中...');
        
        $oldAreas = $this->oldDb->table('area')->get();
        $bar = $this->output->createProgressBar(count($oldAreas));

        foreach ($oldAreas as $oldArea) {
            // 都道府県を特定
            $prefecture = $this->guessPrefecture($oldArea->ken);
            
            if (!$prefecture) {
                $this->warn("都道府県が特定できません: {$oldArea->ken} ({$oldArea->area})");
                continue;
            }

            // エリア名からスラッグを生成
            $slug = $this->generateAreaSlug($oldArea->area_ja ?? $oldArea->area);

            Area::updateOrCreate(
                ['slug' => $slug, 'prefecture_id' => $prefecture->id],
                [
                    'name' => $oldArea->area_ja ?? $oldArea->area,
                    'name_kana' => $oldArea->area ?? '',
                    'description' => $oldArea->content ?? null,
                    'old_area_id' => $oldArea->area,
                ]
            );

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('エリアデータの移行が完了しました。');
    }

    /**
     * イベントデータの移行
     */
    protected function migrateEvents()
    {
        $this->info('イベントデータを移行中...');
        
        $oldEvents = $this->oldDb->table('events')->get();
        $bar = $this->output->createProgressBar(count($oldEvents));

        $animeType = EventType::where('slug', 'anime')->first();
        $machiType = EventType::where('slug', 'machi')->first();

        foreach ($oldEvents as $oldEvent) {
            try {
                // エリアを特定
                $area = Area::where('old_area_id', $oldEvent->area ?? '')->first();
                if (!$area) {
                    $this->warn("エリアが見つかりません: {$oldEvent->area}");
                    continue;
                }

                // イベントタイプを判定（イベント名やカテゴリから推測）
                $eventType = $this->guessEventType($oldEvent, $animeType, $machiType);

                // 日付と時間を解析
                $eventDate = $this->parseEventDate($oldEvent);
                $startTime = $this->parseTime($oldEvent->start_time ?? '14:00');
                $endTime = $this->parseTime($oldEvent->end_time ?? '17:00');

                // イベントコードを生成
                $eventCode = $this->generateEventCode($eventType, $eventDate);

                // スラッグを生成
                $slug = Event::generateSlug($area, $eventDate, $eventCode);

                Event::updateOrCreate(
                    ['event_code' => $eventCode],
                    [
                        'event_type_id' => $eventType->id,
                        'area_id' => $area->id,
                        'title' => $oldEvent->title ?? $this->generateEventTitle($eventType, $area, $eventDate),
                        'slug' => $slug,
                        'description' => $oldEvent->description ?? '',
                        'event_date' => $eventDate,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'capacity_male' => $oldEvent->capacity_male ?? 20,
                        'capacity_female' => $oldEvent->capacity_female ?? 20,
                        'registered_male' => $oldEvent->registered_male ?? 0,
                        'registered_female' => $oldEvent->registered_female ?? 0,
                        'price_male' => $oldEvent->price_male ?? 5000,
                        'price_female' => $oldEvent->price_female ?? 2000,
                        'price_male_early' => $oldEvent->price_male_early ?? null,
                        'price_female_early' => $oldEvent->price_female_early ?? null,
                        'early_deadline' => $oldEvent->early_deadline ? Carbon::parse($oldEvent->early_deadline) : null,
                        'age_min_male' => $oldEvent->age_min_male ?? 20,
                        'age_max_male' => $oldEvent->age_max_male ?? 39,
                        'age_min_female' => $oldEvent->age_min_female ?? 20,
                        'age_max_female' => $oldEvent->age_max_female ?? 39,
                        'venue_name' => $oldEvent->venue_name ?? null,
                        'venue_address' => $oldEvent->venue_address ?? null,
                        'status' => 'published',
                        'is_accepting_male' => ($oldEvent->is_accepting_male ?? 1) == 1,
                        'is_accepting_female' => ($oldEvent->is_accepting_female ?? 1) == 1,
                    ]
                );

                $bar->advance();
            } catch (\Exception $e) {
                $this->error("イベント移行エラー: " . $e->getMessage());
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info('イベントデータの移行が完了しました。');
    }

    /**
     * 顧客データの移行
     */
    protected function migrateCustomers()
    {
        $this->info('顧客データを移行中...');
        
        $oldCustomers = $this->oldDb->table('customers')->get();
        $bar = $this->output->createProgressBar(count($oldCustomers));

        foreach ($oldCustomers as $oldCustomer) {
            Customer::updateOrCreate(
                ['email' => $oldCustomer->email],
                [
                    'name' => $oldCustomer->name,
                    'name_kana' => $oldCustomer->name_kana ?? null,
                    'gender' => $oldCustomer->gender ?? 'male',
                    'birth_date' => $oldCustomer->birth_date ? Carbon::parse($oldCustomer->birth_date) : null,
                    'phone' => $oldCustomer->phone ?? null,
                    'postal_code' => $oldCustomer->postal_code ?? null,
                    'prefecture' => $oldCustomer->prefecture ?? null,
                    'city' => $oldCustomer->city ?? null,
                    'address' => $oldCustomer->address ?? null,
                    'created_at' => $oldCustomer->created_at ? Carbon::parse($oldCustomer->created_at) : now(),
                ]
            );

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('顧客データの移行が完了しました。');
    }

    /**
     * 都道府県を推測
     */
    protected function guessPrefecture($ken)
    {
        $mapping = [
            '東京' => '東京都',
            '大阪' => '大阪府',
            '神奈川' => '神奈川県',
            '埼玉' => '埼玉県',
            '千葉' => '千葉県',
            '愛知' => '愛知県',
            '福岡' => '福岡県',
            '北海道' => '北海道',
        ];

        $prefName = $mapping[$ken] ?? $ken;
        return Prefecture::where('name', $prefName)->first();
    }

    /**
     * エリアスラッグを生成
     */
    protected function generateAreaSlug($areaName)
    {
        $mapping = [
            '池袋' => 'ikebukuro',
            '新宿' => 'shinjuku',
            '渋谷' => 'shibuya',
            '秋葉原' => 'akihabara',
            '横浜' => 'yokohama',
            '大宮' => 'omiya',
            '梅田' => 'umeda',
            '難波' => 'namba',
            '名古屋' => 'nagoya',
            '栄' => 'sakae',
            '博多' => 'hakata',
            '天神' => 'tenjin',
            '札幌' => 'sapporo',
            '仙台' => 'sendai',
        ];

        return $mapping[$areaName] ?? \Str::slug($areaName);
    }

    /**
     * イベントタイプを推測
     */
    protected function guessEventType($oldEvent, $animeType, $machiType)
    {
        $title = $oldEvent->title ?? '';
        $category = $oldEvent->category ?? '';

        if (stripos($title, 'アニメ') !== false || stripos($category, 'anime') !== false) {
            return $animeType;
        }

        return $machiType;
    }

    /**
     * イベント日付を解析
     */
    protected function parseEventDate($oldEvent)
    {
        if (isset($oldEvent->event_date)) {
            return Carbon::parse($oldEvent->event_date);
        }

        // 他のフィールドから推測
        if (isset($oldEvent->date)) {
            return Carbon::parse($oldEvent->date);
        }

        // デフォルトは来月の最初の土曜日
        return Carbon::now()->addMonth()->next(Carbon::SATURDAY);
    }

    /**
     * 時間を解析
     */
    protected function parseTime($timeStr)
    {
        try {
            return Carbon::parse($timeStr);
        } catch (\Exception $e) {
            return Carbon::parse('14:00');
        }
    }

    /**
     * イベントコードを生成
     */
    protected function generateEventCode($eventType, $eventDate)
    {
        $prefix = strtoupper(substr($eventType->slug, 0, 1));
        $dateStr = $eventDate->format('Ymd');
        $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return "{$prefix}{$dateStr}{$random}";
    }

    /**
     * イベントタイトルを生成
     */
    protected function generateEventTitle($eventType, $area, $eventDate)
    {
        $month = $eventDate->format('n');
        $day = $eventDate->format('j');
        
        if ($eventType->slug === 'anime') {
            return "{$month}月{$day}日 {$area->name}アニメコン";
        } else {
            return "{$month}月{$day}日 {$area->name}街コン";
        }
    }
}
