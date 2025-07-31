@extends('layouts.app')

@section('title', 'イベントカレンダー | KOIKOI')
@section('description', '婚活イベントのカレンダー表示。開催予定が一目でわかります。')

@section('content')
<!-- ヘッダー -->
<section class="page-header calendar-header">
    <div class="page-header-container">
        <h1 class="page-title">イベントカレンダー</h1>
        <p class="page-subtitle">{{ $year }}年{{ $month }}月の開催予定</p>
    </div>
</section>

<!-- カレンダーナビゲーション -->
<section class="calendar-nav-section">
    <div class="calendar-nav-container">
        <div class="calendar-nav">
            <a href="{{ route('events.calendar', ['year' => $month == 1 ? $year - 1 : $year, 'month' => $month == 1 ? 12 : $month - 1]) }}" 
               class="nav-button prev">
                <i class="icon-chevron-left"></i> 前月
            </a>
            
            <div class="current-month">
                <select name="year" class="year-select" onchange="changeMonth()">
                    @for($y = now()->year - 1; $y <= now()->year + 1; $y++)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}年</option>
                    @endfor
                </select>
                <select name="month" class="month-select" onchange="changeMonth()">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ $m }}月</option>
                    @endfor
                </select>
            </div>
            
            <a href="{{ route('events.calendar', ['year' => $month == 12 ? $year + 1 : $year, 'month' => $month == 12 ? 1 : $month + 1]) }}" 
               class="nav-button next">
                次月 <i class="icon-chevron-right"></i>
            </a>
        </div>
        
        <div class="view-switch">
            <a href="{{ route('events.index') }}" class="view-link">
                <i class="icon-list"></i> リスト表示に戻る
            </a>
        </div>
    </div>
</section>

<!-- カレンダー本体 -->
<section class="calendar-section">
    <div class="calendar-container">
        <div class="calendar">
            <div class="calendar-header">
                <div class="day-header">日</div>
                <div class="day-header">月</div>
                <div class="day-header">火</div>
                <div class="day-header">水</div>
                <div class="day-header">木</div>
                <div class="day-header">金</div>
                <div class="day-header">土</div>
            </div>
            
            <div class="calendar-body">
                @php
                    $firstDay = $startDate->copy()->startOfMonth();
                    $lastDay = $endDate->copy()->endOfMonth();
                    $startWeekday = $firstDay->dayOfWeek;
                    $currentDate = $firstDay->copy()->subDays($startWeekday);
                @endphp
                
                @while($currentDate <= $lastDay || $currentDate->dayOfWeek != 0)
                    @if($currentDate->dayOfWeek == 0)
                        <div class="calendar-week">
                    @endif
                    
                    <div class="calendar-day {{ $currentDate->month != $month ? 'other-month' : '' }} 
                                           {{ $currentDate->isToday() ? 'today' : '' }}
                                           {{ $currentDate->dayOfWeek == 0 ? 'sunday' : '' }}
                                           {{ $currentDate->dayOfWeek == 6 ? 'saturday' : '' }}">
                        <div class="day-number">{{ $currentDate->day }}</div>
                        
                        @if(isset($events[$currentDate->format('Y-m-d')]))
                            <div class="day-events">
                                @foreach($events[$currentDate->format('Y-m-d')] as $event)
                                    <a href="{{ $event->eventType->slug == 'anime' ? route('anime.show', $event->slug) : route('machi.show', $event->slug) }}" 
                                       class="event-item {{ $event->eventType->slug }}">
                                        <span class="event-time">{{ $event->start_time->format('H:i') }}</span>
                                        <span class="event-name">{{ Str::limit($event->title, 20) }}</span>
                                        <span class="event-area">{{ $event->area->name }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    
                    @if($currentDate->dayOfWeek == 6)
                        </div>
                    @endif
                    
                    @php $currentDate->addDay() @endphp
                @endwhile
                
                @if($currentDate->dayOfWeek != 0)
                    </div>
                @endif
            </div>
        </div>
        
        <!-- カレンダー凡例 -->
        <div class="calendar-legend">
            <h3>凡例</h3>
            <div class="legend-items">
                <div class="legend-item">
                    <span class="legend-color anime"></span>
                    <span>アニメコン</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color machi"></span>
                    <span>街コン</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- イベント一覧（モバイル用） -->
<section class="mobile-events-section">
    <div class="mobile-events-container">
        <h2>{{ $year }}年{{ $month }}月のイベント</h2>
        
        @php
            $allEvents = collect($events)->flatten();
        @endphp
        
        @if($allEvents->count() > 0)
            <div class="mobile-events-list">
                @foreach($allEvents->sortBy('event_date') as $event)
                    <div class="mobile-event-item {{ $event->eventType->slug }}">
                        <div class="mobile-event-date">
                            <span class="date">{{ $event->event_date->format('n/j') }}</span>
                            <span class="weekday">({{ $event->day_of_week }})</span>
                            <span class="time">{{ $event->start_time->format('H:i') }}</span>
                        </div>
                        <div class="mobile-event-info">
                            <h3>{{ $event->title }}</h3>
                            <p>{{ $event->area->prefecture->name }} {{ $event->area->name }}</p>
                            <div class="mobile-event-capacity">
                                男性 残り{{ $event->remaining_male_seats }}名 / 
                                女性 残り{{ $event->remaining_female_seats }}名
                            </div>
                        </div>
                        <a href="{{ $event->eventType->slug == 'anime' ? route('anime.show', $event->slug) : route('machi.show', $event->slug) }}" 
                           class="mobile-event-link">
                            詳細 →
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="no-events-message">この月にはイベントがありません。</p>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
/* カレンダーヘッダー */
.calendar-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 60px 0;
    text-align: center;
}

