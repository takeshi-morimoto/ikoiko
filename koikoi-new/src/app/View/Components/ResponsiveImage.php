<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Storage;

class ResponsiveImage extends Component
{
    public string $src;
    public string $alt;
    public string $class;
    public string $loading;
    public ?string $webpSrc;
    public array $sizes;
    public array $srcset;
    
    /**
     * レスポンシブ画像コンポーネント
     */
    public function __construct(
        string $src,
        string $alt = '',
        string $class = '',
        string $loading = 'lazy',
        ?array $sizes = null
    ) {
        $this->src = $src;
        $this->alt = $alt;
        $this->class = $class;
        $this->loading = $loading;
        
        // WebP版のパスを生成
        $this->webpSrc = $this->getWebpPath($src);
        
        // レスポンシブサイズの設定
        $this->sizes = $sizes ?? [
            '(max-width: 640px) 100vw',
            '(max-width: 1024px) 50vw',
            '33vw'
        ];
        
        // srcsetの生成
        $this->srcset = $this->generateSrcset($src);
    }
    
    /**
     * WebPパスの取得
     */
    protected function getWebpPath(string $src): ?string
    {
        // 既にWebP形式の場合はそのまま返す
        if (str_ends_with($src, '.webp')) {
            return $src;
        }
        
        // WebP版のパスを生成
        $webpPath = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $src);
        
        // Storage内のパスに変換
        $storagePath = str_replace('/storage/', '', $webpPath);
        
        // WebP版が存在するか確認
        if (Storage::disk('public')->exists($storagePath)) {
            return $webpPath;
        }
        
        return null;
    }
    
    /**
     * srcsetの生成
     */
    protected function generateSrcset(string $src): array
    {
        $srcset = [];
        $basePath = dirname($src);
        $filename = pathinfo($src, PATHINFO_FILENAME);
        $extension = pathinfo($src, PATHINFO_EXTENSION);
        
        // 異なるサイズの画像パスを生成
        $sizes = [
            'small' => '600w',
            'medium' => '1200w',
            'large' => '1920w',
        ];
        
        foreach ($sizes as $size => $width) {
            $sizePath = "{$basePath}/{$size}/{$filename}_{$size}.{$extension}";
            $storagePath = str_replace('/storage/', '', $sizePath);
            
            if (Storage::disk('public')->exists($storagePath)) {
                $srcset[] = "{$sizePath} {$width}";
            }
        }
        
        // オリジナル画像も含める
        if (!empty($srcset)) {
            $srcset[] = "{$src} 2400w";
        }
        
        return $srcset;
    }
    
    /**
     * コンポーネントのレンダリング
     */
    public function render()
    {
        return view('components.responsive-image');
    }
}