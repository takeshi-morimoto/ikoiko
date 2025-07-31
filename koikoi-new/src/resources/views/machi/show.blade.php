@extends('layouts.app')

@section('title', $event->title . ' | 街コン | KOIKOI')
@section('description', $event->meta_description ?? $event->title . 'の詳細情報。' . $event->area->prefecture->name . $event->area->name . 'で' . $event->event_date->format('Y年n月j日') . '開催。美味しい料理とお酒を楽しみながら出会いを。')
@section('og_title', $event->meta_title ?? $event->title)
@section('og_description', $event->meta_description ?? $event->title . 'の詳細情報')
@section('og_image', $event->og_image ?? asset('img/machi-ogp.jpg'))
@section('og_type', 'event')
@section('body_class', 'theme-machi')

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
<section class="event-header machi-event-header">
    <div class="event-header-container">
        <div class="breadcrumb">
            <a href="/">ホーム</a>
            <span>/</span>
            <a href="{{ route('machi.index') }}">街コン</a>
            <span>/</span>
            <span>{{ $event->title }}</span>
        </div>
        
        <h1 class="event-main-title">{{ $event->title }}</h1>
        
        @if($event->pr_comment)
        <p class="event-pr-comment">{{ $event->pr_comment }}</p>
        @endif
        
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
            @if($event->venue_name)
            <div class="meta-item">
                <i class="icon-venue"></i>
                <span>{{ $event->venue_name }}</span>
            </div>
            @endif
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
            <div class="sales-copy machi-theme">
                <p>{{ $event->sales_copy }}</p>
            </div>
            @endif
            
            <!-- イベント説明 -->
            <section class="event-section">
                <h2 class="section-title">街コンの内容</h2>
                <div class="event-description">
                    @if($event->description)
                        {!! nl2br(e($event->description)) !!}
                    @else
                        <p>{{ $event->area->name }}で開催される街コンです。美味しい料理とお酒を楽しみながら、素敵な出会いを見つけてください。</p>
                        <p>初めての方でも安心して参加できるよう、スタッフがしっかりサポートいたします。</p>
                    @endif
                </div>
            </section>
            
            <!-- タイムスケジュール -->
            <section class="event-section">
                <h2 class="section-title">当日の流れ</h2>
                <div class="event-schedule">
                    @if($event->schedule)
                        {!! nl2br(e($event->schedule)) !!}
                    @else
                        <div class="default-schedule">
                            <div class="schedule-item">
                                <div class="schedule-time">{{ $event->start_time->format('H:i') }}</div>
                                <div class="schedule-content">
                                    <h4>受付開始</h4>
                                    <p>受付でプロフィールカードをお渡しします</p>
                                </div>
                            </div>
                            <div class="schedule-item">
                                <div class="schedule-time">{{ $event->start_time->copy()->addMinutes(15)->format('H:i') }}</div>
                                <div class="schedule-content">
                                    <h4>乾杯・スタート</h4>
                                    <p>まずは乾杯！リラックスして楽しみましょう</p>
                                </div>
                            </div>
                            <div class="schedule-item">
                                <div class="schedule-time">{{ $event->start_time->copy()->addMinutes(30)->format('H:i') }}</div>
                                <div class="schedule-content">
                                    <h4>フリータイム</h4>
                                    <p>自由に交流を楽しんでください</p>
                                </div>
                            </div>
                            <div class="schedule-item">
                                <div class="schedule-time">{{ $event->end_time->copy()->subMinutes(30)->format('H:i') }}</div>
                                <div class="schedule-content">
                                    <h4>席替えタイム</h4>
                                    <p>新しい出会いのチャンス！</p>
                                </div>
                            </div>
                            <div class="schedule-item">
                                <div class="schedule-time">{{ $event->end_time->format('H:i') }}</div>
                                <div class="schedule-content">
                                    <h4>終了</h4>
                                    <p>連絡先交換をお忘れなく！</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
            
            <!-- 会場情報 -->
            <section class="event-section">
                <h2 class="section-title">会場情報</h2>
                <div class="venue-info">
                    <h3>{{ $event->venue_name ?? $event->area->name . 'エリア' }}</h3>
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
            
            <!-- 参加者の声 -->
            <section class="event-section">
                <h2 class="section-title">参加者の声</h2>
                <div class="testimonials">
                    <div class="testimonial-item">
                        <div class="testimonial-header">
                            <span class="gender male">男性</span>
                            <span class="age">30代</span>
                        </div>
                        <p>初めて参加しましたが、スタッフの方のサポートもあり楽しく過ごせました。料理も美味しくて大満足です！</p>
                    </div>
                    <div class="testimonial-item">
                        <div class="testimonial-header">
                            <span class="gender female">女性</span>
                            <span class="age">20代</span>
                        </div>
                        <p>大人数なので色々な方とお話しできて良かったです。友達と一緒に参加したので安心でした。</p>
                    </div>
                </div>
            </section>
            
            <!-- 注意事項 -->
            <section class="event-section">
                <h2 class="section-title">注意事項</h2>
                <div class="event-notes">
                    @if($event->notes)
                        {!! nl2br(e($event->notes)) !!}
                    @else
                        <ul>
                            <li>開始時間に遅れないようお願いします</li>
                            <li>身分証明書をご持参ください</li>
                            <li>過度な飲酒はご遠慮ください</li>
                            <li>他の参加者への迷惑行為は禁止です</li>
                            <li>キャンセルは開催3日前までにご連絡ください</li>
                        </ul>
                    @endif
                </div>
            </section>
            
            <!-- 関連イベント -->
            @if($relatedEvents->count() > 0)
            <section class="event-section">
                <h2 class="section-title">{{ $event->area->name }}の他の街コン</h2>
                <div class="related-events">
                    @foreach($relatedEvents as $related)
                    <a href="{{ route('machi.show', $related->slug) }}" class="related-event-card">
                        <div class="related-date">
                            {{ $related->event_date->format('n/j') }}({{ $related->day_of_week }})
                        </div>
                        <div class="related-title">{{ $related->title }}</div>
                        <div class="related-info">
                            <span class="related-time">{{ $related->start_time->format('H:i') }}〜</span>
                            @if($related->venue_name)
                                <span class="related-venue">{{ Str::limit($related->venue_name, 20) }}</span>
                            @endif
                        </div>
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
            <div class="booking-card machi-theme">
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
                    <p class="price-note">※ 料金には飲み放題・料理代が含まれます</p>
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
                        <div class="condition-note">
                            ※ 独身の方限定
                        </div>
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
                            <a href="{{ route('entry.show', $event) }}?gender=male" class="btn btn-machi btn-block">
                                男性として申し込む
                            </a>
                        @endif
                        
                        @if($event->is_accepting_female && $event->remaining_female_seats > 0)
                            <a href="{{ route('entry.show', $event) }}?gender=female" class="btn btn-machi-outline btn-block">
                                女性として申し込む
                            </a>
                        @endif
                    </div>
                    
                    <div class="booking-note">
                        <p>※ お友達同士での参加も歓迎です</p>
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
/* 街コンイベント詳細専用スタイル */
.machi-event-header {
    background: linear-gradient(135deg, #4ECDC4 0%, #44A3AA 100%);
    color: white;
    padding: 40px 0;
}

.event-pr-comment {
    font-size: 1.125rem;
    margin: 15px 0;
    opacity: 0.95;
}

/* 販売コピー - 街コン版 */
.sales-copy.machi-theme {
    background: var(--color-machi-secondary);
    border-left: 4px solid var(--color-machi-primary);
}

/* セクションタイトル - 街コン版 */
.theme-machi .section-title {
    border-bottom-color: var(--color-machi-primary);
}

/* デフォルトスケジュール */
.default-schedule {
    position: relative;
    padding-left: 30px;
}

.schedule-item {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    position: relative;
}

.schedule-item::before {
    content: '';
    position: absolute;
    left: -22px;
    top: 8px;
    width: 12px;
    height: 12px;
    background: var(--color-machi-primary);
    border-radius: 50%;
}

.schedule-item::after {
    content: '';
    position: absolute;
    left: -16px;
    top: 20px;
    width: 1px;
    height: calc(100% + 10px);
    background: #e0e0e0;
}

.schedule-item:last-child::after {
    display: none;
}

.schedule-time {
    font-weight: 700;
    color: var(--color-machi-primary);
    min-width: 60px;
}

.schedule-content h4 {
    font-size: 1.125rem;
    margin-bottom: 5px;
}

/* 参加者の声 */
.testimonials {
    display: grid;
    gap: 20px;
}

.testimonial-item {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border-left: 3px solid var(--color-machi-primary);
}

.testimonial-header {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.testimonial-header .gender {
    padding: 2px 10px;
    border-radius: 15px;
    font-size: 0.875rem;
    font-weight: 500;
}

.testimonial-header .gender.male {
    background: #e3f2fd;
    color: #1976d2;
}

.testimonial-header .gender.female {
    background: #fce4ec;
    color: #c2185b;
}

.testimonial-header .age {
    color: #6c757d;
    font-size: 0.875rem;
}

/* 注意事項 */
.event-notes ul {
    list-style: none;
    padding-left: 0;
}

.event-notes li {
    padding: 8px 0;
    padding-left: 25px;
    position: relative;
}

.event-notes li::before {
    content: "・";
    position: absolute;
    left: 10px;
    color: var(--color-machi-primary);
}

/* 予約カード - 街コン版 */
.booking-card.machi-theme {
    border-color: var(--color-machi-primary);
}

.price-note {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 10px;
}

.condition-note {
    font-size: 0.875rem;
    color: #dc3545;
    margin-top: 10px;
}

.booking-note {
    text-align: center;
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 15px;
}

/* 関連イベント - 街コン版 */
.related-info {
    display: flex;
    gap: 15px;
    font-size: 0.875rem;
    color: #6c757d;
    margin: 5px 0;
}

.related-event-card:hover {
    border-color: var(--color-machi-primary);
}

.theme-machi .related-date {
    color: var(--color-machi-primary);
}

/* レスポンシブ */
@media (max-width: 768px) {
    .default-schedule {
        padding-left: 20px;
    }
    
    .schedule-item {
        flex-direction: column;
        gap: 10px;
    }
    
    .schedule-item::before {
        left: -12px;
    }
    
    .schedule-item::after {
        left: -6px;
    }
}
</style>
@endpush