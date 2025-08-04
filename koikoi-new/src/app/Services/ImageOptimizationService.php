<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageOptimizationService
{
    protected ImageManager $manager;
    
    // 画像品質設定
    protected array $quality = [
        'jpg' => 85,
        'jpeg' => 85,
        'png' => 90,
        'webp' => 85,
    ];
    
    // 最大サイズ設定
    protected array $maxSizes = [
        'thumbnail' => ['width' => 300, 'height' => 300],
        'small' => ['width' => 600, 'height' => 600],
        'medium' => ['width' => 1200, 'height' => 1200],
        'large' => ['width' => 1920, 'height' => 1920],
        'original' => null,
    ];
    
    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }
    
    /**
     * 画像を最適化して保存
     */
    public function optimizeAndStore(UploadedFile $file, string $path = 'images', array $sizes = ['thumbnail', 'medium', 'original']): array
    {
        $results = [];
        $filename = $this->generateFilename($file);
        
        foreach ($sizes as $size) {
            // オリジナル画像を読み込み
            $image = $this->manager->read($file->getRealPath());
            
            // サイズ調整
            if ($size !== 'original' && isset($this->maxSizes[$size])) {
                $image = $this->resizeImage($image, $this->maxSizes[$size]);
            }
            
            // フォーマット別に保存
            $formats = $this->getFormatsToSave($file);
            
            foreach ($formats as $format) {
                $outputFilename = $this->getOutputFilename($filename, $size, $format);
                $outputPath = "{$path}/{$size}/{$outputFilename}";
                
                // 画像を最適化して保存
                $optimized = $this->optimizeImage($image, $format);
                
                // ストレージに保存
                Storage::disk('public')->put($outputPath, $optimized);
                
                $results[$size][$format] = [
                    'path' => $outputPath,
                    'url' => Storage::url($outputPath),
                    'size' => Storage::disk('public')->size($outputPath),
                ];
            }
        }
        
        return $results;
    }
    
    /**
     * 既存の画像をWebP形式に変換
     */
    public function convertToWebP(string $imagePath): ?string
    {
        try {
            $fullPath = Storage::disk('public')->path($imagePath);
            
            if (!file_exists($fullPath)) {
                return null;
            }
            
            // 画像を読み込み
            $image = $this->manager->read($fullPath);
            
            // WebP形式で保存
            $webpPath = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $imagePath);
            $webpContent = $image->toWebp($this->quality['webp']);
            
            Storage::disk('public')->put($webpPath, $webpContent);
            
            return $webpPath;
        } catch (\Exception $e) {
            \Log::error('WebP conversion failed', ['path' => $imagePath, 'error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
     * 画像のリサイズ
     */
    protected function resizeImage($image, array $maxSize)
    {
        $width = $image->width();
        $height = $image->height();
        
        // アスペクト比を維持してリサイズ
        if ($width > $maxSize['width'] || $height > $maxSize['height']) {
            $image->scale(width: $maxSize['width'], height: $maxSize['height']);
        }
        
        return $image;
    }
    
    /**
     * 画像の最適化
     */
    protected function optimizeImage($image, string $format): string
    {
        switch ($format) {
            case 'webp':
                return $image->toWebp($this->quality['webp']);
            case 'jpg':
            case 'jpeg':
                return $image->toJpeg($this->quality['jpeg']);
            case 'png':
                return $image->toPng();
            default:
                return $image->toString();
        }
    }
    
    /**
     * 保存するフォーマットを決定
     */
    protected function getFormatsToSave(UploadedFile $file): array
    {
        $originalFormat = strtolower($file->getClientOriginalExtension());
        
        // 常にWebP版も作成
        $formats = ['webp'];
        
        // オリジナルフォーマットも保持
        if (!in_array($originalFormat, ['webp'])) {
            $formats[] = $originalFormat;
        }
        
        return $formats;
    }
    
    /**
     * ファイル名の生成
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $hash = md5_file($file->getRealPath());
        return date('Ymd_His') . '_' . substr($hash, 0, 8);
    }
    
    /**
     * 出力ファイル名の生成
     */
    protected function getOutputFilename(string $basename, string $size, string $format): string
    {
        return "{$basename}_{$size}.{$format}";
    }
    
    /**
     * 画像の情報を取得
     */
    public function getImageInfo(string $path): ?array
    {
        try {
            $fullPath = Storage::disk('public')->path($path);
            
            if (!file_exists($fullPath)) {
                return null;
            }
            
            $image = $this->manager->read($fullPath);
            
            return [
                'width' => $image->width(),
                'height' => $image->height(),
                'size' => filesize($fullPath),
                'mime' => mime_content_type($fullPath),
                'format' => pathinfo($path, PATHINFO_EXTENSION),
            ];
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * バッチ処理で既存画像を最適化
     */
    public function batchOptimize(string $directory = 'images', int $limit = 100): array
    {
        $results = [
            'processed' => 0,
            'saved_bytes' => 0,
            'errors' => [],
        ];
        
        $files = Storage::disk('public')->files($directory);
        $processed = 0;
        
        foreach ($files as $file) {
            if ($processed >= $limit) {
                break;
            }
            
            // 画像ファイルのみ処理
            if (!preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                continue;
            }
            
            // WebP版が既に存在する場合はスキップ
            $webpPath = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $file);
            if (Storage::disk('public')->exists($webpPath)) {
                continue;
            }
            
            try {
                $originalSize = Storage::disk('public')->size($file);
                $webpPath = $this->convertToWebP($file);
                
                if ($webpPath) {
                    $webpSize = Storage::disk('public')->size($webpPath);
                    $savedBytes = $originalSize - $webpSize;
                    
                    $results['processed']++;
                    $results['saved_bytes'] += $savedBytes;
                }
            } catch (\Exception $e) {
                $results['errors'][] = [
                    'file' => $file,
                    'error' => $e->getMessage(),
                ];
            }
            
            $processed++;
        }
        
        return $results;
    }
}