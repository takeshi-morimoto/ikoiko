@props([
    'currentDate' => now(),
    'events' => [],
    'onDateSelect' => '',
    'showMonths' => 2,
    'highlightHolidays' => true,
    'highlightWeekends' => true
])

@php
    use Carbon\Carbon;
    
    $current = Carbon::parse($currentDate);
    $today = Carbon::today();
    
    // 日本の祝日（簡易版 - 実際は祝日APIやライブラリを使用推奨）
    $holidays = [
        '2025-08-11' => '山の日',
        '2025-09-15' => '敬老の日',
        '2025-09-23' => '秋分の日',
    ];
    
    // イベントのある日付を取得
    $eventDates = collect($events)->pluck('event_date')->map(function($date) {
        return Carbon::parse($date)->format('Y-m-d');
    })->toArray();
@endphp

<div class="calendar-filter-component" data-current-date="{{ $current->format('Y-m-d') }}">
    <div class="calendar-filter-header mb-3">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="navigateMonth(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="navigateToday()">
                今日
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="navigateMonth(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
    
    <div class="calendar-months d-flex gap-3">
        @for($m = 0; $m < $showMonths; $m++)
            @php
                $monthDate = $current->copy()->addMonths($m);
                $firstDay = $monthDate->copy()->startOfMonth();
                $lastDay = $monthDate->copy()->endOfMonth();
                $startWeek = $firstDay->copy()->startOfWeek();
                $endWeek = $lastDay->copy()->endOfWeek();
            @endphp
            
            <div class="calendar-month">
                <table class="calendar-table">
                    <thead>
                        <tr>
                            <th colspan="7" class="calendar-month-header">
                                {{ $monthDate->format('Y年n月') }}
                            </th>
                        </tr>
                        <tr>
                            <th class="calendar-day-header text-danger">日</th>
                            <th class="calendar-day-header">月</th>
                            <th class="calendar-day-header">火</th>
                            <th class="calendar-day-header">水</th>
                            <th class="calendar-day-header">木</th>
                            <th class="calendar-day-header">金</th>
                            <th class="calendar-day-header text-primary">土</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $currentDate = $startWeek->copy(); @endphp
                        @while($currentDate <= $endWeek)
                            <tr>
                                @for($d = 0; $d < 7; $d++)
                                    @php
                                        $dateStr = $currentDate->format('Y-m-d');
                                        $isCurrentMonth = $currentDate->month === $monthDate->month;
                                        $isToday = $currentDate->isToday();
                                        $isPast = $currentDate->isPast() && !$isToday;
                                        $isWeekend = $currentDate->isWeekend();
                                        $isHoliday = isset($holidays[$dateStr]);
                                        $hasEvent = in_array($dateStr, $eventDates);
                                        
                                        $classes = ['calendar-date'];
                                        if (!$isCurrentMonth) $classes[] = 'calendar-date-other';
                                        if ($isToday) $classes[] = 'calendar-date-today';
                                        if ($isPast) $classes[] = 'calendar-date-past';
                                        if ($isWeekend && $highlightWeekends) {
                                            $classes[] = $currentDate->dayOfWeek === 0 ? 'text-danger' : 'text-primary';
                                        }
                                        if ($isHoliday && $highlightHolidays) $classes[] = 'text-danger';
                                        if ($hasEvent) $classes[] = 'calendar-date-event';
                                        if (!$isPast || $isToday) $classes[] = 'calendar-date-clickable';
                                    @endphp
                                    
                                    <td class="{{ implode(' ', $classes) }}"
                                        @if(!$isPast || $isToday)
                                            onclick="selectDate('{{ $dateStr }}')"
                                            data-date="{{ $dateStr }}"
                                            @if($isHoliday)
                                                data-holiday="{{ $holidays[$dateStr] }}"
                                            @endif
                                        @endif>
                                        @if($isCurrentMonth)
                                            <div class="calendar-date-content">
                                                {{ $currentDate->day }}
                                                @if($hasEvent)
                                                    <span class="calendar-event-marker"></span>
                                                @endif
                                            </div>
                                        @else
                                            &nbsp;
                                        @endif
                                    </td>
                                    @php $currentDate->addDay(); @endphp
                                @endfor
                            </tr>
                        @endwhile
                    </tbody>
                </table>
            </div>
        @endfor
    </div>
    
    <div class="calendar-selected-date mt-3" style="display: none;">
        <div class="alert alert-info">
            <i class="fas fa-calendar-check me-2"></i>
            選択された日付: <strong id="selectedDateText"></strong>
        </div>
    </div>
