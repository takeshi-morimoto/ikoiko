<?php 

function CSR( $page, $searchArea, $searchDate ){

	if ( empty($searchArea) ):

		$where_1 = ' 1 = 1 ';

	else:

		$where_1 = " area.ken = '{$searchArea}' ";

	endif;


	if ( empty($searchDate) ):

	    $today = date("Y") . '-' . date("m") . '-' . date("j") ;	
		$where_2 = " and events.date >= '{$today}' ";

	else:

		$where_2 = " and events.date = '{$searchDate}' ";

	endif;


	//DBの初期化
	$SERV = 'mysql103.phy.lolipop.lan';
	$USER = 'LAA0375178';
	$PASS = 'koikoi5151';
	$DBNM = 'LAA0375178-koikoi';

	$dsn = "mysql:host=$SERV;dbname=$DBNM";
	$db = new PDO($dsn, $USER, $PASS) ;
	$db->query("SET NAMES utf8");

    $result = $db->query("
                  select count(events.find) from events join area using(area)
                  where {$where_1} {$where_2} and area.page = '{$page}' 
                  ");

    list( $count ) = $result->fetch() ;

     $return = ( $count == 0 ) ? 'empty' : 'contains' ;

    return $return;

}





?>