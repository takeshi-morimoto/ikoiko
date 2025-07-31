@extends('layouts.app')

@section('title', $event->title . ' 申込フォーム | KOIKOI')
@section('description', $event->title . 'への参加申込フォーム')
@section('body_class', 'theme-' . $theme)

@section('content')
<!-- 申込フォームヘッダー -->
<section class="registration-header {{ $theme }}-registration-header">
    <div class="registration-header-container">
        <div class="breadcrumb">
            <a href="/">ホーム</a>
            <span>/</span>
            <a href="{{ route($theme . '.index') }}">{{ $event->eventType->name }}</a>
            <span>/</span>
            <a href="{{ route($theme . '.show', $event->slug) }}">{{ $event->title }}</a>
            <span>/</span>
            <span>申込フォーム</span>
        </div>
        
        <h1 class="registration-title">参加申込フォーム</h1>
        <div class="event-summary">
            <h2>{{ $event->title }}</h2>
            <div class="event-meta">
                <span><i class="icon-calendar"></i> {{ $event->event_date->format('Y年n月j日') }}({{ $event->day_of_week }})</span>
                <span><i class="icon-time"></i> {{ $event->start_time->format('H:i') }}〜{{ $event->end_time->format('H:i') }}</span>
                <span><i class="icon-location"></i> {{ $event->area->prefecture->name }} {{ $event->area->name }}</span>
            </div>
        </div>
    </div>
</section>

