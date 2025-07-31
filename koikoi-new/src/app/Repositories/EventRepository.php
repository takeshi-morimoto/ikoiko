<?php

namespace App\Repositories;

use App\Models\Event;
use Illuminate\Http\Request;

class EventRepository
{
    /**
     * 公開中のイベントを取得
     */
    public function getPublishedEvents()
    {
        return Event::with(['area.prefecture', 'eventType'])
            ->where('status', 'published')
            ->where('event_date', '>=', now());
    }

    /**
     * イベントタイプごとのイベントを取得
     */
    public function getEventsByType(string $eventType)
    {
        return $this->getPublishedEvents()
            ->whereHas('eventType', function ($query) use ($eventType) {
                $query->where('slug', $eventType);
            });
    }

    /**
     * 関連イベントを取得
     */
    public function getRelatedEvents(Event $event, int $limit = 3)
    {
        return $this->getPublishedEvents()
            ->where('event_type_id', $event->event_type_id)
            ->where('area_id', $event->area_id)
            ->where('id', '!=', $event->id)
            ->orderBy('event_date', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * 月間イベントを取得
     */
    public function getMonthlyEvents(int $year, int $month)
    {
        $startDate = now()->setYear($year)->setMonth($month)->startOfMonth();
        $endDate = now()->setYear($year)->setMonth($month)->endOfMonth();

        return Event::with(['area.prefecture', 'eventType'])
            ->where('status', 'published')
            ->whereBetween('event_date', [$startDate, $endDate])
            ->orderBy('event_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();
    }

    /**
     * スラッグでイベントを検索
     */
    public function findBySlug(string $slug, string $eventType = null)
    {
        $query = Event::with(['area.prefecture', 'eventType'])
            ->where('slug', $slug);

        if ($eventType) {
            $query->whereHas('eventType', function ($q) use ($eventType) {
                $q->where('slug', $eventType);
            });
        }

        return $query->firstOrFail();
    }
}