@props(['items' => []])

<div class="timeline">
    @foreach($items as $item)
        <div class="timeline-item">
            <div class="timeline-marker bg-{{ $item['color'] ?? 'primary' }}"></div>
            <div class="timeline-content">
                <h6 class="mb-1">{{ $item['title'] }}</h6>
                <small class="text-muted">{{ $item['description'] }}</small>
                <div class="text-muted small">{{ $item['time'] }}</div>
            </div>
        </div>
    @endforeach
</div>