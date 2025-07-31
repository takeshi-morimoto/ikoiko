@props(['theme' => '', 'title', 'subtitle' => null])

<section class="page-header {{ $theme }}-header">
    <div class="page-header-container">
        <h1 class="page-title">{{ $title }}</h1>
        @if($subtitle)
            <p class="page-subtitle">{{ $subtitle }}</p>
        @endif
    </div>
</section>