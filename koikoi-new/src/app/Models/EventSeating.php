<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSeating extends Model
{
    use HasFactory;

    protected $table = 'event_seating';

    protected $fillable = [
        'event_id',
        'group_name',
        'table_number',
        'seat_number',
        'customer_id',
        'special_notes',
    ];

    /**
     * イベントリレーション
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * 顧客リレーション
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * テーブルごとのグループ化スコープ
     */
    public function scopeByTable($query)
    {
        return $query->orderBy('table_number')->orderBy('seat_number');
    }

    /**
     * 空席のみのスコープ
     */
    public function scopeVacant($query)
    {
        return $query->whereNull('customer_id');
    }

    /**
     * 着席済みのスコープ
     */
    public function scopeOccupied($query)
    {
        return $query->whereNotNull('customer_id');
    }
}