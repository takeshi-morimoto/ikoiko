@props([
    'type' => 'info',
    'title' => '',
    'dismissible' => true,
    'icon' => ''
])

@php
    $alertClasses = [
        'success' => 'alert-success',
        'error' => 'alert-danger',
        'danger' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
        'primary' => 'alert-primary'
    ];
    
    $icons = [
        'success' => 'fas fa-check-circle',
        'error' => 'fas fa-exclamation-circle',
        'danger' => 'fas fa-exclamation-circle',
        'warning' => 'fas fa-exclamation-triangle',
        'info' => 'fas fa-info-circle',
        'primary' => 'fas fa-info-circle'
    ];
    
    $alertClass = $alertClasses[$type] ?? $alertClasses['info'];
    $defaultIcon = $icons[$type] ?? $icons['info'];
    $finalIcon = $icon ?: $defaultIcon;
@endphp

<div class="alert {{ $alertClass }}{{ $dismissible ? ' alert-dismissible fade show' : '' }}" role="alert">
    @if($finalIcon)
        <i class="{{ $finalIcon }} me-2"></i>
    @endif
    
    @if($title)
        <strong>{{ $title }}</strong><br>
    @endif
    
    {{ $slot }}
    
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>