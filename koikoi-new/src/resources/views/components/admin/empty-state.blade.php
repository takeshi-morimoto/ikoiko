@props([
    'title' => 'データがありません',
    'description' => '',
    'icon' => 'fas fa-inbox',
    'actionText' => '',
    'actionHref' => '',
    'size' => 'normal'
])

@php
    $iconSize = match($size) {
        'small' => 'fa-2x',
        'large' => 'fa-4x',
        default => 'fa-3x'
    };
    
    $paddingClass = match($size) {
        'small' => 'py-3',
        'large' => 'py-5',
        default => 'py-4'
    };
@endphp

<div class="text-center {{ $paddingClass }} text-muted">
    <i class="{{ $icon }} {{ $iconSize }} mb-3 opacity-50"></i>
    <h6 class="mb-2">{{ $title }}</h6>
    
    @if($description)
        <p class="mb-3">{{ $description }}</p>
    @endif
    
    @if($actionText && $actionHref)
        <a href="{{ $actionHref }}" class="btn btn-primary btn-sm">
            {{ $actionText }}
        </a>
    @endif
</div>