@extends('layouts.app')

@section('title', '「' . $keyword . '」の検索結果 | KOIKOI')
@section('description', '「' . $keyword . '」の検索結果。婚活イベント情報を検索できます。')

@section('content')
<!-- ヘッダー -->
<section class="page-header search-header">
    <div class="page-header-container">
        <h1 class="page-title">検索結果</h1>
        <p class="page-subtitle">「{{ $keyword }}」で検索</p>
    </div>
</section>

<!-- 検索バー -->
<section class="search-section">
    <div class="search-container">
        <form method="GET" action="{{ route('events.search') }}" class="search-form">
            <input type="text" name="q" placeholder="イベント名、エリア、会場名で検索" 
                   value="{{ $keyword }}" class="search-input">
            <button type="submit" class="search-button">
                <i class="icon-search"></i> 再検索
            </button>
        </form>
        <div class="search-links">
            <a href="{{ route('events.index') }}" class="back-link">
                <i class="icon-arrow-left"></i> イベント一覧に戻る
            </a>
        </div>
    </div>
</section>

<!-- 検索結果 -->
<section class="events-section">
    <div class="events-container">
        @if($events->count() > 0)
            <div class="search-summary">
                <p>{{ $events->total() }}件のイベントが見つかりました</p>
            </div>
            
            <div class="event-grid unified">
                @foreach($events as $event)
                <article class="event-card {{ $event->eventType->slug }}-theme">
                    <a href="{{ $event->eventType->slug == 'anime' ? route('anime.show', $event->slug) : route('machi.show', $event->slug) }}" 
                       class="event-card-link">
                        <div class="event-header">
                            <div class="event-type-badge {{ $event->eventType->slug }}">
                                {{ $event->eventType->name }}
                            </div>
                            
                            <div class="event-date-badge">
                                <span class="month">{{ $event->event_date->format('n') }}月</span>
                                <span class="day">{{ $event->event_date->format('j') }}</span>
                                <span class="weekday">{{ $event->day_of_week }}</span>
                            </div>
                            
                            @if($event->remaining_male_seats <= 5 || $event->remaining_female_seats <= 5)
                                <div class="event-badge urgent">残りわずか</div>
                            @elseif($event->event_date->diffInDays(now()) <= 7)
                                <div class="event-badge soon">まもなく開催</div>
                            @endif
                        </div>
                        
                        <div class="event-body">
                            <h2 class="event-title">
                                @php
                                    $title = $event->title;
                                    if (stripos($title, $keyword) !== false) {
                                        $title = preg_replace('/(' . preg_quote($keyword, '/') . ')/i', '<mark>$1</mark>', $title);
                                    }
                                @endphp
                                {!! $title !!}
                            </h2>
                            
                            <div class="event-meta">
                                <div class="meta-item">
                                    <i class="icon-location"></i>
                                    <span>
                                        @php
                                            $location = $event->area->prefecture->name . ' ' . $event->area->name;
                                            if (stripos($location, $keyword) !== false) {
                                                $location = preg_replace('/(' . preg_quote($keyword, '/') . ')/i', '<mark>$1</mark>', $location);
                                            }
                                        @endphp
                                        {!! $location !!}
                                    </span>
                                </div>
                                <div class="meta-item">
                                    <i class="icon-time"></i>
                                    <span>{{ $event->start_time->format('H:i') }}〜{{ $event->end_time->format('H:i') }}</span>
                                </div>
                                @if($event->venue_name)
                                <div class="meta-item">
                                    <i class="icon-venue"></i>
                                    <span>
                                        @php
                                            $venue = Str::limit($event->venue_name, 20);
                                            if (stripos($venue, $keyword) !== false) {
                                                $venue = preg_replace('/(' . preg_quote($keyword, '/') . ')/i', '<mark>$1</mark>', $venue);
                                            }
                                        @endphp
                                        {!! $venue !!}
                                    </span>
                                </div>
                                @endif
                            </div>
                            
                            @if($event->description && stripos($event->description, $keyword) !== false)
                            <div class="search-match-context">
                                @php
                                    $pos = stripos($event->description, $keyword);
                                    $start = max(0, $pos - 30);
                                    $length = 100;
                                    $context = mb_substr($event->description, $start, $length);
                                    if ($start > 0) $context = '...' . $context;
                                    if ($start + $length < mb_strlen($event->description)) $context .= '...';
                                    $context = preg_replace('/(' . preg_quote($keyword, '/') . ')/i', '<mark>$1</mark>', $context);
                                @endphp
                                {!! $context !!}
                            </div>
                            @endif
                            
                            <div class="event-capacity">
                                <div class="capacity-bar male">
                                    <div class="capacity-label">
                                        <span>男性</span>
                                        <span class="remaining">残り{{ $event->remaining_male_seats }}名</span>
                                    </div>
                                    <div class="capacity-progress">
                                        <div class="progress-bar" style="width: {{ ($event->registered_male / $event->capacity_male) * 100 }}%"></div>
                                    </div>
                                </div>
                                
                                <div class="capacity-bar female">
                                    <div class="capacity-label">
                                        <span>女性</span>
                                        <span class="remaining">残り{{ $event->remaining_female_seats }}名</span>
                                    </div>
                                    <div class="capacity-progress">
                                        <div class="progress-bar" style="width: {{ ($event->registered_female / $event->capacity_female) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="event-price">
                                <div class="price-item">
                                    <span class="gender">男性</span>
                                    <span class="amount">¥{{ number_format($event->price_male) }}</span>
                                </div>
                                <div class="price-item">
                                    <span class="gender">女性</span>
                                    <span class="amount">¥{{ number_format($event->price_female) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="event-footer">
                            <span class="btn btn-{{ $event->eventType->slug }}-outline">詳細を見る</span>
                        </div>
                    </a>
                </article>
                @endforeach
            </div>
            
            <!-- ページネーション -->
            <div class="pagination-wrapper">
                {{ $events->withQueryString()->links() }}
            </div>
        @else
            <div class="no-results">
                <i class="icon-search-empty"></i>
                <h2>「{{ $keyword }}」に一致するイベントが見つかりませんでした</h2>
                <p>別のキーワードで検索するか、条件を変更してお試しください。</p>
                
                <div class="search-suggestions">
                    <h3>検索のヒント</h3>
                    <ul>
                        <li>キーワードのスペルを確認してください</li>
                        <li>より一般的な言葉で検索してみてください</li>
                        <li>都道府県名やエリア名で検索してみてください</li>
                    </ul>
                </div>
                
                <div class="action-buttons">
                    <a href="{{ route('events.index') }}" class="btn btn-primary">すべてのイベントを見る</a>
                    <a href="{{ route('anime.index') }}" class="btn btn-anime-outline">アニメコン一覧</a>
                    <a href="{{ route('machi.index') }}" class="btn btn-machi-outline">街コン一覧</a>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- 人気の検索キーワード -->
<section class="popular-searches-section">
    <div class="popular-searches-container">
        <h2 class="section-title">人気の検索キーワード</h2>
        <div class="keyword-tags">
            <a href="{{ route('events.search', ['q' => '東京']) }}" class="keyword-tag">東京</a>
            <a href="{{ route('events.search', ['q' => '横浜']) }}" class="keyword-tag">横浜</a>
            <a href="{{ route('events.search', ['q' => '大阪']) }}" class="keyword-tag">大阪</a>
            <a href="{{ route('events.search', ['q' => '名古屋']) }}" class="keyword-tag">名古屋</a>
            <a href="{{ route('events.search', ['q' => '池袋']) }}" class="keyword-tag">池袋</a>
            <a href="{{ route('events.search', ['q' => '新宿']) }}" class="keyword-tag">新宿</a>
            <a href="{{ route('events.search', ['q' => '渋谷']) }}" class="keyword-tag">渋谷</a>
            <a href="{{ route('events.search', ['q' => 'アニメ']) }}" class="keyword-tag">アニメ</a>
            <a href="{{ route('events.search', ['q' => 'ゲーム']) }}" class="keyword-tag">ゲーム</a>
            <a href="{{ route('events.search', ['q' => '20代']) }}" class="keyword-tag">20代</a>
            <a href="{{ route('events.search', ['q' => '30代']) }}" class="keyword-tag">30代</a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* 検索結果ヘッダー */
.search-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 60px 0;
    text-align: center;
}

/* 検索サマリー */
.search-summary {
    margin-bottom: 20px;
    font-size: 1.125rem;
    color: #495057;
}

/* 検索ハイライト */
mark {
    background-color: #fff3cd;
    padding: 0 2px;
    border-radius: 2px;
}

/* 検索マッチコンテキスト */
.search-match-context {
    margin-top: 10px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 5px;
    font-size: 0.875rem;
    color: #6c757d;
    line-height: 1.5;
}

/* 検索結果なし */
.no-results {
    text-align: center;
    padding: 80px 20px;
}

.icon-search-empty {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 20px;
}

.no-results h2 {
    font-size: 1.5rem;
    margin-bottom: 15px;
    color: #495057;
}

.search-suggestions {
    margin: 40px auto;
    max-width: 500px;
    text-align: left;
    padding: 30px;
    background: #f8f9fa;
    border-radius: 8px;
}

.search-suggestions h3 {
    font-size: 1.125rem;
    margin-bottom: 15px;
}

.search-suggestions ul {
    list-style: none;
    padding: 0;
}

.search-suggestions li {
    padding: 8px 0;
    padding-left: 20px;
    position: relative;
}

.search-suggestions li::before {
    content: "•";
    position: absolute;
    left: 0;
    color: #667eea;
}

.action-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 30px;
}

/* 人気の検索キーワード */
.popular-searches-section {
    background: #f8f9fa;
    padding: 60px 0;
}

.popular-searches-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    text-align: center;
}

.keyword-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    margin-top: 30px;
}

.keyword-tag {
    display: inline-block;
    padding: 8px 20px;
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    text-decoration: none;
    color: #495057;
    transition: all 0.3s;
}

.keyword-tag:hover {
    background: #667eea;
    border-color: #667eea;
    color: white;
}

/* レスポンシブ */
@media (max-width: 768px) {
    .search-form {
        flex-direction: column;
    }
    
    .search-input {
        width: 100%;
    }
    
    .search-button {
        width: 100%;
    }
}
</style>
@endpush