@props([
    'title' => '',
    'icon' => 'fas fa-plus',
    'color' => 'primary',
    'href' => '#',
    'onclick' => ''
])

<div class="col-md-3 mb-3">
    @if($onclick)
        <button class="btn btn-outline-{{ $color }} w-100 h-100 d-flex flex-column justify-content-center"
                onclick="{{ $onclick }}">
            <i class="{{ $icon }} fa-2x mb-2"></i>
            <span>{{ $title }}</span>
        </button>
    @else
        <a href="{{ $href }}" class="btn btn-outline-{{ $color }} w-100 h-100 d-flex flex-column justify-content-center">
            <i class="{{ $icon }} fa-2x mb-2"></i>
            <span>{{ $title }}</span>
        </a>
    @endif
</div>