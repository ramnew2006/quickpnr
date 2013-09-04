<?php
set_time_limit(60);
session_start();
require_once '../database.php';
require_once '../userscripts/postcurl.php';

$dbobj = new database();
$dbobj->dbconnect();

if(isset($_POST['removeAccount'])){
	if(isset($_SESSION['userName'])){
		$mobnum = $_SESSION['userName'];
		session_write_close();
		
		//IRCTC Login Parameters
		$username = $_POST['irctcUsername'];
		
		$query="DELETE FROM irctcaccounts WHERE mobilenum=". $mobnum . " AND username='" . $username . "'";
		if(mysql_query($query)){
			echo "Success";
		}else{
			echo "Failure";
		}		
	}else{
		header("Location:/user/login");
		$_SESSION['redirect_url']=$_SERVER["REQUEST_URI"];
	}
}else{
	header("Location: /index.php");
}

$dbobj->dbdisconnect();
?>