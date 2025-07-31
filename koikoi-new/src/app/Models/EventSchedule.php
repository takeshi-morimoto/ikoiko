<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'time',
        'activity',
        'description',
        'responsible_staff',
        'duration_minutes',
        'display_order',
    ];

    protected $casts = [
        'time' => 'datetime:H:i',
    ];

    /**
     * イベントリレーション
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}