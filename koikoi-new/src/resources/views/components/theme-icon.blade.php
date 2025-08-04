@props(['type', 'class' => ''])

@php
    $config = config('theme.colors');
    $iconClass = match($type) {
        'anime' => 'fas fa-star',
        'machi' => 'fas fa-city',
        default => 'fas fa-th'
    };
    $iconColor = match($type) {
        'anime' => $config['anime']['icon'] ?? '#0575E6',
        'machi' => $config['machi']['icon'] ?? '#FA709A',
        default => $config['common']['primary'] ?? '#FF6B6B'
    };
@endphp

<i class="{{ $iconClass }} {{ $class }}" style="color: {{ $iconColor }};"></i>