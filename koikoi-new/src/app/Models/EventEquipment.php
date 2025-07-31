<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventEquipment extends Model
{
    use HasFactory;

    protected $table = 'event_equipment';

    protected $fillable = [
        'event_id',
        'item_name',
        'quantity',
        'status',
        'responsible_staff',
        'notes',
        'is_rental',
        'rental_cost',
    ];

    protected $casts = [
        'is_rental' => 'boolean',
        'rental_cost' => 'decimal:2',
    ];

    /**
     * イベントリレーション
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * ステータスごとのスコープ
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePrepared($query)
    {
        return $query->where('status', 'prepared');
    }

    public function scopeChecked($query)
    {
        return $query->where('status', 'checked');
    }
}