<?php
set_time_limit(0);
require_once 'getcurl.php';
require_once 'database.php';

$db = new database();
$db->dbconnect();

$url = "http://www.indianrail.gov.in/tourist_trn_list.html";
$tablenum = 5;

$train = new getcurl($url,$tablenum);
$totalRows = $train->tableRows();

for($i=1;$i<$totalRows;$i++){
	$query = "INSERT INTO trainlist (train_num,train_name,source_stn,source_dep_time,dest_stn,dest_arr_time) VALUES (";
	for($j=0;$j<6;$j++){
		$query .= "'" . trim($train->getInfoFromRow($i,$j)) . "',";
	}
	$query = trim($query,",");
	$query .= ")";
	mysql_query($query);
	//echo $query . "<br/>";
}

$db->dbdisconnect();

?>