<?php
require_once '../database.php';
require_once '../userscripts/postcurl.php';
require_once '../userscripts/sendsms_class.php';

$dbobj = new database();
$dbobj->dbconnect();

$query=mysql_query("SELECT pnrnum,mobnum FROM userpnrdetails WHERE doj>=CURDATE()");

while($row = mysql_fetch_array($query)){
	$postUrl = "http://" . $_SERVER['HTTP_HOST'] . "/userscripts/sendtextmessage.php";
	$postParams = "anyMobile=Y&pnrNum=" . $row['pnrnum'] . "&mobileNum=" . $row['mobnum'];
	
	$ch = curl_init();        //Initialize the cURL handler
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);    //To give the file fetched back to the handler
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);   //To stop redirects
	curl_setopt($ch, CURLOPT_POSTFIELDS,$postParams); //Post fields
	curl_setopt($ch, CURLOPT_POST,true); //To send the POST parameters
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch, CURLOPT_URL,$postUrl);   //To set the page to be fetched
	curl_exec($ch);    //Execute and return the response
	curl_close($ch);
	
	sleep(1);
}

$dbobj->dbdisconnect();

?>