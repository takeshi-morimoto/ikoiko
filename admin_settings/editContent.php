<?php 

require_once("../db_data/db_init.php");
$db->query("SET NAMES utf8");


if (isset($_POST['submit_select'])):

	$PP = 1;
	$tmpnum = $_POST['num'];

elseif ( isset($_POST['submit_edit']) ):

	$PP = 2;

elseif ( isset($_POST['submit_add']) ):

	$PP = 3;

elseif ( isset($_POST['submit_add_2']) ):

	$PP = 4;

else:

	$PP = 0;


endif;


if ( $PP == 2 ):

	$ps = $db->prepare("update content set title = '{$_POST['title']}', name = '{$_POST['name']}', text = '{$_POST['text']}' where num = {$_POST['num']};");
	$ps->execute();


elseif ( $PP == 4 ):


	$ps = $db->prepare("insert into content ( title, text ) value ( '{$_POST['title']}', '{$_POST['text']}' );");
	$ps->execute();


endif;



?>


<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Document</title>

	<style>

		input[type=submit] {

			display: inline-block;
			padding: 5px 10px;
		}

		select {

			display: inline-block;
			padding: 5px 10px;
			font-size: 120%;
		}

		div.c01 {

			margin: 10px 0;
		}

		.center {

			margin: 0 auto;
			width: 70%;
		}

		.txt_center {

			text-align: center;
		}

	</style>
</head>
<body>

<p style="font-size:40;"><a href="admin.php">コントロールパネルトップにもどる</a></p>


<form action="" method="post">

	<div class="c01">

		編集したいテキストを選択してください。

		<select name="num">

			<?php 
				
				$ps = $db->prepare('select num, title from content;');
				$ps->execute();

				while( $row = $ps->fetch() ):

					print "<option value='{$row['num']}'>{$row['title']}</option>";

				endwhile;
			?>

		</select>

		<input type="submit" name="submit_select" value="選択"><br />

	</div>

	<div class="c01">

	新しいコンテンツを作成する場合はこちら
	<input type="submit" name="submit_add" value="新規作成" />

	</div>

</form>

<hr />

<?php if ( $PP == 1 ): ?>

	<?php 

		$ps = $db->prepare("select title, name, text from content where num = {$tmpnum};");
		$ps->execute();
		$data = $ps->fetch();
	?>

	<div class="center txt_center">
		
		<form action="" method="post">

				<?php print "<input type='hidden' name='num' value='{$tmpnum}'>"; ?>

				<h2>
					タイトル：
					<?php print "<input type='text' size='50' name='title' value='{$data['title']}' />"; ?>
				</h2>

				<h2>
					表示名：
					<?php print "<input type='text' size='50' name='name' value='{$data['name']}' />"; ?>
				</h2>

				<textarea name="text" cols="100" rows="30">
					<?php print $data['text']; ?>
				</textarea>

				<div class="txt_center">

					<input type="submit" name="submit_edit" value="編集完了" />
					
				</div>

		</form>

	</div>

	<hr />

<?php elseif ( $PP == 2 ): ?>

	[<?php print $_POST['title']; ?>]を編集しました。


<?php elseif ( $PP == 3 ): ?>

	<div class="center txt_center">

		<form action="" method="post">
			
			<h2>
				タイトル：
				<input type="text" size='50' name="title" value="" />
			</h2>

			<textarea name="text" cols="100" rows="30"></textarea>

			<div class="txt_center">

				<input type="submit" name="submit_add_2" value="入力完了" />

			</div>

		</form>

	</div>

<?php endif; ?>
	
</body>
</html>
