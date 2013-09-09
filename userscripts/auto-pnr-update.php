<?php
set_time_limit(0);
require_once '../database.php';
require_once '../userscripts/postcurl.php';
require_once '../userscripts/sendsms_class.php';

$dbobj = new database();
$dbobj->dbconnect();

$query=mysql_query("SELECT pnrnum,mobnum FROM userpnrdetails WHERE doj>=CURDATE()");

while($row = mysql_fetch_array($query)){
	$postUrl = "http://" . $_SERVER['HTTP_HOST'] . "/userscripts/sendtextmessage.php";
	$postParams = "anyMobileautoPNR=Y&pnrNum=" . $row['pnrnum'] . "&mobileNum=" . $row['mobnum'];
	
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

	sleep(5);
}

// while($row = mysql_fetch_array($query)){
// 	$quickpnrmob = $row['mobnum'];
// 	$url = "http://www.indianrail.gov.in/cgi_bin/inet_pnrstat_cgi.cgi";
// 	$tablenum = 25;
// 	$postparams = "lccp_pnrno1=" . $row['pnrnum'] . "&submit=Wait+For+PNR+Enquiry%21";

// 	$postobj = new postcurl($url,$tablenum,$postparams);
// 	$lengthRow = $postobj->tableRows();

// 	if($lengthRow==1){
// 		$tablenum = 26;
// 		$postobj = new postcurl($url,$tablenum,$postparams);
// 		$lengthRow = $postobj->tableRows();
// 	}

// 	if($lengthRow){
// 		$message = "PNR: " . $row['pnrnum'] . "%0aSNo.  B.Status  C.Status%0a";

// 		for($i=1;$i<$lengthRow-1;$i++){
// 			$lengthCol = $postobj->tableColumns($i);
// 			for($j=0;$j<3;$j++){
// 				$temp = $postobj->getInfoFromRow($i,$j);
// 				$temp = preg_replace('/Passenger /','P',$temp);
// 				//if($j==1 && ($i!=$lengthRow-2)){
// 				if($i!=$lengthRow-2){	
// 					$temp = preg_replace('/\s+/','',$temp);
// 				}
// 				//}
// 				if(($i==$lengthRow-2)&&($j==0)){
					
// 				}else{
// 					$message .= trim($temp) . "  ";
// 				}
// 			}
// 			//$message = trim($message);
// 			if($i!=$lengthRow-2){
// 				$message .= "%0a";
// 			}
// 		}
		
// 		//echo $message;
// 		if($lengthRow>0){
// 			$smsobj = new sendSMS($quickpnrmob,$message);
// 			if($smsobj){
// 				//return true;
// 				$query1=mysql_query("SELECT messagecnt FROM dailysms WHERE mobilenum=" . $quickpnrmob ." AND date=CURDATE()");
// 				$numrows = mysql_num_rows($query1);
// 				if($numrows==0){
// 					$query2 = mysql_query("INSERT INTO dailysms (mobilenum,messagecnt,date) VALUES ('" . $quickpnrmob . "','1',CURDATE())");
// 				}else{
// 					$msgcnt = mysql_result($query1, 0);
// 					$msgcnt = $msgcnt+1;
// 					$query2 = mysql_query("UPDATE dailysms SET messagecnt=" . $msgcnt . " WHERE mobilenum=" . $quickpnrmob . " AND date=CURDATE()");
// 				}
// 				echo "Success";
// 			}else{
// 				//return false;
// 				echo "Failure";
// 			}
// 		}
// 	}else{
// 		echo "There is a problem with Indian Railway Servers!!";
// 	}
// 	//sleep(1);
// }

$dbobj->dbdisconnect();

?>