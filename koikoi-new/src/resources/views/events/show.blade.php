@extends('layouts.app')

@section('title', $event->title . ' | KOIKOI')
@section('description', $event->sales_copy ?? $event->description)
@section('og_image', $event->og_image ?? asset('img/ogp-default.jpg'))
@section('body_class', 'theme-' . $event->eventType->slug)

@section('content')
<!-- イベント詳細ヘッダー -->
<section class="event-detail-header {{ $event->eventType->slug }}-header">
    <div class="container mx-auto px-4">
        <nav class="breadcrumb">
            <a href="/">ホーム</a> &gt;
            <a href="{{ route($event->eventType->slug . '.index') }}">{{ $event->eventType->name }}</a> &gt;
            <span>{{ $event->title }}</span>
        </nav>
        
        <h1 class="event-detail-title">{{ $event->title }}</h1>
        
        @if($event->sales_copy)
            <p class="event-detail-subtitle">{{ $event->sales_copy }}</p>
        @endif
        
        <div class="event-detail-meta">
            <span class="event-type-badge {{ $event->eventType->slug }}">{{ $event->eventType->name }}</span>
            @if($event->event_date < now())
                <span class="status-badge ended">終了</span>
            @elseif(!$event->is_accepting_male && !$event->is_accepting_female)
                <span class="status-badge closed">受付終了</span>
            @elseif($event->remaining_male_seats <= 5 || $event->remaining_female_seats <= 5)
                <span class="status-badge urgent">残りわずか</span>
            @endif
        </div>
    </div>
</section>

<!-- イベント情報 -->
<section class="event-detail-section">
    <div class="container mx-auto px-4">
        <div class="event-detail-grid">
            <!-- メインコンテンツ -->
            <div class="event-detail-main">
                <!-- 開催情報 -->
                <div class="detail-card">
                    <h2 class="detail-card-title">開催情報</h2>
                    <dl class="detail-list">
                        <dt>開催日時</dt>
                        <dd>
                            {{ $event->event_date->format('Y年n月j日') }}（{{ $event->day_of_week }}）
                            {{ $event->start_time?->format('H:i') }} - {{ $event->end_time?->format('H:i') }}
                        </dd>
                        
                        <dt>会場</dt>
                        <dd>
                            {{ $event->venue_name }}<br>
                            <span class="text-sm text-gray-600">{{ $event->venue_address }}</span>
                            @if($event->venue_url)
                                <br><a href="{{ $event->venue_url }}" target="_blank" class="text-blue-600 hover:underline">会場サイト</a>
                            @endif
                        </dd>
                        
                        <dt>アクセス</dt>
                        <dd>{{ $event->venue_access }}</dd>
                        
                        @if($event->meeting_point)
                            <dt>集合場所</dt>
                            <dd>{{ $event->meeting_point }}</dd>
                        @endif
                    </dl>
                </div>
                
                <!-- イベント詳細 -->
                @if($event->description)
                    <div class="detail-card">
                        <h2 class="detail-card-title">イベント詳細</h2>
                        <div class="prose">
                            {!! nl2br(e($event->description)) !!}
                        </div>
                    </div>
                @endif
                
                <!-- スケジュール -->
                @if($event->schedule)
                    <div class="detail-card">
                        <h2 class="detail-card-title">当日のスケジュール</h2>
                        <div class="prose">
                            {!! nl2br(e($event->schedule)) !!}
                        </div>
                    </div>
                @endif
                
                <!-- 注意事項 -->
                @if($event->notes)
                    <div class="detail-card">
                        <h2 class="detail-card-title">注意事項</h2>
                        <div class="prose">
                            {!! nl2br(e($event->notes)) !!}
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- サイドバー -->
            <div class="event-detail-sidebar">
                <!-- 申込情報 -->
                <div class="sidebar-card">
                    <h3 class="sidebar-card-title">参加費</h3>
                    <div class="price-info">
                        <div class="price-item">
                            <span class="gender">男性</span>
                            <span class="amount">¥{{ number_format($event->price_male) }}</span>
                            @if($event->price_male_early && $event->early_deadline >= now())
                                <span class="early-price">早割: ¥{{ number_format($event->price_male_early) }}</span>
                            @endif
                        </div>
                        <div class="price-item">
                            <span class="gender">女性</span>
                            <span class="amount">¥{{ number_format($event->price_female) }}</span>
                            @if($event->price_female_early && $event->early_deadline >= now())
                                <span class="early-price">早割: ¥{{ number_format($event->price_female_early) }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <h3 class="sidebar-card-title mt-6">参加条件</h3>
                    <div class="condition-info">
                        <p>男性: {{ $event->age_min_male }}歳〜{{ $event->age_max_male }}歳</p>
                        <p>女性: {{ $event->age_min_female }}歳〜{{ $event->age_max_female }}歳</p>
                    </div>
                    
                    <h3 class="sidebar-card-title mt-6">募集状況</h3>
                    <div class="capacity-info">
                        <div class="capacity-item">
                            <div class="capacity-header">
                                <span>男性</span>
                                <span>{{ $event->registered_male }}/{{ $event->capacity_male }}名</span>
                            </div>
                            <div class="capacity-bar">
                                <div class="capacity-progress" style="width: {{ ($event->registered_male / $event->capacity_male) * 100 }}%"></div>
                            </div>
                            @if($event->remaining_male_seats > 0)
                                <p class="remaining">残り{{ $event->remaining_male_seats }}名</p>
                            @else
                                <p class="full">満席</p>
                            @endif
                        </div>
                        
                        <div class="capacity-item">
                            <div class="capacity-header">
                                <span>女性</span>
                                <span>{{ $event->registered_female }}/{{ $event->capacity_female }}名</span>
                            </div>
                            <div class="capacity-bar">
                                <div class="capacity-progress female" style="width: {{ ($event->registered_female / $event->capacity_female) * 100 }}%"></div>
                            </div>
                            @if($event->remaining_female_seats > 0)
                                <p class="remaining">残り{{ $event->remaining_female_seats }}名</p>
                            @else
                                <p class="full">満席</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- 申込ボタン -->
                    @if($event->event_date >= now())
                        @if($event->is_accepting_male || $event->is_accepting_female)
                            <div class="apply-buttons">
                                @if($event->is_accepting_male && $event->remaining_male_seats > 0)
                                    <a href="{{ route('entry.show', ['event' => $event->id, 'gender' => 'male']) }}" 
                                       class="btn btn-primary btn-block">男性申込</a>
                                @endif
                                @if($event->is_accepting_female && $event->remaining_female_seats > 0)
                                    <a href="{{ route('entry.show', ['event' => $event->id, 'gender' => 'female']) }}" 
                                       class="btn btn-primary btn-block">女性申込</a>
                                @endif
                            </div>
                        @else
                            <p class="text-center text-gray-500 mt-4">現在受付を停止しています</p>
                        @endif
                    @else
                        <p class="text-center text-gray-500 mt-4">このイベントは終了しました</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 関連イベント -->
@if($relatedEvents->count() > 0)
<section class="related-events-section">
    <div class="section-container">
        <h2 class="section-title">関連イベント</h2>
        <x-event-grid :events="$relatedEvents" :theme="$event->eventType->slug" />
    </div>
</section>
@endif
@endsection