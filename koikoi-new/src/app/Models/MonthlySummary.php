<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'event_type_slug',
        'total_events',
        'total_participants',
        'total_revenue',
        'average_participants_per_event',
        'average_revenue_per_event',
        'male_female_ratio',
        'new_customers',
        'repeat_customers',
        'repeat_rate',
        'top_areas',
    ];

    protected $casts = [
        'total_revenue' => 'decimal:2',
        'average_participants_per_event' => 'decimal:2',
        'average_revenue_per_event' => 'decimal:2',
        'male_female_ratio' => 'decimal:2',
        'repeat_rate' => 'decimal:2',
        'top_areas' => 'array',
    ];

    /**
     * 年月でのスコープ
     */
    public function scopeOfYearMonth($query, $year, $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }

    /**
     * イベントタイプ別のスコープ
     */
    public function scopeOfEventType($query, $eventType)
    {
        return $query->where('event_type_slug', $eventType);
    }

    /**
     * 全体集計のスコープ
     */
    public function scopeOverall($query)
    {
        return $query->whereNull('event_type_slug');
    }

    /**
     * 前月比成長率を計算
     */
    public function getGrowthRateAttribute()
    {
        $previousMonth = $this->month == 1 ? 12 : $this->month - 1;
        $previousYear = $this->month == 1 ? $this->year - 1 : $this->year;
        
        $previous = self::where('year', $previousYear)
                        ->where('month', $previousMonth)
                        ->where('event_type_slug', $this->event_type_slug)
                        ->first();
                        
        if (!$previous || $previous->total_revenue == 0) {
            return null;
        }
        
        return round((($this->total_revenue - $previous->total_revenue) / $previous->total_revenue) * 100, 2);
    }
}