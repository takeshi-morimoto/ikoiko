<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>セットアップ</title>
</head>
<body>


<?php 

print "<p><a href='admin.php'>コントロールパネルトップに戻る</a></p>";


//データベースの設定ファイルがあるか確認
$dbInfoExist = is_file('../db_data/db_info/db_info.csv');

if ( $dbInfoExist ):
	
	//データベースの初期化
	require_once("../db_data/db_init.php");
	$db->query("SET NAMES utf8");


endif;


//押されたSubmitボタンによってページのパターンを選択
if ( isset($_POST['dbSet']) ):
	$pagePat = 1 ;
elseif ( isset($_POST['tbSet']) ):
	$pagePat = 2 ;
elseif ( isset($_POST['a']) ):

else:
	$pagePat = 0 ;
endif;


//ボタンが押されてない場合
if ( $pagePat === 0 ):

	//データベースの設定ファイルがあるか確認
	$dbInfoExist = is_file('../db_data/db_info/db_info.csv');
	

	if ( $dbInfoExist == false ):

		print 
			'
			<p>データベースの設定ファイルが存在しません。<br />
			新たに作成してください。</p>
			<form action="setup.php" method="post">
			<table>
			<tr><td>サーバー名：</td><td><input type="text" name="serv" value="" /></td></tr>
			<tr><td>ユーザー名：</td><td><input type="text" name="user" value="" /></td></tr>
			<tr><td>パスワード：</td><td><input type="text" name="pass" value="" /></td></tr>
			<tr><td>データベース名：</td><td><input type="text" name="dbnm" value="" /></td></tr>
			</table>
			<input type="submit" name="dbSet" value="入力完了">
			</form>
			' ;

	else:

		$fh = fopen( '../db_data/db_info/db_info.csv', 'rt' ) ;
		$t = fgets($fh) ;
		
		$SERV = strtok($t, ',');
		$USER = strtok(',');
		$PASS = strtok(',');
		$DBNM = strtok(',');

		print "現在使用中のデータベース名 ： {$DBNM} <br />ユーザー名 ： {$USER}" ;
		print 
			'
			<p>情報を変更する場合は下記に入力してください。</p>
			<form action="setup.php" method="post">
			<table>
			<tr><td>サーバー名：</td><td><input type="text" name="serv" value="" /></td></tr>
			<tr><td>ユーザー名：</td><td><input type="text" name="user" value="" /></td></tr>
			<tr><td>パスワード：</td><td><input type="text" name="pass" value="" /></td></tr>
			<tr><td>データベース名：</td><td><input type="text" name="dbnm" value="" /></td></tr>
			</table>
			<input type="submit" name="dbSet" value="変更">
			</form>
			' ;


		//システムに必要なテーブルが存在するか確認
		$checkExists[1] = $db->query("show tables like 'area';");
		$checkExists[2] = $db->query("show tables like 'events';");
		$checkExists[3] = $db->query("show tables like 'customers';");

		$exists = 0 ;

		for ( $n = 1 ; $n <= 3 ; $n += 1 ):

			( $checkExists[$n]->rowCount() == 0 ) ? : $exists += 1 ;

		endfor;


		//テーブルが存在している場合はメッセージを表示す
		if ( $exists === 3 ):

			print "<p>すでにセットアップが完了しています。<br />システムを使用する準備はできています。</p>" ;

		//テーブルが存在しない場合はセットアップ開始ボタンを表示
		else:

			print
					"
					<p>セットアップを開始します。<br />
					この作業でシステムに必要なテーブルをデータベースに作成します。</p>
					<form action='setup.php' method='post'><input type='submit' value='セットアップを開始' name='tbSet' /></form>
					";

		endif;

	endif;

//データベースの情報が変更又は新規で入力された場合
elseif ( $pagePat === 1 ):


	if ( !is_dir('../db_data/db_info') )://DB情報格納用のディレクトリが存在しない場合

		mkdir('../db_data/db_info');//ディレクトリを作成する

	endif;

	$dbInfo = 
				$_POST['serv'] . ',' .
				$_POST['user'] . ',' .
				$_POST['pass'] . ',' .
				$_POST['dbnm'] ;

	$fh = fopen( '../db_data/db_info/db_info.csv', 'wt' ) ;
	fwrite( $fh, $dbInfo );
	fclose($fh);
				

	print 
			'
			<p>データベースの設定ファイルを作成しました。<br />続いてシステムに必要なテーブルをデータベースに作成します。</p>
			<form action="setup.php" method="post"><input type="submit" value="セットアップを開始" name="tbSet" /></form>
			';


//セットアップ開始のボタンが押された場合
elseif ( $pagePat === 2 ):



	$res_1 = $db->query("create table area(

									number int(11) auto_increment primary key,
									page varchar(12),
									area varchar(32) unique,
									area_ja varchar(32),
									ken varchar(10),
									place varchar(64),
									price_h varchar(16),
									price_l varchar(16),
									age_m varchar(12),
									age_w varchar(12),
									free_text1 text,
									free_text2 text

									);");

	$res_2 = $db->query("create table events(

									number int(11) auto_increment primary key,
									find varchar(32) unique,
									area varchar(32),
									date date,
									week varchar(2),
									begin time,
									end time,
									state_m int(1),
									state_w int(1),
									sale varchar(128),
									pr_comment varchar(128),
									free_text text,
									meetingpoint int(11),
									store	varchar(128),
									address	varchar(128),
									url	varchar(128)


									);");


	$res_3 = $db->query("create table customers(

									number int(11) auto_increment primary key,
									find varchar(64),
									area varchar(32),
									event varchar(24),
									sex varchar(1),
									date datetime,
									entry int(1),
									state int(1) default 2,
									payment_d date,
									payment_p varchar(6),
									name varchar(32),
									hurigana varchar(32),
									age int(3),
									mail varchar(128),
									tel varchar(32),
									ninzu int(2),
									memo text,
									download int(1) default 0
									
									);");

	//テーブルが作成できたか確認
	$checkExists[1] = $db->query("show tables like 'area';");
	$checkExists[2] = $db->query("show tables like 'events';");
	$checkExists[3] = $db->query("show tables like 'customers';");

	$exists = 0 ;

	for ( $n = 1 ; $n <= 3 ; $n += 1 ):

		( $checkExists[$n]->rowCount() == 0 ) ? : $exists += 1 ;

	endfor;

	//テーブルが存在している場合は完了メッセージ
	if ( $exists === 3 ):

		print "<p>セットアップが完了しました。</p>" ;

	//テーブルが存在しない場合はセットアップ開始ボタンを表示
	else:

		print "テーブルの作成に失敗しました。<br />システムの管理者にお問い合わせください。" ;

	endif;

endif;


 
 ?>


</body>
</html>
