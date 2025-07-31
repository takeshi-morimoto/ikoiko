@extends('layouts.app')

@section('title', '申込完了 | KOIKOI')
@section('description', 'イベントへの申込が完了しました')
@section('body_class', 'theme-' . $theme)

@section('content')
<!-- 完了画面 -->
<section class="thanks-section {{ $theme }}-thanks">
    <div class="thanks-container">
        <div class="thanks-content">
            <div class="thanks-icon">
                <i class="icon-check-circle"></i>
            </div>
            
            <h1 class="thanks-title">申込が完了しました</h1>
            
            <p class="thanks-message">
                {{ $customer->name }}様<br>
                この度は「{{ $event->title }}」にお申込みいただき、誠にありがとうございます。
            </p>
            
            <div class="registration-info">
                <h2>申込情報</h2>
                <div class="info-card">
                    <div class="info-item">
                        <span class="label">申込番号</span>
                        <span class="value">{{ $customer->registration_number }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">イベント名</span>
                        <span class="value">{{ $event->title }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">開催日時</span>
                        <span class="value">
                            {{ $event->event_date->format('Y年n月j日') }}({{ $event->day_of_week }})
                            {{ $event->start_time->format('H:i') }}〜{{ $event->end_time->format('H:i') }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="label">会場</span>
                        <span class="value">
                            {{ $event->area->prefecture->name }} {{ $event->area->name }}
                            @if($event->venue_name)
                                <br>{{ $event->venue_name }}
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="label">参加費</span>
                        <span class="value">
                            @if($customer->gender === 'male')
                                @if($event->price_male_early && $event->early_deadline >= now())
                                    ¥{{ number_format($event->price_male_early) }}（早割料金）
                                @else
                                    ¥{{ number_format($event->price_male) }}
                                @endif
                            @else
                                @if($event->price_female_early && $event->early_deadline >= now())
                                    ¥{{ number_format($event->price_female_early) }}（早割料金）
                                @else
                                    ¥{{ number_format($event->price_female) }}
                                @endif
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="next-steps">
                <h2>今後の流れ</h2>
                <ol class="steps-list">
                    <li>
                        <strong>確認メールをご確認ください</strong><br>
                        {{ $customer->email }}宛に申込確認メールをお送りしました。
                        届かない場合は迷惑メールフォルダをご確認ください。
                    </li>
                    <li>
                        <strong>当日の持ち物</strong><br>
                        身分証明書（運転免許証、保険証など）をご持参ください。
                    </li>
                    <li>
                        <strong>参加費のお支払い</strong><br>
                        参加費は当日、会場受付でお支払いください。
                    </li>
                    <li>
                        <strong>キャンセルについて</strong><br>
                        キャンセルされる場合は、開催3日前までにメールでご連絡ください。
                    </li>
                </ol>
            </div>
            
            <div class="important-notes">
                <h3>重要なお知らせ</h3>
                <ul>
                    <li>開始時間の10分前までに会場にお越しください</li>
                    <li>過度な飲酒はご遠慮ください</li>
                    <li>他の参加者への迷惑行為は禁止です</li>
                    <li>写真撮影がある場合があります（掲載許可は別途確認します）</li>
                </ul>
            </div>
            
            <div class="thanks-actions">
                <a href="{{ route($theme . '.index') }}" class="btn btn-{{ $theme }}">
                    他のイベントを見る
                </a>
                <a href="/" class="btn btn-outline-secondary">
                    トップページへ
                </a>
            </div>
            
            <div class="contact-info">
                <p>ご不明な点がございましたら、お気軽にお問い合わせください。</p>
                <p>
                    <strong>KOIKOI運営事務局</strong><br>
                    メール: info@koikoi.co.jp<br>
                    電話: 03-1234-5678（平日10:00〜18:00）
                </p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* 完了画面スタイル */
.thanks-section {
    padding: 60px 0;
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
}

.anime-thanks {
    background: linear-gradient(135deg, #FFE5E5 0%, #FFE9E9 100%);
}

.machi-thanks {
    background: linear-gradient(135deg, #E5F8F7 0%, #E9FAF9 100%);
}

.thanks-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.thanks-content {
    background: white;
    padding: 60px;
    border-radius: 12px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.1);
    text-align: center;
}

.thanks-icon {
    font-size: 4rem;
    margin-bottom: 20px;
}

.theme-anime .thanks-icon {
    color: var(--color-anime-primary);
}

.theme-machi .thanks-icon {
    color: var(--color-machi-primary);
}

.thanks-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.thanks-message {
    font-size: 1.125rem;
    line-height: 1.8;
    margin-bottom: 40px;
}

/* 申込情報 */
.registration-info {
    margin: 40px 0;
    text-align: left;
}

.registration-info h2 {
    font-size: 1.25rem;
    margin-bottom: 20px;
    text-align: center;
}

.info-card {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 8px;
}

.info-item {
    display: flex;
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item .label {
    width: 120px;
    font-weight: 600;
    color: #6c757d;
}

.info-item .value {
    flex: 1;
}

/* 今後の流れ */
.next-steps {
    margin: 40px 0;
    text-align: left;
}

.next-steps h2 {
    font-size: 1.25rem;
    margin-bottom: 20px;
    text-align: center;
}

.steps-list {
    counter-reset: step-counter;
    list-style: none;
    padding: 0;
}

.steps-list li {
    position: relative;
    padding: 20px 0 20px 50px;
    counter-increment: step-counter;
}

.steps-list li::before {
    content: counter(step-counter);
    position: absolute;
    left: 0;
    top: 20px;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: white;
}

.theme-anime .steps-list li::before {
    background: var(--color-anime-primary);
}

.theme-machi .steps-list li::before {
    background: var(--color-machi-primary);
}

/* 重要事項 */
.important-notes {
    background: #fff3cd;
    padding: 20px;
    border-radius: 8px;
    margin: 40px 0;
    text-align: left;
}

.important-notes h3 {
    font-size: 1.125rem;
    margin-bottom: 15px;
    color: #856404;
}

.important-notes ul {
    list-style: none;
    padding: 0;
}

.important-notes li {
    padding: 5px 0;
    padding-left: 20px;
    position: relative;
    color: #856404;
}

.important-notes li::before {
    content: "！";
    position: absolute;
    left: 0;
    font-weight: 700;
}

/* アクション */
.thanks-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin: 40px 0;
}

/* 連絡先 */
.contact-info {
    margin-top: 40px;
    padding-top: 40px;
    border-top: 1px solid #e9ecef;
    font-size: 0.875rem;
    color: #6c757d;
}

/* レスポンシブ */
@media (max-width: 768px) {
    .thanks-content {
        padding: 40px 20px;
    }
    
    .thanks-icon {
        font-size: 3rem;
    }
    
    .thanks-title {
        font-size: 1.5rem;
    }
    
    .info-item {
        flex-direction: column;
    }
    
    .info-item .label {
        width: 100%;
        margin-bottom: 5px;
    }
    
    .thanks-actions {
        flex-direction: column;
    }
    
    .thanks-actions .btn {
        width: 100%;
    }
}
</style>
@endpush