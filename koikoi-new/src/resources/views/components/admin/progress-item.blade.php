@props([
    'title' => '',
    'subtitle' => '',
    'value' => 0,
    'max' => 100,
    'color' => 'primary'
])

@php
    $percentage = $max > 0 ? ($value / $max) * 100 : 0;
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h6 class="mb-1">{{ $title }}</h6>
        @if($subtitle)
            <small class="text-muted">{{ $subtitle }}</small>
        @endif
    </div>
    <div class="d-flex align-items-center">
        <div class="progress me-2" style="width: 60px; height: 8px;">
            <div class="progress-bar bg-{{ $color }}" style="width: {{ $percentage }}%"></div>
        </div>
        <small class="text-muted">{{ $value }}</small>
    </div>
</div>