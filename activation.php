<?php
include('../checkcookie.php');

if(isset($_SESSION['userName'])){
	header("Location:../user/profile.php");
	exit();
}

require_once '../database.php';

$dbobj = new database();
$dbobj->dbconnect();
 
if(isset($_POST['mobActivation'])){
	$mobnum = $_SESSION['registerNum'];
	$query = mysql_query("SELECT act_code FROM userlogin WHERE mobilenum=" . $mobnum);
	if(mysql_num_rows($query)==1){
		$result = mysql_fetch_array($query);
		$act_code = $result['act_code'];
		if($_POST['act_code']==$act_code){
			$query = mysql_query("UPDATE userlogin SET act_code=NULL, act_status='Y'");
			if($query){
				$_SESSION['regSuccess']="Yes";
				// echo "Successfully Activated" . "<br/>";
				// echo "<a href=\"/quickpnr/userlogin.php\">Click Here</a>";				
				header("Location:userlogin.php");
			}else{
				echo "Sorry! Something is broke!";
			}
		}
	}else{
		echo "please try again";
	}
}else{
	if(isset($_SESSION['registerNum'])){
		header("Location:../doregister.php");
	}else{
		header("Location:../index.php");
	}
}

$dbobj->dbdisconnect();
?>
