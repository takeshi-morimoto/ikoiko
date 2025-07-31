<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_name',
        'event_type',
        'roles',
        'total_staff_required',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'roles' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * アクティブなテンプレートのスコープ
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * イベントタイプ別のスコープ
     */
    public function scopeForEventType($query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    /**
     * 必要スタッフ数を計算
     */
    public function getTotalStaffCountAttribute()
    {
        if (!$this->roles) {
            return 0;
        }
        
        return collect($this->roles)->sum('count');
    }
}