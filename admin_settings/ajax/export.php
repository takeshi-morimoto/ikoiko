<?php 

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');

require_once "../../db_data/db_init.php";
require '../lib/IOExcelCsv.php';

$db->query("SET NAMES utf8");

if ($_POST['option'] === 'events') {
	$colList = [
		'number','area','title','date','week','begin','end','price_m','price_f','img_url','sale','feature','pr_comment'
	];

} elseif ($_POST['option'] === 'area') {
	$colList = [
		'number','page','area','area_ja','ken','place','price_h','age_m','age_w','content'
	];
}

$select = '';
foreach ($colList as $col) {
	$select .= $col . ',';
}
$select = rtrim($select, ',');

$pdos = $db->query("select {$select} from {$_POST['option']}");
$header = [ $colList, ];

$path = '/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/admin_settings/tmp/' .$_POST['option']. '.csv';

$io = new IOExcelCsv();
$io->export($path, $header, $pdos);
