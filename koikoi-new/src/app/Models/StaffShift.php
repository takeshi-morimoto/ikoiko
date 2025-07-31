<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'event_id',
        'shift_date',
        'start_time',
        'end_time',
        'break_duration',
        'shift_type',
        'status',
        'notes',
    ];

    protected $casts = [
        'shift_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'break_duration' => 'datetime:H:i',
    ];

    /**
     * スタッフリレーション
     */
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * イベントリレーション
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * 勤務記録リレーション
     */
    public function workRecord()
    {
        return $this->hasOne(StaffWorkRecord::class, 'shift_id');
    }

    /**
     * ステータス別のスコープ
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * 今後のシフトのスコープ
     */
    public function scopeUpcoming($query)
    {
        return $query->where('shift_date', '>=', today())
                     ->orderBy('shift_date')
                     ->orderBy('start_time');
    }

    /**
     * シフト時間を計算（休憩時間を除く）
     */
    public function getShiftHoursAttribute()
    {
        $start = \Carbon\Carbon::parse($this->shift_date->format('Y-m-d') . ' ' . $this->start_time);
        $end = \Carbon\Carbon::parse($this->shift_date->format('Y-m-d') . ' ' . $this->end_time);
        
        $totalMinutes = $end->diffInMinutes($start);
        
        if ($this->break_duration) {
            $breakMinutes = \Carbon\Carbon::parse($this->break_duration)->hour * 60 
                          + \Carbon\Carbon::parse($this->break_duration)->minute;
            $totalMinutes -= $breakMinutes;
        }
        
        return round($totalMinutes / 60, 2);
    }
}