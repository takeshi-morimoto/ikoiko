<?php

use Illuminate\Support\Str;
use Carbon\Carbon;

if (!function_exists('format_price')) {
    /**
     * 価格をフォーマット
     */
    function format_price($price): string
    {
        return '¥' . number_format($price);
    }
}

if (!function_exists('format_date_ja')) {
    /**
     * 日付を日本語フォーマットに変換
     */
    function format_date_ja($date, $format = 'Y年n月j日'): string
    {
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }
        
        return $date->format($format);
    }
}

if (!function_exists('get_event_status')) {
    /**
     * イベントのステータスを取得
     */
    function get_event_status($event): string
    {
        $now = Carbon::now();
        $eventDate = Carbon::parse($event->event_date);
        
        if ($eventDate->isPast()) {
            return 'ended';
        }
        
        if ($event->is_full) {
            return 'full';
        }
        
        $daysUntil = $now->diffInDays($eventDate);
        
        if ($daysUntil <= 3) {
            return 'urgent';
        }
        
        if ($daysUntil <= 7) {
            return 'soon';
        }
        
        return 'available';
    }
}

if (!function_exists('get_status_label')) {
    /**
     * ステータスのラベルを取得
     */
    function get_status_label($status): string
    {
        return match($status) {
            'ended' => '終了',
            'full' => '満席',
            'urgent' => '締切間近',
            'soon' => '開催間近',
            'available' => '受付中',
            default => ''
        };
    }
}

if (!function_exists('get_status_color')) {
    /**
     * ステータスの色を取得
     */
    function get_status_color($status): string
    {
        return match($status) {
            'ended' => 'secondary',
            'full' => 'danger',
            'urgent' => 'warning',
            'soon' => 'info',
            'available' => 'success',
            default => 'primary'
        };
    }
}

if (!function_exists('calculate_discount')) {
    /**
     * 割引額を計算
     */
    function calculate_discount($price, $event_date): int
    {
        $daysUntil = Carbon::now()->diffInDays(Carbon::parse($event_date));
        $earlyBirdDays = config('constants.pricing.early_bird_days', 14);
        $earlyBirdDiscount = config('constants.pricing.early_bird_discount', 500);
        
        if ($daysUntil >= $earlyBirdDays) {
            return $earlyBirdDiscount;
        }
        
        return 0;
    }
}

if (!function_exists('sanitize_search')) {
    /**
     * 検索文字列をサニタイズ
     */
    function sanitize_search($string): string
    {
        // 全角スペースを半角に変換
        $string = mb_convert_kana($string, 's');
        
        // 前後の空白を削除
        $string = trim($string);
        
        // SQLワイルドカードをエスケープ
        $string = str_replace(['%', '_'], ['\%', '\_'], $string);
        
        return $string;
    }
}

if (!function_exists('get_theme_color')) {
    /**
     * テーマカラーを取得
     */
    function get_theme_color($type, $element = 'primary'): string
    {
        $colors = config("theme.colors.{$type}");
        
        return $colors[$element] ?? '#000000';
    }
}

if (!function_exists('truncate_ja')) {
    /**
     * 日本語テキストを適切に切り詰め
     */
    function truncate_ja($string, $length = 100, $suffix = '...'): string
    {
        if (mb_strlen($string) <= $length) {
            return $string;
        }
        
        return mb_substr($string, 0, $length) . $suffix;
    }
}

if (!function_exists('is_admin_ip')) {
    /**
     * 管理者IPかチェック
     */
    function is_admin_ip(): bool
    {
        $adminIps = config('constants.security.admin_ips', []);
        $clientIp = request()->ip();
        
        return in_array($clientIp, $adminIps);
    }
}