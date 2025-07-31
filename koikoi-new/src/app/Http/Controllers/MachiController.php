<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Area;
use App\Models\Prefecture;
use Illuminate\Http\Request;

class MachiController extends Controller
{
    /**
     * 街コン一覧ページ
     */
    public function index(Request $request)
    {
        $query = Event::with(['area.prefecture', 'eventType'])
            ->whereHas('eventType', function ($q) {
                $q->where('slug', 'machi');
            })
            ->where('status', 'published')
            ->where('event_date', '>=', now());

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

        $events = $query->orderBy('event_date', 'asc')
            ->paginate(12);

        // フィルタ用のデータ（すべての都道府県を表示）
        $prefectures = Prefecture::orderBy('display_order')->get();

        return view('machi.index', [
            'events' => $events,
            'prefectures' => $prefectures,
            'theme' => 'machi'
        ]);
    }

    /**
     * 街コンについてページ
     */
    public function about()
    {
        return view('machi.about', [
            'theme' => 'machi'
        ]);
    }

    /**
     * 街コン詳細ページ
     */
    public function show($slug)
    {
        $event = Event::with(['area.prefecture', 'eventType'])
            ->where('slug', $slug)
            ->whereHas('eventType', function ($q) {
                $q->where('slug', 'machi');
            })
            ->firstOrFail();

        // 同じエリアの他のイベント
        $relatedEvents = Event::with(['area.prefecture', 'eventType'])
            ->where('area_id', $event->area_id)
            ->where('id', '!=', $event->id)
            ->whereHas('eventType', function ($q) {
                $q->where('slug', 'machi');
            })
            ->where('status', 'published')
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->limit(3)
            ->get();

        return view('machi.show', [
            'event' => $event,
            'relatedEvents' => $relatedEvents,
            'theme' => 'machi'
        ]);
    }
}
