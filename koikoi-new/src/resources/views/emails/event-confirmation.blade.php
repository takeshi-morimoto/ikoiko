<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>イベント申込み確認</title>
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
            background: linear-gradient(135deg, #0575E6 0%, #21D4FD 100%);
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
        .event-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .value {
            color: #333;
        }
        .qr-section {
            text-align: center;
            background: #f0f8ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .important-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background: #0575E6;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 5px;
        }
        .button-cancel {
            background: #dc3545;
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
        <h1>お申込みありがとうございます！</h1>
        <p>{{ $event->title }}</p>
    </div>
    
    <div class="content">
        <p>{{ $customer->name_sei }} {{ $customer->name_mei }} 様</p>
        
        <p>この度は KOIKOI のイベントにお申込みいただき、誠にありがとうございます。<br>
        以下の内容でお申込みを承りました。</p>
        
        <div class="event-details">
            <h3>📅 イベント詳細</h3>
            <div class="detail-row">
                <span class="label">イベント名</span>
                <span class="value">{{ $event->title }}</span>
            </div>
            <div class="detail-row">
                <span class="label">開催日時</span>
                <span class="value">{{ \Carbon\Carbon::parse($event->event_date)->format('Y年m月d日（D）') }} {{ $event->event_time }}</span>
            </div>
            <div class="detail-row">
                <span class="label">会場</span>
                <span class="value">{{ $event->venue }}</span>
            </div>
            <div class="detail-row">
                <span class="label">参加費</span>
                <span class="value">{{ number_format($event->price ?? 0) }}円</span>
            </div>
            <div class="detail-row">
                <span class="label">申込番号</span>
                <span class="value">{{ $customer->registration_number }}</span>
            </div>
        </div>
        
        <div class="qr-section">
            <h3>🎫 受付用QRコード</h3>
            <p>当日の受付で以下のQRコードをご提示ください</p>
            <img src="{{ $qr_code_url }}" alt="QRコード" style="max-width: 200px; height: auto;">
            <p><small>※ スマートフォンでこのメールを表示してQRコードをご提示ください</small></p>
        </div>
        
        <div class="important-note">
            <h4>⚠️ 重要なお知らせ</h4>
            <ul>
                <li>当日は開始時刻の30分前から受付を開始いたします</li>
                <li>身分証明書をお持ちください</li>
                <li>キャンセルは開催日の3日前まで可能です</li>
                <li>悪天候等による開催可否は前日の夜にメールでお知らせいたします</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('event.show', $event->slug) }}" class="button">イベント詳細を見る</a>
            <a href="{{ $cancellation_url }}" class="button button-cancel">キャンセルする</a>
        </div>
        
        <h4>📞 お問い合わせ</h4>
        <p>ご不明な点がございましたら、お気軽にお問い合わせください。</p>
        <ul>
            <li>メール: support@koikoi.co.jp</li>
            <li>電話: 03-1234-5678（平日 10:00-18:00）</li>
        </ul>
        
        <p>当日お会いできることを楽しみにお待ちしております！</p>
        
        <p>KOIKOI運営チーム</p>
    </div>
    
    <div class="footer">
        <p>このメールは自動送信されています。返信はできませんのでご了承ください。</p>
        <p>© {{ date('Y') }} KOIKOI. All rights reserved.</p>
    </div>
</body>
</html>