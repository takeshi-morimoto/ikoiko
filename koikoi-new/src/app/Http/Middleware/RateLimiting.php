<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimiting
{
    /**
     * カスタムレート制限の実装
     */
    public function handle(Request $request, Closure $next, string $limiterName = 'default'): Response
    {
        $key = $this->resolveRequestSignature($request, $limiterName);
        $maxAttempts = $this->getMaxAttempts($limiterName);
        $decayMinutes = $this->getDecayMinutes($limiterName);
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return $this->buildResponse($request, $key, $maxAttempts);
        }
        
        RateLimiter::hit($key, $decayMinutes * 60);
        
        $response = $next($request);
        
        return $this->addHeaders(
            $response, 
            $maxAttempts,
            RateLimiter::remaining($key, $maxAttempts),
            RateLimiter::availableIn($key)
        );
    }
    
    /**
     * リクエストのシグネチャを生成
     */
    protected function resolveRequestSignature(Request $request, string $limiterName): string
    {
        $user = $request->user();
        
        if ($user) {
            return sprintf('%s|%s|%s', $limiterName, $user->id, $request->ip());
        }
        
        return sprintf('%s|%s', $limiterName, $request->ip());
    }
    
    /**
     * リミッター名に応じた最大試行回数を取得
     */
    protected function getMaxAttempts(string $limiterName): int
    {
        $limits = [
            'default' => 60,
            'api' => 100,
            'login' => 5,
            'register' => 3,
            'password-reset' => 3,
            'contact' => 5,
            'search' => 30,
            'upload' => 10
        ];
        
        return $limits[$limiterName] ?? 60;
    }
    
    /**
     * リミッター名に応じた減衰時間（分）を取得
     */
    protected function getDecayMinutes(string $limiterName): int
    {
        $decays = [
            'default' => 1,
            'api' => 1,
            'login' => 15,
            'register' => 60,
            'password-reset' => 60,
            'contact' => 30,
            'search' => 1,
            'upload' => 10
        ];
        
        return $decays[$limiterName] ?? 1;
    }
    
    /**
     * レート制限エラーレスポンスの作成
     */
    protected function buildResponse(Request $request, string $key, int $maxAttempts): Response
    {
        $retryAfter = RateLimiter::availableIn($key);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'リクエストが多すぎます。しばらくお待ちください。',
                'retry_after' => $retryAfter
            ], 429);
        }
        
        return response()->view('errors.429', [
            'retryAfter' => $retryAfter
        ], 429);
    }
    
    /**
     * レート制限ヘッダーの追加
     */
    protected function addHeaders(Response $response, int $maxAttempts, int $remainingAttempts, int $retryAfter): Response
    {
        $response->headers->set('X-RateLimit-Limit', $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', max(0, $remainingAttempts));
        
        if ($retryAfter > 0) {
            $response->headers->set('Retry-After', $retryAfter);
            $response->headers->set('X-RateLimit-Reset', time() + $retryAfter);
        }
        
        return $response;
    }
}