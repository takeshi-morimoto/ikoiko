<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Area;
use App\Models\Prefecture;
use App\Repositories\EventRepository;
use App\Repositories\PrefectureRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\EventFilterable;

class AnimeController extends Controller
{
    use EventFilterable;
    
    public function __construct(
        private EventRepository $eventRepository,
        private PrefectureRepository $prefectureRepository
    ) {}
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
        $relatedEvents = $this->eventRepository->getRelatedEvents($event, 3);

        return view('anime.show', [
            'event' => $event,
            'relatedEvents' => $relatedEvents,
            'theme' => 'anime'
        ]);
    }
}
