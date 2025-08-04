<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Area;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * イベント一覧表示
     */
    public function index(Request $request)
    {
        $query = Event::with(['area', 'eventType'])
            ->withCount('customers');
        
        // 日付フィルタ（カレンダーから選択）
        if ($request->has('filter_date')) {
            $date = Carbon::parse($request->filter_date);
            $query->whereDate('event_date', $date);
        }
        
        // イベントタイプフィルタ
        if ($request->has('types')) {
            $types = explode(',', $request->types);
            $query->whereHas('eventType', function($q) use ($types) {
                $q->whereIn('slug', $types);
            });
        }
        
        // ステータスフィルタ
        if ($request->has('statuses')) {
            $statuses = explode(',', $request->statuses);
            $query->whereIn('status', $statuses);
        }
        
        // 検索
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // ソート（デフォルトは日付順）
        $query->orderBy('event_date', 'desc')
              ->orderBy('start_time', 'desc');
        
        $events = $query->paginate(20);
        
        return view('admin.events.index', compact('events'));
    }
    
    /**
     * イベント作成フォーム
     */
    public function create()
    {
        $eventTypes = EventType::all();
        $areas = Area::orderBy('name')->get();
        
        return view('admin.events.create', compact('eventTypes', 'areas'));
    }
    
    /**
     * イベント保存
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'event_type_id' => 'required|exists:event_types,id',
            'area_id' => 'required|exists:areas,id',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'capacity' => 'required|integer|min:1',
            'price_male' => 'required|integer|min:0',
            'price_female' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,published,cancelled',
        ]);
        
        $event = Event::create($validated);
        
        return redirect()->route('admin.events.index')
            ->with('success', 'イベントを作成しました。');
    }
    
    /**
     * イベント編集フォーム
     */
    public function edit(Event $event)
    {
        $eventTypes = EventType::all();
        $areas = Area::orderBy('name')->get();
        
        return view('admin.events.edit', compact('event', 'eventTypes', 'areas'));
    }
    
    /**
     * イベント更新
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'event_type_id' => 'required|exists:event_types,id',
            'area_id' => 'required|exists:areas,id',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'capacity' => 'required|integer|min:1',
            'price_male' => 'required|integer|min:0',
            'price_female' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,published,cancelled',
        ]);
        
        $event->update($validated);
        
        return redirect()->route('admin.events.index')
            ->with('success', 'イベントを更新しました。');
    }
    
    /**
     * イベント削除
     */
    public function destroy(Event $event)
    {
        // 参加者がいる場合は削除不可
        if ($event->customers()->count() > 0) {
            return back()->with('error', '参加者がいるイベントは削除できません。');
        }
        
        $event->delete();
        
        return redirect()->route('admin.events.index')
            ->with('success', 'イベントを削除しました。');
    }
}