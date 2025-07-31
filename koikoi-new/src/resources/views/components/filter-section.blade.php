@props(['action', 'filters' => []])

<section class="filter-section">
    <div class="filter-container">
        <form method="GET" action="{{ $action }}" class="filter-form">
            {{ $slot }}
            
            @if(count(request()->except('page')) > 0)
                <a href="{{ $action }}" class="filter-reset">
                    フィルタをリセット
                </a>
            @endif
        </form>
    </div>
</section>