<?php
// 出力バッファリングのテスト
echo "出力バッファリングテスト開始<br>";

// 現在の出力バッファリングの状態
echo "ob_get_level(): " . ob_get_level() . "<br>";
echo "ob_get_status(): ";
print_r(ob_get_status());
echo "<br>";

// ヘッダーが送信済みかチェック
if (headers_sent($file, $line)) {
    echo "ヘッダーは既に送信されています: $file:$line<br>";
} else {
    echo "ヘッダーはまだ送信されていません<br>";
}

// PHPの設定確認
echo "output_buffering: " . ini_get('output_buffering') . "<br>";
echo "implicit_flush: " . ini_get('implicit_flush') . "<br>";

// セッションの状態
echo "session_status(): ";
switch (session_status()) {
    case PHP_SESSION_DISABLED:
        echo "セッション無効<br>";
        break;
    case PHP_SESSION_NONE:
        echo "セッション未開始<br>";
        break;
    case PHP_SESSION_ACTIVE:
        echo "セッションアクティブ<br>";
        break;
}

// includeパスの確認
echo "include_path: " . ini_get('include_path') . "<br>";
?>