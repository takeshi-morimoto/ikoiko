@props([
    'events' => [],
    'selectedDate' => null
])

@php
    use Carbon\Carbon;
    
    $today = Carbon::today();
    $currentMonth = request('calendar_month') ? Carbon::parse(request('calendar_month')) : Carbon::now();
    
    // イベントのある日付を取得
    $eventDates = collect($events)->groupBy(function($event) {
        return Carbon::parse($event->event_date)->format('Y-m-d');
    })->map->count();
    
    // 日本の祝日（2025年）
    $holidays = [
        '2025-01-01' => '元日',
        '2025-01-13' => '成人の日', 
        '2025-02-11' => '建国記念の日',
        '2025-02-23' => '天皇誕生日',
        '2025-02-24' => '振替休日',
        '2025-03-20' => '春分の日',
        '2025-04-29' => '昭和の日',
        '2025-05-03' => '憲法記念日',
        '2025-05-04' => 'みどりの日',
        '2025-05-05' => 'こどもの日',
        '2025-05-06' => '振替休日',
        '2025-07-21' => '海の日',
        '2025-08-11' => '山の日',
        '2025-09-15' => '敬老の日',
        '2025-09-23' => '秋分の日',
        '2025-10-13' => 'スポーツの日',
        '2025-11-03' => '文化の日',
        '2025-11-23' => '勤労感謝の日',
        '2025-11-24' => '振替休日',
    ];
    
    $firstDay = $currentMonth->copy()->startOfMonth();
    $lastDay = $currentMonth->copy()->endOfMonth();
    $startWeek = $firstDay->copy()->startOfWeek(Carbon::SUNDAY);
    $endWeek = $lastDay->copy()->endOfWeek(Carbon::SATURDAY);
@endphp

<div class="calendar-filter-widget">
    <div class="calendar-header d-flex justify-content-between align-items-center mb-3">
        <button type="button" class="btn btn-sm btn-link p-0" onclick="changeMonth(-1)">
            <i class="fas fa-chevron-left"></i>
        </button>
        <h6 class="mb-0">{{ $currentMonth->format('Y年n月') }}</h6>
        <button type="button" class="btn btn-sm btn-link p-0" onclick="changeMonth(1)">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
    
    <table class="table table-sm calendar-filter-table">
        <thead>
            <tr class="small text-center">
                <th class="text-danger">日</th>
                <th>月</th>
                <th>火</th>
                <th>水</th>
                <th>木</th>
                <th>金</th>
                <th class="text-primary">土</th>
            </tr>
        </thead>
        <tbody>
            @php $currentDate = $startWeek->copy(); @endphp
            @while($currentDate <= $endWeek)
                <tr>
                    @for($d = 0; $d < 7; $d++)
                        @php
                            $dateStr = $currentDate->format('Y-m-d');
                            $isCurrentMonth = $currentDate->month === $currentMonth->month;
                            $isToday = $currentDate->isToday();
                            $isSelected = $selectedDate && $dateStr === $selectedDate;
                            $isWeekend = $currentDate->isWeekend();
                            $isHoliday = isset($holidays[$dateStr]);
                            $eventCount = $eventDates[$dateStr] ?? 0;
                            
                            $classes = ['calendar-day', 'text-center', 'position-relative'];
                            if (!$isCurrentMonth) $classes[] = 'other-month';
                            if ($isToday) $classes[] = 'today';
                            if ($isSelected) $classes[] = 'selected';
                            if ($eventCount > 0) $classes[] = 'has-events';
                            
                            $textClasses = [];
                            if ($isWeekend && !$isHoliday) {
                                $textClasses[] = $currentDate->dayOfWeek === 0 ? 'text-danger' : 'text-primary';
                            }
                            if ($isHoliday) $textClasses[] = 'text-danger fw-bold';
                        @endphp
                        
                        <td class="{{ implode(' ', $classes) }}"
                            @if($isCurrentMonth)
                                onclick="selectDate('{{ $dateStr }}')"
                                data-date="{{ $dateStr }}"
                                @if($isHoliday)
                                    title="{{ $holidays[$dateStr] }}"
                                @endif
                            @endif>
                            @if($isCurrentMonth)
                                <span class="{{ implode(' ', $textClasses) }}">
                                    {{ $currentDate->day }}
                                </span>
                                @if($eventCount > 0)
                                    <span class="event-dot" title="{{ $eventCount }}件のイベント">●</span>
                                @endif
                            @endif
                        </td>
                        @php $currentDate->addDay(); @endphp
                    @endfor
                </tr>
            @endwhile
        </tbody>
    </table>
    
    @if($selectedDate)
        <div class="selected-date-info small text-muted text-center mt-2">
            <i class="fas fa-calendar-check me-1"></i>
            {{ Carbon::parse($selectedDate)->format('n月j日') }}で絞り込み中
        </div>
    @endif
</div>

<style>
    .calendar-filter-widget {
        background: white;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .calendar-filter-table {
        width: 100%;
        table-layout: fixed;
    }
    
    .calendar-filter-table th {
        padding: 5px;
        font-weight: 600;
        border: none;
    }
    
    .calendar-day {
        padding: 8px;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all 0.2s;
        position: relative;
    }
    
    .calendar-day:hover:not(.other-month) {
        background: #f0f8ff;
        border-color: #dee2e6;
    }
    
    .calendar-day.other-month {
        color: #e0e0e0;
        cursor: default;
    }
    
    .calendar-day.today {
        background: #e3f2fd;
        font-weight: bold;
    }
    
    .calendar-day.selected {
        background: #c8e6c9;
        border-color: #4caf50;
    }
    
    .event-dot {
        position: absolute;
        bottom: 2px;
        left: 50%;
        transform: translateX(-50%);
        color: #ff9800;
        font-size: 8px;
        line-height: 1;
    }
</style>

<script>
function selectDate(date) {
    const url = new URL(window.location);
    url.searchParams.set('date', date);
    window.location.href = url.toString();
}

function changeMonth(direction) {
    const url = new URL(window.location);
    const currentMonth = '{{ $currentMonth->format("Y-m") }}';
    const [year, month] = currentMonth.split('-').map(Number);
    const newDate = new Date(year, month - 1 + direction, 1);
    const newMonth = `${newDate.getFullYear()}-${String(newDate.getMonth() + 1).padStart(2, '0')}`;
    url.searchParams.set('calendar_month', newMonth);
    window.location.href = url.toString();
}
</script>