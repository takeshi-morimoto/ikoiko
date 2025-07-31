<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRevenueSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'total_revenue',
        'male_revenue',
        'female_revenue',
        'early_bird_revenue',
        'regular_revenue',
        'cancellation_fees',
        'paid_male_count',
        'paid_female_count',
        'unpaid_count',
        'collection_rate',
        'calculated_at',
    ];

    protected $casts = [
        'total_revenue' => 'decimal:2',
        'male_revenue' => 'decimal:2',
        'female_revenue' => 'decimal:2',
        'early_bird_revenue' => 'decimal:2',
        'regular_revenue' => 'decimal:2',
        'cancellation_fees' => 'decimal:2',
        'collection_rate' => 'decimal:2',
        'calculated_at' => 'date',
    ];

    /**
     * イベントリレーション
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * 収益率計算
     */
    public function getRevenuePerParticipantAttribute()
    {
        $totalParticipants = $this->paid_male_count + $this->paid_female_count;
        return $totalParticipants > 0 ? round($this->total_revenue / $totalParticipants, 2) : 0;
    }
}