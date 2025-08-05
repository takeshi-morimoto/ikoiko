<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>イベント開催中止のお知らせ</title>
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
            background: linear-gradient(135deg, #dc3545 0%, #ffc107 100%);
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
        .alert {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .refund-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .alternative-events {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .event-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        .event-item:last-child {
            border-bottom: none;
        }
        .contact-info {
            background: #e2e3e5;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 5px;
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
        <h1>🚫 開催中止のお知らせ</h1>
        <p>{{ $event->title }}</p>
    </div>
    
    <div class="content">
        <p>{{ $customer->name_sei }} {{ $customer->name_mei }} 様</p>
        
        <div class="alert">
            <h3>重要なお知らせ</h3>
            <p>お申込みいただいていた下記イベントが、やむを得ない事情により<strong>開催中止</strong>となりましたことを深くお詫び申し上げます。</p>
        </div>
        
        <h4>📅 中止イベント詳細</h4>
        <ul>
            <li><strong>イベント名：</strong>{{ $event->title }}</li>
            <li><strong>開催予定日：</strong>{{ \Carbon\Carbon::parse($event->event_date)->format('Y年m月d日（D）') }} {{ $event->event_time }}</li>
            <li><strong>会場：</strong>{{ $event->venue }}</li>
            <li><strong>申込番号：</strong>{{ $customer->registration_number }}</li>
        </ul>
        
        @if($reason)
        <h4>🔍 中止理由</h4>
        <p>{{ $reason }}</p>
        @endif
        
        <div class="refund-info">
            <h3>💰 返金について</h3>
            @if($refund_info['contact_required'])
            <p><strong>参加費 {{ number_format($refund_info['refund_amount']) }}円 を全額返金いたします。</strong></p>
            <ul>
                <li><strong>返金方法：</strong>{{ $refund_info['method'] }}</li>
                <li><strong>処理期間：</strong>{{ $refund_info['processing_days'] }}</li>
                <li><strong>返金予定：</strong>お支払いいただいた方法にて返金いたします</li>
            </ul>
            <p><small>※ 返金処理に関して別途詳細をご連絡させていただきます</small></p>
            @else
            <p>参加費のお支払いがまだお済みでないため、返金の手続きは不要です。</p>
            @endif
        </div>
        
        @if(!empty($alternative_events))
        <div class="alternative-events">
            <h3>🎯 代替イベントのご案内</h3>
            <p>同じタイプの他のイベントもございます。ぜひご検討ください。</p>
            
            @foreach($alternative_events as $alt_event)
            <div class="event-item">
                <h4>{{ $alt_event['title'] }}</h4>
                <p>📅 {{ \Carbon\Carbon::parse($alt_event['date'])->format('m月d日（D）') }}</p>
                <p>📍 {{ $alt_event['venue'] }}</p>
                <a href="{{ $alt_event['url'] }}" class="button">詳細を見る</a>
            </div>
            @endforeach
        </div>
        @endif
        
        <div class="contact-info">
            <h3>📞 お問い合わせ</h3>
            <p>ご不明な点やご質問がございましたら、お気軽にお問い合わせください。</p>
            <ul>
                <li><strong>メール：</strong>{{ $contact_info['email'] }}</li>
                <li><strong>電話：</strong>{{ $contact_info['phone'] }}（{{ $contact_info['hours'] }}）</li>
                <li><strong>回答時間：</strong>{{ $contact_info['response_time'] }}</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('home') }}" class="button">他のイベントを見る</a>
        </div>
        
        <p>この度は、楽しみにしていただいていたにも関わらず、開催中止となり誠に申し訳ございませんでした。</p>
        
        <p>今後ともKOIKOIをよろしくお願いいたします。</p>
        
        <p>KOIKOI運営チーム</p>
    </div>
    
    <div class="footer">
        <p>このメールは自動送信されています。</p>
        <p>© {{ date('Y') }} KOIKOI. All rights reserved.</p>
    </div>
</body>
</html>