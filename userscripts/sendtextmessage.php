<?php
session_start();
set_time_limit(10);
require_once '../userscripts/postcurl.php';
require_once '../userscripts/sendsms_class.php';
require_once '../database.php';

$dbobj = new database();
$dbobj->dbconnect();

//Send SMS to Registered Mobile Number
if(isset($_POST['regMobile'])){
	$quickpnrmob = $_SESSION['userName'];
	session_write_close();
	$url = "http://www.indianrail.gov.in/cgi_bin/inet_pnrstat_cgi.cgi";
	$tablenum = 25;
	$postparams = "lccp_pnrno1=" . $_POST['pnrNum'] . "&submit=Wait+For+PNR+Enquiry%21";
	
	$postobj = new postcurl($url,$tablenum,$postparams);
	$lengthRow = $postobj->tableRows();
	
	if($lengthRow==1){
		$tablenum = 26;
		$postobj = new postcurl($url,$tablenum,$postparams);
		$lengthRow = $postobj->tableRows();
	}
	
	if($lengthRow){
		
		$message = "PNR: " . $_POST['pnrNum'] . "%0aSNo.  B.Status  C.Status%0a";

		for($i=1;$i<$lengthRow-1;$i++){
			$lengthCol = $postobj->tableColumns($i);
			for($j=0;$j<3;$j++){
				$temp = $postobj->getInfoFromRow($i,$j);
				$temp = preg_replace('/Passenger /','P',$temp);
				//if($j==1 && ($i!=$lengthRow-2)){
				if($i!=$lengthRow-2){	
					$temp = preg_replace('/\s+/','',$temp);
				}
				//}
				if(($i==$lengthRow-2)&&($j==0)){
					
				}else{
					$message .= trim($temp) . "  ";
				}
			}
			//$message = trim($message);
			if($i!=$lengthRow-2){
				$message .= "%0a";
			}
		}
		
		//echo $message;
		if($lengthRow>0){
			$query=mysql_query("SELECT username, AES_DECRYPT(password,'" . $dbobj->returnSalt() . "') FROM onesixtybytwo WHERE mobilenum=". $quickpnrmob);
			$result = mysql_fetch_array($query);
			$username = $result[0];
			$password = $result[1];
			$smsobj = new sendSMS($username, $password, $quickpnrmob,$message);
			if($smsobj){
				//return true;
				echo "Success";
			}else{
				//return false;
				echo "Failure";
			}
		}
	}else{
		echo "There is a problem with Indian Railway Servers!!";
	}
}

//Send SMS to Any Mobile Number
if(isset($_POST['anyMobile'])){
	$quickpnrmob = $_POST['mobileNum'];
	$url = "http://www.indianrail.gov.in/cgi_bin/inet_pnrstat_cgi.cgi";
	$tablenum = 25;
	$postparams = "lccp_pnrno1=" . $_POST['pnrNum'] . "&submit=Wait+For+PNR+Enquiry%21";
	
	$postobj = new postcurl($url,$tablenum,$postparams);
	$lengthRow = $postobj->tableRows();
	
	if($lengthRow==1){
		$tablenum = 26;
		$postobj = new postcurl($url,$tablenum,$postparams);
		$lengthRow = $postobj->tableRows();
	}
	
	if($lengthRow){
		$message = "PNR: " . $_POST['pnrNum'] . "%0aSNo.  B.Status  C.Status%0a";

		for($i=1;$i<$lengthRow-1;$i++){
			$lengthCol = $postobj->tableColumns($i);
			for($j=0;$j<3;$j++){
				$temp = $postobj->getInfoFromRow($i,$j);
				$temp = preg_replace('/Passenger /','P',$temp);
				if($j==1 && ($i!=$lengthRow-2)){
					$temp = preg_replace('/ /','',$temp);
				}
				if(($i==$lengthRow-2)&&($j==0)){
					
				}else{
					$message .= trim($temp) . "  ";
				}
			}
			//$message = trim($message);
			if($i!=$lengthRow-2){
				$message .= "%0a";
			}
		}
		
		//echo $message;
		if($lengthRow>0){
			$query=mysql_query("SELECT username, AES_DECRYPT(password,'" . $dbobj->returnSalt() . "') FROM onesixtybytwo WHERE mobilenum=". $quickpnrmob);
			$result = mysql_fetch_array($query);
			$username = $result[0];
			$password = $result[1];
			$smsobj = new sendSMS($username, $password, $quickpnrmob,$message);
			if($smsobj){
				//return true;
				echo "Success";
			}else{
				//return false;
				echo "Failure";
			}
		}
	}else{
		echo "There is a problem with Indian Railway Servers!!";
	}
}

$dbobj->dbdisconnect();
?>