<?php
set_time_limit(60);
session_start();
require_once '../database.php';
require_once '../userscripts/postcurl.php';

$dbobj = new database();
$dbobj->dbconnect();


if(isset($_POST['savePnrHistory'])){
	if(isset($_SESSION['userName'])){
		//quickpnr mobilenum
		$mobnum = $_SESSION['userName'];
		session_write_close();
		
		//IRCTC Login Parameters
		$username = $_POST['irctcUsername'];
		$password = $_POST['irctcPassword'];
		
		importIrctc($mobnum,$username,$password,$dbobj);
	}else{
		header("Location:/user/login");
		$_SESSION['redirect_url']=$_SERVER["REQUEST_URI"];
	}
}elseif(isset($_POST['savePnrHistoryButton'])){
	if(isset($_SESSION['userName'])){
		//quickpnr mobilenum
		$mobnum = $_SESSION['userName'];
		session_write_close();
		
		//IRCTC Login Parameters
		$username = $_POST['irctcUsername'];
		
		$query=mysql_query("SELECT AES_DECRYPT(password,'" . $dbobj->returnSalt() . "') FROM irctcaccounts WHERE mobilenum=" . $mobnum . " AND username='" . $username . "'");
		$password = mysql_result($query,0);
		
		importIrctc($mobnum,$username,$password,$dbobj);
	}else{
		header("Location:/quickpnr/userlogin.php");
		$_SESSION['redirect_url']=$_SERVER["REQUEST_URI"];
	}
}else{
	header("Location: /");
}

$dbobj->dbdisconnect();

function importIrctc($mobnum,$username,$password,$dbobj){
	$url = "https://www.irctc.co.in/cgi-bin/bv60.dll/irctc/services/login.do";
	$postparams = "screen=home&userName=" . $username . "&password=" . $password;
	
	//Initial Login
	$postobj = new postcurl($url,1,$postparams);
	$code = $postobj->curlheaders();
	$code = $code['http_code'];

	//Store IRCTC account in database at initial Go
	$query = mysql_query("SELECT * FROM irctcaccounts WHERE mobilenum=". $mobnum ." AND username='". $username . "'");
	
	if(mysql_num_rows($query)==1){
		$query2 = mysql_query("UPDATE irctcaccounts SET updatetime=CURRENT_TIMESTAMP(), password=AES_ENCRYPT('" . $password ."', '" . $dbobj->returnSalt() . "') WHERE mobilenum=" . $mobnum . " AND username='" . $username . "'");
	}
	if(mysql_num_rows($query)==0){
		$query2 = mysql_query("INSERT INTO irctcaccounts (mobilenum,username,password) VALUES ('" . $mobnum . "','" . $username . "',AES_ENCRYPT('" . $password . "','" . $dbobj->returnSalt() . "'))");
	}

	if($code==302){
		$url = $postobj->curlheaders();
		$url = $url['redirect_url'];
		$query = parse_url($url);
		$query = $query['query'];
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
		$code = $postobj->curlheaders();
		$code = $code['http_code'];

		if($code==200){
			
			//Store IRCTC account in database after username and password are validated
			$query = mysql_query("SELECT * FROM irctcaccounts WHERE mobilenum=". $mobnum ." AND username='". $username . "'");
			
			if(mysql_num_rows($query)==1){
				$query2 = mysql_query("UPDATE irctcaccounts SET updatetime=CURRENT_TIMESTAMP(), password=AES_ENCRYPT('" . $password ."', '" . $dbobj->returnSalt() . "') WHERE mobilenum=" . $mobnum . " AND username='" . $username . "'");
			}
			if(mysql_num_rows($query)==0){
				$query2 = mysql_query("INSERT INTO irctcaccounts (mobilenum,username,password) VALUES ('" . $mobnum . "','" . $username . "',AES_ENCRYPT('" . $password . "','" . $dbobj->returnSalt() . "'))");
			}
		
			//print_r($postobj->curlheaders());
			$numRows = $postobj->tableRows();
			
			$confirm = 0;
			
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
						$query = "INSERT INTO `userpnrdetails` (`pnrnum`, `mobnum`, `archive`, `src_stn`, `dest_stn`, `doj`) VALUES " . $values;
						//echo $query;
						if(mysql_query($query)){
							$confirm++;
						}else{
						}
					}
				}
			}
			
			if($confirm==0){
				echo "Failure";
			}else{
				echo "Success";
			}
		}
	}elseif($code==200){
		echo "WrongPassword";
	}else{
		echo "Try Again";
	}
}
?>
