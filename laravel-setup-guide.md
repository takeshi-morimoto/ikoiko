# Laravel新サイト構築ガイド

## 1. 開発環境のセットアップ

### 1.1 プロジェクト作成

```bash
# プロジェクトディレクトリ作成
mkdir koikoi-new
cd koikoi-new

# Docker環境の構築
docker-compose up -d

# Laravelプロジェクトの作成
docker-compose exec php composer create-project laravel/laravel src --prefer-dist
```

### 1.2 必要なパッケージのインストール

```bash
# 認証システム
docker-compose exec php composer require laravel/breeze

# 日本語化
docker-compose exec php composer require laravel-lang/lang

# 管理画面
docker-compose exec php composer require filament/filament

# その他便利なパッケージ
docker-compose exec php composer require spatie/laravel-permission
docker-compose exec php composer require spatie/laravel-activitylog
docker-compose exec php composer require barryvdh/laravel-debugbar --dev
```

## 2. データベース設計（Laravel Migration）

### 2.1 都道府県マスタ

```php
// database/migrations/2025_01_30_000001_create_prefectures_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('prefectures', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique();
            $table->string('name', 10);
            $table->string('name_kana', 20);
            $table->string('region', 20);
            $table->integer('display_order')->default(0);
            $table->timestamps();
            
            $table->index('region');
        });
    }

    public function down()
    {
        Schema::dropIfExists('prefectures');
    }
};
```

### 2.2 エリア（会場）テーブル

```php
// database/migrations/2025_01_30_000002_create_areas_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 32)->unique();
            $table->string('name', 100);
            $table->foreignId('prefecture_id')->constrained();
            $table->string('venue_name')->nullable();
            $table->string('venue_address')->nullable();
            $table->text('access_info')->nullable();
            $table->string('google_map_url')->nullable();
            $table->integer('capacity')->default(0);
            $table->json('price_settings')->nullable();
            $table->json('age_restrictions')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('slug');
            $table->index('prefecture_id');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('areas');
    }
};
```

### 2.3 イベントカテゴリ

```php
// database/migrations/2025_01_30_000003_create_event_categories_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('event_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 20)->unique();
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#000000');
            $table->string('icon')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('slug');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_categories');
    }
};
```

### 2.4 イベントテーブル

```php
// database/migrations/2025_01_30_000004_create_events_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('code', 32)->unique();
            $table->foreignId('area_id')->constrained();
            $table->foreignId('event_category_id')->constrained();
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('title');
            $table->text('description')->nullable();
            
            // 定員・参加者数
            $table->integer('capacity_male')->default(0);
            $table->integer('capacity_female')->default(0);
            $table->integer('current_male')->default(0);
            $table->integer('current_female')->default(0);
            $table->integer('minimum_participants')->default(0);
            
            // 料金
            $table->integer('price_male')->default(0);
            $table->integer('price_female')->default(0);
            $table->json('price_options')->nullable(); // 早割、ペア割など
            
            // ステータス
            $table->enum('status', ['draft', 'published', 'full', 'cancelled'])->default('draft');
            $table->boolean('is_online')->default(false);
            $table->dateTime('registration_deadline')->nullable();
            $table->dateTime('cancellation_deadline')->nullable();
            
            // SEO・マーケティング
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_image')->nullable();
            $table->integer('view_count')->default(0);
            
            $table->timestamps();
            
            $table->index(['area_id', 'event_date']);
            $table->index('event_category_id');
            $table->index('status');
            $table->index('event_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};
```

### 2.5 予約（顧客）テーブル

```php
// database/migrations/2025_01_30_000005_create_reservations_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reservation_code', 20)->unique();
            $table->foreignId('event_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            
            // 参加者情報
            $table->string('name', 100);
            $table->string('name_kana', 100);
            $table->enum('gender', ['male', 'female', 'other']);
            $table->integer('age');
            $table->string('email');
            $table->string('phone', 20);
            $table->integer('participants_count')->default(1);
            
            // 支払い情報
            $table->integer('total_amount');
            $table->enum('payment_method', ['credit', 'bank', 'convenience', 'onsite'])->nullable();
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->dateTime('paid_at')->nullable();
            
            // ステータス
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->text('cancellation_reason')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            
            // その他
            $table->text('notes')->nullable();
            $table->string('referral_source', 50)->nullable();
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            $table->index('reservation_code');
            $table->index('event_id');
            $table->index('email');
            $table->index('status');
            $table->index(['event_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};
```

