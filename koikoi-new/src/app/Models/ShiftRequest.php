<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'request_date',
        'request_type',
        'preferred_start',
        'preferred_end',
        'reason',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'request_date' => 'date',
        'preferred_start' => 'datetime:H:i',
        'preferred_end' => 'datetime:H:i',
        'approved_at' => 'datetime',
    ];

    /**
     * スタッフリレーション
     */
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * 承認者リレーション
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * ステータス別のスコープ
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * リクエストタイプ別のスコープ
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('request_type', $type);
    }
}