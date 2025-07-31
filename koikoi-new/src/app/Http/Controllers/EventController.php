<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Area;
use App\Models\Prefecture;
use App\Models\EventType;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * 全イベント一覧ページ
     */
    public function index(Request $request)
    {
        $query = Event::with(['area.prefecture', 'eventType'])
            ->where('status', 'published')
            ->where('event_date', '>=', now());

        // イベントタイプフィルタ
        if ($request->filled('type')) {
            $query->whereHas('eventType', function ($q) use ($request) {
                $q->where('slug', $request->type);
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

        // 検索フィルタ
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

        // ソート
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
                $query->orderBy('event_date', 'asc');
        }

        $events = $query->paginate(15);

        // フィルタ用のデータ
        $eventTypes = EventType::withCount(['events' => function ($q) {
            $q->where('status', 'published')
              ->where('event_date', '>=', now());
        }])->get();

        // すべての都道府県を表示
        $prefectures = Prefecture::orderBy('display_order')->get();

        return view('events.index', [
            'events' => $events,
            'eventTypes' => $eventTypes,
            'prefectures' => $prefectures
        ]);
    }

    /**
     * カレンダー表示
     */
    public function calendar(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $startDate = now()->setYear($year)->setMonth($month)->startOfMonth();
        $endDate = now()->setYear($year)->setMonth($month)->endOfMonth();

        $events = Event::with(['area.prefecture', 'eventType'])
            ->where('status', 'published')
            ->whereBetween('event_date', [$startDate, $endDate])
            ->orderBy('event_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy(function ($event) {
                return $event->event_date->format('Y-m-d');
            });

        return view('events.calendar', [
            'events' => $events,
            'year' => $year,
            'month' => $month,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    /**
     * 検索機能
     */
    public function search(Request $request)
    {
        $keyword = $request->get('q');
        
        if (!$keyword) {
            return redirect()->route('events.index');
        }

        $events = Event::with(['area.prefecture', 'eventType'])
            ->where('status', 'published')
            ->where('event_date', '>=', now())
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                      ->orWhere('description', 'like', "%{$keyword}%")
                      ->orWhere('venue_name', 'like', "%{$keyword}%")
                      ->orWhereHas('area', function ($q) use ($keyword) {
                          $q->where('name', 'like', "%{$keyword}%");
                      })
                      ->orWhereHas('area.prefecture', function ($q) use ($keyword) {
                          $q->where('name', 'like', "%{$keyword}%");
                      });
            })
            ->orderBy('event_date', 'asc')
            ->paginate(15);

        return view('events.search', [
            'events' => $events,
            'keyword' => $keyword
        ]);
    }

    /**
     * イベント詳細表示
     */
    public function show($eventType, $slug)
    {
        $event = Event::with(['area.prefecture', 'eventType'])
            ->where('slug', $slug)
            ->whereHas('eventType', function ($query) use ($eventType) {
                $query->where('slug', $eventType);
            })
            ->firstOrFail();

        // 関連イベント
        $relatedEvents = Event::with(['area.prefecture', 'eventType'])
            ->where('event_type_id', $event->event_type_id)
            ->where('area_id', $event->area_id)
            ->where('id', '!=', $event->id)
            ->where('status', 'published')
            ->where('event_date', '>=', now())
            ->limit(3)
            ->get();

        return view('events.show', [
            'event' => $event,
            'relatedEvents' => $relatedEvents
        ]);
    }
}
