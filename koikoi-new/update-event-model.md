# イベントモデルの実装詳細

## Eventモデルの実装

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'title', 'event_type_id', 'area_id',
        'event_date', 'day_of_week', 'start_time', 'end_time',
        'capacity_male', 'capacity_female', 'registered_male', 'registered_female',
        'price_male', 'price_female', 'price_male_early', 'price_female_early', 'early_deadline',
        'age_min_male', 'age_max_male', 'age_min_female', 'age_max_female',
        'venue_name', 'venue_address', 'venue_url', 'venue_access', 'meeting_point',
        'sales_copy', 'pr_comment', 'description', 'schedule', 'notes',
        'status', 'is_accepting_male', 'is_accepting_female',
        'meta_title', 'meta_description', 'og_image'
    ];

    protected $casts = [
        'event_date' => 'date',
        'early_deadline' => 'date',
        'is_accepting_male' => 'boolean',
        'is_accepting_female' => 'boolean',
    ];

    // リレーション
    public function eventType()
    {
        return $this->belongsTo(EventType::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    // 男性の残席数を取得
    public function getRemainingMaleSeatsAttribute()
    {
        return $this->capacity_male - $this->registered_male;
    }

    // 女性の残席数を取得
    public function getRemainingFemaleSeatsAttribute()
    {
        return $this->capacity_female - $this->registered_female;
    }

    // イベントURLを生成
    public function getUrlAttribute()
    {
        return route('event.show', ['eventType' => $this->eventType->slug, 'slug' => $this->slug]);
    }

    // スラッグを自動生成
    public static function generateSlug($area, $eventType, $date)
    {
        $baseSlug = Str::slug($area->slug . '-' . $eventType->slug . '-' . $date->format('Y-m-d'));
        $count = self::where('slug', 'like', $baseSlug . '%')->count();
        
        return $count > 0 ? $baseSlug . '-' . ($count + 1) : $baseSlug;
    }
}
```

## ルーティング例

```php
// routes/web.php
Route::get('/{eventType}/{slug}', [EventController::class, 'show'])->name('event.show');
// 例: /anime/tokyo-anime-2025-08-15
// 例: /machi/yokohama-machi-2025-08-20

Route::get('/{eventType}', [EventController::class, 'index'])->name('event.index');
// 例: /anime - アニメコンの一覧
// 例: /machi - 街コンの一覧
```