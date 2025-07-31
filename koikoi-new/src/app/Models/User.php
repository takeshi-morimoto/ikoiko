<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    /**
     * スタッフプロフィール
     */
    public function staffProfile()
    {
        return $this->hasOne(StaffProfile::class);
    }
    
    /**
     * スタッフシフト
     */
    public function shifts()
    {
        return $this->hasMany(StaffShift::class, 'staff_id');
    }
    
    /**
     * シフト希望
     */
    public function shiftRequests()
    {
        return $this->hasMany(ShiftRequest::class, 'staff_id');
    }
    
    /**
     * 勤務記録
     */
    public function workRecords()
    {
        return $this->hasMany(StaffWorkRecord::class, 'staff_id');
    }
    
    /**
     * スキル評価
     */
    public function skillEvaluations()
    {
        return $this->hasMany(StaffSkillEvaluation::class, 'staff_id');
    }
    
    /**
     * イベント役割
     */
    public function eventRoles()
    {
        return $this->hasMany(EventRole::class, 'staff_id');
    }
    
    /**
     * スタッフかどうかを判定
     */
    public function isStaff()
    {
        return $this->staffProfile()->exists();
    }
    
    /**
     * アクティブなスタッフかどうかを判定
     */
    public function isActiveStaff()
    {
        return $this->staffProfile()->where('is_active', true)->exists();
    }
}
