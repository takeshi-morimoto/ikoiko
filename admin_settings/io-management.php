<?php 
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');

$mes = '';

require_once "../db_data/db_init.php";
require 'lib/MysqlBulkUpdate.php';
require 'lib/IOExcelCsv.php';
$db->query("SET NAMES utf8");

if ( isset($_POST['import_e']) || isset($_POST['import_a']) ) {

	try {
		$file = new IOExcelCsv();
		$file->import($_FILES['csv']['tmp_name']);
		$data = $file->fetchAll();

		if ( isset($_POST['import_e']) ) {
			$mbu = new MysqlBulkUpdate($db, 'events');
			$mbu->setLockedCol([ 'find', ]);
			$mbu->update($data, function(&$record){

				// イベントの識別子を生成するフック
				if ( empty($record['number']) ){

					$date = preg_replace("#-|/#", '-', $record['date']);
					strtok($date, '-');
					$m = sprintf( "%02d", strtok('-') );
					$d = sprintf( "%02d", strtok('-') );
					$record['find'] = $record['area'] . $m . $d;
				}
			});

		} elseif ( isset($_POST['import_a']) ){
			$mbu = new MysqlBulkUpdate($db, 'area');
			$mbu->setLockedCol([ 'area', ]);
			$mbu->update($data);

		}

	    $mes = 'インポートが完了しました。';

	} catch (Exception $e) {
	    $mes = 'エラー: ' .  $e->getMessage();
	}

}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>データ入出力</title>
	<link rel="stylesheet" href="https://koikoi.co.jp/ikoiko/css/admin.css">
	<script src="https://koikoi.co.jp/ikoiko/js/jquery-3.1.0.min.js"></script>
	<script>
		$(function(){
			$('#export button').on('click', function(event) {
				event.preventDefault();
				var option = $(this).attr('class');

				$.ajax({
					url: '/ikoiko/admin_settings/ajax/export.php',
					type: 'POST',
					data: { option : option },

				}).done(function(){
					location.href = '/ikoiko/admin_settings/tmp/' + option + '.csv';
				});
				
			});

			// $('form#import').submit(function(){
			$('form#import button').on('click', function() {

				var target = $(this).attr('class');
				if( !window.confirm( target + ' をアップデートします。本当に宜しいですか？') ){
					return false;
				}
			});
		});
		
	</script>
</head>
<body>
	<div id="container" style="max-width: 1080px;margin: auto;">
		<p style="font-size:40;"><a href="admin.php">コントロールパネルトップにもどる</a></p>
		<p style="color: red;"><?= $mes ?></p>
		<h2>エクスポート</h2>
		<p>データをcsv形式でエクスポートします。</p>
		<form id="export">
			<button class="events" type="button">イベントをエクスポート</button>
			<button class="area" type="button">エリアをエクスポート</button>
		</form>

		<h2>インポート</h2>
		<p>csv形式のデータをインポートします。</p>
		<form id="import" action="" method="post" enctype="multipart/form-data">
			<input type="file" name="csv" value="">
			<button class="events" name="import_e" type="submit">イベントをインポート</button>
			<button class="area" name="import_a" type="submit">エリアをインポート</button>
		</form>
		<h3>インポートするデータについて</h3>
		<p>
			通常、インポートしたレコードに対し更新処理が行われます。<br>
			「id」を空欄にした際には新規追加の処理が行われます。<br>
		</p>
		<p>
			何も記載されていないセルは更新されません。（空欄の場合は内容が削除されるのではない点にご注意ください。）<br>
			内容を削除したい場合にはセルの内容に半角感嘆符「!」を記述してください。<br>
			そのレコード自体を削除する場合は下記のフラグを使用します。
		</p>
		<h3>カラム「flag」を追加することによりフラグ指定が可能です。</h3>

		<ul>
			<li>「i」インサートフラグ： 対象のレコードを新規追加します。IDが指定されている場合にも新規追加処理を行います。</li>
			<li>「d」デリートフラグ： 対象のレコードを削除します。</li>
		</ul>
	</div>

</body>
</html>