<?php
include('checkcookie.php');

if(isset($_SESSION['userName'])){
	header("Location:user/profile.php");
	exit();
}

require_once 'database.php';

$dbobj = new database();
$dbobj->dbconnect();

if(isset($_POST['changePassword'])){
	$mobnum = $_POST['mobNum'];
	$act_code = $_POST['act_code'];
	$query = mysql_query("SELECT * FROM userlogin WHERE mobilenum=" . $mobnum . " AND fgt_passcode='" . $act_code . "'");
	if(mysql_num_rows($query)==1){
		$password = $_POST['forgotuserPassword'];
		$password = $password . $dbobj->returnSalt();
		$password = hash('sha512',$password);
		$query="UPDATE userlogin SET password='" . $password . "', fgt_passcode=NULL WHERE mobilenum=" . $mobnum;
		if(mysql_query($query)){
			$_SESSION['changePassword']="Y";
			header("Location:user/login");
		}
	}else{
		echo "Something went wrong. Please try again";
	}
}else{
	header("Location:index.php");
}

$dbobj->dbdisconnect();
include('footer.php');
?>
