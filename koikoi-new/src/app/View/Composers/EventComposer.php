<?php

namespace App\View\Composers;

use App\Models\EventType;
use App\Models\Prefecture;
use Illuminate\View\View;

class EventComposer
{
    /**
     * イベント一覧ページに共通データをバインド
     */
    public function compose(View $view)
    {
        $view->with('eventTypes', $this->getEventTypes());
        $view->with('months', $this->getMonths());
        $view->with('ages', $this->getAges());
        $view->with('priceRanges', $this->getPriceRanges());
    }

    /**
     * イベントタイプ一覧を取得
     */
    private function getEventTypes()
    {
        return EventType::withCount(['events' => function ($query) {
            $query->published();
        }])->get();
    }

    /**
     * 月の選択肢を取得
     */
    private function getMonths()
    {
        return collect(range(1, 12))->mapWithKeys(function ($month) {
            return [$month => $month . '月'];
        });
    }

    /**
     * 年齢の選択肢を取得
     */
    private function getAges()
    {
        return collect(range(20, 45, 5))->mapWithKeys(function ($age) {
            return [$age => $age . '代'];
        });
    }

    /**
     * 価格帯の選択肢を取得
     */
    private function getPriceRanges()
    {
        return collect([
            3000 => '3,000円以下',
            5000 => '5,000円以下',
            7000 => '7,000円以下',
            10000 => '10,000円以下',
        ]);
    }
}