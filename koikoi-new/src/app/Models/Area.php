<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'prefecture_id', 'name', 'name_kana', 'slug', 'display_order', 'old_area_id'
    ];

    // リレーション
    public function prefecture()
    {
        return $this->belongsTo(Prefecture::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
