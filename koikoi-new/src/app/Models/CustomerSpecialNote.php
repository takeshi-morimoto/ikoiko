<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSpecialNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'event_id',
        'category',
        'details',
        'is_critical',
        'is_confirmed',
    ];

    protected $casts = [
        'is_critical' => 'boolean',
        'is_confirmed' => 'boolean',
    ];

    /**
     * 顧客リレーション
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * イベントリレーション
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * 重要な注記のみのスコープ
     */
    public function scopeCritical($query)
    {
        return $query->where('is_critical', true);
    }

    /**
     * カテゴリごとのスコープ
     */
    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * アレルギー情報のスコープ
     */
    public function scopeAllergies($query)
    {
        return $query->where('category', 'allergy');
    }
}