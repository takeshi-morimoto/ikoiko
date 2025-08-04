<?php

namespace App\Http\Controllers;

use App\Services\EventService;
use Illuminate\Http\Request;

class AnimeController extends Controller
{
    public function __construct(
        private EventService $eventService
    ) {}
    
    /**
     * アニメコンイベント一覧
     */
    public function index(Request $request)
    {
        $filters = $request->only(['area', 'date', 'month', 'search', 'sort', 'prefecture', 'age']);
        $filters['category'] = 'anime';
        
        $data = $this->eventService->getEventList($filters);
        
        return view('anime.index', $data);
    }
}