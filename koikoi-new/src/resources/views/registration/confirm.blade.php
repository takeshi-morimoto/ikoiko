@extends('layouts.app')

@section('title', '申込内容確認 | KOIKOI')
@section('description', '申込内容の確認')
@section('body_class', 'theme-' . $theme)

@section('content')
<!-- 確認画面ヘッダー -->
<section class="registration-header {{ $theme }}-registration-header">
    <div class="registration-header-container">
        <h1 class="registration-title">申込内容確認</h1>
        <p>以下の内容で申込みます。よろしければ「申込を確定する」ボタンを押してください。</p>
    </div>
</section>

<!-- 確認内容 -->
<section class="confirmation-section">
    <div class="confirmation-container">
        <div class="confirmation-content">
            <!-- イベント情報 -->
            <div class="confirmation-block">
                <h3 class="confirmation-title">イベント情報</h3>
                <table class="confirmation-table">
                    <tr>
                        <th>イベント名</th>
                        <td>{{ $event->title }}</td>
                    </tr>
                    <tr>
                        <th>開催日時</th>
                        <td>
                            {{ $event->event_date->format('Y年n月j日') }}({{ $event->day_of_week }})
                            {{ $event->start_time->format('H:i') }}〜{{ $event->end_time->format('H:i') }}
                        </td>
                    </tr>
                    <tr>
                        <th>会場</th>
                        <td>
                            {{ $event->area->prefecture->name }} {{ $event->area->name }}
                            @if($event->venue_name)
                                <br>{{ $event->venue_name }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>参加費</th>
                        <td>
                            @if($data['gender'] === 'male')
                                @if($event->price_male_early && $event->early_deadline >= now())
                                    <strong>¥{{ number_format($event->price_male_early) }}</strong>（早割料金）
                                @else
                                    <strong>¥{{ number_format($event->price_male) }}</strong>
                                @endif
                            @else
                                @if($event->price_female_early && $event->early_deadline >= now())
                                    <strong>¥{{ number_format($event->price_female_early) }}</strong>（早割料金）
                                @else
                                    <strong>¥{{ number_format($event->price_female) }}</strong>
                                @endif
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- 申込者情報 -->
            <div class="confirmation-block">
                <h3 class="confirmation-title">申込者情報</h3>
                <table class="confirmation-table">
                    <tr>
                        <th>お名前</th>
                        <td>{{ $data['name'] }}</td>
                    </tr>
                    <tr>
                        <th>お名前（カナ）</th>
                        <td>{{ $data['name_kana'] }}</td>
                    </tr>
                    <tr>
                        <th>性別</th>
                        <td>{{ $data['gender'] === 'male' ? '男性' : '女性' }}</td>
                    </tr>
                    <tr>
                        <th>年齢</th>
                        <td>{{ $age }}歳</td>
                    </tr>
                    <tr>
                        <th>メールアドレス</th>
                        <td>{{ $data['email'] }}</td>
                    </tr>
                    <tr>
                        <th>電話番号</th>
                        <td>{{ $data['phone'] }}</td>
                    </tr>
                    <tr>
                        <th>住所</th>
                        <td>
                            〒{{ substr($data['postal_code'], 0, 3) }}-{{ substr($data['postal_code'], 3) }}<br>
                            {{ $data['prefecture'] }}{{ $data['city'] }}{{ $data['address'] }}
                        </td>
                    </tr>
                    @if($data['emergency_name'] && $data['emergency_contact'])
                    <tr>
                        <th>緊急連絡先</th>
                        <td>
                            {{ $data['emergency_name'] }}<br>
                            {{ $data['emergency_contact'] }}
                        </td>
                    </tr>
                    @endif
                    @if($data['comment'])
                    <tr>
                        <th>コメント</th>
                        <td>{{ $data['comment'] }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            
            <!-- 注意事項 -->
            <div class="confirmation-block">
                <h3 class="confirmation-title">ご確認事項</h3>
                <ul class="confirmation-notes">
                    <li>参加費は当日会場でお支払いください</li>
                    <li>キャンセルされる場合は、開催3日前までにご連絡ください</li>
                    <li>開始時間に遅れないようお願いします</li>
                    <li>申込完了メールが届かない場合は、迷惑メールフォルダをご確認ください</li>
                </ul>
            </div>
            
            <!-- アクションボタン -->
            <div class="confirmation-actions">
                <form method="POST" action="{{ route('entry.complete', $event) }}" class="confirmation-form">
                    @csrf
                    <button type="submit" class="btn btn-{{ $theme }} btn-lg">
                        申込を確定する
                    </button>
                </form>
                
                <form method="GET" action="{{ route('entry.show', $event) }}" class="back-form">
                    <input type="hidden" name="gender" value="{{ $data['gender'] }}">
                    @foreach($data as $key => $value)
                        @if($key !== 'terms')
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <button type="submit" class="btn btn-outline-secondary">
                        入力内容を修正する
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* 確認画面スタイル */
.confirmation-section {
    padding: 40px 0;
    background: #f8f9fa;
    min-height: calc(100vh - 200px);
}

.confirmation-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.confirmation-content {
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.confirmation-block {
    margin-bottom: 40px;
}

.confirmation-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
}

.confirmation-table {
    width: 100%;
    border-collapse: collapse;
}

.confirmation-table th,
.confirmation-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e9ecef;
}

.confirmation-table th {
    width: 30%;
    font-weight: 600;
    background: #f8f9fa;
}

.confirmation-table tr:last-child th,
.confirmation-table tr:last-child td {
    border-bottom: none;
}

.confirmation-notes {
    list-style: none;
    padding: 0;
}

.confirmation-notes li {
    padding: 8px 0;
    padding-left: 25px;
    position: relative;
}

.confirmation-notes li::before {
    content: "・";
    position: absolute;
    left: 10px;
}

.confirmation-actions {
    display: flex;
    gap: 15px;
    margin-top: 40px;
}

.confirmation-form,
.back-form {
    flex: 1;
}

.confirmation-actions .btn {
    width: 100%;
}

/* テーマ別カラー */
.theme-anime .confirmation-title {
    border-bottom-color: var(--color-anime-primary);
}

.theme-machi .confirmation-title {
    border-bottom-color: var(--color-machi-primary);
}

/* レスポンシブ */
@media (max-width: 768px) {
    .confirmation-content {
        padding: 20px;
    }
    
    .confirmation-table {
        font-size: 0.875rem;
    }
    
    .confirmation-table th,
    .confirmation-table td {
        padding: 8px;
    }
    
    .confirmation-table th {
        width: 40%;
    }
    
    .confirmation-actions {
        flex-direction: column;
    }
}
</style>
@endpush