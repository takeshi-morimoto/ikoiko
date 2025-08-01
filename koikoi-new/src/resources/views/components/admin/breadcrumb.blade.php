@props(['items' => []])

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach($items as $item)
            @if($loop->last)
                <li class="breadcrumb-item active">
                    @if(isset($item['icon']))
                        <i class="{{ $item['icon'] }} me-1"></i>
                    @endif
                    {{ $item['title'] }}
                </li>
            @else
                <li class="breadcrumb-item">
                    @if(isset($item['href']))
                        <a href="{{ $item['href'] }}">
                            @if(isset($item['icon']))
                                <i class="{{ $item['icon'] }} me-1"></i>
                            @endif
                            {{ $item['title'] }}
                        </a>
                    @else
                        @if(isset($item['icon']))
                            <i class="{{ $item['icon'] }} me-1"></i>
                        @endif
                        {{ $item['title'] }}
                    @endif
                </li>
            @endif
        @endforeach
    </ol>
</nav>