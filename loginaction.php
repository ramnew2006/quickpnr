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
			$act_status = mysql_fetch_array($query)['act_status'];
			if($act_status=="Y"){
				echo "User Identified";
				$_SESSION['user']="loggedin";
				$_SESSION['userName']=$mobileNum;
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