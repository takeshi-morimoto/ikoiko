<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendReminders extends Command
{
    /**
     * コマンドのシグネチャ
     */
    protected $signature = 'reminders:send 
                            {--dry-run : 実際には送信せず、対象を表示のみ}
                            {--event= : 特定のイベントIDのみ対象}';

    /**
     * コマンドの説明
     */
    protected $description = '自動リマインダーメールを送信';

    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * コマンドの実行
     */
    public function handle()
    {
        $this->info('リマインダーメール送信を開始します...');
        
        if ($this->option('dry-run')) {
            $this->warn('DRY RUN モード: 実際には送信されません');
            $this->showTargets();
        } else {
            $sent = $this->notificationService->sendAutomaticReminders();
            $this->info("リマインダーメールを {$sent} 件送信しました。");
        }
        
        return Command::SUCCESS;
    }
    
    /**
     * 対象の表示（ドライラン用）
     */
    protected function showTargets()
    {
        $upcomingEvents = \App\Models\Event::where('event_date', '=', now()->addDay()->toDateString())
            ->where('status', 'active')
            ->with(['customers' => function ($query) {
                $query->where('status', 'confirmed')
                      ->where('reminder_sent', false);
            }])
            ->get();
        
        if ($upcomingEvents->isEmpty()) {
            $this->info('明日開催のイベントはありません。');
            return;
        }
        
        $totalCustomers = 0;
        
        foreach ($upcomingEvents as $event) {
            $customerCount = $event->customers->count();
            $totalCustomers += $customerCount;
            
            $this->line("📅 {$event->title}");
            $this->line("   日時: {$event->event_date} {$event->event_time}");
            $this->line("   対象者: {$customerCount}人");
            $this->line("");
        }
        
        $this->info("合計送信対象: {$totalCustomers}人");
    }
}