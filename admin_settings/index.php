<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Settings - デバッグメニュー</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        ul { line-height: 2; }
        a { color: #0066cc; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .section { margin: 20px 0; padding: 10px; border: 1px solid #ddd; }
        .working { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Admin Settings デバッグメニュー</h1>
    
    <div class="section">
        <h2>メインページ</h2>
        <ul>
            <li><a href="admin.php">管理画面トップ</a></li>
            <li><a href="area_set.php">area_set.php</a> <span class="error">(ホワイトスクリーン)</span></li>
            <li><a href="event_set.php">event_set.php</a> <span class="error">(ホワイトスクリーン)</span></li>
        </ul>
    </div>
    
    <div class="section">
        <h2>動作確認済みページ</h2>
        <ul>
            <li><a href="area_set_v2.php">area_set_v2.php</a> <span class="working">(動作OK)</span></li>
            <li><a href="area_set_fixed.php">area_set_fixed.php</a> <span class="working">(動作OK)</span></li>
            <li><a href="area_set_minimal.php">area_set_minimal.php</a> <span class="working">(動作OK)</span></li>
            <li><a href="area_set_test.php">area_set_test.php</a> <span class="working">(動作OK)</span></li>
        </ul>
    </div>
    
    <div class="section">
        <h2>デバッグツール</h2>
        <ul>
            <li><a href="test_db.php">test_db.php</a> - データベース接続テスト</li>
            <li><a href="simple_test.php">simple_test.php</a> - 基本動作確認</li>
            <li><a href="check_syntax.php">check_syntax.php</a> - 構文チェック</li>
            <li><a href="check_files.php">check_files.php</a> - ファイル確認</li>
            <li><a href="check_permissions.php">check_permissions.php</a> - 権限確認</li>
            <li><a href="check_encoding.php">check_encoding.php</a> - エンコーディング確認</li>
            <li><a href="test_output.php">test_output.php</a> - 出力バッファ確認</li>
            <li><a href="test_start.php">test_start.php</a> - 開始テスト</li>
            <li><a href="error_test.php">error_test.php</a> - エラーテスト</li>
            <li><a href="opcache_reset.php">opcache_reset.php</a> - OPcacheリセット</li>
            <li><a href="check_logs.php">check_logs.php</a> - ログ確認</li>
        </ul>
    </div>
</body>
</html>