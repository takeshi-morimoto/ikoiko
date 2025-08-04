<?php

namespace App\Traits;

use App\Services\LogService;

trait LogsActivity
{
    /**
     * アクティビティをログに記録
     */
    protected function logActivity(string $action, array $context = []): void
    {
        LogService::info($action, array_merge($context, [
            'class' => static::class,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));
    }
    
    /**
     * エラーをログに記録
     */
    protected function logError(string $message, \Throwable $exception = null, array $context = []): void
    {
        LogService::error($message, array_merge($context, [
            'class' => static::class,
        ]), $exception);
    }
    
    /**
     * パフォーマンスをログに記録
     */
    protected function logPerformance(string $operation, callable $callback)
    {
        $start = microtime(true);
        
        try {
            $result = $callback();
            $duration = microtime(true) - $start;
            
            LogService::performance($operation, $duration, [
                'class' => static::class,
            ]);
            
            return $result;
        } catch (\Throwable $e) {
            $duration = microtime(true) - $start;
            
            LogService::performance($operation, $duration, [
                'class' => static::class,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }
}