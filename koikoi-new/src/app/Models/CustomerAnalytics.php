<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAnalytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'total_events_registered',
        'total_events_attended',
        'total_cancellations',
        'attendance_rate',
        'total_spent',
        'average_spent_per_event',
        'first_event_date',
        'last_event_date',
        'days_since_last_event',
        'customer_segment',
        'lifetime_value',
        'preferred_event_types',
        'preferred_areas',
    ];

    protected $casts = [
        'attendance_rate' => 'decimal:2',
        'total_spent' => 'decimal:2',
        'average_spent_per_event' => 'decimal:2',
        'lifetime_value' => 'decimal:2',
        'first_event_date' => 'date',
        'last_event_date' => 'date',
        'preferred_event_types' => 'array',
        'preferred_areas' => 'array',
    ];

    /**
     * 顧客リレーション
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * セグメント別のスコープ
     */
    public function scopeOfSegment($query, $segment)
    {
        return $query->where('customer_segment', $segment);
    }

    /**
     * VIP顧客のスコープ
     */
    public function scopeVip($query)
    {
        return $query->where('customer_segment', 'vip');
    }

    /**
     * 休眠顧客のスコープ
     */
    public function scopeDormant($query)
    {
        return $query->where('customer_segment', 'dormant')
                     ->orWhere('days_since_last_event', '>', 90);
    }

    /**
     * 高LTV顧客のスコープ
     */
    public function scopeHighValue($query, $minValue = 50000)
    {
        return $query->where('lifetime_value', '>=', $minValue);
    }
}