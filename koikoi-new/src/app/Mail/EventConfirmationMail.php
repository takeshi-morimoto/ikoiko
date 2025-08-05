<?php

namespace App\Mail;

use App\Models\Customer;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Customer $customer,
        public Event $event
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "【KOIKOI】{$this->event->title} お申込み確認",
            from: config('mail.from.address', 'noreply@koikoi.co.jp'),
            replyTo: 'support@koikoi.co.jp'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.event-confirmation',
            with: [
                'customer' => $this->customer,
                'event' => $this->event,
                'qr_code_url' => $this->generateQrCodeUrl(),
                'cancellation_url' => $this->generateCancellationUrl(),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
    
    /**
     * QRコードURLの生成
     */
    private function generateQrCodeUrl(): string
    {
        $data = [
            'customer_id' => $this->customer->id,
            'event_id' => $this->event->id,
            'registration_number' => $this->customer->registration_number,
        ];
        
        return "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . 
               urlencode(json_encode($data));
    }
    
    /**
     * キャンセルURLの生成
     */
    private function generateCancellationUrl(): string
    {
        return route('entry.cancel', [
            'token' => encrypt([
                'customer_id' => $this->customer->id,
                'event_id' => $this->event->id,
            ])
        ]);
    }
}