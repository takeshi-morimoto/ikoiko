<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Event;
use Illuminate\Support\Facades\Mail;

class CustomerService
{
    /**
     * 顧客の申込履歴を取得
     */
    public function getCustomerHistory(string $email)
    {
        return Customer::with(['event.area.prefecture', 'event.eventType'])
            ->where('email', $email)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * 申込確認メールを送信
     */
    public function sendConfirmationEmail(Customer $customer): void
    {
        $event = $customer->event;
        
        // メール送信処理（後で実装）
        // Mail::to($customer->email)->send(new EventRegistrationConfirmation($customer));
    }

    /**
     * リマインダーメールを送信
     */
    public function sendReminderEmail(Customer $customer): void
    {
        $event = $customer->event;
        
        // イベント3日前にリマインダー送信
        if ($event->event_date->diffInDays(now()) === 3) {
            // Mail::to($customer->email)->send(new EventReminder($customer));
        }
    }

    /**
     * 支払いステータスを更新
     */
    public function updatePaymentStatus(Customer $customer, string $status): void
    {
        $customer->update([
            'payment_status' => $status,
            'payment_date' => $status === 'paid' ? now() : null,
        ]);
    }

    /**
     * キャンセル処理
     */
    public function cancelRegistration(Customer $customer): void
    {
        $event = $customer->event;
        
        // ステータス更新
        $customer->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
        
        // 参加人数を減らす
        $event->decrement($customer->gender === 'male' ? 'registered_male' : 'registered_female');
    }
}