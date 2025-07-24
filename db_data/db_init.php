<?php 

require_once("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/db_data/db_info/db_info.php");

$dsn = "mysql:host=$SERV;dbname=$DBNM";
$db = new PDO($dsn, $USER, $PASS) ;

?>