<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'category',
        'task',
        'is_completed',
        'completed_at',
        'completed_by',
        'display_order',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    /**
     * イベントリレーション
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * 完了者リレーション
     */
    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    /**
     * 未完了タスクのスコープ
     */
    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    /**
     * 完了済みタスクのスコープ
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * カテゴリごとのスコープ
     */
    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}