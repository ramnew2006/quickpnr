<?php
set_time_limit(60);
session_start();
require_once 'database.php';
require_once 'postcurl.php';

if(isset($_POST['savePnrHistory'])){
	$dbobj = new database();
	$dbobj->dbconnect();

	if(isset($_SESSION['user'])){
		
		//IRCTC Login Parameters
		$url = "https://www.irctc.co.in/cgi-bin/bv60.dll/irctc/services/login.do";
		$username = $_POST['irctcUsername'];
		$password = $_POST['irctcPassword'];
		$postparams = "screen=home&userName=" . $username . "&password=" . $password;

		//Initial Login
		$postobj = new postcurl($url,1,$postparams);
		$code = $postobj->curlheaders()['http_code'];
		
		if($code==302){
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
			//$error = $postobj->errorcheck();
			$code = $postobj->curlheaders()['http_code'];

			if($code==200){
				//print_r($postobj->curlheaders());
				$numRows = $postobj->tableRows();
				$mobnum = $_SESSION['userName'];
				
				$confirm = 0;
				$irctcimportprogress=0;
				$progressbit = (100/($numRows-1));
				
				for($i=1;$i<$numRows;$i++){
					if($i%2==1){
						$pnrnum = $postobj->getInfoFromRow($i,3);
						
						if(!empty($pnrnum)){
							$src_stn = $postobj->getInfoFromRow($i,4);
							$dest_stn = $postobj->getInfoFromRow($i,5);
							$doj_temp = $postobj->getInfoFromRow($i,6);
							$doj_temp = explode('-',$doj_temp);
							$doj = $doj_temp[2] . "-" . $doj_temp[1] . "-" . $doj_temp[0];
							$time_diff = strtotime(date("Y-m-d"))-strtotime($doj);
							if($time_diff<0){
								$archive = "N";
							}else{
								$archive = "Y";
							}
							$values = "('" . $pnrnum . "','" . $mobnum . "','" . $archive . "','" . $src_stn . "','" . $dest_stn . "','" . $doj . "')";
							$query = "INSERT INTO `pnrdetails` (`pnrnum`, `mobnum`, `archive`, `src_stn`, `dest_stn`, `doj`) VALUES " . $values;
							//echo $query;
							if(mysql_query($query)){
								$confirm=1;
							}else{
								$confirm =0;
							}
						}
					}
					$irctcimportprogress=$irctcimportprogress+$progressbit;
					//file_put_contents("irctcpnrimportprogress.php",round($irctcimportprogress));
					$_SESSION['irctcimportprogress']=round($irctcimportprogress);
				}
				if($confirm==1){
					echo "Successfully imported PNR numbers";
				}else{
					echo "There is some problem while importing PNR numbers";
				}
			}
		}else{
			echo "There is some problem while connecting to IRCTC Servers!";
		}
	}else{
		header("Location:userlogin.php");
		$_SESSION['redirect_url']=$_SERVER["REQUEST_URI"];
	}
	
	$dbobj->dbdisconnect();
}else{
	header("Location: temp.php");
}
?>
