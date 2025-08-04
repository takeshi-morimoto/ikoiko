<?php

namespace App\Console\Commands;

use App\Services\ImageOptimizationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeImages extends Command
{
    /**
     * コマンドのシグネチャ
     */
    protected $signature = 'images:optimize 
                            {--path=images : 処理するディレクトリ}
                            {--limit=100 : 一度に処理する画像数}
                            {--webp : WebP形式への変換を実行}
                            {--analyze : 画像の分析のみ実行}';

    /**
     * コマンドの説明
     */
    protected $description = '画像を最適化してWebP形式に変換';

    protected ImageOptimizationService $imageService;

    public function __construct(ImageOptimizationService $imageService)
    {
        parent::__construct();
        $this->imageService = $imageService;
    }

    /**
     * コマンドの実行
     */
    public function handle()
    {
        $path = $this->option('path');
        $limit = (int) $this->option('limit');
        
        if ($this->option('analyze')) {
            $this->analyzeImages($path);
        } elseif ($this->option('webp')) {
            $this->convertToWebP($path, $limit);
        } else {
            $this->optimizeImages($path, $limit);
        }
        
        return Command::SUCCESS;
    }
    
    /**
     * 画像の分析
     */
    protected function analyzeImages(string $path)
    {
        $this->info("画像を分析中: {$path}");
        
        $files = Storage::disk('public')->files($path, true);
        $stats = [
            'total_files' => 0,
            'total_size' => 0,
            'formats' => [],
            'large_files' => [],
            'missing_webp' => [],
        ];
        
        $progressBar = $this->output->createProgressBar(count($files));
        $progressBar->start();
        
        foreach ($files as $file) {
            if (!preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file)) {
                continue;
            }
            
            $size = Storage::disk('public')->size($file);
            $format = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            
            $stats['total_files']++;
            $stats['total_size'] += $size;
            
            if (!isset($stats['formats'][$format])) {
                $stats['formats'][$format] = ['count' => 0, 'size' => 0];
            }
            $stats['formats'][$format]['count']++;
            $stats['formats'][$format]['size'] += $size;
            
            // 1MB以上の大きいファイル
            if ($size > 1024 * 1024) {
                $stats['large_files'][] = [
                    'file' => $file,
                    'size' => $this->formatBytes($size),
                ];
            }
            
            // WebP版が存在しない画像
            if ($format !== 'webp') {
                $webpPath = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $file);
                if (!Storage::disk('public')->exists($webpPath)) {
                    $stats['missing_webp'][] = $file;
                }
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        // 統計情報を表示
        $this->displayStats($stats);
    }
    
    /**
     * WebP形式への変換
     */
    protected function convertToWebP(string $path, int $limit)
    {
        $this->info("WebP形式への変換を開始: {$path}");
        
        $results = $this->imageService->batchOptimize($path, $limit);
        
        $this->newLine();
        $this->info("変換完了:");
        $this->table(
            ['項目', '値'],
            [
                ['処理済み画像数', $results['processed']],
                ['削減サイズ', $this->formatBytes($results['saved_bytes'])],
                ['エラー数', count($results['errors'])],
            ]
        );
        
        if (!empty($results['errors'])) {
            $this->error("\nエラーが発生した画像:");
            foreach ($results['errors'] as $error) {
                $this->line("  - {$error['file']}: {$error['error']}");
            }
        }
    }
    
    /**
     * 画像の最適化
     */
    protected function optimizeImages(string $path, int $limit)
    {
        $this->info("画像の最適化を開始: {$path}");
        
        $files = Storage::disk('public')->files($path, true);
        $processed = 0;
        $totalSaved = 0;
        
        $progressBar = $this->output->createProgressBar(min(count($files), $limit));
        $progressBar->start();
        
        foreach ($files as $file) {
            if ($processed >= $limit) {
                break;
            }
            
            if (!preg_match('/\.(jpg|jpeg|png)$/i', $file)) {
                continue;
            }
            
            try {
                $originalSize = Storage::disk('public')->size($file);
                
                // WebP版を作成
                $webpPath = $this->imageService->convertToWebP($file);
                
                if ($webpPath) {
                    $webpSize = Storage::disk('public')->size($webpPath);
                    $saved = $originalSize - $webpSize;
                    $totalSaved += $saved;
                    
                    $this->info("\n  {$file} -> {$webpPath} (削減: {$this->formatBytes($saved)})");
                }
                
                $processed++;
                $progressBar->advance();
            } catch (\Exception $e) {
                $this->error("\n  エラー: {$file} - {$e->getMessage()}");
            }
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        $this->info("最適化完了:");
        $this->info("  処理済み: {$processed} 画像");
        $this->info("  削減サイズ: {$this->formatBytes($totalSaved)}");
    }
    
    /**
     * 統計情報の表示
     */
    protected function displayStats(array $stats)
    {
        $this->info("画像分析結果:");
        $this->newLine();
        
        // 全体統計
        $this->table(
            ['項目', '値'],
            [
                ['総ファイル数', $stats['total_files']],
                ['総サイズ', $this->formatBytes($stats['total_size'])],
                ['WebP未変換', count($stats['missing_webp'])],
                ['大容量ファイル (>1MB)', count($stats['large_files'])],
            ]
        );
        
        // フォーマット別統計
        if (!empty($stats['formats'])) {
            $this->newLine();
            $this->info("フォーマット別統計:");
            
            $formatData = [];
            foreach ($stats['formats'] as $format => $data) {
                $formatData[] = [
                    strtoupper($format),
                    $data['count'],
                    $this->formatBytes($data['size']),
                    round(($data['count'] / $stats['total_files']) * 100, 1) . '%',
                ];
            }
            
            $this->table(
                ['フォーマット', 'ファイル数', 'サイズ', '割合'],
                $formatData
            );
        }
        
        // 大容量ファイルのリスト
        if (!empty($stats['large_files'])) {
            $this->newLine();
            $this->warn("大容量ファイル (最適化推奨):");
            foreach (array_slice($stats['large_files'], 0, 10) as $file) {
                $this->line("  - {$file['file']} ({$file['size']})");
            }
            
            if (count($stats['large_files']) > 10) {
                $this->line("  ... 他 " . (count($stats['large_files']) - 10) . " ファイル");
            }
        }
        
        // WebP未変換ファイル
        if (!empty($stats['missing_webp'])) {
            $this->newLine();
            $this->warn("WebP未変換ファイル数: " . count($stats['missing_webp']));
            $this->info("  `php artisan images:optimize --webp` で変換できます");
        }
    }
    
    /**
     * バイト数を読みやすい形式に変換
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}