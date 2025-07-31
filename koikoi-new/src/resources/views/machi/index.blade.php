@extends('layouts.app')

@section('title', '街コン一覧 | KOIKOI')
@section('description', '地域密着型の大人数婚活イベント「街コン」の開催予定一覧。東京・横浜・大阪など全国で開催中。美味しい料理とお酒を楽しみながら出会いを。')
@section('body_class', 'theme-machi')

@section('content')
<!-- ヘッダービジュアル -->
<x-page-header 
    theme="machi"
    title="街コン開催予定"
    subtitle="美味しい料理とお酒を楽しみながら素敵な出会いを" />

<!-- フィルタセクション -->
<x-filter-section :action="route('machi.index')">
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
</x-filter-section>

<!-- イベント一覧 -->
<section class="events-section">
    <div class="section-container">
        <x-event-grid :events="$events" theme="machi" />
        
        <!-- ページネーション -->
        @if($events->hasPages())
            <div class="mt-8">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</section>

<!-- 街コンの特徴 -->
<section class="features-section">
    <div class="section-container">
        <h2 class="section-title">街コンの特徴</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🍺</div>
                <h3 class="feature-title">お酒と料理を楽しみながら</h3>
                <p class="feature-text">美味しい料理とお酒を楽しみながら、自然な雰囲気で出会えます。</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">👫</div>
                <h3 class="feature-title">大人数で賑やか</h3>
                <p class="feature-text">男女各20〜30名の大人数制。たくさんの人と出会えるチャンスがあります。</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🌃</div>
                <h3 class="feature-title">地域密着型イベント</h3>
                <p class="feature-text">地元の人気店を会場に、地域に根ざした出会いの場を提供します。</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section machi-cta">
    <div class="cta-container">
        <h2>地元で素敵な出会いを見つけよう</h2>
        <p>美味しい料理とお酒、そして素敵な出会いがあなたを待っています</p>
        <a href="#" class="btn btn-white btn-large">イベントを探す</a>
    </div>
</section>
@endsection