<?php
set_time_limit(0);
require_once 'getcurl.php';
require_once 'database.php';

$db = new database();
$db->dbconnect();

//$url = "http://www.indianrail.gov.in/mail_express_trn_list.html";
//$tablenum = 5;
$query = mysql_query("SELECT train_num from trainlist");
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
	//curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);    //To mask the robot as a browser
	curl_setopt($ch, CURLOPT_POSTFIELDS,$postparams); //Post fields
	curl_setopt($ch, CURLOPT_POST,true); //To send the POST parameters
	//curl_setopt($ch, CURLOPT_REFERER, 'http://www.indianrail.gov.in/train_Schedule.html');
	//curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_URL,"http://localhost/quickpnr/storetrainrunning.php");   //To set the page to be fetched
	curl_exec($ch);
	curl_close($ch);
	sleep(3);
}

//$obj = new getcurl($url,$tablenum);

//echo $obj->getInfoFromRow(2,2);

$db->dbdisconnect();

?>