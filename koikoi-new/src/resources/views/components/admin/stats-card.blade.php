@props([
    'title' => '',
    'value' => 0,
    'icon' => 'fas fa-chart-line',
    'color' => 'primary',
    'formatter' => 'number',
    'suffix' => '',
    'prefix' => ''
])

@php
    $colorClasses = [
        'primary' => 'linear-gradient(135deg, var(--accent-color), #5dade2)',
        'success' => 'linear-gradient(135deg, #27ae60, #58d68d)',
        'warning' => 'linear-gradient(135deg, #f39c12, #f7dc6f)',
        'danger' => 'linear-gradient(135deg, #e74c3c, #f1948a)',
        'info' => 'linear-gradient(135deg, #17a2b8, #7dd3fc)',
        'purple' => 'linear-gradient(135deg, #8e44ad, #bb8fce)',
    ];
    
    $background = $colorClasses[$color] ?? $colorClasses['primary'];
    
    // フォーマット処理
    $formattedValue = match($formatter) {
        'currency' => '¥' . number_format($value),
        'number' => number_format($value),
        'percentage' => $value . '%',
        default => $value
    };
    
    $displayValue = $prefix . $formattedValue . $suffix;
@endphp

<div class="col-lg-3 col-md-6 mb-4">
    <div class="card text-white stats-card" style="background: {{ $background }};">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h3 class="mb-0">{{ $displayValue }}</h3>
                    <p class="mb-0">{{ $title }}</p>
                </div>
                <div class="align-self-center">
                    <i class="{{ $icon }} fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>