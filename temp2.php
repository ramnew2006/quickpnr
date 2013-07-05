<?php
set_time_limit(0);
require_once 'getcurl.php';
require_once 'database.php';

$db = new database();
$db->dbconnect();

$query = mysql_query("select train_num from trainlist where train_num not in (select train_num from trainschedule)");
$numRows = mysql_num_rows($query);

for($j=0;$j<$numRows;$j++){
	$trn_num[$j] = mysql_result($query,$j);
}

for($i=0;$i<$numRows;$i++){
	$postparams = "train_num=" . $trn_num[$i];
	$ch = curl_init();        //Initialize the cURL handler
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);    //To give the file fetched back to the handler
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,false);   //To stop redirects
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$postparams); //Post fields
	curl_setopt($ch, CURLOPT_POST,true); //To send the POST parameters
	curl_setopt($ch, CURLOPT_URL,"http://localhost/quickpnr/storetrainschedule.php");   //To set the page to be fetched
	curl_exec($ch);
	curl_close($ch);
	sleep(1);
}

$db->dbdisconnect();

?>