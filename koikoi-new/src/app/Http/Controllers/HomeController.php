<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventType;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * トップページの表示
     */
    public function index()
    {
        // アニメコンの直近イベント
        $animeEvents = Event::with(['area.prefecture', 'eventType'])
            ->whereHas('eventType', function ($query) {
                $query->where('slug', 'anime');
            })
            ->where('status', 'published')
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->limit(3)
            ->get();

        // 街コンの直近イベント
        $machiEvents = Event::with(['area.prefecture', 'eventType'])
            ->whereHas('eventType', function ($query) {
                $query->where('slug', 'machi');
            })
            ->where('status', 'published')
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->limit(3)
            ->get();

        // 統計情報
        $stats = [
            'total_events' => Event::where('status', 'published')->count(),
            'total_participants' => Event::sum('registered_male') + Event::sum('registered_female'),
            'upcoming_events' => Event::where('status', 'published')
                ->where('event_date', '>=', now())
                ->count(),
        ];

        return view('home.index', compact('animeEvents', 'machiEvents', 'stats'));
    }
}
