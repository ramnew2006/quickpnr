<?php
session_start();
require_once 'database.php';

$dbobj = new database();
$dbobj->dbconnect();

if(isset($_SESSION['start']) && isset($_COOKIE['userName'])){
}else{
	if(isset($_COOKIE['userName'])){
		$mobileNum = $_COOKIE['userName'];
		$cookie = $_COOKIE['usercookie'];
		
		$query=mysql_query("SELECT * FROM userlogin WHERE mobilenum=" . $mobileNum . " AND cookie='" . $cookie . "'");

		if(mysql_num_rows($query)==1){
			$_SESSION['user']="loggedin";
			$_SESSION['userName']=$mobileNum;
			$_SESSION['start']="Y";
		}
	}else{
		unset($_SESSION['user']);
		unset($_SESSION['userName']);
	}
}
	
$dbobj->dbdisconnect();
?>
