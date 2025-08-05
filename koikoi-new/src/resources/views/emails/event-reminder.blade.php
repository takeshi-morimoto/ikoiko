<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>イベント開催リマインダー</title>
    <style>
        body {
            font-family: 'Noto Sans JP', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #FA709A 0%, #FEE140 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #fff;
            padding: 30px 20px;
            border: 1px solid #e0e0e0;
        }
        .reminder-badge {
            background: #ff6b6b;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 20px;
        }
        .event-card {
            background: #f8f9fa;
            border-left: 4px solid #FA709A;
            padding: 20px;
            margin: 20px 0;
        }
        .info-section {
            background: #e8f4fd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .checklist {
            background: #f0f8ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .weather-info {
            background: #fffbf0;
            border: 1px solid #ffd93d;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background: #FA709A;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 5px;
        }
        .map-button {
            background: #28a745;
        }
        .footer {
            text-align: center;
            color: #777;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>⏰ 明日開催です！</h1>
        <p>{{ $event->title }}</p>
    </div>
    
    <div class="content">
        <div class="reminder-badge">明日開催</div>
        
        <p>{{ $customer->name_sei }} {{ $customer->name_mei }} 様</p>
        
        <p>いよいよ明日、お申込みいただいたイベントが開催されます！<br>
        最終確認をさせていただきますので、ご確認ください。</p>
        
        <div class="event-card">
            <h3>📅 イベント情報</h3>
            <p><strong>{{ $event->title }}</strong></p>
            <p>📍 {{ $event->venue }}</p>
            <p>🕐 {{ \Carbon\Carbon::parse($event->event_date)->format('m月d日（D）') }} {{ $event->event_time }}</p>
            <p>💰 {{ number_format($event->price ?? 0) }}円</p>
        </div>
        
        @if($weather_info)
        <div class="weather-info">
            <h4>🌤️ 明日の天気</h4>
            <p><strong>{{ $weather_info['condition'] }}</strong> / {{ $weather_info['temperature'] }}</p>
            <p>{{ $weather_info['advice'] }}</p>
        </div>
        @endif
        
        <div class="info-section">
            <h3>🚪 受付・チェックイン情報</h3>
            <ul>
                <li><strong>受付開始：</strong>{{ $checkin_info['start_time'] }}</li>
                <li><strong>受付場所：</strong>{{ $checkin_info['location'] }}</li>
                <li><strong>遅刻の場合：</strong>必ず事前にご連絡ください</li>
            </ul>
        </div>
        
        <div class="checklist">
            <h3>✅ 当日の持ち物チェックリスト</h3>
            <ul>
                @foreach($checkin_info['required_items'] as $item)
                <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('event.show', $event->slug) }}" class="button">イベント詳細</a>
            <a href="{{ $map_url }}" class="button map-button" target="_blank">会場地図</a>
        </div>
        
        <div class="info-section">
            <h4>🔄 キャンセルについて</h4>
            <p>体調不良等でキャンセルされる場合は、できるだけ早めにご連絡をお願いいたします。</p>
            <p>📞 緊急連絡先: 03-1234-5678</p>
        </div>
        
        <div class="info-section">
            <h4>📱 SNSでシェア</h4>
            <p>イベントの様子は #KOIKOI #{{ str_replace(' ', '', $event->title) }} でシェアしてください！</p>
        </div>
        
        <p>素敵な出会いがありますように。明日お会いできることを楽しみにしております！</p>
        
        <p>KOIKOI運営チーム</p>
    </div>
    
    <div class="footer">
        <p>このメールは自動送信されています。</p>
        <p>© {{ date('Y') }} KOIKOI. All rights reserved.</p>
    </div>
</body>
</html>