<?php

namespace App\Services;

use App\Mail\EventConfirmationMail;
use App\Mail\EventReminderMail;
use App\Mail\EventCancellationMail;
use App\Mail\PasswordResetMail;
use App\Models\Event;
use App\Models\Customer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

class NotificationService
{
    /**
     * イベント申込み確認メールを送信
     */
    public function sendEventConfirmation(Customer $customer, Event $event): bool
    {
        try {
            Mail::to($customer->email)
                ->send(new EventConfirmationMail($customer, $event));
            
            // 送信ログを記録
            Log::info('Event confirmation email sent', [
                'customer_id' => $customer->id,
                'event_id' => $event->id,
                'email' => $customer->email,
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send event confirmation email', [
                'customer_id' => $customer->id,
                'event_id' => $event->id,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }
    
    /**
     * イベントリマインダーメールを送信
     */
    public function sendEventReminder(Customer $customer, Event $event): bool
    {
        try {
            Mail::to($customer->email)
                ->send(new EventReminderMail($customer, $event));
            
            Log::info('Event reminder email sent', [
                'customer_id' => $customer->id,
                'event_id' => $event->id,
                'email' => $customer->email,
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send event reminder email', [
                'customer_id' => $customer->id,
                'event_id' => $event->id,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }
    
    /**
     * イベントキャンセル通知メールを送信
     */
    public function sendEventCancellation(Customer $customer, Event $event, string $reason = ''): bool
    {
        try {
            Mail::to($customer->email)
                ->send(new EventCancellationMail($customer, $event, $reason));
            
            Log::info('Event cancellation email sent', [
                'customer_id' => $customer->id,
                'event_id' => $event->id,
                'email' => $customer->email,
                'reason' => $reason,
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send event cancellation email', [
                'customer_id' => $customer->id,
                'event_id' => $event->id,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }
    
    /**
     * 一括メール送信（キューを使用）
     */
    public function sendBulkNotifications(array $customers, Event $event, string $type = 'reminder'): void
    {
        foreach ($customers as $customer) {
            switch ($type) {
                case 'confirmation':
                    Queue::push(function () use ($customer, $event) {
                        $this->sendEventConfirmation($customer, $event);
                    });
                    break;
                    
                case 'reminder':
                    Queue::push(function () use ($customer, $event) {
                        $this->sendEventReminder($customer, $event);
                    });
                    break;
                    
                case 'cancellation':
                    Queue::push(function () use ($customer, $event) {
                        $this->sendEventCancellation($customer, $event);
                    });
                    break;
            }
        }
        
        Log::info('Bulk email notifications queued', [
            'count' => count($customers),
            'event_id' => $event->id,
            'type' => $type,
        ]);
    }
    
    /**
     * 自動リマインダーの送信
     */
    public function sendAutomaticReminders(): int
    {
        $sent = 0;
        
        // 明日開催のイベントを取得
        $upcomingEvents = Event::where('event_date', '=', now()->addDay()->toDateString())
            ->where('status', 'active')
            ->with('customers')
            ->get();
        
        foreach ($upcomingEvents as $event) {
            foreach ($event->customers as $customer) {
                if ($customer->status === 'confirmed' && !$customer->reminder_sent) {
                    if ($this->sendEventReminder($customer, $event)) {
                        // リマインダー送信フラグを更新
                        $customer->update(['reminder_sent' => true]);
                        $sent++;
                    }
                }
            }
        }
        
        Log::info('Automatic reminders sent', ['count' => $sent]);
        
        return $sent;
    }
    
    /**
     * 管理者通知の送信
     */
    public function sendAdminNotification(string $subject, string $message, array $data = []): bool
    {
        try {
            $adminEmail = config('mail.admin_email', 'admin@koikoi.co.jp');
            
            Mail::raw($message, function ($mail) use ($subject, $adminEmail) {
                $mail->to($adminEmail)
                    ->subject($subject);
            });
            
            Log::info('Admin notification sent', [
                'subject' => $subject,
                'admin_email' => $adminEmail,
                'data' => $data,
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send admin notification', [
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }
    
    /**
     * メール送信レート制限チェック
     */
    public function canSendEmail(string $email): bool
    {
        $cacheKey = "email_rate_limit:{$email}";
        $sentCount = cache()->get($cacheKey, 0);
        $maxEmails = config('mail.rate_limit.max_emails', 10);
        $timeWindow = config('mail.rate_limit.time_window', 3600); // 1時間
        
        if ($sentCount >= $maxEmails) {
            Log::warning('Email rate limit exceeded', [
                'email' => $email,
                'sent_count' => $sentCount,
                'max_emails' => $maxEmails,
            ]);
            
            return false;
        }
        
        // 送信カウントを増加
        cache()->put($cacheKey, $sentCount + 1, $timeWindow);
        
        return true;
    }
    
    /**
     * メールテンプレートの検証
     */
    public function validateTemplate(string $template, array $variables = []): bool
    {
        try {
            $viewPath = "emails.{$template}";
            
            if (!view()->exists($viewPath)) {
                return false;
            }
            
            // テンプレートの描画テスト
            view($viewPath, $variables)->render();
            
            return true;
        } catch (\Exception $e) {
            Log::error('Email template validation failed', [
                'template' => $template,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }
    
    /**
     * メール送信統計の取得
     */
    public function getEmailStats(int $days = 30): array
    {
        // ここでは簡単な実装を示します
        // 実際の本番環境では、メール送信ログを別テーブルに保存することを推奨
        
        return [
            'total_sent' => 0, // 実際の送信数を取得
            'success_rate' => 0.0, // 成功率を計算
            'bounce_rate' => 0.0, // バウンス率を計算
            'open_rate' => 0.0, // 開封率を計算（可能であれば）
        ];
    }
}