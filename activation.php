<?php
session_start();
require_once 'database.php';

$dbobj = new database();
$dbobj->dbconnect();

if(isset($_SESSION['user'])){
	header("Location: index.php");
}else{
	if(isset($_POST['mobActivation'])){
		$mobnum = $_SESSION['registerNum'];
		$query = mysql_query("SELECT act_code FROM userlogin WHERE mobilenum=" . $mobnum);
		$result = mysql_fetch_array($query);
		$act_code = $result['act_code'];
		if($_POST['act_code']==$act_code){
			$query = mysql_query("UPDATE userlogin SET act_code=NULL, act_status='Y'");
			if($query){
				echo "Successfully Activated";
			}else{
				echo "Sorry! Something is broke!";
			}
		}
	}else{
		if(isset($_SESSION['registerNum'])){
			header("Location:doregister.php");
		}
	}
}

$dbobj->dbdisconnect();
?>