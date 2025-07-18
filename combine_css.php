<?php
// CSS結合スクリプト
// 複数のCSSファイルを1つに結合して最適化

$cssDir = __DIR__ . '/css/';
$outputFile = $cssDir . 'all.css';
$outputMinFile = $cssDir . 'all.min.css';

// 結合する順序（依存関係を考慮）
$cssFiles = [
    'base.css',
    'globalMenu.css', 
    'onepcssgrid.css',
    'content.css'
];

// ページ固有のCSS（別途読み込み）
$pageSpecificCss = [
    'admin.css' => '管理画面用',
    'shop.css' => 'ショップページ用',
    'shop_mb.css' => 'ショップモバイル用',
    'manga.css' => '漫画ページ用',
    'footer.css' => 'フッター用（結合候補）',
    'chosen.min.css' => '外部ライブラリ（結合しない）'
];

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>CSS結合ツール</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: #28a745; }
        .error { color: #dc3545; }
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
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h1>CSS結合ツール</h1>
    
    <div class="info">
        <h2>結合対象のCSSファイル</h2>
        <ol>
            <?php foreach ($cssFiles as $file): ?>
                <li><?php echo $file; ?> 
                    <?php 
                    if (file_exists($cssDir . $file)) {
                        $size = filesize($cssDir . $file);
                        echo '(' . round($size / 1024, 1) . ' KB)';
                    } else {
                        echo '<span class="error">- ファイルが見つかりません</span>';
                    }
                    ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
    
    <div class="info">
        <h2>ページ固有のCSS（結合しない）</h2>
        <table>
            <tr>
                <th>ファイル名</th>
                <th>用途</th>
                <th>サイズ</th>
            </tr>
            <?php foreach ($pageSpecificCss as $file => $usage): ?>
            <tr>
                <td><?php echo $file; ?></td>
                <td><?php echo $usage; ?></td>
                <td>
                    <?php 
                    if (file_exists($cssDir . $file)) {
                        $size = filesize($cssDir . $file);
                        echo round($size / 1024, 1) . ' KB';
                    } else {
                        echo '-';
                    }
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    
    <?php if (isset($_GET['action']) && $_GET['action'] === 'combine'): ?>
        <div class="info">
            <h2>結合処理結果</h2>
            <?php
            $combinedCss = '';
            $totalOriginalSize = 0;
            
            // 各CSSファイルを読み込んで結合
            foreach ($cssFiles as $file) {
                $filePath = $cssDir . $file;
                if (file_exists($filePath)) {
                    $content = file_get_contents($filePath);
                    $totalOriginalSize += strlen($content);
                    
                    // ファイル区切りコメントを追加
                    $combinedCss .= "\n/* ============================================\n";
                    $combinedCss .= "   " . $file . "\n";
                    $combinedCss .= "   ============================================ */\n\n";
                    $combinedCss .= $content;
                    
                    echo '<p class="success">✓ ' . $file . ' を読み込みました</p>';
                } else {
                    echo '<p class="error">✗ ' . $file . ' が見つかりません</p>';
                }
            }
            
            // 結合したCSSを保存
            if (!empty($combinedCss)) {
                file_put_contents($outputFile, $combinedCss);
                echo '<p class="success">✓ 結合ファイル all.css を作成しました (' . round(strlen($combinedCss) / 1024, 1) . ' KB)</p>';
                
                // 簡易的な最小化（本格的な最小化は別ツールを推奨）
                $minifiedCss = $combinedCss;
                // コメント削除
                $minifiedCss = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $minifiedCss);
                // 改行・タブ・余分なスペース削除
                $minifiedCss = str_replace(array("\r\n", "\r", "\n", "\t"), '', $minifiedCss);
                $minifiedCss = preg_replace('/\s+/', ' ', $minifiedCss);
                $minifiedCss = str_replace(array('; ', ': ', ' {', '{ ', ' }', '} ', ': '), array(';', ':', '{', '{', '}', '}', ':'), $minifiedCss);
                
                file_put_contents($outputMinFile, $minifiedCss);
                $reduction = round((1 - strlen($minifiedCss) / strlen($combinedCss)) * 100, 1);
                echo '<p class="success">✓ 最小化ファイル all.min.css を作成しました (' . round(strlen($minifiedCss) / 1024, 1) . ' KB - ' . $reduction . '% 削減)</p>';
            }
            ?>
        </div>
        
        <div class="info">
            <h2>次のステップ</h2>
            <p>outputHead.phpを以下のように更新してください：</p>
            <pre>&lt;!-- 変更前 --&gt;
&lt;link rel="stylesheet" href="//koikoi.co.jp/ikoiko/css/base.css" /&gt;
&lt;link rel="stylesheet" href="//koikoi.co.jp/ikoiko/css/content.css" /&gt;
&lt;link rel="stylesheet" href="//koikoi.co.jp/ikoiko/css/globalMenu.css" /&gt;
&lt;link rel="stylesheet" href="//koikoi.co.jp/ikoiko/css/onepcssgrid.css" /&gt;

&lt;!-- 変更後 --&gt;
&lt;link rel="stylesheet" href="//koikoi.co.jp/ikoiko/css/all.min.css" /&gt;</pre>
        </div>
    <?php endif; ?>
    
    <form method="get">
        <button type="submit" name="action" value="combine" class="button">CSSファイルを結合</button>
    </form>
    
    <div class="info">
        <h2>推奨事項</h2>
        <ul>
            <li><strong>footer.css</strong> (958B) は小さいので、content.cssに統合することを推奨</li>
            <li>本格的な最小化には <a href="https://cssnano.co/" target="_blank">cssnano</a> や <a href="https://github.com/css/csso" target="_blank">CSSO</a> を使用</li>
            <li>結合後は必ず全ページで表示確認を実施</li>
        </ul>
    </div>
    
</body>
</html>