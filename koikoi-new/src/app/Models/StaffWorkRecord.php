<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffWorkRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'shift_id',
        'event_id',
        'work_date',
        'actual_start',
        'actual_end',
        'break_time',
        'work_hours',
        'overtime_hours',
        'attendance_status',
        'performance_notes',
        'performance_rating',
        'recorded_by',
    ];

    protected $casts = [
        'work_date' => 'date',
        'actual_start' => 'datetime:H:i',
        'actual_end' => 'datetime:H:i',
        'break_time' => 'datetime:H:i',
        'work_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
    ];

    /**
     * スタッフリレーション
     */
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * シフトリレーション
     */
    public function shift()
    {
        return $this->belongsTo(StaffShift::class);
    }

    /**
     * イベントリレーション
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * 記録者リレーション
     */
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * 出勤ステータス別のスコープ
     */
    public function scopePresent($query)
    {
        return $query->whereIn('attendance_status', ['present', 'late']);
    }

    public function scopeAbsent($query)
    {
        return $query->where('attendance_status', 'absent');
    }

    /**
     * 高評価のスコープ
     */
    public function scopeHighPerformance($query, $minRating = 4)
    {
        return $query->where('performance_rating', '>=', $minRating);
    }
}