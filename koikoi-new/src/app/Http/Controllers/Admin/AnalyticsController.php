<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Customer;
use App\Models\EventRevenueSummary;
use App\Models\EventParticipantAnalytics;
use App\Models\CustomerAnalytics;
use App\Models\MonthlySummary;
use App\Models\KpiTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * 分析ダッシュボード
     */
    public function dashboard(Request $request)
    {
        $dateRange = $this->getDateRange($request);
        
        $data = [
            'dateRange' => $dateRange,
            'revenue' => $this->getRevenueMetrics($dateRange),
            'participants' => $this->getParticipantMetrics($dateRange),
            'eventTypes' => $this->getEventTypeMetrics($dateRange),
            'areas' => $this->getAreaMetrics($dateRange),
            'kpis' => $this->getKPIs($dateRange),
            'trends' => $this->getTrends($dateRange),
        ];
        
        return view('admin.analytics.dashboard', $data);
    }

    /**
     * イベント別分析
     */
    public function events(Request $request)
    {
        $events = Event::with(['eventType', 'area', 'revenueSummary', 'participantAnalytics'])
            ->when($request->event_type, function($q, $type) {
                $q->whereHas('eventType', function($q2) use ($type) {
                    $q2->where('slug', $type);
                });
            })
            ->when($request->date_from, function($q, $date) {
                $q->where('event_date', '>=', $date);
            })
            ->when($request->date_to, function($q, $date) {
                $q->where('event_date', '<=', $date);
            })
            ->orderBy('event_date', 'desc')
            ->paginate(20);
            
        return view('admin.analytics.events', compact('events'));
    }

    /**
     * 顧客分析
     */
    public function customers(Request $request)
    {
        $customers = CustomerAnalytics::with('customer')
            ->when($request->segment, function($q, $segment) {
                $q->where('customer_segment', $segment);
            })
            ->when($request->min_ltv, function($q, $ltv) {
                $q->where('lifetime_value', '>=', $ltv);
            })
            ->orderBy($request->sort_by ?? 'lifetime_value', 'desc')
            ->paginate(50);
            
        $segments = CustomerAnalytics::select('customer_segment', DB::raw('count(*) as count'))
            ->groupBy('customer_segment')
            ->get();
            
        return view('admin.analytics.customers', compact('customers', 'segments'));
    }

    /**
     * 売上分析
     */
    public function revenue(Request $request)
    {
        $dateRange = $this->getDateRange($request);
        
        $data = [
            'dailyRevenue' => $this->getDailyRevenue($dateRange),
            'monthlyRevenue' => $this->getMonthlyRevenue($dateRange),
            'eventTypeRevenue' => $this->getEventTypeRevenue($dateRange),
            'areaRevenue' => $this->getAreaRevenue($dateRange),
            'paymentMethods' => $this->getPaymentMethodBreakdown($dateRange),
            'discountAnalysis' => $this->getDiscountAnalysis($dateRange),
        ];
        
        return view('admin.analytics.revenue', array_merge($data, ['dateRange' => $dateRange]));
    }

    /**
     * レポート生成
     */
    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:monthly,quarterly,yearly,custom',
            'date_from' => 'required_if:report_type,custom|date',
            'date_to' => 'required_if:report_type,custom|date|after:date_from',
            'include_sections' => 'required|array',
        ]);

        $dateRange = $this->getReportDateRange($validated['report_type'], $validated);
        
        $report = [
            'metadata' => [
                'generated_at' => now(),
                'generated_by' => auth()->user()->name,
                'period' => $dateRange,
            ],
        ];

        if (in_array('summary', $validated['include_sections'])) {
            $report['summary'] = $this->generateSummarySection($dateRange);
        }

        if (in_array('revenue', $validated['include_sections'])) {
            $report['revenue'] = $this->generateRevenueSection($dateRange);
        }

        if (in_array('participants', $validated['include_sections'])) {
            $report['participants'] = $this->generateParticipantsSection($dateRange);
        }

        if (in_array('customers', $validated['include_sections'])) {
            $report['customers'] = $this->generateCustomersSection($dateRange);
        }

        return view('admin.analytics.report', compact('report'));
    }

    /**
     * データ更新（集計処理）
     */
    public function updateAnalytics()
    {
        DB::transaction(function () {
            // イベント別の売上・参加者分析を更新
            $this->updateEventAnalytics();
            
            // 顧客分析を更新
            $this->updateCustomerAnalytics();
            
            // 月次サマリーを更新
            $this->updateMonthlySummaries();
            
            // KPIを更新
            $this->updateKPIs();
        });

        return redirect()->route('admin.analytics.dashboard')
            ->with('success', '分析データを更新しました。');
    }

    // Private methods

    private function getDateRange(Request $request)
    {
        return [
            'from' => $request->date_from ? Carbon::parse($request->date_from) : now()->subMonth(),
            'to' => $request->date_to ? Carbon::parse($request->date_to) : now(),
        ];
    }

    private function getRevenueMetrics($dateRange)
    {
        return EventRevenueSummary::whereBetween('calculated_at', [$dateRange['from'], $dateRange['to']])
            ->select(
                DB::raw('SUM(total_revenue) as total'),
                DB::raw('SUM(male_revenue) as male'),
                DB::raw('SUM(female_revenue) as female'),
                DB::raw('SUM(early_bird_revenue) as early_bird'),
                DB::raw('SUM(cancellation_fees) as cancellation'),
                DB::raw('AVG(collection_rate) as avg_collection_rate')
            )
            ->first();
    }

    private function getParticipantMetrics($dateRange)
    {
        return EventParticipantAnalytics::whereHas('event', function($q) use ($dateRange) {
                $q->whereBetween('event_date', [$dateRange['from'], $dateRange['to']]);
            })
            ->select(
                DB::raw('SUM(total_registered) as total_registered'),
                DB::raw('SUM(total_attended) as total_attended'),
                DB::raw('SUM(cancelled_count) as total_cancelled'),
                DB::raw('AVG(attendance_rate) as avg_attendance_rate'),
                DB::raw('AVG(male_female_ratio) as avg_gender_ratio')
            )
            ->first();
    }

    private function updateEventAnalytics()
    {
        $events = Event::where('event_date', '<=', today())
            ->whereDoesntHave('revenueSummary', function($q) {
                $q->where('calculated_at', today());
            })
            ->get();

        foreach ($events as $event) {
            // 売上集計
            $revenue = [
                'event_id' => $event->id,
                'calculated_at' => today(),
                'total_revenue' => 0,
                'male_revenue' => 0,
                'female_revenue' => 0,
                // ... 他の集計処理
            ];

            EventRevenueSummary::updateOrCreate(
                ['event_id' => $event->id, 'calculated_at' => today()],
                $revenue
            );

            // 参加者分析
            $participants = [
                'event_id' => $event->id,
                'total_registered' => $event->customers()->count(),
                'male_registered' => $event->customers()->where('gender', 'male')->count(),
                'female_registered' => $event->customers()->where('gender', 'female')->count(),
                // ... 他の分析処理
            ];

            EventParticipantAnalytics::updateOrCreate(
                ['event_id' => $event->id],
                $participants
            );
        }
    }

    private function updateCustomerAnalytics()
    {
        $customers = Customer::with(['events', 'payments'])
            ->chunk(100, function ($customers) {
                foreach ($customers as $customer) {
                    $analytics = [
                        'customer_id' => $customer->id,
                        'total_events_registered' => $customer->events->count(),
                        'total_events_attended' => $customer->events->where('pivot.status', 'attended')->count(),
                        'total_spent' => $customer->payments->where('status', 'completed')->sum('amount'),
                        // ... 他の分析処理
                    ];

                    CustomerAnalytics::updateOrCreate(
                        ['customer_id' => $customer->id],
                        $analytics
                    );
                }
            });
    }

    private function getKPIs($dateRange)
    {
        return KpiTracking::whereBetween('date', [$dateRange['from'], $dateRange['to']])
            ->whereIn('kpi_name', ['conversion_rate', 'avg_revenue_per_user', 'repeat_rate'])
            ->orderBy('date')
            ->get()
            ->groupBy('kpi_name');
    }
}