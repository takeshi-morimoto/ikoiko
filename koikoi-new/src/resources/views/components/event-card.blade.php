@props(['event', 'theme' => null])

<div class="event-card">
    <div class="event-date @if($theme) {{ $theme }}-theme @endif">
        <div class="text-3xl font-bold">{{ $event->event_date->format('d') }}</div>
        <div class="text-sm">{{ $event->event_date->format('M') }}</div>
        <div class="text-xs">{{ $event->day_of_week }}</div>
    </div>
    
    <div class="event-content">
        <h3 class="event-title">
            <a href="{{ route('event.show', ['eventType' => $event->eventType->slug, 'slug' => $event->slug]) }}">
                {{ $event->title }}
            </a>
        </h3>
        
        <div class="event-info">
            <span>📍 {{ $event->area->prefecture->name }} {{ $event->area->name }}</span>
            <span>🏢 {{ $event->venue_name }}</span>
            <span>⏰ {{ $event->start_time?->format('H:i') }} - {{ $event->end_time?->format('H:i') }}</span>
        </div>
        
        <div class="event-capacity">
            <div class="capacity-item">
                <span class="text-blue-600">男性</span>
                {{ $event->registered_male }}/{{ $event->capacity_male }}名
                @if($event->remaining_male_seats <= 5)
                    <span class="text-red-600 text-xs">残りわずか</span>
                @endif
            </div>
            <div class="capacity-item">
                <span class="text-pink-600">女性</span>
                {{ $event->registered_female }}/{{ $event->capacity_female }}名
                @if($event->remaining_female_seats <= 5)
                    <span class="text-red-600 text-xs">残りわずか</span>
                @endif
            </div>
        </div>
        
        <div class="event-price">
            男性: ¥{{ number_format($event->price_male) }} / 
            女性: ¥{{ number_format($event->price_female) }}
        </div>
    </div>
</div>