<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Area;
use App\Models\Prefecture;
use App\Models\EventType;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\EventFilterable;

class EventController extends Controller
{
    use EventFilterable;
    /**
     * 全イベント一覧ページ
     */
    public function index(Request $request)
    {
        $query = Event::with(['area.prefecture', 'eventType']);
        
        // イベントタイプフィルタ用のオプション
        $options = [];
        if ($request->filled('type')) {
            $options['event_type'] = $request->type;
        }
        
        // フィルター適用
        $this->applyEventFilters($query, $request, $options);
        $this->applySorting($query, $request);
        
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
