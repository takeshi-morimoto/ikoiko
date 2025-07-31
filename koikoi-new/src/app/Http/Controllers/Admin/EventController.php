<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Area;
use App\Models\Prefecture;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * イベント一覧表示
     */
    public function index(Request $request)
    {
        $query = Event::with(['area.prefecture', 'eventType'])
            ->orderBy('event_date', 'desc')
            ->orderBy('start_time', 'desc');

        // フィルタリング
        if ($request->filled('event_type_id')) {
            $query->where('event_type_id', $request->event_type_id);
        }

        if ($request->filled('prefecture_id')) {
            $query->whereHas('area', function ($q) use ($request) {
                $q->where('prefecture_id', $request->prefecture_id);
            });
        }

        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('event_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('event_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                    ->orWhere('event_code', 'like', $search)
                    ->orWhere('venue_name', 'like', $search);
            });
        }

        $events = $query->paginate(50);

        // 集計情報
        $stats = [
            'total' => Event::count(),
            'upcoming' => Event::where('event_date', '>=', now())->count(),
            'past' => Event::where('event_date', '<', now())->count(),
            'published' => Event::where('status', 'published')->count(),
            'draft' => Event::where('status', 'draft')->count(),
        ];

        return view('admin.events.index', [
            'events' => $events,
            'stats' => $stats,
            'eventTypes' => EventType::all(),
            'prefectures' => Prefecture::orderBy('display_order')->get(),
            'areas' => $request->prefecture_id ? Area::where('prefecture_id', $request->prefecture_id)->get() : [],
        ]);
    }
}
