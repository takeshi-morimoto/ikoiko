<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_code',
        'phone',
        'emergency_contact',
        'hire_date',
        'employment_type',
        'skills',
        'qualifications',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'skills' => 'array',
        'qualifications' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * ユーザーリレーション
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * アクティブなスタッフのスコープ
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * 雇用形態別のスコープ
     */
    public function scopeOfEmploymentType($query, $type)
    {
        return $query->where('employment_type', $type);
    }

    /**
     * スキルを持つスタッフのスコープ
     */
    public function scopeWithSkill($query, $skill)
    {
        return $query->whereJsonContains('skills', $skill);
    }

    /**
     * 勤続年数を計算
     */
    public function getYearsOfServiceAttribute()
    {
        return $this->hire_date ? $this->hire_date->diffInYears(now()) : 0;
    }
}