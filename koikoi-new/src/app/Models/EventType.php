<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'color_primary', 'color_secondary', 'display_order'
    ];

    // リレーション
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
