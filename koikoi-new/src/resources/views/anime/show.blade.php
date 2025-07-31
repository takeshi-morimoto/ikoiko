@extends('layouts.app')

@section('title', $event->title . ' | アニメコン | KOIKOI')
@section('description', $event->meta_description ?? $event->title . 'の詳細情報。' . $event->area->prefecture->name . $event->area->name . 'で' . $event->event_date->format('Y年n月j日') . '開催。')
@section('og_title', $event->meta_title ?? $event->title)
@section('og_description', $event->meta_description ?? $event->title . 'の詳細情報')
@section('og_image', $event->og_image ?? asset('img/anime-ogp.jpg'))
@section('og_type', 'event')
@section('body_class', 'theme-anime')

@section('content')
<!-- 構造化データ -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Event",
  "name": "{{ $event->title }}",
  "startDate": "{{ $event->event_date->format('Y-m-d') }}T{{ $event->start_time->format('H:i:s') }}+09:00",
  "endDate": "{{ $event->event_date->format('Y-m-d') }}T{{ $event->end_time->format('H:i:s') }}+09:00",
  "eventStatus": "https://schema.org/EventScheduled",
  "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
  "location": {
    "@type": "Place",
    "name": "{{ $event->venue_name ?? $event->area->name }}",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "{{ $event->area->name }}",
      "addressRegion": "{{ $event->area->prefecture->name }}",
      "addressCountry": "JP"
    }
  },
  "organizer": {
    "@type": "Organization",
    "name": "KOIKOI",
    "url": "{{ url('/') }}"
  },
  "offers": [{
    "@type": "Offer",
    "name": "男性チケット",
    "price": "{{ $event->price_male }}",
    "priceCurrency": "JPY",
    "availability": "{{ $event->remaining_male_seats > 0 ? 'https://schema.org/InStock' : 'https://schema.org/SoldOut' }}"
  },{
    "@type": "Offer",
    "name": "女性チケット",
    "price": "{{ $event->price_female }}",
    "priceCurrency": "JPY",
    "availability": "{{ $event->remaining_female_seats > 0 ? 'https://schema.org/InStock' : 'https://schema.org/SoldOut' }}"
  }]
}
</script>

<!-- イベントヘッダー -->
<section class="event-header anime-event-header">
    <div class="event-header-container">
        <div class="breadcrumb">
            <a href="/">ホーム</a>
            <span>/</span>
            <a href="{{ route('anime.index') }}">アニメコン</a>
            <span>/</span>
            <span>{{ $event->title }}</span>
        </div>
        
        <h1 class="event-main-title">{{ $event->title }}</h1>
        
        <div class="event-header-meta">
            <div class="meta-item featured">
                <i class="icon-calendar"></i>
                <span>{{ $event->event_date->format('Y年n月j日') }}({{ $event->day_of_week }})</span>
            </div>
            <div class="meta-item">
                <i class="icon-time"></i>
                <span>{{ $event->start_time->format('H:i') }}〜{{ $event->end_time->format('H:i') }}</span>
            </div>
            <div class="meta-item">
                <i class="icon-location"></i>
                <span>{{ $event->area->prefecture->name }} {{ $event->area->name }}</span>
            </div>
        </div>
    </div>
</section>

