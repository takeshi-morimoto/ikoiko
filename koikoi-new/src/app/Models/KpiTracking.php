<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiTracking extends Model
{
    use HasFactory;

    protected $table = 'kpi_tracking';

    protected $fillable = [
        'date',
        'kpi_name',
        'value',
        'dimension',
        'dimension_value',
    ];

    protected $casts = [
        'date' => 'date',
        'value' => 'decimal:4',
    ];

    /**
     * KPI名でのスコープ
     */
    public function scopeOfKpi($query, $kpiName)
    {
        return $query->where('kpi_name', $kpiName);
    }

    /**
     * 期間でのスコープ
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * ディメンション別のスコープ
     */
    public function scopeWithDimension($query, $dimension, $value = null)
    {
        $query->where('dimension', $dimension);
        
        if ($value !== null) {
            $query->where('dimension_value', $value);
        }
        
        return $query;
    }

    /**
     * 最新値を取得
     */
    public static function getLatestValue($kpiName, $dimension = null, $dimensionValue = null)
    {
        $query = self::where('kpi_name', $kpiName);
        
        if ($dimension) {
            $query->where('dimension', $dimension);
            
            if ($dimensionValue) {
                $query->where('dimension_value', $dimensionValue);
            }
        } else {
            $query->whereNull('dimension');
        }
        
        return $query->orderBy('date', 'desc')->first();
    }
}