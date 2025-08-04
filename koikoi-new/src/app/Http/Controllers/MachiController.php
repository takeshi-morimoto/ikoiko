<?php

namespace App\Http\Controllers;

use App\Services\EventService;
use Illuminate\Http\Request;

class MachiController extends Controller
{
    public function __construct(
        private EventService $eventService
    ) {}
    
    /**
     * 街コンイベント一覧
     */
    public function index(Request $request)
    {
        $filters = $request->only(['area', 'date', 'month', 'search', 'sort', 'prefecture', 'age']);
        $filters['category'] = 'machi';
        
        $data = $this->eventService->getEventList($filters);
        
        return view('machi.index', $data);
    }
}