@props([
    'title' => '',
    'icon' => 'fas fa-chart-line',
    'chartId' => 'chart',
    'height' => '300px',
    'headerActions' => false
])

<x-admin.card :title="$title" :icon="$icon">
    @if($headerActions)
        <x-slot name="headerActions">
            {{ $headerActions }}
        </x-slot>
    @endif
    
    <div style="height: {{ $height }}; position: relative;">
        <canvas id="{{ $chartId }}" height="100"></canvas>
    </div>
</x-admin.card>