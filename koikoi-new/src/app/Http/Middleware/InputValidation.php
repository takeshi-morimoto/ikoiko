<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class InputValidation
{
    /**
     * 一般的な入力値の検証
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 危険な入力パターンをチェック
        if ($this->containsDangerousPatterns($request)) {
            return response()->json([
                'success' => false,
                'message' => '不正な入力が検出されました'
            ], 400);
        }
        
        // ファイルアップロードの検証
        if ($request->hasFile('file')) {
            if (!$this->validateFileUpload($request)) {
                return response()->json([
                    'success' => false,
                    'message' => '許可されていないファイル形式です'
                ], 400);
            }
        }
        
        // URLパラメータの検証
        if (!$this->validateUrlParameters($request)) {
            return response()->json([
                'success' => false,
                'message' => '不正なURLパラメータです'
            ], 400);
        }
        
        return $next($request);
    }
    
    /**
     * 危険なパターンの検出
     */
    protected function containsDangerousPatterns(Request $request): bool
    {
        $input = $request->all();
        $dangerousPatterns = [
            // SQLインジェクション
            '/(\bunion\b|\bselect\b|\binsert\b|\bupdate\b|\bdelete\b|\bdrop\b|\bcreate\b)/i',
            // コマンドインジェクション
            '/(\||\;|\&|\$\(|\`)/i',
            // パストラバーサル
            '/(\.\.[\/\\\\])/i',
            // XXE攻撃
            '/(<!DOCTYPE|<!ENTITY|SYSTEM)/i'
        ];
        
        foreach ($input as $value) {
            if (is_string($value)) {
                foreach ($dangerousPatterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        // ログに記録
                        \Log::warning('Dangerous pattern detected', [
                            'pattern' => $pattern,
                            'value' => substr($value, 0, 100),
                            'ip' => $request->ip(),
                            'url' => $request->fullUrl()
                        ]);
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
    
    /**
     * ファイルアップロードの検証
     */
    protected function validateFileUpload(Request $request): bool
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
        $maxFileSize = 10 * 1024 * 1024; // 10MB
        
        $files = $request->allFiles();
        
        foreach ($files as $file) {
            // ファイルサイズチェック
            if ($file->getSize() > $maxFileSize) {
                return false;
            }
            
            // 拡張子チェック
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, $allowedExtensions)) {
                return false;
            }
            
            // MIMEタイプチェック
            $mimeType = $file->getMimeType();
            $allowedMimeTypes = [
                'image/jpeg',
                'image/png',
                'image/gif',
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];
            
            if (!in_array($mimeType, $allowedMimeTypes)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * URLパラメータの検証
     */
    protected function validateUrlParameters(Request $request): bool
    {
        // IDパラメータの検証
        if ($request->has('id')) {
            $id = $request->input('id');
            if (!is_numeric($id) || $id < 1 || $id > PHP_INT_MAX) {
                return false;
            }
        }
        
        // ページ番号の検証
        if ($request->has('page')) {
            $page = $request->input('page');
            if (!is_numeric($page) || $page < 1 || $page > 10000) {
                return false;
            }
        }
        
        // ソート順の検証
        if ($request->has('sort')) {
            $sort = $request->input('sort');
            $allowedSorts = ['asc', 'desc', 'latest', 'oldest', 'popular'];
            if (!in_array($sort, $allowedSorts)) {
                return false;
            }
        }
        
        return true;
    }
}