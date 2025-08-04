@props([
    'selectedDate' => null,
    'events' => [],
    'showMonths' => 2
])

@php
    use Carbon\Carbon;
    
    $today = Carbon::today();
    $currentMonth = Carbon::now()->startOfMonth();
    
    // イベントのある日付を取得
    $eventDates = collect($events)->groupBy(function($event) {
        return Carbon::parse($event->event_date)->format('Y-m-d');
    })->map->count();
    
    // 日本の祝日（簡易版）
    $holidays = [
        '2025-01-01' => '元日',
        '2025-01-13' => '成人の日',
        '2025-02-11' => '建国記念の日',
        '2025-02-23' => '天皇誕生日',
        '2025-03-20' => '春分の日',
        '2025-04-29' => '昭和の日',
        '2025-05-03' => '憲法記念日',
        '2025-05-04' => 'みどりの日',
        '2025-05-05' => 'こどもの日',
        '2025-07-21' => '海の日',
        '2025-08-11' => '山の日',
        '2025-09-15' => '敬老の日',
        '2025-09-23' => '秋分の日',
        '2025-10-13' => 'スポーツの日',
        '2025-11-03' => '文化の日',
        '2025-11-23' => '勤労感謝の日',
    ];
@endphp

<div class="calendar-sidebar-filter">
    <div class="calendar-controls mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-sm btn-link p-0" id="prevMonth">
                <i class="fas fa-chevron-left"></i>
            </button>
            <span class="fw-bold" id="currentMonthDisplay">{{ $currentMonth->format('Y年n月') }}</span>
            <button type="button" class="btn btn-sm btn-link p-0" id="nextMonth">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
    
    <div class="calendar-container" data-current-month="{{ $currentMonth->format('Y-m') }}">
        @for($m = 0; $m < $showMonths; $m++)
            @php
                $monthDate = $currentMonth->copy()->addMonths($m);
                $firstDay = $monthDate->copy()->startOfMonth();
                $lastDay = $monthDate->copy()->endOfMonth();
                $startWeek = $firstDay->copy()->startOfWeek(Carbon::SUNDAY);
                $endWeek = $lastDay->copy()->endOfWeek(Carbon::SATURDAY);
            @endphp
            
            <div class="calendar-month mb-3" data-month="{{ $monthDate->format('Y-m') }}">
                @if($m > 0)
                    <div class="month-label text-center fw-bold mb-2">
                        {{ $monthDate->format('Y年n月') }}
                    </div>
                @endif
                
                <table class="table table-sm calendar-table">
                    <thead>
                        <tr class="small">
                            <th class="text-center text-danger">日</th>
                            <th class="text-center">月</th>
                            <th class="text-center">火</th>
                            <th class="text-center">水</th>
                            <th class="text-center">木</th>
                            <th class="text-center">金</th>
                            <th class="text-center text-primary">土</th>
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
                                        $isSelected = $selectedDate && $dateStr === $selectedDate;
                                        $isWeekend = $currentDate->isWeekend();
                                        $isHoliday = isset($holidays[$dateStr]);
                                        $eventCount = $eventDates[$dateStr] ?? 0;
                                        
                                        $classes = ['calendar-day', 'text-center', 'position-relative'];
                                        if (!$isCurrentMonth) $classes[] = 'other-month';
                                        if ($isToday) $classes[] = 'today';
                                        if ($isSelected) $classes[] = 'selected';
                                        if ($eventCount > 0) $classes[] = 'has-event';
                                        if ($isHoliday) $classes[] = 'holiday';
                                        
                                        $textClasses = [];
                                        if ($isWeekend && !$isHoliday) {
                                            $textClasses[] = $currentDate->dayOfWeek === 0 ? 'text-danger' : 'text-primary';
                                        }
                                        if ($isHoliday) $textClasses[] = 'text-danger';
                                    @endphp
                                    
                                    <td class="{{ implode(' ', $classes) }}"
                                        @if($isCurrentMonth)
                                            data-date="{{ $dateStr }}"
                                            @if($isHoliday)
                                                title="{{ $holidays[$dateStr] }}"
                                            @endif
                                            @if($eventCount > 0)
                                                data-events="{{ $eventCount }}"
                                            @endif
                                        @endif>
                                        @if($isCurrentMonth)
                                            <span class="{{ implode(' ', $textClasses) }}">
                                                {{ $currentDate->day }}
                                            </span>
                                            @if($eventCount > 0)
                                                <span class="event-indicator">{{ $eventCount }}</span>
                                            @endif
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
    
    <div class="calendar-legend small text-muted mt-2">
        <div class="d-flex justify-content-around">
            <span><span class="badge bg-primary text-white">●</span> 今日</span>
            <span><span class="badge bg-warning">●</span> イベント</span>
            <span class="text-danger">● 祝日</span>
        </div>
    </div>
