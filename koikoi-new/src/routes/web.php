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

// トップページ
Route::get('/', [HomeController::class, 'index'])->name('home');

// 全イベント一覧
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/calendar', [EventController::class, 'calendar'])->name('events.calendar');

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

// 謎解きイベント（将来的な拡張用）
Route::prefix('nazo')->name('nazo.')->group(function () {
    Route::get('/', function () {
        return view('coming-soon', ['type' => 'nazo']);
    })->name('index');
});

// イベント登録フロー
Route::prefix('entry')->middleware('throttle:60,1')->name('entry.')->group(function () {
    Route::get('/{event}', [RegistrationController::class, 'show'])->name('show');
    Route::post('/{event}/confirm', [RegistrationController::class, 'confirm'])->name('confirm');
    Route::post('/{event}/complete', [RegistrationController::class, 'complete'])->name('complete');
    Route::get('/thanks/{registration}', [RegistrationController::class, 'thanks'])->name('thanks');
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
Route::get('/{eventType}/{slug}', [EventController::class, 'show'])
    ->name('event.show')
    ->where('eventType', 'anime|machi|nazo|other');

// 地域別ページ（SEO用）
Route::get('/area/{prefecture}', [EventController::class, 'byPrefecture'])->name('area.prefecture');
Route::get('/area/{prefecture}/{area}', [EventController::class, 'byArea'])->name('area.detail');

// 管理画面（簡易認証）
Route::prefix('admin')->name('admin.')->middleware(['auth.basic'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.events.index');
    })->name('index');
    
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\EventController::class, 'index'])->name('index');
    });
});