<!-- 申込フォーム本体 -->
<section class="registration-form-section">
    <div class="registration-form-container">
        <form method="POST" action="{{ route('entry.confirm', $event) }}" class="registration-form">
            @csrf
            <input type="hidden" name="gender" value="{{ $gender }}">
            
            <!-- エラー表示 -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- 基本情報 -->
            <div class="form-section">
                <h3 class="form-section-title">基本情報</h3>
                
                <div class="form-group">
                    <label for="name" class="required">お名前</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" placeholder="山田 太郎">
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="name_kana" class="required">お名前（カナ）</label>
                    <input type="text" class="form-control @error('name_kana') is-invalid @enderror" 
                           id="name_kana" name="name_kana" value="{{ old('name_kana') }}" placeholder="ヤマダ タロウ">
                    @error('name_kana')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="email" class="required">メールアドレス</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" placeholder="example@email.com">
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="phone" class="required">電話番号</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}" placeholder="090-1234-5678">
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="birth_date" class="required">生年月日</label>
                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                           id="birth_date" name="birth_date" value="{{ old('birth_date') }}" 
                           max="{{ now()->subYears($gender === 'male' ? $event->age_min_male : $event->age_min_female)->format('Y-m-d') }}"
                           min="{{ now()->subYears($gender === 'male' ? $event->age_max_male : $event->age_max_female)->format('Y-m-d') }}">
                    <small class="form-text text-muted">
                        ※ このイベントの参加条件：{{ $gender === 'male' ? $event->age_min_male : $event->age_min_female }}歳〜{{ $gender === 'male' ? $event->age_max_male : $event->age_max_female }}歳
                    </small>
                    @error('birth_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <!-- 住所情報 -->
            <div class="form-section">
                <h3 class="form-section-title">住所</h3>
                
                <div class="form-group">
                    <label for="postal_code" class="required">郵便番号（ハイフンなし）</label>
                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                           id="postal_code" name="postal_code" value="{{ old('postal_code') }}" 
                           placeholder="1234567" maxlength="7">
                    @error('postal_code')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="prefecture" class="required">都道府県</label>
                        <select class="form-control @error('prefecture') is-invalid @enderror" 
                                id="prefecture" name="prefecture">
                            <option value="">選択してください</option>
                            @foreach(['北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県','茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県','新潟県','富山県','石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県','山口県','徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県'] as $pref)
                                <option value="{{ $pref }}" {{ old('prefecture') == $pref ? 'selected' : '' }}>{{ $pref }}</option>
                            @endforeach
                        </select>
                        @error('prefecture')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group col-md-8">
                        <label for="city" class="required">市区町村</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" 
                               id="city" name="city" value="{{ old('city') }}" placeholder="新宿区">
                        @error('city')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="address" class="required">番地・建物名</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" 
                           id="address" name="address" value="{{ old('address') }}" 
                           placeholder="1-2-3 ○○マンション 101号室">
                    @error('address')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <!-- 緊急連絡先 -->
            <div class="form-section">
                <h3 class="form-section-title">緊急連絡先（任意）</h3>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="emergency_name">緊急連絡先のお名前</label>
                        <input type="text" class="form-control @error('emergency_name') is-invalid @enderror" 
                               id="emergency_name" name="emergency_name" value="{{ old('emergency_name') }}">
                        @error('emergency_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="emergency_contact">緊急連絡先の電話番号</label>
                        <input type="tel" class="form-control @error('emergency_contact') is-invalid @enderror" 
                               id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact') }}">
                        @error('emergency_contact')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- その他 -->
            <div class="form-section">
                <h3 class="form-section-title">その他</h3>
                
                <div class="form-group">
                    <label for="comment">コメント・要望（任意）</label>
                    <textarea class="form-control @error('comment') is-invalid @enderror" 
                              id="comment" name="comment" rows="3" maxlength="500">{{ old('comment') }}</textarea>
                    <small class="form-text text-muted">アレルギーや配慮が必要な事項がありましたらご記入ください</small>
                    @error('comment')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <!-- 料金確認 -->
            <div class="form-section price-confirmation">
                <h3 class="form-section-title">参加費</h3>
                <div class="price-display">
                    @if($gender === 'male')
                        @if($event->price_male_early && $event->early_deadline >= now())
                            <div class="price-item early">
                                <span class="label">早割料金</span>
                                <span class="amount">¥{{ number_format($event->price_male_early) }}</span>
                                <span class="deadline">（{{ $event->early_deadline->format('n月j日') }}まで）</span>
                            </div>
                            <div class="price-item regular">
                                <span class="label">通常料金</span>
                                <span class="amount">¥{{ number_format($event->price_male) }}</span>
                            </div>
                        @else
                            <div class="price-item">
                                <span class="label">参加費</span>
                                <span class="amount">¥{{ number_format($event->price_male) }}</span>
                            </div>
                        @endif
                    @else
                        @if($event->price_female_early && $event->early_deadline >= now())
                            <div class="price-item early">
                                <span class="label">早割料金</span>
                                <span class="amount">¥{{ number_format($event->price_female_early) }}</span>
                                <span class="deadline">（{{ $event->early_deadline->format('n月j日') }}まで）</span>
                            </div>
                            <div class="price-item regular">
                                <span class="label">通常料金</span>
                                <span class="amount">¥{{ number_format($event->price_female) }}</span>
                            </div>
                        @else
                            <div class="price-item">
                                <span class="label">参加費</span>
                                <span class="amount">¥{{ number_format($event->price_female) }}</span>
                            </div>
                        @endif
                    @endif
                </div>
                <p class="payment-note">※ 当日会場でお支払いください</p>
            </div>
            
            <!-- 利用規約同意 -->
            <div class="form-section">
                <div class="form-check">
                    <input class="form-check-input @error('terms') is-invalid @enderror" 
                           type="checkbox" id="terms" name="terms" value="1" {{ old('terms') ? 'checked' : '' }}>
                    <label class="form-check-label" for="terms">
                        <a href="/terms" target="_blank">利用規約</a>と<a href="/privacy" target="_blank">プライバシーポリシー</a>に同意します
                    </label>
                    @error('terms')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <!-- 送信ボタン -->
            <div class="form-actions">
                <button type="submit" class="btn btn-{{ $theme }} btn-lg btn-block">
                    確認画面へ進む
                </button>
                <a href="{{ route($theme . '.show', $event->slug) }}" class="btn btn-outline-secondary btn-block">
                    イベント詳細に戻る
                </a>
            </div>
        </form>
    </div>
</section>
@endsection

@push('styles')
<style>
/* 申込フォーム専用スタイル */
.registration-header {
    padding: 40px 0;
    color: white;
}

.anime-registration-header {
    background: linear-gradient(135deg, #FF6B6B 0%, #FF8E8E 100%);
}

.machi-registration-header {
    background: linear-gradient(135deg, #4ECDC4 0%, #44A3AA 100%);
}

.registration-header-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.registration-title {
    font-size: 2rem;
    margin: 20px 0;
}

.event-summary {
    background: rgba(255, 255, 255, 0.1);
    padding: 20px;
    border-radius: 8px;
    margin-top: 20px;
}

.event-summary h2 {
    font-size: 1.25rem;
    margin-bottom: 10px;
}

.event-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.event-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}

/* フォーム本体 */
.registration-form-section {
    padding: 40px 0;
    background: #f8f9fa;
    min-height: calc(100vh - 200px);
}

.registration-form-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.registration-form {
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.form-section {
    margin-bottom: 40px;
    padding-bottom: 40px;
    border-bottom: 1px solid #e9ecef;
}

.form-section:last-of-type {
    border-bottom: none;
    margin-bottom: 20px;
}

.form-section-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 20px;
    color: #333;
}

.required::after {
    content: " *";
    color: #dc3545;
}

.form-group {
    margin-bottom: 20px;
}

.form-control {
    font-size: 1rem;
    padding: 10px 15px;
    border: 1px solid #ced4da;
    border-radius: 5px;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 5px;
}

/* 料金確認 */
.price-confirmation {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 8px;
    margin: 30px -20px;
}

.price-display {
    margin: 20px 0;
}

.price-item {
    display: flex;
    align-items: baseline;
    gap: 10px;
    margin: 10px 0;
}

.price-item.early {
    color: #28a745;
    font-weight: 700;
}

.price-item .amount {
    font-size: 1.5rem;
    font-weight: 700;
}

.payment-note {
    color: #6c757d;
    font-size: 0.875rem;
    margin-top: 15px;
}

/* アクション */
.form-actions {
    margin-top: 40px;
}

.form-actions .btn {
    margin-bottom: 10px;
}

/* レスポンシブ */
@media (max-width: 768px) {
    .registration-form {
        padding: 20px;
    }
    
    .form-row {
        display: block;
    }
    
    .form-row .form-group {
        margin-bottom: 20px;
    }
    
    .event-meta {
        flex-direction: column;
        gap: 10px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// 郵便番号自動入力（簡易版）
document.getElementById('postal_code').addEventListener('blur', function() {
    const postalCode = this.value;
    if (postalCode.length === 7) {
        // 実装は省略（実際にはAPIを使用）
    }
});
</script>
@endpush