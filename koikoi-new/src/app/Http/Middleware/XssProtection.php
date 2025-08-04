<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XssProtection
{
    /**
     * XSS対策として入力値のサニタイゼーション
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 入力データのサニタイゼーション
        $input = $request->all();
        $sanitized = $this->sanitizeInput($input);
        $request->merge($sanitized);
        
        // レスポンスの処理
        $response = $next($request);
        
        return $response;
    }
    
    /**
     * 入力データを再帰的にサニタイゼーション
     */
    protected function sanitizeInput($input)
    {
        if (is_array($input)) {
            return array_map([$this, 'sanitizeInput'], $input);
        }
        
        if (is_string($input)) {
            // 危険なHTMLタグとJavaScriptを除去
            $input = $this->removeScriptTags($input);
            
            // HTMLエンティティのエスケープ
            $input = $this->escapeHtml($input);
            
            // SQLインジェクション対策
            $input = $this->escapeSql($input);
        }
        
        return $input;
    }
    
    /**
     * スクリプトタグとイベントハンドラを除去
     */
    protected function removeScriptTags($input): string
    {
        // scriptタグの除去
        $input = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', '', $input);
        
        // イベントハンドラの除去
        $input = preg_replace('/\bon\w+\s*=\s*["\'][^"\']*["\']/i', '', $input);
        $input = preg_replace('/\bon\w+\s*=\s*[^\s>]*/i', '', $input);
        
        // javascriptプロトコルの除去
        $input = preg_replace('/javascript\s*:/i', '', $input);
        
        // dataプロトコルの危険な使用を除去
        $input = preg_replace('/data:text\/html[^,]*,/i', '', $input);
        
        return $input;
    }
    
    /**
     * HTMLエンティティのエスケープ
     */
    protected function escapeHtml($input): string
    {
        // 基本的なHTMLエスケープ
        $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        return $input;
    }
    
    /**
     * SQL特殊文字のエスケープ
     */
    protected function escapeSql($input): string
    {
        // バックスラッシュのエスケープ
        $input = str_replace('\\', '\\\\', $input);
        
        // NULL文字の除去
        $input = str_replace("\0", '', $input);
        
        return $input;
    }
    
    /**
     * 特定のフィールドはサニタイゼーションから除外
     */
    protected function shouldSkipField($fieldName): bool
    {
        $skipFields = [
            'password',
            'password_confirmation',
            '_token',
            'remember_token',
            'api_token'
        ];
        
        return in_array($fieldName, $skipFields);
    }
}