## 3. モデルの作成

### 3.1 Area モデル

```php
// app/Models/Area.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'name', 'prefecture_id', 'venue_name', 'venue_address',
        'access_info', 'google_map_url', 'capacity', 'price_settings',
        'age_restrictions', 'description', 'image_path', 'is_active'
    ];

    protected $casts = [
        'price_settings' => 'array',
        'age_restrictions' => 'array',
        'is_active' => 'boolean',
    ];

    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(Prefecture::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getPriceRangeAttribute()
    {
        $prices = $this->price_settings;
        return sprintf('%s円〜%s円', 
            number_format($prices['min'] ?? 0),
            number_format($prices['max'] ?? 0)
        );
    }
}
```

### 3.2 Event モデル

```php
// app/Models/Event.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'area_id', 'event_category_id', 'event_date', 'start_time',
        'end_time', 'title', 'description', 'capacity_male', 'capacity_female',
        'current_male', 'current_female', 'minimum_participants', 'price_male',
        'price_female', 'price_options', 'status', 'is_online',
        'registration_deadline', 'cancellation_deadline', 'meta_title',
        'meta_description', 'og_image', 'view_count'
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'price_options' => 'array',
        'is_online' => 'boolean',
        'registration_deadline' => 'datetime',
        'cancellation_deadline' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->code)) {
                $event->code = static::generateEventCode($event);
            }
        });
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', today())
                     ->where('status', 'published')
                     ->orderBy('event_date')
                     ->orderBy('start_time');
    }

    public function scopeByArea($query, $areaSlug)
    {
        return $query->whereHas('area', function ($q) use ($areaSlug) {
            $q->where('slug', $areaSlug);
        });
    }

    public function getAvailableSeatsAttribute()
    {
        return [
            'male' => $this->capacity_male - $this->current_male,
            'female' => $this->capacity_female - $this->current_female,
        ];
    }

    public function isFull($gender = null)
    {
        if ($gender === 'male') {
            return $this->current_male >= $this->capacity_male;
        }
        if ($gender === 'female') {
            return $this->current_female >= $this->capacity_female;
        }
        return $this->isFull('male') && $this->isFull('female');
    }

    protected static function generateEventCode($event)
    {
        $date = Carbon::parse($event->event_date)->format('ymd');
        $area = Area::find($event->area_id);
        return strtolower($area->slug . '_' . $date . '_' . uniqid());
    }
}
```

## 4. コントローラーの実装

### 4.1 EventController

```php
// app/Http/Controllers/EventController.php
<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Area;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['area', 'category'])
                      ->upcoming();

        if ($request->has('area')) {
            $query->byArea($request->area);
        }

        if ($request->has('category')) {
            $query->where('event_category_id', $request->category);
        }

        if ($request->has('date')) {
            $query->whereDate('event_date', $request->date);
        }

        $events = $query->paginate(20);

        return view('events.index', compact('events'));
    }

    public function show($area, $code)
    {
        $event = Event::with(['area', 'category', 'reservations'])
                      ->where('code', $code)
                      ->firstOrFail();

        // ビューカウントを増やす
        $event->increment('view_count');

        return view('events.show', compact('event'));
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'prefecture' => 'nullable|exists:prefectures,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'category' => 'nullable|exists:event_categories,id',
        ]);

        $query = Event::with(['area.prefecture', 'category'])
                      ->upcoming();

        if (!empty($validated['prefecture'])) {
            $query->whereHas('area', function ($q) use ($validated) {
                $q->where('prefecture_id', $validated['prefecture']);
            });
        }

        if (!empty($validated['date_from'])) {
            $query->whereDate('event_date', '>=', $validated['date_from']);
        }

        if (!empty($validated['date_to'])) {
            $query->whereDate('event_date', '<=', $validated['date_to']);
        }

        if (!empty($validated['category'])) {
            $query->where('event_category_id', $validated['category']);
        }

        $events = $query->paginate(20);

        return view('events.search', compact('events'));
    }
}
```

### 4.2 ReservationController

