@props(['action' => route('events.index'), 'placeholder' => 'イベント名、エリア、会場名で検索'])

<form method="GET" action="{{ $action }}" class="search-form">
    <input type="text" name="q" placeholder="{{ $placeholder }}" 
           value="{{ request('q') }}" class="search-input">
    <button type="submit" class="search-button">
        <i class="icon-search"></i> 検索
    </button>
</form>