</div>

@push('styles')
<style>
    .calendar-filter-component {
        background: white;
        padding: 20px;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }
    
    .calendar-table {
        width: 100%;
        table-layout: fixed;
        border-collapse: separate;
        border-spacing: 2px;
    }
    
    .calendar-month-header {
        background: var(--primary-color);
        color: white;
        padding: 10px;
        text-align: center;
        font-weight: bold;
        border-radius: 4px 4px 0 0;
    }
    
    .calendar-day-header {
        padding: 8px;
        text-align: center;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .calendar-date {
        padding: 8px;
        text-align: center;
        position: relative;
        height: 40px;
        vertical-align: middle;
        transition: all 0.2s ease;
    }
    
    .calendar-date-content {
        position: relative;
        display: inline-block;
    }
    
    .calendar-date-clickable {
        cursor: pointer;
    }
    
    .calendar-date-clickable:hover {
        background: #f0f0f0;
        border-radius: 4px;
    }
    
    .calendar-date-today {
        background: var(--accent-color) !important;
        color: white !important;
        font-weight: bold;
        border-radius: 4px;
    }
    
    .calendar-date-past {
        color: #ccc;
        cursor: default !important;
    }
    
    .calendar-date-past:hover {
        background: none !important;
    }
    
    .calendar-date-other {
        color: #e0e0e0;
    }
    
    .calendar-date-event {
        font-weight: 600;
    }
    
    .calendar-event-marker {
        position: absolute;
        bottom: 2px;
        left: 50%;
        transform: translateX(-50%);
        width: 4px;
        height: 4px;
        background: var(--warning-color);
        border-radius: 50%;
    }
    
    .calendar-date-selected {
        background: var(--success-color) !important;
        color: white !important;
        border-radius: 4px;
    }
    
    @media (max-width: 768px) {
        .calendar-months {
            flex-direction: column;
        }
        
        .calendar-date {
            font-size: 0.875rem;
            height: 35px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let selectedDate = null;
    
    function selectDate(date) {
        // 以前の選択をクリア
        document.querySelectorAll('.calendar-date-selected').forEach(el => {
            el.classList.remove('calendar-date-selected');
        });
        
        // 新しい日付を選択
        const dateElements = document.querySelectorAll(`[data-date="${date}"]`);
        dateElements.forEach(el => {
            el.classList.add('calendar-date-selected');
        });
        
        selectedDate = date;
        
        // 選択された日付を表示
        const selectedDateText = document.getElementById('selectedDateText');
        const selectedDateContainer = document.querySelector('.calendar-selected-date');
        
        if (selectedDateText && selectedDateContainer) {
            const d = new Date(date);
            const options = { year: 'numeric', month: 'long', day: 'numeric', weekday: 'long' };
            selectedDateText.textContent = d.toLocaleDateString('ja-JP', options);
            selectedDateContainer.style.display = 'block';
        }
        
        // カスタムイベントを発火
        const event = new CustomEvent('calendar-date-selected', { 
            detail: { date: date } 
        });
        document.dispatchEvent(event);
        
        // コールバック関数が定義されていれば実行
        @if($onDateSelect)
            {{ $onDateSelect }}(date);
        @endif
    }
    
    function navigateMonth(direction) {
        // 月移動の実装（ページリロードまたはAjax）
        const currentDate = document.querySelector('.calendar-filter-component').dataset.currentDate;
        const date = new Date(currentDate);
        date.setMonth(date.getMonth() + direction);
        
        // URLパラメータを更新してリロード
        const url = new URL(window.location);
        url.searchParams.set('calendar_date', date.toISOString().split('T')[0]);
        window.location.href = url.toString();
    }
    
    function navigateToday() {
        const today = new Date().toISOString().split('T')[0];
        selectDate(today);
        
        // 今日の日付が表示されている月にスクロール
        const todayElement = document.querySelector('.calendar-date-today');
        if (todayElement) {
            todayElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
    
    // イベントがある日付にツールチップを追加（オプション）
    document.addEventListener('DOMContentLoaded', function() {
        const eventDates = document.querySelectorAll('.calendar-date-event');
        eventDates.forEach(el => {
            if (el.dataset.date) {
                el.setAttribute('title', 'イベントあり');
            }
        });
    });
</script>
@endpush