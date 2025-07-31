<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait EventFilterable
{
    /**
     * イベントクエリにフィルターを適用
     */
    protected function applyEventFilters($query, Request $request, array $options = [])
    {
        // 公開イベントのみ
        $query->where('status', 'published')
              ->where('event_date', '>=', now());

        // イベントタイプフィルタ
        if (isset($options['event_type'])) {
            $query->whereHas('eventType', function ($q) use ($options) {
                $q->where('slug', $options['event_type']);
            });
        }

        // 都道府県フィルタ
        if ($request->filled('prefecture')) {
            $query->whereHas('area.prefecture', function ($q) use ($request) {
                $q->where('code_en', $request->prefecture);
            });
        }

        // エリアフィルタ
        if ($request->filled('area')) {
            $query->whereHas('area', function ($q) use ($request) {
                $q->where('slug', $request->area);
            });
        }

        // 月フィルタ
        if ($request->filled('month')) {
            $query->whereMonth('event_date', $request->month);
        }

        // 年齢フィルタ
        if ($request->filled('age')) {
            $age = (int)$request->age;
            $query->where(function ($q) use ($age) {
                $q->where(function ($q2) use ($age) {
                    $q2->where('age_min_male', '<=', $age)
                       ->where('age_max_male', '>=', $age);
                })->orWhere(function ($q2) use ($age) {
                    $q2->where('age_min_female', '<=', $age)
                       ->where('age_max_female', '>=', $age);
                });
            });
        }

        // 価格フィルタ
        if ($request->filled('price_max')) {
            $priceMax = (int)$request->price_max;
            $query->where(function ($q) use ($priceMax) {
                $q->where('price_male', '<=', $priceMax)
                  ->orWhere('price_female', '<=', $priceMax);
            });
        }

        // 検索キーワード
        if ($request->filled('q')) {
            $keyword = $request->get('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('venue_name', 'like', "%{$keyword}%")
                  ->orWhereHas('area', function ($q2) use ($keyword) {
                      $q2->where('name', 'like', "%{$keyword}%");
                  })
                  ->orWhereHas('area.prefecture', function ($q2) use ($keyword) {
                      $q2->where('name', 'like', "%{$keyword}%");
                  });
            });
        }

        return $query;
    }

    /**
     * イベントのソート
     */
    protected function applySorting($query, Request $request)
    {
        $sortBy = $request->get('sort', 'date');
        
        switch ($sortBy) {
            case 'price_asc':
                $query->orderByRaw('LEAST(price_male, price_female) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('LEAST(price_male, price_female) DESC');
                break;
            case 'capacity':
                $query->orderByRaw('(remaining_male_seats + remaining_female_seats) DESC');
                break;
            default:
                $query->orderBy('event_date', 'asc')
                      ->orderBy('start_time', 'asc');
        }

        return $query;
    }
}