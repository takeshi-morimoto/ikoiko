<?php

namespace App\Mail;

use App\Models\Customer;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventCancellationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Customer $customer,
        public Event $event,
        public string $reason = ''
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "【KOIKOI】重要：{$this->event->title} 開催中止のお知らせ",
            from: config('mail.from.address', 'noreply@koikoi.co.jp'),
            replyTo: 'support@koikoi.co.jp'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event-cancellation',
            with: [
                'customer' => $this->customer,
                'event' => $this->event,
                'reason' => $this->reason,
                'refund_info' => $this->getRefundInfo(),
                'alternative_events' => $this->getAlternativeEvents(),
                'contact_info' => $this->getContactInfo(),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
    
    /**
     * 返金情報の取得
     */
    private function getRefundInfo(): array
    {
        return [
            'method' => $this->customer->payment_method ?? 'クレジットカード',
            'processing_days' => '5-7営業日',
            'contact_required' => $this->customer->payment_status === 'paid',
            'refund_amount' => $this->event->price ?? 0,
        ];
    }
    
    /**
     * 代替イベントの取得
     */
    private function getAlternativeEvents(): array
    {
        // 同じタイプの他のイベントを提案
        return Event::where('event_type', $this->event->event_type)
            ->where('status', 'active')
            ->where('event_date', '>', now())
            ->where('id', '!=', $this->event->id)
            ->limit(3)
            ->get()
            ->map(function ($event) {
                return [
                    'title' => $event->title,
                    'date' => $event->event_date,
                    'venue' => $event->venue,
                    'url' => route('event.show', $event->slug),
                ];
            })
            ->toArray();
    }
    
    /**
     * 連絡先情報の取得
     */
    private function getContactInfo(): array
    {
        return [
            'email' => 'support@koikoi.co.jp',
            'phone' => '03-1234-5678',
            'hours' => '平日 10:00-18:00',
            'response_time' => '24時間以内',
        ];
    }
}