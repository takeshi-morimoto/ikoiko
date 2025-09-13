<?php 
// 出力バッファリングを開始
if (!ob_get_level()) {
    ob_start();
}

// エラーハンドラを設定
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo "エラー[$errno]: $errstr in $errfile on line $errline<br>";
    return true;
});

// シャットダウンハンドラを設定
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        while (ob_get_level()) {
            ob_end_clean();
        }
        echo "致命的エラー: " . $error['message'] . " in " . $error['file'] . " on line " . $error['line'];
    }
});

// エラー表示を有効化（デバッグ用）
ini_set('display_errors', 1);
error_reporting(E_ALL);

//表示する内容の切り替え（フォーム未入力、入力済み、完了画面）
if ( isset($_POST['submit_1']) ):
    $pagePat = 1 ;//フォームにデータが入力された場合・入力確認画面を表示
elseif ( isset($_POST['submit_2']) ):
    $pagePat = 2 ;//確認画面から入力完了ボタンが押された場合・内容をDBに格納
else:
    $pagePat = 0 ;//何も入力されてない場合・入力フォームと一覧表示を出力
endif;

// HTMLを出力する前にすべてのPHP処理を完了させる
$html_output = '';

// パターン0の処理
if ($pagePat === 0) {
    // データベースの初期化
    require_once("../db_data/db_init.php");
}

// 出力バッファをフラッシュ
ob_end_flush();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Document</title>
</head>
<body>

<p style="font-size:40;"><a href="admin.php">コントロールパネルトップにもどる</a></p>

<?php if ( $pagePat === 0 ): ?>

<center>

<hr />
開催地ページを作成します。<br />
イベントを作成するには<a href="event_set.php">こちら</a>
<hr />

<form action="area_set.php" method="post" enctype="multipart/form-data">
    <table border="1" width="80%">
        <tbody>
            <tr>
                <th width="" height="50px">ページ設定</th>
                <td width="">
                    <select name="data_01">
                        <option>選択してください</option>
                        <option value="ani">アニメ</option>
                        <option value="machi">街コン</option>
                        <option value="nazo">謎解き</option>
                        <option value="off">オフ会</option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <center><p>
    <input type="submit" name="submit_1" value="送信">
    </p></center>
</form>

<hr />
動作確認: area_set_fixed.php は正常に表示されています。
<hr />

<?php endif; ?>

</body>
</html>