<?php

namespace App\Http\Controllers;

use App\Services\ImageOptimizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    protected ImageOptimizationService $imageService;
    
    public function __construct(ImageOptimizationService $imageService)
    {
        $this->imageService = $imageService;
    }
    
    /**
     * 画像のアップロード
     */
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,gif|max:10240', // 最大10MB
            'sizes' => 'array',
            'sizes.*' => 'in:thumbnail,small,medium,large,original',
        ]);
        
        $sizes = $request->input('sizes', ['thumbnail', 'medium', 'original']);
        $path = $request->input('path', 'uploads/' . date('Y/m'));
        
        try {
            // 画像を最適化して保存
            $results = $this->imageService->optimizeAndStore(
                $request->file('image'),
                $path,
                $sizes
            );
            
            return response()->json([
                'success' => true,
                'message' => '画像がアップロードされました',
                'data' => $results,
            ]);
        } catch (\Exception $e) {
            \Log::error('Image upload failed', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => '画像のアップロードに失敗しました',
            ], 500);
        }
    }
    
    /**
     * 画像の一括アップロード
     */
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'images' => 'required|array|max:10',
            'images.*' => 'image|mimes:jpeg,jpg,png,gif|max:10240',
        ]);
        
        $results = [];
        $errors = [];
        
        foreach ($request->file('images') as $index => $image) {
            try {
                $result = $this->imageService->optimizeAndStore(
                    $image,
                    'uploads/' . date('Y/m'),
                    ['thumbnail', 'medium']
                );
                
                $results[] = [
                    'index' => $index,
                    'success' => true,
                    'data' => $result,
                ];
            } catch (\Exception $e) {
                $errors[] = [
                    'index' => $index,
                    'filename' => $image->getClientOriginalName(),
                    'error' => $e->getMessage(),
                ];
            }
        }
        
        return response()->json([
            'success' => empty($errors),
            'message' => count($results) . '個の画像を処理しました',
            'uploaded' => $results,
            'errors' => $errors,
        ]);
    }
    
    /**
     * 画像情報の取得
     */
    public function info(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);
        
        $path = $request->input('path');
        
        if (!Storage::disk('public')->exists($path)) {
            return response()->json([
                'success' => false,
                'message' => '画像が見つかりません',
            ], 404);
        }
        
        $info = $this->imageService->getImageInfo($path);
        
        // WebP版の存在確認
        $webpPath = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $path);
        $hasWebp = Storage::disk('public')->exists($webpPath);
        
        return response()->json([
            'success' => true,
            'data' => [
                'info' => $info,
                'has_webp' => $hasWebp,
                'webp_path' => $hasWebp ? $webpPath : null,
                'url' => Storage::url($path),
            ],
        ]);
    }
    
    /**
     * 画像の削除
     */
    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'delete_all_sizes' => 'boolean',
        ]);
        
        $path = $request->input('path');
        $deleteAllSizes = $request->input('delete_all_sizes', false);
        
        if (!Storage::disk('public')->exists($path)) {
            return response()->json([
                'success' => false,
                'message' => '画像が見つかりません',
            ], 404);
        }
        
        try {
            // 指定された画像を削除
            Storage::disk('public')->delete($path);
            
            if ($deleteAllSizes) {
                // 全サイズを削除
                $baseName = pathinfo($path, PATHINFO_FILENAME);
                $directory = dirname($path);
                
                // 関連する全ての画像を検索して削除
                $pattern = $directory . '/*/' . $baseName . '*';
                $relatedFiles = Storage::disk('public')->files($directory);
                
                foreach ($relatedFiles as $file) {
                    if (strpos($file, $baseName) !== false) {
                        Storage::disk('public')->delete($file);
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => '画像を削除しました',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '画像の削除に失敗しました',
            ], 500);
        }
    }
}