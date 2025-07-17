<?php
// WebP変換スクリプト
// ImageMagickまたはGDライブラリを使用して画像をWebPに変換

ini_set('max_execution_time', 300); // 5分のタイムアウト
ini_set('memory_limit', '256M');

// 変換対象の画像を取得
$targetImages = [
    '/img/4koma.gif' => ['priority' => 'high', 'size_mb' => 2.8],
    '/img/Japan/4.jpg' => ['priority' => 'high', 'size_mb' => 2.6],
    '/img/banners/yoruuuuutop.jpg' => ['priority' => 'high', 'size_mb' => 1.6],
];

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>WebP画像変換ツール</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .converter { background: #f5f5f5; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        .warning { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin-bottom: 20px; }
        pre { background: #f8f9fa; padding: 10px; overflow-x: auto; }
        .button { 
            background: #007bff; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 3px; 
            cursor: pointer; 
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }
        .button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>WebP画像変換ツール</h1>
    
    <div class="warning">
        <strong>⚠️ 重要:</strong> 
        <ul>
            <li>変換前に必ず元画像のバックアップを取ってください</li>
            <li>WebP形式はIE11以前のブラウザではサポートされていません</li>
            <li>大きな画像の変換には時間がかかる場合があります</li>
        </ul>
    </div>
    
    <?php
    // GDライブラリのWebPサポート確認
    $gdInfo = gd_info();
    $webpSupport = isset($gdInfo['WebP Support']) && $gdInfo['WebP Support'];
    
    // ImageMagickの確認
    $imagickAvailable = extension_loaded('imagick');
    if ($imagickAvailable) {
        $imagick = new Imagick();
        $imagickFormats = $imagick->queryFormats();
        $imagickWebpSupport = in_array('WEBP', $imagickFormats);
    }
    ?>
    
    <div class="converter">
        <h2>変換環境の確認</h2>
        <ul>
            <li>GDライブラリ: <?php echo $webpSupport ? '<span class="success">✓ WebP対応</span>' : '<span class="error">✗ WebP非対応</span>'; ?></li>
            <li>ImageMagick: <?php echo ($imagickAvailable && $imagickWebpSupport) ? '<span class="success">✓ WebP対応</span>' : '<span class="error">✗ WebP非対応または未インストール</span>'; ?></li>
        </ul>
    </div>
    
    <?php if (!$webpSupport && (!$imagickAvailable || !$imagickWebpSupport)): ?>
        <div class="error">
            <h3>エラー: WebP変換がサポートされていません</h3>
            <p>サーバーがWebP形式をサポートしていないため、変換を実行できません。</p>
            <p>代替案として、以下のオンラインツールを使用してください：</p>
            <ul>
                <li><a href="https://squoosh.app/" target="_blank">Squoosh (Google製)</a></li>
                <li><a href="https://cloudconvert.com/jpg-to-webp" target="_blank">CloudConvert</a></li>
            </ul>
        </div>
    <?php else: ?>
        
        <?php if (isset($_GET['convert'])): ?>
            <div class="converter">
                <h2>変換実行中...</h2>
                <?php
                // 実際の変換処理
                function convertToWebP($sourcePath, $quality = 80) {
                    global $webpSupport, $imagickAvailable, $imagickWebpSupport;
                    
                    $sourceFullPath = __DIR__ . $sourcePath;
                    if (!file_exists($sourceFullPath)) {
                        return ['success' => false, 'message' => 'ファイルが見つかりません: ' . $sourcePath];
                    }
                    
                    $pathInfo = pathinfo($sourceFullPath);
                    $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
                    
                    // ImageMagickを優先的に使用
                    if ($imagickAvailable && $imagickWebpSupport) {
                        try {
                            $imagick = new Imagick($sourceFullPath);
                            $imagick->setImageFormat('webp');
                            $imagick->setImageCompressionQuality($quality);
                            $imagick->writeImage($webpPath);
                            $imagick->destroy();
                            
                            $originalSize = filesize($sourceFullPath);
                            $webpSize = filesize($webpPath);
                            $reduction = round((1 - $webpSize / $originalSize) * 100, 1);
                            
                            return [
                                'success' => true,
                                'message' => 'ImageMagickで変換成功',
                                'original_size' => round($originalSize / 1024 / 1024, 2),
                                'webp_size' => round($webpSize / 1024 / 1024, 2),
                                'reduction' => $reduction
                            ];
                        } catch (Exception $e) {
                            return ['success' => false, 'message' => 'ImageMagickエラー: ' . $e->getMessage()];
                        }
                    }
                    
                    // GDライブラリを使用
                    if ($webpSupport) {
                        $imageInfo = getimagesize($sourceFullPath);
                        $mimeType = $imageInfo['mime'];
                        
                        switch ($mimeType) {
                            case 'image/jpeg':
                                $image = imagecreatefromjpeg($sourceFullPath);
                                break;
                            case 'image/png':
                                $image = imagecreatefrompng($sourceFullPath);
                                break;
                            case 'image/gif':
                                $image = imagecreatefromgif($sourceFullPath);
                                break;
                            default:
                                return ['success' => false, 'message' => 'サポートされていない画像形式: ' . $mimeType];
                        }
                        
                        if ($image) {
                            imagewebp($image, $webpPath, $quality);
                            imagedestroy($image);
                            
                            $originalSize = filesize($sourceFullPath);
                            $webpSize = filesize($webpPath);
                            $reduction = round((1 - $webpSize / $originalSize) * 100, 1);
                            
                            return [
                                'success' => true,
                                'message' => 'GDライブラリで変換成功',
                                'original_size' => round($originalSize / 1024 / 1024, 2),
                                'webp_size' => round($webpSize / 1024 / 1024, 2),
                                'reduction' => $reduction
                            ];
                        }
                    }
                    
                    return ['success' => false, 'message' => '変換に失敗しました'];
                }
                
                // 選択された画像の変換
                if (isset($_GET['image'])) {
                    $imagePath = $_GET['image'];
                    if (isset($targetImages[$imagePath])) {
                        echo "<h3>変換中: " . htmlspecialchars($imagePath) . "</h3>";
                        $result = convertToWebP($imagePath, 85);
                        
                        if ($result['success']) {
                            echo '<div class="success">';
                            echo '<p>✓ ' . $result['message'] . '</p>';
                            echo '<p>元のサイズ: ' . $result['original_size'] . ' MB</p>';
                            echo '<p>WebPサイズ: ' . $result['webp_size'] . ' MB</p>';
                            echo '<p>削減率: ' . $result['reduction'] . '%</p>';
                            echo '</div>';
                        } else {
                            echo '<div class="error">';
                            echo '<p>✗ ' . $result['message'] . '</p>';
                            echo '</div>';
                        }
                    }
                }
                ?>
            </div>
        <?php endif; ?>
        
        <div class="converter">
            <h2>優先度の高い画像</h2>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <th style="padding: 10px; background: #f0f0f0; border: 1px solid #ddd;">ファイル</th>
                    <th style="padding: 10px; background: #f0f0f0; border: 1px solid #ddd;">サイズ</th>
                    <th style="padding: 10px; background: #f0f0f0; border: 1px solid #ddd;">アクション</th>
                </tr>
                <?php foreach ($targetImages as $path => $info): ?>
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($path); ?></td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $info['size_mb']; ?> MB</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        <a href="?convert=1&image=<?php echo urlencode($path); ?>" class="button">WebPに変換</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        
    <?php endif; ?>
    
    <div class="converter">
        <h2>HTMLの更新方法</h2>
        <p>WebP画像を作成した後、HTMLを以下のように更新してください：</p>
        <pre>&lt;picture&gt;
  &lt;source srcset="image.webp" type="image/webp"&gt;
  &lt;source srcset="image.jpg" type="image/jpeg"&gt;
  &lt;img src="image.jpg" alt="説明" loading="lazy"&gt;
&lt;/picture&gt;</pre>
        <p>この方法により、WebP対応ブラウザにはWebPを、非対応ブラウザには元の画像を配信できます。</p>
    </div>
    
</body>
</html>