<?php 

require_once(__DIR__ . "/db_info/db_info.php");

$dsn = "mysql:host=$SERV;dbname=$DBNM";
$db = new PDO($dsn, $USER, $PASS) ;

?>