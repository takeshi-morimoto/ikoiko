@extends('layouts.app')

@section('title', 'アニメコン一覧 | KOIKOI')
@section('description', 'アニメ・マンガ・ゲーム好きのための婚活イベント「アニメコン」の開催予定一覧。東京・横浜・大阪など全国で開催中。')
@section('body_class', 'theme-anime')

@section('content')
<!-- ヘッダービジュアル -->
<x-page-header 
    theme="anime"
    title="アニメコン開催予定"
    subtitle="アニメ・マンガ・ゲーム好きが集まる婚活イベント" />

<!-- フィルタセクション -->
<x-filter-section :action="route('anime.index')">
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
        <x-event-grid :events="$events" theme="anime" />
        
        <!-- ページネーション -->
        @if($events->hasPages())
            <div class="mt-8">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</section>

<!-- アニメコンの特徴 -->
<section class="features-section">
    <div class="section-container">
        <h2 class="section-title">アニメコンの特徴</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🎮</div>
                <h3 class="feature-title">共通の趣味で盛り上がる</h3>
                <p class="feature-text">アニメ・マンガ・ゲームなど共通の趣味があるから話題に困りません。</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">👥</div>
                <h3 class="feature-title">少人数制で話しやすい</h3>
                <p class="feature-text">男女各10〜15名の少人数制。全員と落ち着いて話せます。</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💝</div>
                <h3 class="feature-title">理解し合える相手と出会える</h3>
                <p class="feature-text">オタク趣味を隠す必要なし。理解し合えるパートナーを見つけられます。</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section anime-cta">
    <div class="cta-container">
        <h2>アニメ好きな恋人を見つけよう</h2>
        <p>共通の趣味を持つ素敵なパートナーとの出会いがここに</p>
        <a href="#" class="btn btn-white btn-large">イベントを探す</a>
    </div>
</section>
@endsection