<!-- メインコンテンツ -->
<div class="event-detail-container">
    <div class="event-detail-content">
        <!-- 左カラム：イベント詳細 -->
        <div class="event-main-column">
            <!-- 販売コピー -->
            @if($event->sales_copy)
            <div class="sales-copy anime-theme">
                <p>{{ $event->sales_copy }}</p>
            </div>
            @endif
            
            <!-- イベント説明 -->
            <section class="event-section">
                <h2 class="section-title">イベント内容</h2>
                <div class="event-description">
                    {!! nl2br(e($event->description)) !!}
                </div>
            </section>
            
            <!-- タイムスケジュール -->
            @if($event->schedule)
            <section class="event-section">
                <h2 class="section-title">タイムスケジュール</h2>
                <div class="event-schedule">
                    {!! nl2br(e($event->schedule)) !!}
                </div>
            </section>
            @endif
            
            <!-- 会場情報 -->
            <section class="event-section">
                <h2 class="section-title">会場情報</h2>
                <div class="venue-info">
                    <h3>{{ $event->venue_name }}</h3>
                    @if($event->venue_address)
                        <p class="venue-address">{{ $event->venue_address }}</p>
                    @endif
                    
                    @if($event->venue_access)
                        <div class="venue-access">
                            <h4>アクセス</h4>
                            {!! nl2br(e($event->venue_access)) !!}
                        </div>
                    @endif
                    
                    @if($event->meeting_point)
                        <div class="meeting-point">
                            <h4>集合場所</h4>
                            {!! nl2br(e($event->meeting_point)) !!}
                        </div>
                    @endif
                    
                    @if($event->venue_url)
                        <a href="{{ $event->venue_url }}" target="_blank" rel="noopener noreferrer" class="venue-link">
                            会場の詳細を見る <i class="icon-external"></i>
                        </a>
                    @endif
                </div>
            </section>
            
            <!-- 注意事項 -->
            @if($event->notes)
            <section class="event-section">
                <h2 class="section-title">注意事項</h2>
                <div class="event-notes">
                    {!! nl2br(e($event->notes)) !!}
                </div>
            </section>
            @endif
            
            <!-- 関連イベント -->
            @if($relatedEvents->count() > 0)
            <section class="event-section">
                <h2 class="section-title">{{ $event->area->name }}の他のアニメコン</h2>
                <div class="related-events">
                    @foreach($relatedEvents as $related)
                    <a href="{{ route('anime.show', $related->slug) }}" class="related-event-card">
                        <div class="related-date">
                            {{ $related->event_date->format('n/j') }}({{ $related->day_of_week }})
                        </div>
                        <div class="related-title">{{ $related->title }}</div>
                        <div class="related-capacity">
                            男性 残り{{ $related->remaining_male_seats }}名 / 
                            女性 残り{{ $related->remaining_female_seats }}名
                        </div>
                    </a>
                    @endforeach
                </div>
            </section>
            @endif
        </div>
        
        <!-- 右カラム：申込情報 -->
        <div class="event-sidebar">
            <div class="booking-card anime-theme">
                <h3 class="booking-title">参加申込</h3>
                
                <!-- 開催状況 -->
                @if($event->event_date < now())
                    <div class="event-status finished">
                        このイベントは終了しました
                    </div>
                @elseif(!$event->is_accepting_male && !$event->is_accepting_female)
                    <div class="event-status closed">
                        受付終了
                    </div>
                @endif
                
                <!-- 料金 -->
                <div class="price-section">
                    <h4>参加費</h4>
                    <div class="price-list">
                        <div class="price-row">
                            <span class="gender">男性</span>
                            <span class="price">¥{{ number_format($event->price_male) }}</span>
                        </div>
                        @if($event->price_male_early && $event->early_deadline >= now())
                            <div class="price-row early">
                                <span class="gender">男性早割</span>
                                <span class="price">¥{{ number_format($event->price_male_early) }}</span>
                                <span class="deadline">{{ $event->early_deadline->format('n/j') }}まで</span>
                            </div>
                        @endif
                        
                        <div class="price-row">
                            <span class="gender">女性</span>
                            <span class="price">¥{{ number_format($event->price_female) }}</span>
                        </div>
                        @if($event->price_female_early && $event->early_deadline >= now())
                            <div class="price-row early">
                                <span class="gender">女性早割</span>
                                <span class="price">¥{{ number_format($event->price_female_early) }}</span>
                                <span class="deadline">{{ $event->early_deadline->format('n/j') }}まで</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- 参加条件 -->
                <div class="conditions-section">
                    <h4>参加条件</h4>
                    <div class="condition-list">
                        @if($event->age_min_male || $event->age_max_male)
                            <div class="condition-item">
                                <span class="label">男性</span>
                                <span class="value">
                                    {{ $event->age_min_male }}歳〜{{ $event->age_max_male }}歳
                                </span>
                            </div>
                        @endif
                        @if($event->age_min_female || $event->age_max_female)
                            <div class="condition-item">
                                <span class="label">女性</span>
                                <span class="value">
                                    {{ $event->age_min_female }}歳〜{{ $event->age_max_female }}歳
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- 残席状況 -->
                <div class="capacity-section">
                    <h4>残席状況</h4>
                    <div class="capacity-status">
                        <div class="capacity-item {{ $event->remaining_male_seats <= 5 ? 'limited' : '' }}">
                            <span class="label">男性</span>
                            <div class="seats">
                                <span class="remaining">残り{{ $event->remaining_male_seats }}名</span>
                                <span class="total">/ {{ $event->capacity_male }}名</span>
                            </div>
                            <div class="capacity-bar">
                                <div class="bar-fill" style="width: {{ ($event->registered_male / $event->capacity_male) * 100 }}%"></div>
                            </div>
                        </div>
                        
                        <div class="capacity-item {{ $event->remaining_female_seats <= 5 ? 'limited' : '' }}">
                            <span class="label">女性</span>
                            <div class="seats">
                                <span class="remaining">残り{{ $event->remaining_female_seats }}名</span>
                                <span class="total">/ {{ $event->capacity_female }}名</span>
                            </div>
                            <div class="capacity-bar">
                                <div class="bar-fill female" style="width: {{ ($event->registered_female / $event->capacity_female) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- 申込ボタン -->
                @if($event->event_date >= now() && ($event->is_accepting_male || $event->is_accepting_female))
                    <div class="booking-actions">
                        @if($event->is_accepting_male && $event->remaining_male_seats > 0)
                            <a href="{{ route('entry.show', $event) }}?gender=male" class="btn btn-anime btn-block">
                                男性として申し込む
                            </a>
                        @endif
                        
                        @if($event->is_accepting_female && $event->remaining_female_seats > 0)
                            <a href="{{ route('entry.show', $event) }}?gender=female" class="btn btn-anime-outline btn-block">
                                女性として申し込む
                            </a>
                        @endif
                    </div>
                @endif
                
                <!-- お問い合わせ -->
                <div class="contact-info">
                    <p>このイベントについてのお問い合わせ</p>
                    <a href="/contact?event={{ $event->slug }}" class="contact-link">
                        お問い合わせフォーム
                    </a>
                </div>
            </div>
            
            <!-- シェアボタン -->
            <div class="share-section">
                <h4>このイベントをシェア</h4>
                <div class="share-buttons">
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($event->title) }}&url={{ urlencode(request()->url()) }}" 
                       target="_blank" rel="noopener noreferrer" class="share-btn twitter">
                        <i class="icon-twitter"></i> Twitter
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                       target="_blank" rel="noopener noreferrer" class="share-btn facebook">
                        <i class="icon-facebook"></i> Facebook
                    </a>
                    <a href="https://line.me/R/msg/text/?{{ urlencode($event->title . "\n" . request()->url()) }}" 
                       target="_blank" rel="noopener noreferrer" class="share-btn line">
                        <i class="icon-line"></i> LINE
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* アニメコンイベント詳細専用スタイル */
.anime-event-header {
    background: linear-gradient(135deg, #FF6B6B 0%, #FF8E8E 100%);
    color: white;
    padding: 40px 0;
}

.event-header-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.breadcrumb {
    font-size: 0.875rem;
    margin-bottom: 20px;
    opacity: 0.9;
}

.breadcrumb a {
    color: white;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.event-main-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.event-header-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    font-size: 1.125rem;
}

.event-header-meta .meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.event-header-meta .meta-item.featured {
    font-weight: 700;
    font-size: 1.25rem;
}

/* メインコンテンツレイアウト */
.event-detail-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.event-detail-content {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 40px;
}

/* 販売コピー */
.sales-copy {
    background: var(--color-anime-secondary);
    border-left: 4px solid var(--color-anime-primary);
    padding: 20px;
    margin-bottom: 30px;
    font-size: 1.125rem;
    font-weight: 500;
}

/* セクション */
.event-section {
    margin-bottom: 40px;
}

.section-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--color-anime-primary);
}

