@extends('layouts.app')

@section('title', 'イベント一覧 | KOIKOI')
@section('description', 'アニメコン・街コンなど、全ての婚活イベントの開催予定一覧。東京・横浜・大阪など全国で開催中。')

@section('content')
<!-- ヘッダービジュアル -->
<x-page-header 
    theme="events"
    title="イベント一覧"
    subtitle="アニメコン・街コンなど様々な婚活イベントを開催中" />

<!-- フィルタセクション -->
<x-filter-section :action="route('home')">
    <x-filter-group 
        label="イベントタイプ"
        name="type"
        :options="$eventTypes->mapWithKeys(fn($type) => [$type->slug => $type->name . ' (' . $type->events_count . ')'])"
        :selected="request('type')" />
    
    <x-filter-group 
        label="都道府県"
        name="prefecture"
        :options="$prefectures->pluck('name', 'code_en')"
        :selected="request('prefecture')" />
    
    <x-filter-group 
        label="開催月"
        name="month"
        :options="collect(range(1, 12))->mapWithKeys(fn($i) => [$i => $i.'月'])"
        :selected="request('month')" />
    
    <x-filter-group 
        label="年齢"
        name="age"
        :options="collect(range(20, 45, 5))->mapWithKeys(fn($age) => [$age => $age.'代'])"
        :selected="request('age')" />
    
    <x-filter-group 
        label="価格上限"
        name="price_max"
        :options="[
            '3000' => '〜3,000円',
            '5000' => '〜5,000円',
            '7000' => '〜7,000円',
            '10000' => '〜10,000円'
        ]"
        :selected="request('price_max')" />
    
    <x-filter-group 
        label="並び順"
        name="sort"
        :options="[
            'date' => '開催日順',
            'price_asc' => '価格が安い順',
            'price_desc' => '価格が高い順',
            'capacity' => '残席が多い順'
        ]"
        :selected="request('sort', 'date')" />
</x-filter-section>

<!-- 検索バー -->
<section class="search-section">
    <div class="search-container">
        <x-search-form />
    </div>
</section>

<!-- イベント一覧 -->
<section class="events-section">
    <div class="section-container">
        <div class="section-header">
            <h2 class="section-title">
                検索結果
                <span class="result-count">{{ $events->total() }}件</span>
            </h2>
        </div>
        
        <x-event-grid :events="$events" />
        
        <!-- ページネーション -->
        @if($events->hasPages())
            <div class="mt-8">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="cta-container">
        <h2>理想の相手と出会えるイベントを見つけよう</h2>
        <p>様々なタイプのイベントから、あなたに合った出会いの場を</p>
        <div class="cta-buttons">
            <a href="{{ route('anime.index') }}" class="btn btn-anime btn-large">アニメコンを見る</a>
            <a href="{{ route('machi.index') }}" class="btn btn-machi btn-large">街コンを見る</a>
        </div>
    </div>
</section>
@endsection