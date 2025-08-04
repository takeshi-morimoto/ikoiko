<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventType;
use App\Models\Area;
use App\Models\Prefecture;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class EventService
{
    /**
     * イベント一覧を取得
     */
    public function getEventList(array $filters = []): array
    {
        // イベント一覧
        $events = $this->buildEventQuery($filters)
            ->paginate(config('constants.pagination.items_per_page', 12));
        
        // サイドバーデータ
        $sidebarData = $this->getSidebarData();
        
        // 今後のイベント数
        $upcomingCounts = $this->getUpcomingCounts();
        
        return [
            'events' => $events,
            'eventTypes' => $sidebarData['eventTypes'],
            'areas' => $sidebarData['areas'],
            'upcomingCounts' => $upcomingCounts,
            'prefectures' => $this->getAllPrefectures(),
        ];
    }
    
    /**
     * イベント詳細を取得
     */
    public function getEventDetail(string $slug): array
    {
        $event = $this->findBySlug($slug);
        
        return [
            'event' => $event,
            'relatedEvents' => $this->getRelatedEvents($event),
            'participantStats' => $this->getParticipantStats($event),
        ];
    }
    
    /**
     * 都道府県別イベント一覧
     */
    public function getEventsByPrefecture(string $prefectureSlug): array
    {
        $prefecture = Prefecture::where('slug', $prefectureSlug)->firstOrFail();
        
        $events = Event::with(['area', 'eventType'])
            ->whereHas('area', function($q) use ($prefecture) {
                $q->where('prefecture_id', $prefecture->id);
            })
            ->where('status', 'published')
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->paginate(config('constants.pagination.items_per_page', 12));
        
        return [
            'prefecture' => $prefecture,
            'events' => $events,
        ];
    }
    
    /**
     * エリア別イベント一覧
     */
    public function getEventsByArea(string $prefectureSlug, string $areaSlug): array
    {
        $area = Area::where('slug', $areaSlug)
            ->whereHas('prefecture', function($q) use ($prefectureSlug) {
                $q->where('slug', $prefectureSlug);
            })
            ->firstOrFail();
        
        $events = Event::with(['area', 'eventType'])
            ->where('area_id', $area->id)
            ->where('status', 'published')
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->paginate(config('constants.pagination.items_per_page', 12));
        
        return [
            'area' => $area,
            'events' => $events,
        ];
    }
    
    /**
     * イベントクエリを構築
     */
    private function buildEventQuery(array $filters)
    {
        $query = Event::with(['area', 'eventType', 'area.prefecture'])
            ->where('status', 'published')
            ->where('event_date', '>=', now());
        
        // カテゴリフィルタ
        if (!empty($filters['category'])) {
            $query->whereHas('eventType', function($q) use ($filters) {
                $q->where('slug', $filters['category']);
            });
        }
        
        // エリアフィルタ
        if (!empty($filters['area'])) {
            $query->whereHas('area', function($q) use ($filters) {
                $q->where('slug', $filters['area']);
            });
        }
        
        // 日付フィルタ
        if (!empty($filters['date'])) {
            $date = Carbon::parse($filters['date']);
            $query->whereDate('event_date', $date);
        }
        
        // 月フィルタ
        if (!empty($filters['month'])) {
            $month = Carbon::parse($filters['month']);
            $query->whereMonth('event_date', $month->month)
                  ->whereYear('event_date', $month->year);
        }
        
        // 検索
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // ソート
        $this->applySorting($query, $filters['sort'] ?? 'date');
        
        return $query;
    }
    
    /**
     * ソートを適用
     */
    private function applySorting($query, string $sortBy): void
    {
        switch ($sortBy) {
            case 'popularity':
                $query->withCount('customers')
                      ->orderBy('customers_count', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price_female', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price_male', 'desc');
                break;
            default: // date
                $query->orderBy('event_date', 'asc')
                      ->orderBy('start_time', 'asc');
        }
    }
    
    /**
     * サイドバー用データを取得
     */
    private function getSidebarData(): array
    {
        $cacheKey = 'sidebar:data';
        $cacheTtl = config('constants.cache.ttl.areas', 3600);
        
        if (!config('constants.features.cache_enabled')) {
            return [
                'eventTypes' => $this->getEventTypesWithCount(),
                'areas' => $this->getPopularAreas(),
            ];
        }
        
        return Cache::remember($cacheKey, $cacheTtl, function () {
            return [
                'eventTypes' => $this->getEventTypesWithCount(),
                'areas' => $this->getPopularAreas(),
            ];
        });
    }
    
    /**
     * イベントタイプと件数を取得
     */
    private function getEventTypesWithCount()
    {
        return EventType::withCount(['events' => function($q) {
            $q->where('status', 'published')
              ->where('event_date', '>=', now());
        }])->get();
    }
    
    /**
     * 人気エリアを取得
     */
    private function getPopularAreas()
    {
        return Area::withCount(['events' => function($q) {
            $q->where('status', 'published')
              ->where('event_date', '>=', now());
        }])
        ->orderBy('events_count', 'desc')
        ->limit(10)
        ->get();
    }
    
    /**
     * 今後のイベント数を取得
     */
    private function getUpcomingCounts(): array
    {
        $now = Carbon::now();
        $nextMonth = $now->copy()->addMonth();
        
        return [
            'this_month' => Event::where('status', 'published')
                ->whereMonth('event_date', $now->month)
                ->whereYear('event_date', $now->year)
                ->count(),
            'next_month' => Event::where('status', 'published')
                ->whereMonth('event_date', $nextMonth->month)
                ->whereYear('event_date', $nextMonth->year)
                ->count(),
        ];
    }
    
    /**
     * 全都道府県を取得
     */
    private function getAllPrefectures()
    {
        $cacheKey = 'prefectures:all';
        $cacheTtl = config('constants.cache.ttl.prefectures', 86400);
        
        if (!config('constants.features.cache_enabled')) {
            return Prefecture::orderBy('name')->get();
        }
        
        return Cache::remember($cacheKey, $cacheTtl, function () {
            return Prefecture::orderBy('name')->get();
        });
    }
    
    /**
     * スラッグでイベントを検索
     */
    private function findBySlug(string $slug): Event
    {
        return Event::with(['area', 'eventType', 'area.prefecture'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
    }
    
    /**
     * 関連イベントを取得
     */
    private function getRelatedEvents(Event $event): object
    {
        return Event::with(['area', 'eventType'])
            ->where('status', 'published')
            ->where('event_date', '>=', now())
            ->where('id', '!=', $event->id)
            ->where(function($q) use ($event) {
                $q->where('event_type_id', $event->event_type_id)
                  ->orWhere('area_id', $event->area_id);
            })
            ->orderBy('event_date')
            ->limit(4)
            ->get();
    }
    
    /**
     * 参加者統計を取得
     */
    private function getParticipantStats(Event $event): array
    {
        $total = $event->customers()->count();
        $male = $event->customers()->where('gender', 'male')->count();
        $female = $event->customers()->where('gender', 'female')->count();
        
        return [
            'total' => $total,
            'male' => $male,
            'female' => $female,
            'remaining' => max(0, $event->capacity - $total),
            'male_remaining' => max(0, $event->capacity_male - $male),
            'female_remaining' => max(0, $event->capacity_female - $female),
        ];
    }
}