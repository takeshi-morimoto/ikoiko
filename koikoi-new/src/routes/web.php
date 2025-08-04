<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AnimeController;
use App\Http\Controllers\MachiController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| KOIKOI 統合サイトのルーティング
|
*/

// トップページ（イベント一覧）
Route::get('/', [EventController::class, 'index'])->name('home');

// 旧URLからのリダイレクト（後方互換性）
Route::get('/events', function () {
    return redirect()->route('home');
});

// アニメコン専用セクション
Route::prefix('anime')->name('anime.')->group(function () {
    Route::get('/', [AnimeController::class, 'index'])->name('index');
    Route::get('/about', [AnimeController::class, 'about'])->name('about');
    Route::get('/{slug}', [AnimeController::class, 'show'])->name('show');
});

// 街コン専用セクション
Route::prefix('machi')->name('machi.')->group(function () {
    Route::get('/', [MachiController::class, 'index'])->name('index');
    Route::get('/about', [MachiController::class, 'about'])->name('about');
    Route::get('/{slug}', [MachiController::class, 'show'])->name('show');
});


// イベント登録フロー
Route::prefix('entry')->middleware('throttle:60,1')->name('entry.')->group(function () {
    Route::get('/{event}', [RegistrationController::class, 'show'])->name('show');
    Route::post('/{event}/confirm', [RegistrationController::class, 'confirm'])->name('confirm');
    Route::post('/{event}/complete', [RegistrationController::class, 'complete'])->name('complete');
});

// 静的ページ
Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

Route::get('/company', function () {
    return view('pages.company');
})->name('company');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

// イベント詳細（SEO対応URL）
Route::get('/event/{slug}', [EventController::class, 'show'])->name('event.show');

// カテゴリ別イベント詳細（後方互換性）
Route::get('/{eventType}/{slug}', [EventController::class, 'show'])
    ->name('event.show.legacy')
    ->where('eventType', 'anime|machi|other');

// 地域別ページ（SEO用）
Route::get('/area/{prefecture}', [EventController::class, 'byPrefecture'])->name('area.prefecture');
Route::get('/area/{prefecture}/{area}', [EventController::class, 'byArea'])->name('area.detail');

// 管理画面（一時的に認証無効化）
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    })->name('index');
    
    // ダッシュボード
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/monthly-revenue', [App\Http\Controllers\Admin\DashboardController::class, 'getMonthlyRevenue'])->name('api.monthly-revenue');
    Route::post('/api/update-stats', [App\Http\Controllers\Admin\DashboardController::class, 'updateStats'])->name('api.update-stats');
    
    // イベント管理
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\EventController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\EventController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\EventController::class, 'store'])->name('store');
        Route::get('/{event}/edit', [App\Http\Controllers\Admin\EventController::class, 'edit'])->name('edit');
        Route::put('/{event}', [App\Http\Controllers\Admin\EventController::class, 'update'])->name('update');
        Route::delete('/{event}', [App\Http\Controllers\Admin\EventController::class, 'destroy'])->name('destroy');
    });
    
    // イベント運営管理
    Route::prefix('operations')->name('operations.')->group(function () {
        Route::get('/{event}', [App\Http\Controllers\Admin\EventOperationController::class, 'index'])->name('index');
        Route::get('/{event}/schedules', [App\Http\Controllers\Admin\EventOperationController::class, 'schedules'])->name('schedules');
        Route::post('/{event}/schedules', [App\Http\Controllers\Admin\EventOperationController::class, 'storeSchedule'])->name('schedules.store');
        Route::get('/{event}/equipment', [App\Http\Controllers\Admin\EventOperationController::class, 'equipment'])->name('equipment');
        Route::patch('/{event}/equipment/{equipment}', [App\Http\Controllers\Admin\EventOperationController::class, 'updateEquipmentStatus'])->name('equipment.update');
        Route::get('/{event}/roles', [App\Http\Controllers\Admin\EventOperationController::class, 'roles'])->name('roles');
        Route::get('/{event}/seating', [App\Http\Controllers\Admin\EventOperationController::class, 'seating'])->name('seating');
        Route::post('/{event}/seating/auto-assign', [App\Http\Controllers\Admin\EventOperationController::class, 'autoAssignSeating'])->name('seating.auto-assign');
        Route::get('/{event}/checklist', [App\Http\Controllers\Admin\EventOperationController::class, 'checklist'])->name('checklist');
        Route::patch('/{event}/checklist/{item}', [App\Http\Controllers\Admin\EventOperationController::class, 'completeChecklistItem'])->name('checklist.complete');
        Route::get('/{event}/special-notes', [App\Http\Controllers\Admin\EventOperationController::class, 'specialNotes'])->name('special-notes');
    });
    
    // 分析・レポート
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\AnalyticsController::class, 'dashboard'])->name('dashboard');
        Route::get('/events', [App\Http\Controllers\Admin\AnalyticsController::class, 'events'])->name('events');
        Route::get('/customers', [App\Http\Controllers\Admin\AnalyticsController::class, 'customers'])->name('customers');
        Route::get('/revenue', [App\Http\Controllers\Admin\AnalyticsController::class, 'revenue'])->name('revenue');
        Route::post('/generate-report', [App\Http\Controllers\Admin\AnalyticsController::class, 'generateReport'])->name('generate-report');
        Route::post('/update', [App\Http\Controllers\Admin\AnalyticsController::class, 'updateAnalytics'])->name('update');
    });
    
    // スタッフ管理
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\StaffController::class, 'index'])->name('index');
        Route::get('/{staff}', [App\Http\Controllers\Admin\StaffController::class, 'show'])->name('show');
        Route::get('/shifts', [App\Http\Controllers\Admin\StaffController::class, 'shifts'])->name('shifts');
        Route::post('/shifts', [App\Http\Controllers\Admin\StaffController::class, 'storeShift'])->name('shifts.store');
        Route::get('/shift-requests', [App\Http\Controllers\Admin\StaffController::class, 'shiftRequests'])->name('shift-requests');
        Route::patch('/shift-requests/{request}/approve', [App\Http\Controllers\Admin\StaffController::class, 'approveShiftRequest'])->name('shift-requests.approve');
        Route::get('/work-records', [App\Http\Controllers\Admin\StaffController::class, 'workRecords'])->name('work-records');
        Route::post('/work-records', [App\Http\Controllers\Admin\StaffController::class, 'storeWorkRecord'])->name('work-records.store');
        Route::get('/{staff}/skill-evaluations', [App\Http\Controllers\Admin\StaffController::class, 'skillEvaluations'])->name('skill-evaluations');
        Route::post('/{staff}/skill-evaluations', [App\Http\Controllers\Admin\StaffController::class, 'storeSkillEvaluation'])->name('skill-evaluations.store');
        Route::get('/shift-templates', [App\Http\Controllers\Admin\StaffController::class, 'shiftTemplates'])->name('shift-templates');
        Route::post('/events/{event}/apply-template', [App\Http\Controllers\Admin\StaffController::class, 'applyTemplate'])->name('apply-template');
    });
});
