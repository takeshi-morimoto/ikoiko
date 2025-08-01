@props([
    'title' => '',
    'icon' => '',
    'badge' => '',
    'badgeColor' => 'primary',
    'headerActions' => false,
    'noPadding' => false
])

<div {{ $attributes->merge(['class' => 'card']) }}>
    @if($title || $badge || $headerActions || $icon)
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            @if($icon)
                <i class="{{ $icon }} me-2 text-{{ $badgeColor }}"></i>
            @endif
            {{ $title }}
        </h5>
        
        <div class="d-flex align-items-center gap-2">
            @if($badge)
                <span class="badge bg-{{ $badgeColor }}">{{ $badge }}</span>
            @endif
            
            @if($headerActions)
                {{ $headerActions }}
            @endif
        </div>
    </div>
    @endif
    
    <div class="card-body{{ $noPadding ? ' p-0' : '' }}">
        {{ $slot }}
    </div>
</div>