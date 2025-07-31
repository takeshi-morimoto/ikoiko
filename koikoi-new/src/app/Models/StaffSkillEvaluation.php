<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffSkillEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'skill_name',
        'skill_level',
        'evaluated_at',
        'evaluated_by',
        'evaluation_notes',
    ];

    protected $casts = [
        'evaluated_at' => 'date',
    ];

    /**
     * スタッフリレーション
     */
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * 評価者リレーション
     */
    public function evaluatedBy()
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    /**
     * スキル別のスコープ
     */
    public function scopeOfSkill($query, $skillName)
    {
        return $query->where('skill_name', $skillName);
    }

    /**
     * 高スキルレベルのスコープ
     */
    public function scopeHighLevel($query, $minLevel = 4)
    {
        return $query->where('skill_level', '>=', $minLevel);
    }

    /**
     * 最新の評価のスコープ
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('evaluated_at', 'desc');
    }
}