</div>

<style>
    .calendar-sidebar-filter {
        background: white;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .calendar-table {
        width: 100%;
        font-size: 0.875rem;
    }
    
    .calendar-table th {
        padding: 4px;
        border: none;
        font-weight: 600;
    }
    
    .calendar-day {
        padding: 4px;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all 0.2s;
    }
    
    .calendar-day:hover:not(.other-month) {
        background: #f8f9fa;
        border-color: #dee2e6;
    }
    
    .calendar-day.other-month {
        color: #dee2e6;
        cursor: default;
    }
    
    .calendar-day.today {
        background: var(--bs-primary);
        color: white !important;
        font-weight: bold;
    }
    
    .calendar-day.today span {
        color: white !important;
    }
    
    .calendar-day.selected {
        background: var(--bs-success);
        color: white !important;
        font-weight: bold;
    }
    
    .calendar-day.selected span {
        color: white !important;
    }
    
    .calendar-day.has-event {
        font-weight: 600;
    }
    
    .event-indicator {
        position: absolute;
        top: 2px;
        right: 2px;
        background: var(--bs-warning);
        color: white;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        font-size: 0.625rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    .month-label {
        color: var(--bs-secondary);
        font-size: 0.875rem;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarContainer = document.querySelector('.calendar-container');
    let currentViewMonth = '{{ $currentMonth->format("Y-m") }}';
    
    // 日付クリック
    document.querySelectorAll('.calendar-day:not(.other-month)').forEach(day => {
        day.addEventListener('click', function() {
            const date = this.dataset.date;
            if (!date) return;
            
            // 選択状態を更新
            document.querySelectorAll('.calendar-day.selected').forEach(el => {
                el.classList.remove('selected');
            });
            this.classList.add('selected');
            
            // フィルタを適用
            applyDateFilter(date);
        });
    });
    
    // 月移動
    document.getElementById('prevMonth')?.addEventListener('click', function() {
        changeMonth(-1);
    });
    
    document.getElementById('nextMonth')?.addEventListener('click', function() {
        changeMonth(1);
    });
    
    function changeMonth(direction) {
        const [year, month] = currentViewMonth.split('-').map(Number);
        const newDate = new Date(year, month - 1 + direction, 1);
        currentViewMonth = `${newDate.getFullYear()}-${String(newDate.getMonth() + 1).padStart(2, '0')}`;
        
        // Ajaxでカレンダーを更新
        updateCalendarView(currentViewMonth);
    }
    
    function updateCalendarView(yearMonth) {
        // ここでAjaxリクエストを送信してカレンダーを更新
        const url = new URL(window.location);
        url.searchParams.set('calendar_month', yearMonth);
        
        fetch(url.toString() + '&ajax=1')
            .then(response => response.text())
            .then(html => {
                // カレンダー部分のみを更新
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newCalendar = doc.querySelector('.calendar-container');
                if (newCalendar) {
                    calendarContainer.innerHTML = newCalendar.innerHTML;
                    document.getElementById('currentMonthDisplay').textContent = 
                        new Date(yearMonth + '-01').toLocaleDateString('ja-JP', { year: 'numeric', month: 'long' });
                    
                    // イベントリスナーを再設定
                    bindCalendarEvents();
                }
            });
    }
    
    function applyDateFilter(date) {
        const url = new URL(window.location);
        url.searchParams.set('filter_date', date);
        window.location.href = url.toString();
    }
    
    function bindCalendarEvents() {
        document.querySelectorAll('.calendar-day:not(.other-month)').forEach(day => {
            day.addEventListener('click', function() {
                const date = this.dataset.date;
                if (!date) return;
                
                document.querySelectorAll('.calendar-day.selected').forEach(el => {
                    el.classList.remove('selected');
                });
                this.classList.add('selected');
                
                applyDateFilter(date);
            });
        });
    }
});
</script>