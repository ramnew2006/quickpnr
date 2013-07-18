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
			echo "User Identified";
			$_SESSION['user']="loggedin";
			$_SESSION['userName']=$mobileNum;
			header("Location: {$_SESSION['redirect_url']}");
		}else{
			echo "wrong mobile num or password";
		}
		
	}
}else{
	header("Location:userlogin.php");
}

$dbobj->dbdisconnect();
?>