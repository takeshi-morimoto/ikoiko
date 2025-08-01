<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Customer;
use App\Models\User;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * 管理画面ダッシュボード
     */
    public function index()
    {
        try {
            // キャッシュから統計データを取得（30分間キャッシュ）
            $stats = Cache::remember('admin.dashboard.stats', 1800, function() {
                return $this->calculateStats();
            });
            
            // 今日のイベント（キャッシュなし、リアルタイム）
            $todayEvents = $this->getTodayEvents();
            
            // 人気エリア（1時間キャッシュ）
            $popularAreas = Cache::remember('admin.dashboard.popular_areas', 3600, function() {
                return $this->getPopularAreas();
            });
            
            // 最近の活動（仮データ）
            $recentActivities = $this->getRecentActivities();
            
            return view('admin.dashboard', compact(
                'stats',
                'todayEvents', 
                'popularAreas',
                'recentActivities'
            ));
            
        } catch (\Exception $e) {
            Log::error('Dashboard loading error: ' . $e->getMessage());
            
            // エラー時のフォールバック
            return redirect()->route('admin.dashboard')
                ->with('error', 'ダッシュボードの読み込み中にエラーが発生しました。');
        }
    }
    
    /**
     * 統計データの計算
     */
    private function calculateStats()
    {
        return [
            'total_events' => Event::count(),
            'total_participants' => Customer::count(),
            'total_revenue' => Customer::where('payment_status', 'paid')
                ->join('events', 'customers.event_id', '=', 'events.id')
                ->sum(DB::raw('CASE 
                    WHEN customers.gender = "male" THEN events.price_male 
                    ELSE events.price_female 
                END')),
            'active_staff' => User::whereHas('staffProfile', function($q) {
                $q->where('is_active', true);
            })->count(),
        ];
    }
    
    /**
     * 今日のイベント取得
     */
    private function getTodayEvents()
    {
        $limit = config('admin.dashboard.today_events_limit', 10);
        
        return Event::with(['area', 'eventType'])
            ->where('event_date', today())
            ->orderBy('start_time')
            ->limit($limit)
            ->get();
    }
    
    /**
     * 人気エリア取得
     */
    private function getPopularAreas()
    {
        $limit = config('admin.dashboard.popular_areas_limit', 5);
        
        return Area::withCount('events')
            ->orderBy('events_count', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * 最近のアクティビティを取得（仮実装）
     */
    private function getRecentActivities()
    {
        return collect([
            [
                'type' => 'registration',
                'title' => '新規参加者登録',
                'description' => '田中太郎さんがアニメコンに参加登録しました',
                'time' => '2時間前',
                'icon' => 'user-plus',
                'color' => 'success'
            ],
            [
                'type' => 'event_created',
                'title' => 'イベント作成',
                'description' => '「秋葉原アニメコン」が作成されました',
                'time' => '5時間前',
                'icon' => 'calendar-plus',
                'color' => 'primary'
            ],
            [
                'type' => 'staff_shift',
                'title' => 'スタッフシフト変更',
                'description' => '山田花子さんのシフトが変更されました',
                'time' => '1日前',
                'icon' => 'calendar-week',
                'color' => 'warning'
            ],
        ]);
    }
    
    /**
     * 月別売上データ取得（API用）
     */
    public function getMonthlyRevenue(Request $request)
    {
        try {
            $year = $request->get('year', date('Y'));
            
            // バリデーション
            if (!is_numeric($year) || $year < 2020 || $year > 2030) {
                return response()->json(['error' => '無効な年が指定されました'], 400);
            }
            
            // キャッシュキー
            $cacheKey = "admin.monthly_revenue.{$year}";
            
            // キャッシュから取得（1時間キャッシュ）
            $data = Cache::remember($cacheKey, 3600, function() use ($year) {
                $monthlyRevenue = Event::select(
                        DB::raw('MONTH(event_date) as month'),
                        DB::raw('SUM(CASE WHEN customers.payment_status = "paid" AND customers.gender = "male" THEN events.price_male ELSE 0 END) + SUM(CASE WHEN customers.payment_status = "paid" AND customers.gender = "female" THEN events.price_female ELSE 0 END) as revenue')
                    )
                    ->leftJoin('customers', 'events.id', '=', 'customers.event_id')
                    ->whereYear('events.event_date', $year)
                    ->groupBy(DB::raw('MONTH(event_date)'))
                    ->orderBy('month')
                    ->get()
                    ->keyBy('month');
                
                // 12ヶ月分のデータを準備（データがない月は0）
                $result = [];
                for ($i = 1; $i <= 12; $i++) {
                    $result[] = $monthlyRevenue->get($i)->revenue ?? 0;
                }
                
                return $result;
            });
            
            return response()->json($data);
            
        } catch (\Exception $e) {
            Log::error('Monthly revenue fetch error: ' . $e->getMessage());
            
            // エラー時は空のデータを返す
            return response()->json(array_fill(0, 12, 0), 500);
        }
    }
    
    /**
     * 統計データの更新
     */
    public function updateStats()
    {
        try {
            // キャッシュクリア
            Cache::forget('admin.dashboard.stats');
            Cache::forget('admin.dashboard.popular_areas');
            
            // 年別の売上キャッシュもクリア
            $currentYear = date('Y');
            for ($year = $currentYear - 2; $year <= $currentYear + 1; $year++) {
                Cache::forget("admin.monthly_revenue.{$year}");
            }
            
            Log::info('Admin dashboard stats cache cleared by user');
            
            return response()->json([
                'success' => true,
                'message' => '統計データを更新しました',
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);
            
        } catch (\Exception $e) {
            Log::error('Stats update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => '統計データの更新中にエラーが発生しました',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}