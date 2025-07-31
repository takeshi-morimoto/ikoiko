<?php

namespace App\Repositories;

use App\Models\Prefecture;

class PrefectureRepository
{
    /**
     * すべての都道府県を取得
     */
    public function getAll()
    {
        return Prefecture::orderBy('display_order')->get();
    }

    /**
     * イベントがある都道府県のみ取得
     */
    public function getWithEvents(string $eventType = null)
    {
        $query = Prefecture::whereHas('areas.events', function ($q) use ($eventType) {
            $q->where('status', 'published')
              ->where('event_date', '>=', now());
            
            if ($eventType) {
                $q->whereHas('eventType', function ($q2) use ($eventType) {
                    $q2->where('slug', $eventType);
                });
            }
        });

        return $query->orderBy('display_order')->get();
    }

    /**
     * 地域ごとにグループ化された都道府県を取得
     */
    public function getGroupedByRegion()
    {
        return Prefecture::orderBy('display_order')
            ->get()
            ->groupBy('region');
    }
}