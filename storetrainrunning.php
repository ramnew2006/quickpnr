<?php
set_time_limit(0);
require_once 'database.php';
require_once 'postcurl.php';

$db = new database();
$db->dbconnect();

//$query = mysql_query("SELECT train_num from trainlist");
//$numRows = mysql_num_rows($query);
$url = "http://www.indianrail.gov.in/cgi_bin/inet_trnnum_cgi.cgi";
$tablenum = 23;
$trn_num = $_POST['train_num'];
//for($j=0;$j<5;$j++){
	//$ip = mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' .  mt_rand(0, 255) . '.' .  mt_rand(0, 255);
	//$trn_num = mysql_result($query,263);
	$postparams = "lccp_trnname=" . $trn_num . "&getIt=Please Wait...";

	$postobj = new postcurl($url,$tablenum,$postparams);
	$lengthCol = $postobj->tableColumns();

	$fields = "";
	$values = "";

	for($i=3;$i<$lengthCol;$i++){
		$fields .= strtolower(trim($postobj->getInfoFromRow(1,$i))) . ",";
		$values .= "'Y',";
	}

	$insert = "INSERT INTO trainrunning (train_num," . trim($fields,",") . ") VALUES ('" . $trn_num ."'," . trim($values,",") . ")";
	mysql_query($insert);
	echo $insert;
	//sleep(5);
//}
//echo $fields . "<br/>";
//echo $values;

$db->dbdisconnect();

?>