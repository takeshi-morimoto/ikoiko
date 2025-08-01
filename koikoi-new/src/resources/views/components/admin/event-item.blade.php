@props([
    'event',
    'showDate' => false,
    'compact' => false
])

@php
    $statusColors = [
        'published' => 'success',
        'draft' => 'secondary',
        'cancelled' => 'danger',
        'postponed' => 'warning',
        'completed' => 'info'
    ];
    
    $statusColor = $statusColors[$event->status ?? 'draft'] ?? 'secondary';
@endphp

<div class="d-flex align-items-center {{ $compact ? 'p-2' : 'p-3' }} border-bottom">
    <div class="me-3">
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
             style="width: {{ $compact ? '32px' : '40px' }}; height: {{ $compact ? '32px' : '40px' }};">
            <i class="fas fa-calendar-check {{ $compact ? 'fa-sm' : '' }}"></i>
        </div>
    </div>
    
    <div class="flex-grow-1">
        <h6 class="mb-1 {{ $compact ? 'fs-6' : '' }}">{{ $event->title }}</h6>
        <small class="text-muted">
            @if($showDate && $event->event_date)
                <i class="fas fa-calendar me-1"></i>
                {{ $event->event_date->format('m/d') }}
                <span class="ms-2"></span>
            @endif
            
            @if($event->start_time)
                <i class="fas fa-clock me-1"></i>
                {{ $event->start_time }}
                @if($event->end_time)
                    - {{ $event->end_time }}
                @endif
            @endif
            
            @if($event->area)
                <span class="ms-2">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    {{ $event->area->name }}
                </span>
            @endif
        </small>
    </div>
    
    <div>
        <span class="badge bg-{{ $statusColor }}">
            {{ $event->status_label ?? $event->status }}
        </span>
    </div>
</div>