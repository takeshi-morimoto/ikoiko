<?php

namespace App\Http\Controllers;

use App\Services\LogService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    /**
     * 成功レスポンスを返す
     */
    protected function success($data = null, string $message = '', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }
    
    /**
     * エラーレスポンスを返す
     */
    protected function error(string $message = '', int $code = 400, $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
    
    /**
     * アクションをログに記録
     */
    protected function logAction(string $action, array $context = []): void
    {
        LogService::info("Controller action: {$action}", array_merge($context, [
            'controller' => static::class,
            'user_id' => auth()->id(),
        ]));
    }
    
    /**
     * エラーをログに記録
     */
    protected function logError(string $message, \Throwable $exception = null): void
    {
        LogService::error($message, [
            'controller' => static::class,
        ], $exception);
    }
}
