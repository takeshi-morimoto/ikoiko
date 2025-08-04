<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait HasCache
{
    /**
     * キャッシュキーのプレフィックスを取得
     */
    protected function getCachePrefix(): string
    {
        return strtolower(class_basename(static::class)) . ':';
    }
    
    /**
     * キャッシュから取得、なければコールバックを実行
     */
    protected function remember(string $key, int $ttl, callable $callback)
    {
        if (!config('constants.features.cache_enabled')) {
            return $callback();
        }
        
        $fullKey = $this->getCachePrefix() . $key;
        return Cache::remember($fullKey, $ttl, $callback);
    }
    
    /**
     * キャッシュから取得
     */
    protected function getCache(string $key)
    {
        if (!config('constants.features.cache_enabled')) {
            return null;
        }
        
        $fullKey = $this->getCachePrefix() . $key;
        return Cache::get($fullKey);
    }
    
    /**
     * キャッシュに保存
     */
    protected function putCache(string $key, $value, int $ttl = 3600): void
    {
        if (!config('constants.features.cache_enabled')) {
            return;
        }
        
        $fullKey = $this->getCachePrefix() . $key;
        Cache::put($fullKey, $value, $ttl);
    }
    
    /**
     * キャッシュを削除
     */
    protected function forgetCache(string $key): void
    {
        $fullKey = $this->getCachePrefix() . $key;
        Cache::forget($fullKey);
    }
    
    /**
     * プレフィックスに一致するキャッシュをすべて削除
     */
    protected function flushCache(): void
    {
        // タグ付きキャッシュを使用している場合
        if (Cache::supportsTags()) {
            Cache::tags([$this->getCachePrefix()])->flush();
        }
    }
    
    /**
     * キャッシュキーを生成
     */
    protected function makeCacheKey(array $params = []): string
    {
        ksort($params);
        return md5(serialize($params));
    }
}