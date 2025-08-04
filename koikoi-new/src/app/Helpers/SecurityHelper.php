<?php

namespace App\Helpers;

class SecurityHelper
{
    /**
     * XSS対策：出力のエスケープ
     */
    public static function escape($string, $encoding = 'UTF-8')
    {
        if (is_null($string)) {
            return '';
        }
        
        return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, $encoding);
    }
    
    /**
     * CSRFトークンの検証
     */
    public static function verifyToken($token)
    {
        return hash_equals(session()->token(), $token);
    }
    
    /**
     * セキュアなランダム文字列の生成
     */
    public static function generateSecureToken($length = 32)
    {
        return bin2hex(random_bytes($length / 2));
    }
    
    /**
     * パスワードの強度チェック
     */
    public static function checkPasswordStrength($password)
    {
        $strength = 0;
        
        // 長さチェック
        if (strlen($password) >= 8) $strength++;
        if (strlen($password) >= 12) $strength++;
        
        // 大文字
        if (preg_match('/[A-Z]/', $password)) $strength++;
        
        // 小文字
        if (preg_match('/[a-z]/', $password)) $strength++;
        
        // 数字
        if (preg_match('/[0-9]/', $password)) $strength++;
        
        // 特殊文字
        if (preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) $strength++;
        
        return [
            'score' => $strength,
            'strength' => $strength <= 2 ? 'weak' : ($strength <= 4 ? 'medium' : 'strong')
        ];
    }
    
    /**
     * ファイル名のサニタイゼーション
     */
    public static function sanitizeFileName($filename)
    {
        // 危険な文字を除去
        $filename = preg_replace('/[^a-zA-Z0-9\-\_\.]/', '', $filename);
        
        // 連続するドットを除去
        $filename = preg_replace('/\.{2,}/', '.', $filename);
        
        // 先頭と末尾のドットを除去
        $filename = trim($filename, '.');
        
        return $filename;
    }
    
    /**
     * URLのサニタイゼーション
     */
    public static function sanitizeUrl($url)
    {
        // スキームをチェック
        $allowed_schemes = ['http', 'https'];
        $parsed = parse_url($url);
        
        if (!$parsed || !isset($parsed['scheme']) || !in_array($parsed['scheme'], $allowed_schemes)) {
            return false;
        }
        
        // フィルター適用
        $url = filter_var($url, FILTER_SANITIZE_URL);
        
        // 妥当性チェック
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        
        return $url;
    }
    
    /**
     * IPアドレスの検証
     */
    public static function validateIpAddress($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }
    
    /**
     * メールアドレスの検証
     */
    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * 日本の電話番号の検証
     */
    public static function validatePhoneNumber($phone)
    {
        // ハイフンを除去
        $phone = str_replace('-', '', $phone);
        
        // 日本の電話番号パターン
        $patterns = [
            '/^0[0-9]{9,10}$/', // 固定電話
            '/^0[789]0[0-9]{8}$/', // 携帯電話
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $phone)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * SQLインジェクション対策：危険な文字列のチェック
     */
    public static function containsSqlKeywords($input)
    {
        $dangerous_keywords = [
            'union', 'select', 'insert', 'update', 'delete', 'drop',
            'create', 'alter', 'exec', 'execute', 'script', 'javascript'
        ];
        
        $input_lower = strtolower($input);
        
        foreach ($dangerous_keywords as $keyword) {
            if (strpos($input_lower, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * セッションハイジャック対策：セッションの再生成
     */
    public static function regenerateSession()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }
    
    /**
     * 安全なリダイレクトURL の検証
     */
    public static function isSafeRedirectUrl($url)
    {
        // 相対URLは許可
        if (strpos($url, '/') === 0 && strpos($url, '//') !== 0) {
            return true;
        }
        
        // 同一ドメインチェック
        $parsed = parse_url($url);
        if (!$parsed || !isset($parsed['host'])) {
            return false;
        }
        
        $current_host = $_SERVER['HTTP_HOST'] ?? '';
        return $parsed['host'] === $current_host;
    }
}