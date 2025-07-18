<?php
// キャッシュ最適化設定生成ツール
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>キャッシュ最適化設定</title>
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
        .success { color: #28a745; }
        .info { background: #e7f3ff; padding: 15px; margin: 10px 0; border-radius: 5px; }
        h2 { color: #333; margin-top: 30px; }
    </style>
</head>
<body>
    <h1>キャッシュ最適化設定</h1>
    
    <div class="info">
        <h2>✅ 現在のサイト最適化状況</h2>
        <ul>
            <li class="success">OPcache: 有効</li>
            <li class="success">jQuery: v3.1.0に統一（94KB削減）</li>
            <li class="success">画像: 遅延読み込み実装</li>
            <li class="success">Gzip圧縮: 有効</li>
        </ul>
    </div>
    
    <h2>1. ブラウザキャッシュの強化</h2>
    <p>.htaccessに以下を追加して、静的ファイルのキャッシュ期間を延長：</p>
    
    <div class="code-block">
# ブラウザキャッシュの設定（1年間）
&lt;IfModule mod_expires.c&gt;
    ExpiresActive On
    
    # 画像ファイル（1年間）
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
    ExpiresByType image/x-icon "access plus 1 year"
    
    # CSSとJavaScript（1ヶ月）
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType application/x-javascript "access plus 1 month"
    
    # フォント（1年間）
    ExpiresByType font/ttf "access plus 1 year"
    ExpiresByType font/woff "access plus 1 year"
    ExpiresByType font/woff2 "access plus 1 year"
    ExpiresByType application/font-woff "access plus 1 year"
    
    # その他
    ExpiresByType application/pdf "access plus 1 month"
    ExpiresByType video/mp4 "access plus 1 year"
&lt;/IfModule&gt;

# Cache-Controlヘッダー
&lt;IfModule mod_headers.c&gt;
    # 画像ファイル
    &lt;FilesMatch "\.(jpg|jpeg|png|gif|webp|svg|ico)$"&gt;
        Header set Cache-Control "public, max-age=31536000, immutable"
    &lt;/FilesMatch&gt;
    
    # CSS/JS（バージョニング推奨）
    &lt;FilesMatch "\.(css|js)$"&gt;
        Header set Cache-Control "public, max-age=2592000"
    &lt;/FilesMatch&gt;
    
    # HTML（短時間キャッシュ）
    &lt;FilesMatch "\.(html|php)$"&gt;
        Header set Cache-Control "public, max-age=3600, must-revalidate"
    &lt;/FilesMatch&gt;
&lt;/IfModule&gt;
    </div>
    
    <h2>2. ETags最適化</h2>
    <div class="code-block">
# ETagsを無効化（キャッシュの一貫性向上）
FileETag None
    </div>
    
    <h2>3. Keep-Alive接続</h2>
    <div class="code-block">
# Keep-Alive接続を有効化
&lt;IfModule mod_headers.c&gt;
    Header set Connection keep-alive
&lt;/IfModule&gt;
    </div>
    
    <h2>4. リソースヒント（HTMLに追加）</h2>
    <p>重要なリソースを事前に読み込む設定：</p>
    <div class="code-block">
&lt;!-- DNS先読み --&gt;
&lt;link rel="dns-prefetch" href="//koikoi.co.jp"&gt;
&lt;link rel="dns-prefetch" href="//www.googletagmanager.com"&gt;

&lt;!-- 重要なリソースの先読み --&gt;
&lt;link rel="preload" href="/ikoiko/css/base.css" as="style"&gt;
&lt;link rel="preload" href="/ikoiko/js/jquery-3.1.0.min.js" as="script"&gt;

&lt;!-- 次のページの先読み（オプション） --&gt;
&lt;link rel="prefetch" href="/ikoiko/event.php"&gt;
    </div>
    
    <h2>5. サービスワーカー（PWA対応）</h2>
    <p>オフライン対応とさらなる高速化のために：</p>
    <div class="code-block">
// service-worker.js
self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open('v1').then(function(cache) {
            return cache.addAll([
                '/ikoiko/css/base.css',
                '/ikoiko/css/content.css',
                '/ikoiko/css/globalMenu.css',
                '/ikoiko/css/onepcssgrid.css',
                '/ikoiko/js/jquery-3.1.0.min.js',
                '/ikoiko/img/logo.png'
            ]);
        })
    );
});
    </div>
    
    <div class="info">
        <h2>実装優先順位</h2>
        <ol>
            <li><strong>ブラウザキャッシュ設定</strong> - 即効性が高い</li>
            <li><strong>リソースヒント</strong> - 初回読み込みを高速化</li>
            <li><strong>Keep-Alive</strong> - 接続を効率化</li>
            <li><strong>サービスワーカー</strong> - より高度な最適化</li>
        </ol>
    </div>
    
</body>
</html>