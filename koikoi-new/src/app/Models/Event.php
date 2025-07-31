<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    /**
     * 公開中のイベントのみ取得
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('event_date', '>=', now());
    }

    /**
     * イベントタイプで絞り込み
     */
    public function scopeOfType($query, string $type)
    {
        return $query->whereHas('eventType', function ($q) use ($type) {
            $q->where('slug', $type);
        });
    }

    /**
     * 都道府県で絞り込み
     */
    public function scopeInPrefecture($query, string $prefectureCode)
    {
        return $query->whereHas('area.prefecture', function ($q) use ($prefectureCode) {
            $q->where('code_en', $prefectureCode);
        });
    }

    /**
     * 月で絞り込み
     */
    public function scopeInMonth($query, int $month)
    {
        return $query->whereMonth('event_date', $month);
    }

    /**
     * 年齢範囲で絞り込み
     */
    public function scopeForAge($query, int $age)
    {
        return $query->where(function ($q) use ($age) {
            $q->where(function ($q2) use ($age) {
                $q2->where('age_min_male', '<=', $age)
                   ->where('age_max_male', '>=', $age);
            })->orWhere(function ($q2) use ($age) {
                $q2->where('age_min_female', '<=', $age)
                   ->where('age_max_female', '>=', $age);
            });
        });
    }

    /**
     * 価格範囲で絞り込み
     */
    public function scopeWithinPrice($query, int $maxPrice)
    {
        return $query->where(function ($q) use ($maxPrice) {
            $q->where('price_male', '<=', $maxPrice)
              ->orWhere('price_female', '<=', $maxPrice);
        });
    }

    /**
     * 検索キーワードで絞り込み
     */
    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%")
              ->orWhere('venue_name', 'like', "%{$keyword}%")
              ->orWhereHas('area', function ($q2) use ($keyword) {
                  $q2->where('name', 'like', "%{$keyword}%");
              })
              ->orWhereHas('area.prefecture', function ($q2) use ($keyword) {
                  $q2->where('name', 'like', "%{$keyword}%");
              });
        });
    }

    protected $fillable = [
        'event_code', 'slug', 'title', 'event_type_id', 'area_id',
        'event_date', 'day_of_week', 'start_time', 'end_time',
        'session_name', 'session_number',
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
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'is_accepting_male' => 'boolean',
        'is_accepting_female' => 'boolean',
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($event) {
            // event_dateから曜日を自動設定
            if ($event->event_date) {
                $weekdays = ['日', '月', '火', '水', '木', '金', '土'];
                $event->day_of_week = $weekdays[$event->event_date->dayOfWeek];
            }
        });
    }

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

    /**
     * イベント運営関連のリレーション
     */
    public function schedules()
    {
        return $this->hasMany(EventSchedule::class)->orderBy('time');
    }

    public function equipment()
    {
        return $this->hasMany(EventEquipment::class);
    }

    public function roles()
    {
        return $this->hasMany(EventRole::class);
    }

    public function seating()
    {
        return $this->hasMany(EventSeating::class);
    }

    public function checklist()
    {
        return $this->hasMany(EventChecklist::class);
    }

    public function specialNotes()
    {
        return $this->hasMany(CustomerSpecialNote::class);
    }

    /**
     * 分析関連のリレーション
     */
    public function revenueSummary()
    {
        return $this->hasOne(EventRevenueSummary::class);
    }

    public function participantAnalytics()
    {
        return $this->hasOne(EventParticipantAnalytics::class);
    }

    /**
     * スタッフシフト
     */
    public function staffShifts()
    {
        return $this->hasMany(StaffShift::class);
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

    // ルートモデルバインディング用
    public function getRouteKeyName()
    {
        return 'id';
    }
    
    // イベントURLを生成
    public function getUrlAttribute()
    {
        return route('event.show', ['eventType' => $this->eventType->slug, 'slug' => $this->slug]);
    }

    // イベントコードを生成（通し番号）
    public static function generateEventCode($year = null)
    {
        $year = $year ?: now()->year;
        
        // その年の最新のイベントコードを取得
        $latestEvent = self::where('event_code', 'like', "EV-{$year}-%")
            ->orderBy('event_code', 'desc')
            ->first();
        
        if ($latestEvent) {
            // 番号部分を抽出してインクリメント
            $latestNumber = (int) substr($latestEvent->event_code, -5);
            $newNumber = $latestNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return sprintf("EV-%d-%05d", $year, $newNumber);
    }
    
    // スラッグを自動生成（都道府県＋エリア＋日付＋通し番号）
    public static function generateSlug($area, $eventDate, $eventCode)
    {
        // 都道府県-エリア-日付-通し番号形式
        // 例: tokyo-ikebukuro-20250815-00001
        $prefecture = $area->prefecture->code_en ?? 'unknown'; // 都道府県コード（tokyo, osaka等）
        $areaSlug = $area->slug;
        $dateStr = $eventDate->format('Ymd');
        $number = substr($eventCode, -5);
        
        return Str::slug($prefecture . '-' . $areaSlug . '-' . $dateStr . '-' . $number);
    }
    
    // タイトルを自動生成
    public function generateTitle()
    {
        $title = $this->area->name . ' ' . $this->eventType->name;
        
        if ($this->session_name) {
            $title .= ' ' . $this->session_name;
        }
        
        $title .= ' ' . $this->event_date->format('m/d');
        
        return $title;
    }
}
