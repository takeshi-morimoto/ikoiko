<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prefecture extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'code_en', 'region', 'display_order'
    ];

    // リレーション
    public function areas()
    {
        return $this->hasMany(Area::class);
    }
}