```php
// app/Http/Controllers/ReservationController.php
<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function create($eventCode)
    {
        $event = Event::where('code', $eventCode)->firstOrFail();

        if ($event->isFull()) {
            return redirect()->route('events.show', [$event->area->slug, $event->code])
                           ->with('error', 'このイベントは満席です。');
        }

        return view('reservations.create', compact('event'));
    }

    public function store(Request $request, $eventCode)
    {
        $event = Event::where('code', $eventCode)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'name_kana' => 'required|string|max:100|regex:/^[ァ-ヾ\s]+$/u',
            'gender' => 'required|in:male,female',
            'age' => 'required|integer|min:18|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'participants_count' => 'required|integer|min:1|max:5',
        ]);

        try {
            DB::beginTransaction();

            $reservation = $this->reservationService->create($event, $validated);

            DB::commit();

            // 確認メール送信
            $this->reservationService->sendConfirmationEmail($reservation);

            return redirect()->route('reservations.complete', $reservation->reservation_code);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', '予約処理中にエラーが発生しました。')
                         ->withInput();
        }
    }

    public function complete($reservationCode)
    {
        $reservation = Reservation::where('reservation_code', $reservationCode)
                                  ->firstOrFail();

        return view('reservations.complete', compact('reservation'));
    }
}
```

## 5. ルーティング設定

```php
// routes/web.php
<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// トップページ
Route::get('/', function () {
    return view('welcome');
})->name('home');

// イベント関連
Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/search', [EventController::class, 'search'])->name('search');
    Route::get('/{area}/{code}', [EventController::class, 'show'])->name('show');
});

// 予約関連
Route::prefix('reservations')->name('reservations.')->group(function () {
    Route::get('/create/{event}', [ReservationController::class, 'create'])->name('create');
    Route::post('/store/{event}', [ReservationController::class, 'store'])->name('store');
    Route::get('/complete/{code}', [ReservationController::class, 'complete'])->name('complete');
});

// 管理画面（Filament使用）
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // Filamentが自動的にルートを生成
});

// 認証（Laravel Breeze）
require __DIR__.'/auth.php';
```

## 6. ビューの作成

### 6.1 レイアウト

```blade
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'KOIKOI - 街コン・婚活イベント')</title>
    <meta name="description" content="@yield('description', '全国の街コン・婚活イベント情報')">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <header class="header">
        <div class="container">
            <h1 class="logo">
                <a href="{{ route('home') }}">KOIKOI</a>
            </h1>
            <nav class="nav">
                <ul>
                    <li><a href="{{ route('events.index') }}">イベント一覧</a></li>
                    <li><a href="{{ route('events.search') }}">イベント検索</a></li>
                    <li><a href="/about">初めての方へ</a></li>
                    <li><a href="/contact">お問い合わせ</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 KOIKOI. All rights reserved.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
```

### 6.2 イベント一覧

```blade
{{-- resources/views/events/index.blade.php --}}
@extends('layouts.app')

@section('title', 'イベント一覧 - KOIKOI')

@section('content')
<div class="container">
    <h2>開催予定のイベント</h2>
    
    <div class="events-grid">
        @foreach($events as $event)
            <article class="event-card">
                <div class="event-card__header">
                    <span class="event-card__category" style="background-color: {{ $event->category->color }}">
                        {{ $event->category->name }}
                    </span>
                    <time class="event-card__date">
                        {{ $event->event_date->format('m/d') }}（{{ $event->event_date->isoFormat('ddd') }}）
                    </time>
                </div>
                
                <h3 class="event-card__title">
                    <a href="{{ route('events.show', [$event->area->slug, $event->code]) }}">
                        {{ $event->title }}
                    </a>
                </h3>
                
                <div class="event-card__info">
                    <p class="event-card__location">
                        📍 {{ $event->area->name }}（{{ $event->area->prefecture->name }}）
                    </p>
                    <p class="event-card__time">
                        🕐 {{ $event->start_time->format('H:i') }}〜{{ $event->end_time->format('H:i') }}
                    </p>
                    <p class="event-card__price">
                        💰 男性: {{ number_format($event->price_male) }}円 / 女性: {{ number_format($event->price_female) }}円
                    </p>
                </div>
                
                <div class="event-card__status">
                    @if($event->isFull())
                        <span class="badge badge--full">満席</span>
                    @else
                        <span class="badge badge--available">
                            男性: 残{{ $event->available_seats['male'] }}席 / 
                            女性: 残{{ $event->available_seats['female'] }}席
                        </span>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
    
    {{ $events->links() }}
</div>
@endsection
```

