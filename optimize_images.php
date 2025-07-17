<?php
// 画像最適化スクリプト
// 画像ファイルの分析と最適化レポート生成

$baseDir = __DIR__;
$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$largeImages = [];
$totalSize = 0;
$imageCount = 0;

// 画像ファイルを再帰的に検索
function scanImages($dir, &$largeImages, &$totalSize, &$imageCount, $imageExtensions) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            // admin_settingsディレクトリはスキップ
            if (basename($path) !== 'admin_settings') {
                scanImages($path, $largeImages, $totalSize, $imageCount, $imageExtensions);
            }
        } else {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, $imageExtensions)) {
                $size = filesize($path);
                $totalSize += $size;
                $imageCount++;
                
                // 100KB以上の画像をリストアップ
                if ($size > 100 * 1024) {
                    $largeImages[] = [
                        'path' => str_replace(__DIR__, '', $path),
                        'name' => $file,
                        'size' => $size,
                        'size_mb' => round($size / 1024 / 1024, 2),
                        'size_kb' => round($size / 1024, 0),
                        'type' => $ext
                    ];
                }
            }
        }
    }
}

scanImages($baseDir, $largeImages, $totalSize, $imageCount, $imageExtensions);

// サイズでソート（大きい順）
usort($largeImages, function($a, $b) {
    return $b['size'] - $a['size'];
});

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>画像最適化レポート</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        .summary { background: #f5f5f5; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .warning { background: #fff3cd; padding: 10px; border-left: 4px solid #ffc107; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f0f0f0; }
        .size-large { color: #d9534f; font-weight: bold; }
        .size-medium { color: #f0ad4e; }
        .size-small { color: #5cb85c; }
        .actions { margin-top: 30px; background: #e7f3ff; padding: 20px; border-radius: 5px; }
        .optimize-btn { 
            background: #0275d8; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 3px; 
            cursor: pointer; 
            margin: 5px;
        }
        .optimize-btn:hover { background: #025aa5; }
    </style>
</head>
<body>
    <h1>画像最適化レポート</h1>
    
    <div class="summary">
        <h2>サマリー</h2>
        <p>総画像数: <?php echo number_format($imageCount); ?>枚</p>
        <p>総容量: <?php echo round($totalSize / 1024 / 1024, 2); ?> MB</p>
        <p>100KB以上の画像: <?php echo count($largeImages); ?>枚</p>
    </div>
    
    <div class="warning">
        <strong>⚠️ 注意:</strong> 最適化前に必ずバックアップを取ってください。
    </div>
    
    <h2>最適化が必要な画像（100KB以上）</h2>
    <table>
        <tr>
            <th>ファイル名</th>
            <th>パス</th>
            <th>サイズ</th>
            <th>形式</th>
            <th>推奨アクション</th>
        </tr>
        <?php foreach ($largeImages as $image): ?>
        <tr>
            <td><?php echo htmlspecialchars($image['name']); ?></td>
            <td><?php echo htmlspecialchars($image['path']); ?></td>
            <td class="<?php 
                if ($image['size_kb'] > 1000) echo 'size-large';
                elseif ($image['size_kb'] > 500) echo 'size-medium';
                else echo 'size-small';
            ?>">
                <?php 
                if ($image['size_mb'] >= 1) {
                    echo $image['size_mb'] . ' MB';
                } else {
                    echo $image['size_kb'] . ' KB';
                }
                ?>
            </td>
            <td><?php echo strtoupper($image['type']); ?></td>
            <td>
                <?php
                if ($image['type'] === 'gif' && $image['size_mb'] > 1) {
                    echo 'MP4動画への変換を推奨';
                } elseif ($image['size_kb'] > 1000) {
                    echo '圧縮必須 + WebP変換推奨';
                } elseif ($image['size_kb'] > 500) {
                    echo '圧縮推奨 + WebP変換検討';
                } else {
                    echo 'WebP変換検討';
                }
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <div class="actions">
        <h2>最適化の実施方法</h2>
        
        <h3>1. 画像圧縮ツール（ローカル）</h3>
        <p>以下のツールで画像を圧縮してからアップロード：</p>
        <ul>
            <li><a href="https://tinypng.com/" target="_blank">TinyPNG</a> - PNG/JPGの圧縮</li>
            <li><a href="https://squoosh.app/" target="_blank">Squoosh</a> - Google製、WebP変換も可能</li>
            <li><a href="https://imageoptim.com/" target="_blank">ImageOptim</a> - Mac用</li>
        </ul>
        
        <h3>2. サーバー側での自動最適化</h3>
        <button class="optimize-btn" onclick="window.location.href='?action=create_htaccess'">
            .htaccessにWebP配信設定を追加
        </button>
        
        <h3>3. 遅延読み込みの実装</h3>
        <button class="optimize-btn" onclick="window.location.href='?action=create_lazyload'">
            画像遅延読み込みスクリプトを生成
        </button>
    </div>
    
    <?php
    // アクション処理
    if (isset($_GET['action'])) {
        if ($_GET['action'] === 'create_htaccess') {
            $htaccess_content = '
# WebP画像の自動配信
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # WebPをサポートするブラウザの検出
    RewriteCond %{HTTP_ACCEPT} image/webp
    
    # WebPファイルが存在する場合は配信
    RewriteCond %{REQUEST_FILENAME}.webp -f
    RewriteRule ^(.+)\.(jpe?g|png)$ $1.$2.webp [T=image/webp,E=REQUEST_image]
    
    # WebP画像のContent-Typeヘッダー
    <IfModule mod_headers.c>
        Header append Vary Accept env=REQUEST_image
    </IfModule>
</IfModule>

# 画像の圧縮設定
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
</IfModule>
';
            echo '<div style="background: #d4edda; padding: 20px; margin-top: 20px; border-radius: 5px;">';
            echo '<h3>✅ .htaccess追記内容</h3>';
            echo '<p>以下の内容を.htaccessファイルに追加してください：</p>';
            echo '<pre style="background: white; padding: 15px; overflow-x: auto;">' . htmlspecialchars($htaccess_content) . '</pre>';
            echo '</div>';
        }
        
        if ($_GET['action'] === 'create_lazyload') {
            $lazyload_script = '
<!-- 画像遅延読み込みスクリプト -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // loading="lazy"属性を全ての画像に追加
    var images = document.querySelectorAll("img");
    images.forEach(function(img) {
        if (!img.hasAttribute("loading")) {
            img.setAttribute("loading", "lazy");
        }
    });
    
    // IntersectionObserver APIを使用した高度な遅延読み込み
    if ("IntersectionObserver" in window) {
        var imageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var image = entry.target;
                    if (image.dataset.src) {
                        image.src = image.dataset.src;
                        image.removeAttribute("data-src");
                        imageObserver.unobserve(image);
                    }
                }
            });
        }, {
            rootMargin: "50px 0px" // 50px手前から読み込み開始
        });
        
        // data-src属性を持つ画像を監視
        document.querySelectorAll("img[data-src]").forEach(function(img) {
            imageObserver.observe(img);
        });
    }
});
</script>

<!-- 使用例 -->
<!-- <img data-src="path/to/image.jpg" loading="lazy" alt="説明"> -->
';
            echo '<div style="background: #d4edda; padding: 20px; margin-top: 20px; border-radius: 5px;">';
            echo '<h3>✅ 画像遅延読み込みスクリプト</h3>';
            echo '<p>以下のスクリプトをHTMLの&lt;/body&gt;タグの直前に追加してください：</p>';
            echo '<pre style="background: white; padding: 15px; overflow-x: auto;">' . htmlspecialchars($lazyload_script) . '</pre>';
            echo '</div>';
        }
    }
    ?>
</body>
</html>