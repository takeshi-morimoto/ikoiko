<?php
// WebP画像を使用するようHTMLを更新するヘルパースクリプト

// 更新が必要な主要な画像
$imagesToUpdate = [
    [
        'original' => '/img/4koma.gif',
        'webp' => '/img/4koma.webp',
        'locations' => ['index.php', 'manga関連ページ']
    ],
    [
        'original' => '/img/Japan/4.jpg',
        'webp' => '/img/Japan/4.webp',
        'locations' => ['場所を特定する必要あり']
    ],
    [
        'original' => '/img/banners/yoruuuuutop.jpg',
        'webp' => '/img/banners/yoruuuuutop.webp',
        'locations' => ['バナー表示箇所']
    ]
];

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>WebP画像HTML更新ガイド</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .code-block { 
            background: #f5f5f5; 
            border: 1px solid #ddd; 
            padding: 15px; 
            margin: 10px 0; 
            overflow-x: auto;
            border-radius: 5px;
        }
        .before { background-color: #ffe4e1; }
        .after { background-color: #e1ffe4; }
        h3 { color: #333; margin-top: 30px; }
        .warning { 
            background: #fff3cd; 
            padding: 15px; 
            border-left: 4px solid #ffc107; 
            margin: 20px 0;
        }
        .search-btn {
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
        .search-btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>WebP画像のHTML更新ガイド</h1>
    
    <div class="warning">
        <strong>重要:</strong> HTML更新前に必ずファイルのバックアップを取ってください。
    </div>

    <h2>1. 画像の使用箇所を検索</h2>
    <p>まず、変換した画像がどこで使用されているか検索します：</p>
    
    <form method="get" action="">
        <button name="action" value="search" class="search-btn">画像の使用箇所を検索</button>
    </form>
    
    <?php if (isset($_GET['action']) && $_GET['action'] === 'search'): ?>
        <h3>検索結果:</h3>
        <?php
        // 画像ファイルの使用箇所を検索
        function searchImageUsage($imagePath, $directory = '.') {
            $results = [];
            $extensions = ['php', 'html', 'htm'];
            
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directory)
            );
            
            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $ext = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                    if (in_array($ext, $extensions)) {
                        $content = file_get_contents($file->getPathname());
                        $imageName = basename($imagePath);
                        
                        // 様々なパターンで検索
                        $patterns = [
                            $imagePath,
                            $imageName,
                            str_replace('/', '\/', $imagePath),
                            'img/' . $imageName,
                            'img/banners/' . $imageName,
                            'img/Japan/' . $imageName
                        ];
                        
                        foreach ($patterns as $pattern) {
                            if (stripos($content, $pattern) !== false) {
                                $relativePath = str_replace(__DIR__ . '/', '', $file->getPathname());
                                if (!in_array($relativePath, $results)) {
                                    $results[] = $relativePath;
                                }
                                break;
                            }
                        }
                    }
                }
            }
            
            return $results;
        }
        
        foreach ($imagesToUpdate as $image):
            $usage = searchImageUsage($image['original']);
        ?>
            <div style="margin: 20px 0; padding: 10px; background: #f9f9f9; border-radius: 5px;">
                <h4><?php echo htmlspecialchars($image['original']); ?></h4>
                <?php if (empty($usage)): ?>
                    <p>使用箇所が見つかりませんでした。</p>
                <?php else: ?>
                    <p>以下のファイルで使用されています：</p>
                    <ul>
                        <?php foreach ($usage as $file): ?>
                            <li><?php echo htmlspecialchars($file); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <h2>2. HTML更新方法</h2>
    
    <h3>方法1: picture要素を使用（推奨）</h3>
    <p>WebP対応ブラウザと非対応ブラウザの両方に対応：</p>
    
    <div class="code-block before">
        <strong>更新前:</strong><br>
        &lt;img src="/img/4koma.gif" alt="4コマ漫画"&gt;
    </div>
    
    <div class="code-block after">
        <strong>更新後:</strong><br>
        &lt;picture&gt;<br>
        &nbsp;&nbsp;&lt;source srcset="/img/4koma.webp" type="image/webp"&gt;<br>
        &nbsp;&nbsp;&lt;source srcset="/img/4koma.gif" type="image/gif"&gt;<br>
        &nbsp;&nbsp;&lt;img src="/img/4koma.gif" alt="4コマ漫画" loading="lazy"&gt;<br>
        &lt;/picture&gt;
    </div>

    <h3>方法2: .htaccessで自動配信（既に設定可能）</h3>
    <p>optimize_images.phpで生成した.htaccess設定を使用すると、自動的にWebP画像が配信されます。</p>

    <h3>具体的な更新例</h3>
    
    <h4>例1: yoruuuuutop.jpg の更新</h4>
    <div class="code-block before">
        <strong>更新前:</strong><br>
        &lt;img src="/img/banners/yoruuuuutop.jpg" alt="よるぅぅぅトップ"&gt;
    </div>
    
    <div class="code-block after">
        <strong>更新後:</strong><br>
        &lt;picture&gt;<br>
        &nbsp;&nbsp;&lt;source srcset="/img/banners/yoruuuuutop.webp" type="image/webp"&gt;<br>
        &nbsp;&nbsp;&lt;source srcset="/img/banners/yoruuuuutop.jpg" type="image/jpeg"&gt;<br>
        &nbsp;&nbsp;&lt;img src="/img/banners/yoruuuuutop.jpg" alt="よるぅぅぅトップ" loading="lazy"&gt;<br>
        &lt;/picture&gt;
    </div>

    <h4>例2: CSSでの背景画像の場合</h4>
    <div class="code-block">
        <strong>CSSの更新:</strong><br>
        .banner {<br>
        &nbsp;&nbsp;background-image: url('/img/banners/yoruuuuutop.jpg');<br>
        }<br><br>
        /* WebP対応ブラウザ用 */<br>
        .webp .banner {<br>
        &nbsp;&nbsp;background-image: url('/img/banners/yoruuuuutop.webp');<br>
        }
    </div>
    
    <p>※ WebP検出用のJavaScriptも必要です：</p>
    <div class="code-block">
        &lt;script&gt;<br>
        // WebP対応チェック<br>
        var webpSupport = false;<br>
        var img = new Image();<br>
        img.onload = function() {<br>
        &nbsp;&nbsp;webpSupport = (img.width > 0) && (img.height > 0);<br>
        &nbsp;&nbsp;if(webpSupport) document.documentElement.classList.add('webp');<br>
        };<br>
        img.src = 'data:image/webp;base64,UklGRiIAAABXRUJQVlA4IBYAAAAwAQCdASoBAAEADsD+JaQAA3AAAAAA';<br>
        &lt;/script&gt;
    </div>

    <h2>3. 遅延読み込み（Lazy Loading）の追加</h2>
    <p>すべての画像に <code>loading="lazy"</code> 属性を追加することで、ページの初期読み込みが高速化されます。</p>

    <h2>4. 変換結果の確認</h2>
    <p>更新後は以下を確認してください：</p>
    <ul>
        <li>Chrome DevToolsのNetworkタブでWebP画像が読み込まれているか</li>
        <li>Safari等の非対応ブラウザで元の画像が表示されるか</li>
        <li>ページの読み込み速度が改善されたか</li>
    </ul>

</body>
</html>