## 7. データ移行スクリプト

```php
// database/seeders/MigrateFromOldSystem.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prefecture;
use App\Models\Area;
use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Support\Facades\DB;

class MigrateFromOldSystem extends Seeder
{
    protected $oldDb;

    public function __construct()
    {
        // 旧システムのDB接続
        $this->oldDb = DB::connection('old_system');
    }

    public function run()
    {
        $this->command->info('旧システムからのデータ移行を開始します...');

        $this->migratePrefectures();
        $this->migrateEventCategories();
        $this->migrateAreas();
        $this->migrateEvents();
        $this->migrateCustomers();

        $this->command->info('データ移行が完了しました！');
    }

    protected function migrateAreas()
    {
        $this->command->info('エリアデータを移行中...');

        $oldAreas = $this->oldDb->table('area')->get();

        foreach ($oldAreas as $oldArea) {
            $prefecture = Prefecture::where('name', 'like', $oldArea->ken . '%')->first();

            Area::updateOrCreate(
                ['slug' => $oldArea->area],
                [
                    'name' => $oldArea->area_ja,
                    'prefecture_id' => $prefecture->id ?? 1,
                    'venue_name' => $oldArea->place,
                    'price_settings' => [
                        'high' => $oldArea->price_h,
                        'low' => $oldArea->price_l,
                    ],
                    'age_restrictions' => [
                        'male' => $oldArea->age_m,
                        'female' => $oldArea->age_w,
                    ],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('✓ エリアデータの移行完了');
    }

    protected function migrateEvents()
    {
        $this->command->info('イベントデータを移行中...');

        $oldEvents = $this->oldDb->table('events')
                                 ->where('date', '>=', now())
                                 ->get();

        foreach ($oldEvents as $oldEvent) {
            $area = Area::where('slug', $oldEvent->area)->first();
            if (!$area) continue;

            // カテゴリを推測
            $category = $this->guessCategory($oldEvent);

            Event::updateOrCreate(
                ['code' => $oldEvent->find],
                [
                    'area_id' => $area->id,
                    'event_category_id' => $category->id,
                    'event_date' => $oldEvent->date,
                    'start_time' => $oldEvent->begin,
                    'end_time' => $oldEvent->end,
                    'title' => $this->generateTitle($oldEvent, $area),
                    'status' => $this->convertStatus($oldEvent),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('✓ イベントデータの移行完了');
    }

    protected function guessCategory($oldEvent)
    {
        // URLやフィールドからカテゴリを推測
        if (strpos($oldEvent->find, 'anime') !== false) {
            return EventCategory::where('slug', 'anime')->first();
        }
        
        return EventCategory::where('slug', 'machi')->first();
    }
}
```

## 8. 環境設定

```env
# .env ファイル
APP_NAME="KOIKOI"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8080

# データベース（新システム）
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=koikoi_new
DB_USERNAME=koikoi_user
DB_PASSWORD=koikoi_pass

# 旧システムのDB（データ移行用）
OLD_DB_HOST=localhost
OLD_DB_PORT=3306
OLD_DB_DATABASE=koikoi_old
OLD_DB_USERNAME=old_user
OLD_DB_PASSWORD=old_pass

# メール設定
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@koikoi.co.jp
MAIL_FROM_NAME="${APP_NAME}"

# キャッシュ
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## 9. 次のステップ

1. **環境構築**
   ```bash
   cd koikoi-new
   docker-compose up -d
   docker-compose exec php php artisan key:generate
   docker-compose exec php php artisan migrate
   ```

2. **初期データ投入**
   ```bash
   docker-compose exec php php artisan db:seed
   ```

3. **開発サーバー起動**
   ```bash
   docker-compose exec php php artisan serve --host=0.0.0.0
   ```

4. **管理画面（Filament）のセットアップ**
   ```bash
   docker-compose exec php php artisan filament:install --panels
   ```

これで基本的なLaravel環境が整いました！