.event-description,
.event-schedule,
.event-notes {
    line-height: 1.8;
}

/* 会場情報 */
.venue-info h3 {
    font-size: 1.25rem;
    margin-bottom: 10px;
}

.venue-address {
    color: #6c757d;
    margin-bottom: 20px;
}

.venue-access h4,
.meeting-point h4 {
    font-size: 1.125rem;
    margin: 20px 0 10px;
}

.venue-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: var(--color-anime-primary);
    text-decoration: none;
    margin-top: 15px;
}

/* 予約カード */
.booking-card {
    background: white;
    border: 2px solid var(--color-anime-primary);
    border-radius: 12px;
    padding: 30px;
    position: sticky;
    top: 90px;
}

.booking-title {
    font-size: 1.5rem;
    margin-bottom: 20px;
    text-align: center;
}

.event-status {
    text-align: center;
    padding: 15px;
    border-radius: 8px;
    font-weight: 700;
    margin-bottom: 20px;
}

.event-status.finished {
    background: #e9ecef;
    color: #6c757d;
}

.event-status.closed {
    background: #dc3545;
    color: white;
}

/* 料金セクション */
.price-section h4,
.conditions-section h4,
.capacity-section h4 {
    font-size: 1.125rem;
    margin-bottom: 15px;
}

