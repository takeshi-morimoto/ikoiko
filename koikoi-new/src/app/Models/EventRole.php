<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'staff_id',
        'role_name',
        'responsibilities',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * イベントリレーション
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * スタッフリレーション
     */
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * ステータスごとのスコープ
     */
    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}