<?php

namespace App\Http\Controllers;

use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct(
        private EventService $eventService
    ) {}
    
    /**
     * イベント一覧表示（トップページ）
     */
    public function index(Request $request)
    {
        $filters = $request->only(['category', 'area', 'date', 'month', 'search', 'sort']);
        $data = $this->eventService->getEventList($filters);
        
        return view('events.index', $data);
    }
    
    /**
     * イベント詳細表示
     */
    public function show($slug, $eventType = null)
    {
        $data = $this->eventService->getEventDetail($slug);
        return view('events.show', $data);
    }
    
    /**
     * 都道府県別イベント一覧
     */
    public function byPrefecture($prefecture)
    {
        $data = $this->eventService->getEventsByPrefecture($prefecture);
        return view('events.by-prefecture', $data);
    }
    
    /**
     * エリア別イベント一覧
     */
    public function byArea($prefecture, $area)
    {
        $data = $this->eventService->getEventsByArea($prefecture, $area);
        return view('events.by-area', $data);
    }
}