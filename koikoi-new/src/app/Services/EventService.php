<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Customer;
use App\Repositories\EventRepository;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function __construct(
        private EventRepository $eventRepository
    ) {}

    /**
     * イベント一覧を取得
     */
    public function getEventList(array $filters = [], int $perPage = 15)
    {
        $query = $this->eventRepository->getPublishedEvents();
        
        // フィルター適用はコントローラーのtraitで行う
        
        return $query;
    }

    /**
     * イベント申込処理
     */
    public function registerCustomer(Event $event, array $data): Customer
    {
        return DB::transaction(function () use ($event, $data) {
            // 申込番号生成
            $registrationNumber = $this->generateRegistrationNumber($event);
            
            // 顧客作成
            $customer = Customer::create([
                'event_id' => $event->id,
                'registration_number' => $registrationNumber,
                'name' => $data['name'],
                'name_kana' => $data['name_kana'],
                'gender' => $data['gender'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'birthdate' => $data['birthdate'] ?? null,
                'postal_code' => $data['postal_code'] ?? null,
                'address' => $data['address'] ?? null,
                'status' => 'registered',
                'payment_status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);
            
            // 参加人数を増やす
            $event->increment($data['gender'] === 'male' ? 'registered_male' : 'registered_female');
            
            return $customer;
        });
    }

    /**
     * 申込番号生成
     */
    private function generateRegistrationNumber(Event $event): string
    {
        $prefix = strtoupper(substr($event->eventType->slug, 0, 3));
        $date = $event->event_date->format('ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        
        return "{$prefix}-{$date}-{$random}";
    }

    /**
     * イベントの空席状況を確認
     */
    public function checkAvailability(Event $event, string $gender): bool
    {
        if ($gender === 'male') {
            return $event->remaining_male_seats > 0;
        }
        
        return $event->remaining_female_seats > 0;
    }

    /**
     * イベントステータスを更新
     */
    public function updateEventStatus(Event $event): void
    {
        // 満席チェック
        if ($event->remaining_male_seats === 0 && $event->remaining_female_seats === 0) {
            $event->update(['status' => 'full']);
            return;
        }
        
        // 開催日チェック
        if ($event->event_date < now()) {
            $event->update(['status' => 'ended']);
            return;
        }
        
        // 締切日チェック
        if ($event->application_deadline && $event->application_deadline < now()) {
            $event->update(['status' => 'closed']);
            return;
        }
    }
}