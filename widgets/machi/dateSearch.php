
<div class="dateSearch">

	<script src="//koikoi.co.jp/ikoiko/js/dateSearch.js"></script>

	<h2>カレンダーから検索</h2>

	<div id="next"><img src="//koikoi.co.jp/ikoiko/img/icon/icon_next.png" alt="" /></div>
	<div id="prev"><img src="//koikoi.co.jp/ikoiko/img/icon/icon_prev.png" alt="" /></div>

	<div id="dateSearch_inner">

<?php 

if ( isset($searchArea) ):

  $area = $searchArea. '/';

else: 

  $area = '';

endif;


if ( isset($searchDate) ):

  $date = $searchDate. '/';

else: 

  $date = '';

endif;


$y = date('Y');
$m = date('n');
$d = 1;

?>

<div id="calendar_this" class="cal">

	<div>
		<h3><?php print "{$y}年{$m}月"; ?></h3>
	</div>

	<table>
		
		<tr><td><span class="txtc_red">日</span></td><td>月</td><td>火</td><td>水</td><td>木</td><td>金</td><td><span class="txtc_blue">土</span></td></tr>
		<tr>

		<?php

			// 最初の週の空白を調整
			$fw = date( 'w', mktime(0,0,0,$m,1,$y) );

			for ( $n = 1 ; $n <= $fw ; $n++ ):

				print '<td> </td>';

			endfor;

			while ( checkdate($m, $d, $y) ):

				if ( date('w', mktime(0,0,0,$m,$d,$y) ) == 0 ):

					print '</tr><tr>';

				endif;


				$thisDate = "{$y}-{$m}-{$d}";
				print "<td class='" . CSR( 'machi', $searchArea, $thisDate ) . "'><a href='//koikoi.co.jp/ikoiko/list_2/{$thisDate}/'>{$d}</a></td>";


				$d++;

			endwhile;

			// 最後の週の空白を調整
			$d--;
			$lw = date( 'w', mktime(0,0,0,$m,$d,$y) );

			while ( $lw < 6 ):

				print '<td> </td>';
				$lw++;

			endwhile;

		?>

		</tr>
	</table>
</div>

<?php 

if ( $m == 12 ):	// 12月の場合は年と月を設定

	$y++;
	$m = 1;

else:				// それ以外の場合は翌月を設定

	$m++;

endif;

$d = 1;

?>

<div id="calendar_next" class="cal">

	<div>
		<h3><?php print "{$y}年{$m}月"; ?></h3>
	</div>

	<table>
		
		<tr><td>日</td><td>月</td><td>火</td><td>水</td><td>木</td><td>金</td><td>土</td></tr>
		<tr>

		<?php

			// 最初の週の空白を調整
			$fw = date( 'w', mktime(0,0,0,$m,1,$y) );

			for ( $n = 1 ; $n <= $fw ; $n++ ):

				print '<td> </td>';

			endfor;

			while ( checkdate($m, $d, $y) ):

				if ( date('w', mktime(0,0,0,$m,$d,$y) ) == 0 ):

					print '</tr><tr>';

				endif;

				$thisDate = "{$y}-{$m}-{$d}";
				print "<td class='" . CSR( 'machi', $searchArea, $thisDate ) . "'><a href='//koikoi.co.jp/ikoiko/list_2/{$thisDate}/'>{$d}</a></td>";


				$d++;

			endwhile;

			// 最後の週の空白を調整
			$d--;
			$lw = date( 'w', mktime(0,0,0,$m,$d,$y) );

			while ( $lw < 6 ):

				print '<td> </td>';
				$lw++;

			endwhile;

		?>

		</tr>
	</table>
</div>


<?php 

if ( $m == 12 ):	// 12月の場合は年と月を設定

	$y++;
	$m = 1;

else:				// それ以外の場合は翌月を設定

	$m++;

endif;

$d = 1;

?>

<div id="calendar_next2" class="cal">

	<div>
		<h3><?php print "{$y}年{$m}月"; ?></h3>
	</div>

	<table>
		
		<tr><td>日</td><td>月</td><td>火</td><td>水</td><td>木</td><td>金</td><td>土</td></tr>
		<tr>

		<?php

			// 最初の週の空白を調整
			$fw = date( 'w', mktime(0,0,0,$m,1,$y) );

			for ( $n = 1 ; $n <= $fw ; $n++ ):

				print '<td> </td>';

			endfor;

			while ( checkdate($m, $d, $y) ):

				if ( date('w', mktime(0,0,0,$m,$d,$y) ) == 0 ):

					print '</tr><tr>';

				endif;

				$thisDate = "{$y}-{$m}-{$d}";
				print "<td class='" . CSR( 'machi', $searchArea, $thisDate ) . "'><a href='//koikoi.co.jp/ikoiko/list_2/{$thisDate}/'>{$d}</a></td>";


				$d++;

			endwhile;

			// 最後の週の空白を調整
			$d--;
			$lw = date( 'w', mktime(0,0,0,$m,$d,$y) );

			while ( $lw < 6 ):

				print '<td> </td>';
				$lw++;

			endwhile;

		?>

		</tr>
	</table>
</div>

</div><!-- /dateSearch_inner -->
</div><!-- /dateSearch -->
