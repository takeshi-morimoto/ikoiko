<?php

namespace App\Mail;

use App\Models\Customer;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Customer $customer,
        public Event $event
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "【KOIKOI】明日開催！{$this->event->title} のご案内",
            from: config('mail.from.address', 'noreply@koikoi.co.jp'),
            replyTo: 'support@koikoi.co.jp'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event-reminder',
            with: [
                'customer' => $this->customer,
                'event' => $this->event,
                'weather_info' => $this->getWeatherInfo(),
                'map_url' => $this->generateMapUrl(),
                'checkin_info' => $this->getCheckinInfo(),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
    
    /**
     * 天気情報の取得（サンプル）
     */
    private function getWeatherInfo(): array
    {
        // 実際の天気APIと連携する場合はここに実装
        return [
            'condition' => '晴れ',
            'temperature' => '25°C',
            'advice' => '過ごしやすい気候です。軽めの服装でお越しください。',
        ];
    }
    
    /**
     * 地図URLの生成
     */
    private function generateMapUrl(): string
    {
        $venue = $this->event->venue;
        return "https://maps.google.com/maps?q=" . urlencode($venue);
    }
    
    /**
     * チェックイン情報の取得
     */
    private function getCheckinInfo(): array
    {
        return [
            'start_time' => $this->event->checkin_start_time ?? '開始30分前',
            'location' => $this->event->checkin_location ?? '会場受付',
            'required_items' => [
                'スマートフォン（QRコード表示用）',
                '身分証明書',
                '参加費（現金またはカード）',
            ],
        ];
    }
}