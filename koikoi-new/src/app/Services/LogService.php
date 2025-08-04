<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Throwable;

class LogService
{
    /**
     * エラーログを記録
     */
    public static function error(string $message, array $context = [], ?Throwable $exception = null): void
    {
        $logData = [
            'message' => $message,
            'context' => $context,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ];
        
        if ($exception) {
            $logData['exception'] = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ];
        }
        
        Log::error($message, $logData);
    }
    
    /**
     * 警告ログを記録
     */
    public static function warning(string $message, array $context = []): void
    {
        Log::warning($message, array_merge($context, [
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'url' => request()->fullUrl(),
        ]));
    }
    
    /**
     * 情報ログを記録
     */
    public static function info(string $message, array $context = []): void
    {
        Log::info($message, array_merge($context, [
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
        ]));
    }
    
    /**
     * デバッグログを記録
     */
    public static function debug(string $message, array $context = []): void
    {
        if (config('app.debug')) {
            Log::debug($message, $context);
        }
    }
    
    /**
     * パフォーマンスログを記録
     */
    public static function performance(string $operation, float $duration, array $context = []): void
    {
        $logData = array_merge($context, [
            'operation' => $operation,
            'duration_ms' => round($duration * 1000, 2),
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true),
        ]);
        
        if ($duration > 1.0) {
            Log::warning("Slow operation: {$operation}", $logData);
        } else {
            Log::info("Performance: {$operation}", $logData);
        }
    }
    
    /**
     * セキュリティログを記録
     */
    public static function security(string $event, array $context = []): void
    {
        Log::channel('security')->warning($event, array_merge($context, [
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toIso8601String(),
        ]));
    }
}