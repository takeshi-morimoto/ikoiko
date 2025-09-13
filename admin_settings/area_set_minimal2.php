<?php 
// エラー表示を有効化
error_reporting(E_ALL);
ini_set('display_errors', 1);

//表示する内容の切り替え（フォーム未入力、入力済み、完了画面）
if ( isset($_POST['submit_1']) ):
	$pagePat = 1 ;//フォームにデータが入力された場合・入力確認画面を表示
elseif ( isset($_POST['submit_2']) ):
	$pagePat = 2 ;//確認画面から入力完了ボタンが押された場合・内容をDBに格納
else:
	$pagePat = 0 ;//何も入力されてない場合・入力フォームと一覧表示を出力
endif;

echo "area_set_minimal2.php - ここまでOK<br>";
?>