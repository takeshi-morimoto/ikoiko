<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'event_id',
        'registration_number',
        'name',
        'name_kana',
        'email',
        'phone',
        'gender',
        'birth_date',
        'age',
        'postal_code',
        'prefecture',
        'city',
        'address',
        'emergency_contact',
        'emergency_name',
        'comment',
        'status',
        'payment_method',
        'payment_status',
        'paid_at',
        'registered_at',
        'cancelled_at',
        'cancel_reason'
    ];
    
    protected $casts = [
        'birth_date' => 'date',
        'paid_at' => 'datetime',
        'registered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];
    
    // リレーション
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    
    // スコープ
    public function scopeRegistered($query)
    {
        return $query->where('status', 'registered');
    }
    
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
    
    public function scopeMale($query)
    {
        return $query->where('gender', 'male');
    }
    
    public function scopeFemale($query)
    {
        return $query->where('gender', 'female');
    }
}
