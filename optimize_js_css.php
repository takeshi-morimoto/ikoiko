<?php
// CSS/JS最適化スクリプト

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>CSS/JS最適化ガイド</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .issue { background: #fff3cd; padding: 15px; margin: 10px 0; border-left: 4px solid #ffc107; }
        .solution { background: #d4edda; padding: 15px; margin: 10px 0; border-left: 4px solid #28a745; }
        .code { background: #f8f9fa; padding: 10px; margin: 10px 0; font-family: monospace; overflow-x: auto; }
        .warning { color: #dc3545; font-weight: bold; }
        h2 { color: #333; margin-top: 30px; }
    </style>
</head>
<body>
    <h1>CSS/JS最適化ガイド</h1>
    
    <h2>1. jQuery重複の解決</h2>
    
    <div class="issue">
        <strong>問題:</strong> 複数のjQueryバージョンが読み込まれている
        <ul>
            <li>jQuery.js (v1.11.2) - 94KB - メインサイト全体</li>
            <li>jquery-3.7.1.min.js (v3.7.1) - 85KB - 管理画面の一部</li>
            <li>Google CDN jQuery v1.8.2 - 管理画面の一部</li>
        </ul>
    </div>
    
    <div class="solution">
        <strong>解決策:</strong> jQuery v3.7.1に統一
        <ol>
            <li>既存のjQuery.jsを削除</li>
            <li>outputHead.phpでjquery-3.7.1.min.jsを読み込むよう変更</li>
            <li>jQuery移行プラグインを追加（互換性のため）</li>
        </ol>
    </div>
    
    <h3>実装手順:</h3>
    
    <div class="code">
// 1. outputHead.phpの変更
&lt;!-- 変更前 --&gt;
&lt;script type='text/javascript' src='//koikoi.co.jp/ikoiko/js/jQuery.js' defer&gt;&lt;/script&gt;

&lt;!-- 変更後 --&gt;
&lt;script type='text/javascript' src='//koikoi.co.jp/ikoiko/js/jquery-3.7.1.min.js' defer&gt;&lt;/script&gt;
&lt;script type='text/javascript' src='//koikoi.co.jp/ikoiko/js/jquery-migrate-3.4.1.min.js' defer&gt;&lt;/script&gt;
    </div>
    
    <h2>2. CSS/JSの結合と最小化</h2>
    
    <div class="issue">
        <strong>問題:</strong> 複数のCSS/JSファイルが個別に読み込まれている
        <ul>
            <li>CSSファイル: 9個（約80KB）</li>
            <li>JSファイル: 5個（約210KB）</li>
        </ul>
    </div>
    
    <div class="solution">
        <strong>解決策:</strong> ファイルの結合と最小化
    </div>
    
    <h3>CSS結合順序（outputHead.php内）:</h3>
    <div class="code">
1. base.css
2. content.css
3. globalMenu.css
4. onepcssgrid.css
5. その他ページ固有のCSS
    </div>
    
    <h3>推奨ツール:</h3>
    <ul>
        <li><strong>オンライン:</strong> CSS Minifier, JS Compress</li>
        <li><strong>ローカル:</strong> gulp, webpack</li>
        <li><strong>CDN:</strong> Cloudflare（自動最適化）</li>
    </ul>
    
    <h2>3. クリティカルCSSの実装</h2>
    
    <div class="solution">
        <strong>Above-the-fold CSS:</strong> ファーストビューに必要なCSSのみをインライン化
    </div>
    
    <div class="code">
&lt;style&gt;
/* クリティカルCSS - ヘッダー、ナビゲーション、ファーストビューのみ */
body { margin: 0; font-family: sans-serif; }
#pageHeader { /* 最小限のスタイル */ }
/* ... */
&lt;/style&gt;

&lt;!-- 残りのCSSは非同期で読み込み --&gt;
&lt;link rel="preload" href="/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'"&gt;
    </div>
    
    <h2>4. 不要なファイルの削除</h2>
    
    <div class="issue">
        <strong>確認が必要:</strong>
        <ul>
            <li>_content.css と content.css の重複？</li>
            <li>jQuery.js（古いバージョン）</li>
            <li>使用されていないCSS/JSファイル</li>
        </ul>
    </div>
    
    <h2>5. 実装優先順位</h2>
    
    <ol>
        <li class="warning">jQuery重複の解決（最優先）</li>
        <li>不要ファイルの削除</li>
        <li>CSS/JSの結合</li>
        <li>最小化（minify）</li>
        <li>クリティカルCSSの実装</li>
    </ol>
    
    <div class="solution">
        <strong>期待される効果:</strong>
        <ul>
            <li>HTTPリクエスト数の削減</li>
            <li>ファイルサイズ約30-40%削減</li>
            <li>初期表示速度の向上</li>
        </ul>
    </div>
    
</body>
</html>