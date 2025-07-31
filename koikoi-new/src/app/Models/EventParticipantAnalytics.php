<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventParticipantAnalytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'total_registered',
        'male_registered',
        'female_registered',
        'total_attended',
        'male_attended',
        'female_attended',
        'no_show_count',
        'cancelled_count',
        'attendance_rate',
        'male_female_ratio',
        'age_distribution',
        'prefecture_distribution',
        'satisfaction_score',
    ];

    protected $casts = [
        'attendance_rate' => 'decimal:2',
        'male_female_ratio' => 'decimal:2',
        'age_distribution' => 'array',
        'prefecture_distribution' => 'array',
        'satisfaction_score' => 'decimal:2',
    ];

    /**
     * イベントリレーション
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * 年齢層の最多グループを取得
     */
    public function getMostCommonAgeGroupAttribute()
    {
        if (!$this->age_distribution) {
            return null;
        }
        
        return array_keys($this->age_distribution, max($this->age_distribution))[0] ?? null;
    }

    /**
     * 参加者の最多エリアを取得
     */
    public function getMostCommonPrefectureAttribute()
    {
        if (!$this->prefecture_distribution) {
            return null;
        }
        
        return array_keys($this->prefecture_distribution, max($this->prefecture_distribution))[0] ?? null;
    }
}