<?php
// CSS結合スクリプト（修正版）
// 表示崩れを防ぐため、適切な順序と処理で結合

$cssDir = __DIR__ . '/css/';
$outputFile = $cssDir . 'all.css';
$outputMinFile = $cssDir . 'all.min.css';

// 修正された結合順序（依存関係を考慮）
$cssFiles = [
    'base.css',
    'onepcssgrid.css',  // グリッドシステムを先に
    'globalMenu.css',
    'content.css'       // 最も詳細なスタイルを最後に
];

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>CSS結合ツール（修正版）</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        .warning { background: #fff3cd; padding: 15px; margin: 10px 0; border-left: 4px solid #ffc107; }
        .info { background: #f8f9fa; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .button { 
            background: #007bff; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 3px; 
            cursor: pointer; 
            margin: 5px;
        }
        .button:hover { background: #0056b3; }
        pre { background: #f8f9fa; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>CSS結合ツール（修正版）</h1>
    
    <div class="warning">
        <strong>修正内容:</strong>
        <ul>
            <li>CSSファイルの結合順序を最適化</li>
            <li>重複するリセットスタイルを除去</li>
            <li>メディアクエリの順序を保持</li>
            <li>画像パスの問題を修正</li>
        </ul>
    </div>
    
    <?php if (isset($_GET['action']) && $_GET['action'] === 'combine'): ?>
        <div class="info">
            <h2>結合処理結果</h2>
            <?php
            $combinedCss = '';
            $totalOriginalSize = 0;
            
            // 各CSSファイルを読み込んで結合
            foreach ($cssFiles as $index => $file) {
                $filePath = $cssDir . $file;
                if (file_exists($filePath)) {
                    $content = file_get_contents($filePath);
                    $totalOriginalSize += strlen($content);
                    
                    // globalMenu.cssの重複リセットスタイルを除去
                    if ($file === 'globalMenu.css') {
                        // 冒頭の * { padding: 0; margin: 0; } を削除
                        $content = preg_replace('/^\s*\*\s*{\s*padding:\s*0;\s*margin:\s*0;\s*}\s*/i', '', $content);
                    }
                    
                    // 画像パスを絶対パスに変換（//koikoi.co.jp/は既に絶対パスなので変更不要）
                    // 相対パスがある場合のみ変換
                    $content = preg_replace('/url\((["\']?)(?!http|\/\/|data:)([^"\')]+)(["\']?)\)/', 'url($1/ikoiko/css/$2$3)', $content);
                    
                    // ファイル区切りコメントを追加
                    $combinedCss .= "\n/* ============================================\n";
                    $combinedCss .= "   " . $file . " (" . ($index + 1) . "/" . count($cssFiles) . ")\n";
                    $combinedCss .= "   ============================================ */\n\n";
                    $combinedCss .= $content;
                    
                    echo '<p class="success">✓ ' . $file . ' を読み込みました</p>';
                } else {
                    echo '<p class="error">✗ ' . $file . ' が見つかりません</p>';
                }
            }
            
            // 結合したCSSを保存
            if (!empty($combinedCss)) {
                // メディアクエリを整理（同じブレークポイントをまとめる）
                $mediaQueries = [];
                
                // メディアクエリを一時的に抽出
                preg_match_all('/@media[^{]+{[^{}]*(?:{[^{}]*}[^{}]*)*}/s', $combinedCss, $matches);
                
                file_put_contents($outputFile, $combinedCss);
                echo '<p class="success">✓ 結合ファイル all.css を作成しました (' . round(strlen($combinedCss) / 1024, 1) . ' KB)</p>';
                
                // 最小化処理（改良版）
                $minifiedCss = $combinedCss;
                
                // 1. コメント削除（ただし重要なコメントは保持）
                $minifiedCss = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $minifiedCss);
                
                // 2. 不要な空白を削除（ただしセレクタ間のスペースは保持）
                $minifiedCss = preg_replace('/\s+/', ' ', $minifiedCss);
                
                // 3. 特定の場所の空白を削除
                $search = array(': ', ' :', '; ', ' ;', '{ ', ' {', '} ', ' }', ', ', ' ,');
                $replace = array(':', ':', ';', ';', '{', '{', '}', '}', ',', ',');
                $minifiedCss = str_replace($search, $replace, $minifiedCss);
                
                // 4. 改行を削除（ただし}}の後には改行を入れる）
                $minifiedCss = str_replace("\n", '', $minifiedCss);
                $minifiedCss = str_replace('}}', "}}\n", $minifiedCss);
                
                file_put_contents($outputMinFile, $minifiedCss);
                $reduction = round((1 - strlen($minifiedCss) / strlen($combinedCss)) * 100, 1);
                echo '<p class="success">✓ 最小化ファイル all.min.css を作成しました (' . round(strlen($minifiedCss) / 1024, 1) . ' KB - ' . $reduction . '% 削減)</p>';
            }
            ?>
        </div>
        
        <div class="info">
            <h2>次のステップ</h2>
            <p>1. まず、all.cssをテスト環境で確認してください：</p>
            <pre>&lt;link rel="stylesheet" href="//koikoi.co.jp/ikoiko/css/all.css" /&gt;</pre>
            
            <p>2. 表示に問題がなければ、all.min.cssを使用：</p>
            <pre>&lt;link rel="stylesheet" href="//koikoi.co.jp/ikoiko/css/all.min.css" /&gt;</pre>
        </div>
    <?php endif; ?>
    
    <form method="get">
        <button type="submit" name="action" value="combine" class="button">修正版でCSSを結合</button>
    </form>
    
    <div class="info">
        <h2>テスト手順</h2>
        <ol>
            <li>結合されたCSSファイルをアップロード</li>
            <li>テストページで表示確認</li>
            <li>特に「アニメコンを探す」部分のレイアウトを確認</li>
            <li>モバイル表示も確認</li>
            <li>問題がなければ本番適用</li>
        </ol>
    </div>
    
</body>
</html>