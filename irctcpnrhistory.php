<?php
set_time_limit(0);
session_start();
require_once 'database.php';
require_once 'postcurl.php';

if(isset($_POST['savePnrHistory'])){
	if(isset($_SESSION['user'])){
		
		//IRCTC Login Parameters
		$url = "https://www.irctc.co.in/cgi-bin/bv60.dll/irctc/services/login.do";
		$username = $_POST['irctcUsername'];
		$password = $_POST['irctcPassword'];
		$postparams = "screen=home&userName=" . $username . "&password=" . $password;

		//Initial Login
		$postobj = new postcurl($url,1,$postparams);
		$error = $postobj->errorcheck();
		echo $error;
		
		if($error){
			$url = $postobj->curlheaders()['redirect_url'];
			$query = parse_url($url)['query'];
			$query = explode('&',$query);
			
			//Extracting Session variables
			$bvs = $query[1];
			$bve = $query[2];

			//Getting PNR History
			$url = "https://www.irctc.co.in/cgi-bin/bv60.dll/irctc/services/repassword.do?" . $bvs . "&" . $bve;
			$ref = "https://www.irctc.co.in/cgi-bin/bv60.dll/irctc/services/history.do?click=true&LinkValue=2&voucher=4&" . $bvs . "&" . $bve;
			$postparams = $bvs . "&" . $bve . "&page=history&password=" . $password . "&Submit=GO";
			
			$postobj = new postcurl($url,15,$postparams,$ref);
			$error = $postobj->errorcheck();
			echo $error;

			if($error){
				//print_r($postobj->curlheaders());
				$numRows = $postobj->tableRows();
				$mobnum = $_SESSION['userName'];
				
				for($i=1;$i<$numRows;$i++){
					if($i%2==1){
						$pnrnum = $postobj->getInfoFromRow($i,3);
						if(!empty($pnrnum)){
							$values = "('" . $pnrnum . "','" . $mobnum . "'),";
							$query = "INSERT INTO `quickpnr`.`pnrdetails` (`pnrnum`, `mobnum`) VALUES " . $values;
							$query = mysql_query($query);
						}
					}
				}
				echo "Successfully imported PNR numbers";
			}
		}else{
			echo "There is some problem while connecting to IRCTC Servers!";
		}
	}else{
		header("Location:userlogin.php");
	}
}else{
	header("Location: temp.php");
}
?>