/* カレンダーナビゲーション */
.calendar-nav-section {
    background: white;
    padding: 20px 0;
    border-bottom: 1px solid #e9ecef;
}

.calendar-nav-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.calendar-nav {
    display: flex;
    align-items: center;
    gap: 30px;
}

.nav-button {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 8px 16px;
    background: #f8f9fa;
    border-radius: 5px;
    text-decoration: none;
    color: #495057;
    font-weight: 500;
    transition: all 0.3s;
}

.nav-button:hover {
    background: #e9ecef;
}

.current-month {
    display: flex;
    gap: 10px;
}

.year-select,
.month-select {
    padding: 8px 15px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    background: white;
}

/* カレンダー本体 */
.calendar-section {
    padding: 40px 0;
}

.calendar-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.calendar {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow: hidden;
}

.calendar-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    background: #f8f9fa;
}

.day-header {
    padding: 15px;
    text-align: center;
    font-weight: 700;
    border-right: 1px solid #e9ecef;
}

.day-header:last-child {
    border-right: none;
}

.calendar-body {
    display: flex;
    flex-direction: column;
}

.calendar-week {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    min-height: 120px;
}

.calendar-day {
    border-right: 1px solid #e9ecef;
    border-bottom: 1px solid #e9ecef;
    padding: 10px;
    position: relative;
}

.calendar-day:last-child {
    border-right: none;
}

.calendar-day.other-month {
    background: #f8f9fa;
    opacity: 0.5;
}

.calendar-day.today {
    background: #fff3cd;
}

.calendar-day.sunday .day-number {
    color: #dc3545;
}

.calendar-day.saturday .day-number {
    color: #0069d9;
}

.day-number {
    font-weight: 700;
    margin-bottom: 5px;
}

/* イベント表示 */
.day-events {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.event-item {
    display: block;
    padding: 3px 5px;
    border-radius: 3px;
    font-size: 0.75rem;
    text-decoration: none;
    color: white;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.event-item.anime {
    background: var(--color-anime-primary);
}

.event-item.machi {
    background: var(--color-machi-primary);
}

.event-time {
    font-weight: 700;
    margin-right: 3px;
}

.event-area {
    opacity: 0.8;
    font-size: 0.7rem;
}

/* 凡例 */
.calendar-legend {
    margin-top: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.calendar-legend h3 {
    margin-bottom: 15px;
}

.legend-items {
    display: flex;
    gap: 30px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.legend-color {
    width: 20px;
    height: 20px;
    border-radius: 3px;
}

.legend-color.anime {
    background: var(--color-anime-primary);
}

.legend-color.machi {
    background: var(--color-machi-primary);
}

/* モバイル用イベント一覧 */
.mobile-events-section {
    display: none;
    padding: 40px 0;
    background: #f8f9fa;
}

.mobile-events-container {
    max-width: 768px;
    margin: 0 auto;
    padding: 0 20px;
}

.mobile-events-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 20px;
}

.mobile-event-item {
    background: white;
    padding: 20px;
    border-radius: 8px;
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 20px;
    align-items: center;
    border-left: 4px solid;
}

.mobile-event-item.anime {
    border-left-color: var(--color-anime-primary);
}

.mobile-event-item.machi {
    border-left-color: var(--color-machi-primary);
}

.mobile-event-date {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.mobile-event-date .date {
    font-weight: 700;
    font-size: 1.125rem;
}

.mobile-event-info h3 {
    font-size: 1.125rem;
    margin-bottom: 5px;
}

.mobile-event-capacity {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 5px;
}

.mobile-event-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
}

/* レスポンシブ */
@media (max-width: 968px) {
    .calendar {
        display: none;
    }
    
    .mobile-events-section {
        display: block;
    }
    
    .calendar-nav-container {
        flex-direction: column;
        gap: 20px;
    }
    
    .mobile-event-item {
        grid-template-columns: 1fr;
        text-align: center;
    }
}

@media (max-width: 768px) {
    .calendar-nav {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .current-month {
        order: -1;
        width: 100%;
        justify-content: center;
        margin-bottom: 10px;
    }
}
</style>
@endpush

@push('scripts')
<script>
function changeMonth() {
    const year = document.querySelector('.year-select').value;
    const month = document.querySelector('.month-select').value;
    window.location.href = `{{ route('events.calendar') }}?year=${year}&month=${month}`;
}
</script>
@endpush