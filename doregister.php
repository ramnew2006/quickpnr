<?php
require_once 'database.php';

$dbobj = new database();
$dbobj->dbconnect();

if(isset($_SESSION['user'])){
	header("Location:index.php");
}

if(isset($_POST['userRegister'])){
	$mobileNum = $_POST['mobileNum'];
	$password = $_POST['userPassword'];
	if(is_numeric($mobileNum) && strlen($mobileNum)==10){
		$password = $password . $dbobj->returnSalt();
		$password = hash('sha512',$password);
		$query = "INSERT INTO userlogin (mobilenum, password) VALUES ('" . $mobileNum . "','" . $password . "')";
		if(mysql_query($query)){
			echo "User Successfully Registered";
		}else{
			echo "something s wrong";
		}
		
	}
}else{
	header("Location:register.php");
}

$dbobj->dbdisconnect();

?>