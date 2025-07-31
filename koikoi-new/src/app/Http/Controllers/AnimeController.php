<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Area;
use App\Models\Prefecture;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\EventFilterable;

class AnimeController extends Controller
{
    use EventFilterable;
    /**
     * アニメコン一覧ページ
     */
    public function index(Request $request)
    {
        $query = Event::with(['area.prefecture', 'eventType']);
        
        // フィルター適用
        $this->applyEventFilters($query, $request, ['event_type' => 'anime']);
        $this->applySorting($query, $request);
        
        $events = $query->paginate(12);

        // フィルタ用のデータ（すべての都道府県を表示）
        $prefectures = Prefecture::orderBy('display_order')->get();

        return view('anime.index', [
            'events' => $events,
            'prefectures' => $prefectures,
            'theme' => 'anime'
        ]);
    }

    /**
     * アニメコンについてページ
     */
    public function about()
    {
        return view('anime.about', [
            'theme' => 'anime'
        ]);
    }

    /**
     * アニメコン詳細ページ
     */
    public function show($slug)
    {
        $event = Event::with(['area.prefecture', 'eventType'])
            ->where('slug', $slug)
            ->whereHas('eventType', function ($q) {
                $q->where('slug', 'anime');
            })
            ->firstOrFail();

        // 同じエリアの他のイベント
        $relatedEvents = Event::with(['area.prefecture', 'eventType'])
            ->where('area_id', $event->area_id)
            ->where('id', '!=', $event->id)
            ->whereHas('eventType', function ($q) {
                $q->where('slug', 'anime');
            })
            ->where('status', 'published')
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->limit(3)
            ->get();

        return view('anime.show', [
            'event' => $event,
            'relatedEvents' => $relatedEvents,
            'theme' => 'anime'
        ]);
    }
}
