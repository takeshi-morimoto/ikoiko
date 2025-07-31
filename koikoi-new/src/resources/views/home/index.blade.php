@extends('layouts.app')

@section('title', 'KOIKOI - アニメコン・街コンで素敵な出会いを')
@section('description', '東京・横浜・大阪など全国で開催中のアニメコン・街コン。共通の趣味を持つ人との出会いから、地域密着型の出会いまで。')

@section('content')
<!-- ヒーローセクション -->
<section class="hero-section">
    <div class="hero-container">
        <h1 class="hero-title">恋と出会いのイベントサイト</h1>
        <p class="hero-subtitle">アニメコン・街コンで素敵な出会いを見つけよう</p>
        
        <div class="service-cards">
            <div class="service-card anime-card">
                <div class="card-icon">
                    <img src="{{ asset('img/anime-icon.svg') }}" alt="アニメコン" width="80" height="80">
                </div>
                <h2>アニメコン</h2>
                <p>アニメ・マンガ・ゲーム好きが集まる婚活イベント</p>
                <ul class="feature-list">
                    <li>共通の趣味で盛り上がれる</li>
                    <li>コスプレOKのイベントも</li>
                    <li>アニメトークで自然に交流</li>
                </ul>
                <a href="/anime/" class="btn btn-anime">アニメコンを見る</a>
            </div>
            
            <div class="service-card machi-card">
                <div class="card-icon">
                    <img src="{{ asset('img/machi-icon.svg') }}" alt="街コン" width="80" height="80">
                </div>
                <h2>街コン</h2>
                <p>地域密着型の大人数婚活イベント</p>
                <ul class="feature-list">
                    <li>様々な職業・年齢の人と出会える</li>
                    <li>美味しい料理とお酒を楽しみながら</li>
                    <li>カジュアルな雰囲気で参加しやすい</li>
                </ul>
                <a href="/machi/" class="btn btn-machi">街コンを見る</a>
            </div>
        </div>
    </div>
</section>

<!-- 統計情報 -->
<section class="stats-section">
    <div class="stats-container">
        <div class="stat-item">
            <div class="stat-number">{{ number_format($stats['total_events']) }}</div>
            <div class="stat-label">累計開催数</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ number_format($stats['total_participants']) }}</div>
            <div class="stat-label">累計参加者数</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ number_format($stats['upcoming_events']) }}</div>
            <div class="stat-label">開催予定</div>
        </div>
    </div>
</section>

<!-- アニメコン直近イベント -->
@if($animeEvents->count() > 0)
<section class="upcoming-events anime-events">
    <div class="section-container">
        <h2 class="section-title">
            <span class="title-icon">🎮</span>
            アニメコン開催予定
        </h2>
        
        <div class="event-grid">
            @foreach($animeEvents as $event)
            <article class="event-card anime-theme">
                <div class="event-date">
                    <span class="month">{{ $event->event_date->format('n') }}月</span>
                    <span class="day">{{ $event->event_date->format('j') }}</span>
                    <span class="weekday">{{ $event->day_of_week }}</span>
                </div>
                
                <div class="event-content">
                    <h3 class="event-title">
                        <a href="{{ route('anime.show', $event->slug) }}">
                            {{ $event->title }}
                        </a>
                    </h3>
                    
                    <div class="event-info">
                        <span class="location">📍 {{ $event->area->name }}</span>
                        <span class="time">🕐 {{ $event->start_time->format('H:i') }}〜</span>
                    </div>
                    
                    <div class="event-capacity">
                        <div class="capacity-item">
                            <span class="label">男性</span>
                            <span class="status">残り{{ $event->remaining_male_seats }}名</span>
                        </div>
                        <div class="capacity-item">
                            <span class="label">女性</span>
                            <span class="status">残り{{ $event->remaining_female_seats }}名</span>
                        </div>
                    </div>
                    
                    <div class="event-price">
                        男性 ¥{{ number_format($event->price_male) }} / 
                        女性 ¥{{ number_format($event->price_female) }}
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        
        <div class="section-footer">
            <a href="/anime/" class="btn btn-outline-anime">アニメコン一覧を見る</a>
        </div>
    </div>
</section>
@endif

<!-- 街コン直近イベント -->
@if($machiEvents->count() > 0)
<section class="upcoming-events machi-events">
    <div class="section-container">
        <h2 class="section-title">
            <span class="title-icon">🏙️</span>
            街コン開催予定
        </h2>
        
        <div class="event-grid">
            @foreach($machiEvents as $event)
            <article class="event-card machi-theme">
                <div class="event-date">
                    <span class="month">{{ $event->event_date->format('n') }}月</span>
                    <span class="day">{{ $event->event_date->format('j') }}</span>
                    <span class="weekday">{{ $event->day_of_week }}</span>
                </div>
                
                <div class="event-content">
                    <h3 class="event-title">
                        <a href="{{ route('machi.show', $event->slug) }}">
                            {{ $event->title }}
                        </a>
                    </h3>
                    
                    <div class="event-info">
                        <span class="location">📍 {{ $event->area->name }}</span>
                        <span class="time">🕐 {{ $event->start_time->format('H:i') }}〜</span>
                    </div>
                    
                    <div class="event-capacity">
                        <div class="capacity-item">
                            <span class="label">男性</span>
                            <span class="status">残り{{ $event->remaining_male_seats }}名</span>
                        </div>
                        <div class="capacity-item">
                            <span class="label">女性</span>
                            <span class="status">残り{{ $event->remaining_female_seats }}名</span>
                        </div>
                    </div>
                    
                    <div class="event-price">
                        男性 ¥{{ number_format($event->price_male) }} / 
                        女性 ¥{{ number_format($event->price_female) }}
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        
        <div class="section-footer">
            <a href="/machi/" class="btn btn-outline-machi">街コン一覧を見る</a>
        </div>
    </div>
</section>
@endif

<!-- CTA セクション -->
<section class="cta-section">
    <div class="cta-container">
        <h2>素敵な出会いを見つけよう</h2>
        <p>まずは無料会員登録から始めてみませんか？</p>
        <a href="/register" class="btn btn-primary btn-large">無料で会員登録</a>
    </div>
</section>
@endsection