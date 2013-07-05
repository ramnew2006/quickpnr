<?php
set_time_limit(0);
require_once 'database.php';
require_once 'postcurl.php';

$db = new database();
$db->dbconnect();

$url = "http://www.indianrail.gov.in/cgi_bin/inet_trnnum_cgi.cgi";
$tablenum = 24;
$trn_num = $_POST['train_num'];
$postparams = "lccp_trnname=" . $trn_num . "&getIt=Please Wait...";

$postobj = new postcurl($url,$tablenum,$postparams);
$lengthRow = $postobj->tableRows();
$lengthCol = 9;

$fields = "(train_num,stn_code,stn_name,route_num,arr_time,dep_time,halt_time,distance,day)";
$values = "";
$k=1;

for($i=1;$i<$lengthRow;$i++){
	$values .= "('" . $trn_num . "',";
	for($j=1;$j<$lengthCol;$j++){
		//if the cells have empty values or indifferent values assign Null to them
		if($postobj->getInfoFromRow($i,$j)=="Source" || $postobj->getInfoFromRow($i,$j)=="Destination" || $postobj->getInfoFromRow($i,$j)==""){
			$values .= "NULL,";
		}else{
			$tempvalue = $postobj->getInfoFromRow($i,$j);
			if($j==3){ //calculating route number
				$values .= "'" . $k . "',";
			}elseif($j==6){ //prefixing zero hours to halt time
				$values .= "'00:" . (trim($tempvalue)) . "',";
			}else{
				$values .= "'" . (trim($tempvalue)) . "',";
			}
		}
	}
	$k++;
	$values = trim($values,",") . "),";
}

$insert = "INSERT INTO trainschedule " . $fields . " VALUES ". $values;
$insert = trim($insert,",");
mysql_query($insert);
//echo $insert;

$db->dbdisconnect();

?>