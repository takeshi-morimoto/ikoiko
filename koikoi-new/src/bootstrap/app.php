<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // グローバルミドルウェア
        $middleware->append([
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\XssProtection::class,
        ]);
        
        // エイリアス
        $middleware->alias([
            'auth.basic' => \App\Http\Middleware\BasicAuth::class,
            'security.headers' => \App\Http\Middleware\SecurityHeaders::class,
            'xss.protection' => \App\Http\Middleware\XssProtection::class,
            'input.validation' => \App\Http\Middleware\InputValidation::class,
            'rate.limit' => \App\Http\Middleware\RateLimiting::class,
        ]);
        
        // Web用ミドルウェアグループ
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
        
        // API用ミドルウェアグループ
        $middleware->api(append: [
            \App\Http\Middleware\RateLimiting::class.':api',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, $request) {
            // APIリクエストの場合はJSONレスポンスを返す
            if ($request->expectsJson()) {
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'バリデーションエラー',
                        'errors' => $e->errors()
                    ], 422);
                }
                
                if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'データが見つかりません'
                    ], 404);
                }
                
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'エンドポイントが見つかりません'
                    ], 404);
                }
                
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'リクエストが多すぎます。しばらくお待ちください。'
                    ], 429);
                }
                
                // 本番環境では詳細なエラー情報を隠す
                if (app()->environment('production')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'サーバーエラーが発生しました'
                    ], 500);
                }
            }
        });
        
        // エラーをログに記録
        $exceptions->report(function (\Throwable $e) {
            if (app()->bound('log.service')) {
                app('log.service')->error('Exception occurred', [], $e);
            }
        });
    })->create();