.price-list {
    margin-bottom: 25px;
}

.price-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    padding: 8px 0;
}

.price-row.early {
    color: #28a745;
    font-size: 0.875rem;
}

.price-row .price {
    font-weight: 700;
    font-size: 1.125rem;
}

/* 参加条件 */
.condition-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 25px;
}

.condition-item {
    display: flex;
    justify-content: space-between;
}

/* 残席状況 */
.capacity-status {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 25px;
}

.capacity-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.capacity-item.limited .remaining {
    color: #dc3545;
    font-weight: 700;
}

.capacity-item .seats {
    display: flex;
    justify-content: space-between;
    font-size: 0.875rem;
}

.capacity-bar {
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.bar-fill {
    height: 100%;
    background: var(--color-anime-primary);
    transition: width 0.3s;
}

.bar-fill.female {
    background: #FF8E8E;
}

/* 申込ボタン */
.booking-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}

.btn-block {
    width: 100%;
    text-align: center;
}

/* 関連イベント */
.related-events {
    display: grid;
    gap: 15px;
}

.related-event-card {
    display: block;
    padding: 15px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s;
}

.related-event-card:hover {
    border-color: var(--color-anime-primary);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.related-date {
    color: var(--color-anime-primary);
    font-weight: 700;
    margin-bottom: 5px;
}

.related-title {
    font-weight: 500;
    margin-bottom: 5px;
}

.related-capacity {
    font-size: 0.875rem;
    color: #6c757d;
}

/* シェアセクション */
.share-section {
    margin-top: 30px;
    padding-top: 30px;
    border-top: 1px solid #e9ecef;
}

.share-buttons {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.share-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 8px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 0.875rem;
    transition: all 0.3s;
}

.share-btn.twitter {
    background: #1DA1F2;
    color: white;
}

.share-btn.facebook {
    background: #4267B2;
    color: white;
}

.share-btn.line {
    background: #00B900;
    color: white;
}

/* レスポンシブ */
@media (max-width: 968px) {
    .event-detail-content {
        grid-template-columns: 1fr;
    }
    
    .booking-card {
        position: static;
    }
    
    .event-main-title {
        font-size: 2rem;
    }
}
</style>
@endpush