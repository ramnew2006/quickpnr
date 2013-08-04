<?php
session_start();
require_once 'database.php';

$dbobj = new database();
$dbobj->dbconnect();

if(isset($_SESSION['user'])){
	header("Location:index.php");
}

if(isset($_POST['userLogin'])){
	$mobileNum = $_POST['mobileNum'];
	$password = $_POST['userPassword'];
	if(is_numeric($mobileNum) && strlen($mobileNum)==10){
		$password = $password . $dbobj->returnSalt();
		$password = hash('sha512',$password);
		$query = mysql_query("SELECT * FROM `userlogin` WHERE `mobilenum` = " . $mobileNum . " AND `password` = '" . $password . "'");
		if(mysql_num_rows($query)==1){
			$result = mysql_fetch_array($query);
			$act_status = $result['act_status'];
			if($act_status=="Y"){
				$_SESSION['user']="loggedin";
				$_SESSION['userName']=$mobileNum;
				//$_SESSION['userEmail']=$email;
				//$_SESSION['userFrequency']=$frequency;
				setcookie('userlogin',$_SESSION['user'],time()+(86400*7));
				setcookie('userName',$_SESSION['userName'],time()+(86400*7));
				if(isset($_SESSION['redirect_url'])){
					if($_SESSION['redirect_url']=="/quickpnr/index.php" || $_SESSION['redirect_url']=="/quickpnr/"){
						header("Location:userprofile.php");
					}else{
						header("Location: {$_SESSION['redirect_url']}");
					}
				}
			}else{
			}
		}else{
			echo "wrong mobile num or password";
		}
		
	}
}else{
	header("Location:userlogin.php");
}

$dbobj->dbdisconnect();
?>