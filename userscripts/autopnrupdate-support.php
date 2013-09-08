<?php
require_once '../database.php';
$dbobj = new database();
$dbobj->dbconnect();

if(isset($_POST['anyMobileautoPNR'])){
	$quickpnrmob = $_POST['mobileNum'];
	$url = "http://www.indianrail.gov.in/cgi_bin/inet_pnrstat_cgi.cgi";
	$tablenum = 25;
	$postparams = "lccp_pnrno1=" . $_POST['pnrnum'] . "&submit=Wait+For+PNR+Enquiry%21";

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
			$smsobj = new sendSMS($quickpnrmob,$message);
			if($smsobj){
				//return true;
				$query=mysql_query("SELECT messagecnt FROM dailysms WHERE mobilenum=" . $quickpnrmob ." AND date=CURDATE()");
				$numrows = mysql_num_rows($query);
				if($numrows==0){
					$query = mysql_query("INSERT INTO dailysms (mobilenum,messagecnt,date) VALUES ('" . $quickpnrmob . "','1',CURDATE())");
				}else{
					$msgcnt = mysql_result($query, 0);
					$msgcnt = $msgcnt+1;
					$query = mysql_query("UPDATE dailysms SET messagecnt=" . $msgcnt . " WHERE mobilenum=" . $quickpnrmob . " AND date=CURDATE()");
				}
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