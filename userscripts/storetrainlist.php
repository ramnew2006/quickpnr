<?php
set_time_limit(0);
require_once 'getcurl.php';
require_once 'database.php';

$db = new database();
$db->dbconnect();

$url = array("http://www.indianrail.gov.in/tourist_trn_list.html", "http://www.indianrail.gov.in/duronto_trn_list.html", "http://www.indianrail.gov.in/garibrath_trn_list.html", "http://www.indianrail.gov.in/jan_shatabdi.html", "http://www.indianrail.gov.in/shatabdi_trn_list.html", "http://www.indianrail.gov.in/rajdhani_trn_list.html", "http://www.indianrail.gov.in/mail_express_trn_list.html", "http://www.indianrail.gov.in/special_trn_list.html");
$tablenum = 5;

for($k=0;$k<sizeof($url);$k++){
	$train = new getcurl($url[$k],$tablenum);
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
}

$db->dbdisconnect();

?>